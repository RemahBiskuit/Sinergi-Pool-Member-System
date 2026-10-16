<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::table('members', function (Blueprint $table) {
      $table->date('created_date')->nullable()->after('name');
      $table->string('nik', 30)->nullable()->unique()->after('created_date');
      $table->text('address')->nullable()->after('phone');
    });
  }

  public function down(): void
  {
    Schema::table('members', function (Blueprint $table) {
      $table->dropUnique(['nik']);
      $table->dropColumn(['created_date', 'nik', 'address']);
    });
  }
};
