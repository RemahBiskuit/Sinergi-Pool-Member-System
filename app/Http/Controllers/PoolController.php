<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\PoolAccessLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PoolController extends Controller
{
  public function dashboard()
  {
    $adminId = request()->session()->get('admin_id');
    $currentAdmin = DB::table('admins')->where('id', $adminId)->first();
    $admins = ($currentAdmin->role ?? null) === 'superadmin'
      ? DB::table('admins')->orderBy('username')->get(['id', 'username'])
      : collect();

    return view('dashboard', compact('admins'));
  }
  public function allMembersPage()
  {
    return view('members');
  }
  public function members()
  {
    $this->deactivateExpiredMembers();
    $this->deactivateMembersOverScanLimit();
    $members = Member::where('status', 'active')->latest()->paginate(5);
    return response()->json([
      'data' => $members->items(),
      'current_page' => $members->currentPage(),
      'last_page' => $members->lastPage(),
      'total' => $members->total(),
    ]);
  }
  public function activeMembers()
  {
    $this->deactivateExpiredMembers();
    $this->deactivateMembersOverScanLimit();
    return response()->json(Member::where('status', 'active')->latest()->get());
  }
  public function allMembers()
  {
    $this->deactivateExpiredMembers();
    $this->deactivateMembersOverScanLimit();
    return response()->json(Member::latest()->get());
  }
  public function logs()
  {
    $logs = PoolAccessLog::with('member')->latest('scanned_at')->paginate(10);
    return response()->json([
      'data' => $logs->items(),
      'current_page' => $logs->currentPage(),
      'last_page' => $logs->lastPage(),
      'today_count' => PoolAccessLog::whereDate('scanned_at', now()->toDateString())->count(),
    ]);
  }
  public function exportLogs(Request $r)
  {
    $filters = $r->validate(['from' => 'nullable|date', 'to' => 'nullable|date|after_or_equal:from']);
    $query = PoolAccessLog::with('member')->latest('scanned_at');
    if (!empty($filters['from'])) $query->whereDate('scanned_at', '>=', $filters['from']);
    if (!empty($filters['to'])) $query->whereDate('scanned_at', '<=', $filters['to']);
    return response()->json($query->get());
  }
  public function destroyLogs(Request $r)
  {
    $admin = DB::table('admins')->where('id', $r->session()->get('admin_id'))->first();
    if (!$admin || ($admin->role ?? null) !== 'superadmin') {
      abort(403);
    }

    $filters = $r->validate(['from' => 'required|date', 'to' => 'required|date|after_or_equal:from']);
    $deleted = PoolAccessLog::whereDate('scanned_at', '>=', $filters['from'])
      ->whereDate('scanned_at', '<=', $filters['to'])
      ->delete();
    return response()->json(['message' => $deleted . ' riwayat scan berhasil dihapus.']);
  }
  public function purgeData(Request $r)
  {
    $data = $r->validate(['password' => 'required|string']);
    $admin = DB::table('admins')->where('id', $r->session()->get('admin_id'))->first();

    if (!$admin || ($admin->role ?? null) !== 'superadmin') {
      abort(403);
    }
    if (!Hash::check($data['password'], $admin->password)) {
      return response()->json(['message' => 'Password superadmin tidak sesuai.'], 422);
    }

    $deleted = DB::transaction(function () {
      $logs = PoolAccessLog::query()->delete();
      $members = Member::query()->delete();

      return compact('members', 'logs');
    });

    return response()->json([
      'message' => 'Semua data member dan riwayat scan berhasil dihapus.',
      'deleted' => $deleted,
    ]);
  }
  public function storeMember(Request $r)
  {
    $r->merge(['nfc_uid' => strtoupper(trim($r->input('nfc_uid', '')))]);
    $data = $r->validate(['name' => 'required|string|max:100', 'created_date' => 'required|date', 'nik' => 'required|string|max:30|unique:members,nik', 'phone' => 'required|string|max:30', 'address' => 'nullable|string|max:500', 'room_number' => 'nullable|string|max:30', 'membership_type' => 'required|string|max:50', 'valid_until' => 'required|date', 'nfc_uid' => 'required|string|max:100|unique:members,nfc_uid']);
    $data['member_code'] = 'SNG-' . strtoupper(Str::random(6));
    $data['status'] = $data['valid_until'] < now()->toDateString() ? 'inactive' : 'active';
    if ($data['status'] === 'inactive') $data['nfc_uid'] = null;
    $m = Member::create($data);
    return response()->json($m, 201);
  }
  public function updateMember(Request $r, Member $member)
  {
    $r->merge(['nfc_uid' => strtoupper(trim($r->input('nfc_uid', '')))]);
    $data = $r->validate(['name' => 'required|string|max:100', 'created_date' => 'required|date', 'nik' => ['required', 'string', 'max:30', Rule::unique('members', 'nik')->ignore($member->id)], 'phone' => 'required|string|max:30', 'address' => 'nullable|string|max:500', 'room_number' => 'nullable|string|max:30', 'membership_type' => 'required|string|max:50', 'valid_until' => 'required|date', 'nfc_uid' => ['nullable', 'required_if:status,active', 'string', 'max:100', Rule::unique('members', 'nfc_uid')->ignore($member->id)], 'status' => 'required|in:active,inactive']);
    if ($data['status'] === 'active' && $data['valid_until'] < now()->toDateString()) {
      return response()->json(['message' => 'Membership yang sudah expired tidak dapat diaktifkan.'], 422);
    }
    if ($data['status'] === 'inactive') {
      $data['nfc_uid'] = null;
    } elseif ($member->membership_type !== $data['membership_type'] || $member->nfc_uid !== $data['nfc_uid']) {
      $data['scan_count'] = 0;
    }
    $member->update($data);
    return response()->json($member->fresh());
  }
  public function extendMember(Request $r, Member $member)
  {
    $data = $r->validate(['membership_type' => 'required|in:1 Bulan,2 Bulan', 'valid_until' => 'required|date|after_or_equal:today']);
    $cardUid = $member->nfc_uid ?: PoolAccessLog::where('member_id', $member->id)->where('result', 'granted')->latest('scanned_at')->value('nfc_uid');
    if ($cardUid && Member::where('nfc_uid', $cardUid)->where('id', '!=', $member->id)->exists()) $cardUid = null;
    $member->update(['membership_type' => $data['membership_type'], 'valid_until' => $data['valid_until'], 'status' => 'active', 'scan_count' => 0, 'nfc_uid' => $cardUid]);
    return response()->json($member->fresh());
  }
  public function destroyMember(Member $member)
  {
    $member->delete();
    return response()->json(['message' => 'Member berhasil dihapus.']);
  }
  public function scan(Request $r)
  {
    $this->deactivateExpiredMembers();
    $this->deactivateMembersOverScanLimit();
    $uid = strtoupper(trim($r->input('nfc_uid', '')));
    if (!$uid) return response()->json(['ok' => false, 'message' => 'UID NFC kosong'], 422);
    $m = Member::where('nfc_uid', $uid)->first();
    $now = now();
    $scanLimit = $m ? $this->scanLimit($m->membership_type) : null;
    $successfulScans = $m ? $this->successfulScanCount($m) : 0;
    $withinScanLimit = $scanLimit === null || $successfulScans < $scanLimit;
    $ok = $m && $m->status === 'active' && $m->valid_until->gte($now->toDateString()) && $withinScanLimit;
    $reason = $ok ? 'Akses diterima' : (!$m ? 'Kartu tidak terdaftar' : ($m->status !== 'active' ? 'Membership tidak aktif' : (!$withinScanLimit ? 'Batas scan membership sudah tercapai' : 'Membership sudah expired')));
    PoolAccessLog::create(['member_id' => $m?->id, 'nfc_uid' => $uid, 'result' => $ok ? 'granted' : 'denied', 'reason' => $reason, 'reader_type' => $r->input('reader_type', 'usb-keyboard'), 'scanned_at' => $now]);
    if ($ok) {
      $m->increment('scan_count');
      if ($scanLimit !== null && $successfulScans + 1 >= $scanLimit) $m->update(['status' => 'inactive', 'nfc_uid' => null]);
    }
    return response()->json(['ok' => $ok, 'message' => $reason, 'member' => $m, 'scanned_at' => $now->format('d/m/Y H:i:s')]);
  }

  private function scanLimit(string $membershipType): ?int
  {
    return ['1 Bulan' => 4, '2 Bulan' => 9][$membershipType] ?? null;
  }

  private function successfulScanCount(Member $member): int
  {
    return (int) $member->scan_count;
  }

  private function hasReachedScanLimit(Member $member, string $membershipType): bool
  {
    $limit = $this->scanLimit($membershipType);
    return $limit !== null && $this->successfulScanCount($member) >= $limit;
  }

  private function deactivateMembersOverScanLimit(): void
  {
    Member::where('status', 'active')->get()->each(function (Member $member) {
      if ($this->hasReachedScanLimit($member, $member->membership_type)) {
        $member->update(['status' => 'inactive', 'nfc_uid' => null]);
      }
    });
  }

  private function deactivateExpiredMembers(): void
  {
    Member::where('status', 'active')->whereDate('valid_until', '<', now()->toDateString())->update(['status' => 'inactive', 'nfc_uid' => null]);
  }
}
