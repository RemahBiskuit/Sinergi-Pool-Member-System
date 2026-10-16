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
      $table->string('nfc_uid', 100)->nullable()->change();
    });

    DB::table('members')->where('status', 'inactive')->update(['nfc_uid' => null]);
  }

  public function down(): void
  {
    Schema::table('members', function (Blueprint $table) {
      $table->string('nfc_uid', 100)->nullable(false)->change();
    });
  }
};
