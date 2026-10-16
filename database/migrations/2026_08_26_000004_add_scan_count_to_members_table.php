<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::table('members', function (Blueprint $table) {
      $table->unsignedInteger('scan_count')->default(0)->after('status');
    });

    DB::table('members')->orderBy('id')->each(function (object $member): void {
      $count = DB::table('pool_access_logs')
        ->where('member_id', $member->id)
        ->where('result', 'granted')
        ->count();
      DB::table('members')->where('id', $member->id)->update(['scan_count' => $count]);
    });
  }

  public function down(): void
  {
    Schema::table('members', function (Blueprint $table) {
      $table->dropColumn('scan_count');
    });
  }
};
