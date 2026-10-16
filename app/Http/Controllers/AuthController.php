<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AuthController extends Controller
{
  public function showLogin(): View
  {
    return view('login');
  }

  public function login(Request $request): RedirectResponse
  {
    $credentials = $request->validate([
      'username' => ['required', 'string'],
      'password' => ['required', 'string'],
    ]);

    $admin = DB::table('admins')->where('username', $credentials['username'])->first();
    if (!$admin || !Hash::check($credentials['password'], $admin->password)) {
      return back()->withErrors(['username' => 'Username atau password salah.'])->withInput($request->only('username'));
    }

    $request->session()->regenerate();
    $request->session()->put('admin_authenticated', true);
    $request->session()->put('admin_id', $admin->id);
    $request->session()->put('admin_role', $admin->role ?? 'admin');

    return redirect()->intended(route('dashboard'));
  }

  public function logout(Request $request): RedirectResponse
  {
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login');
  }

  public function changePassword(Request $request): RedirectResponse
  {
    $data = $this->validateWithBag('password', $request, [
      'admin_id' => ['required', 'integer', 'exists:admins,id'],
      'password' => ['required', 'confirmed', Password::min(8)],
    ]);

    $currentAdmin = DB::table('admins')->where('id', $request->session()->get('admin_id'))->first();
    if (!$currentAdmin) {
      abort(403);
    }
    if (($currentAdmin->role ?? 'admin') !== 'superadmin') {
      abort(403);
    }

    DB::table('admins')->where('id', $data['admin_id'])->update([
      'password' => Hash::make($data['password']),
      'updated_at' => now(),
    ]);

    return back()->with('password_status', 'Password admin berhasil diubah.');
  }

  public function createAdmin(Request $request): RedirectResponse
  {
    if ($request->session()->get('admin_role') !== 'superadmin') {
      abort(403);
    }
    $data = $request->validate([
      'username' => ['required', 'string', 'max:100', 'unique:admins,username'],
      'password' => ['required', 'confirmed', Password::min(8)],
      'role' => ['required', 'in:superadmin,admin'],
    ]);
    DB::table('admins')->insert([
      'username' => $data['username'],
      'password' => Hash::make($data['password']),
      'role' => $data['role'],
      'created_at' => now(),
      'updated_at' => now(),
    ]);
    return back()->with('admin_status', 'User admin berhasil ditambahkan.');
  }
}
