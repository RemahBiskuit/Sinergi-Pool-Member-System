<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration { public function up(): void { Schema::create('members', function(Blueprint $t){$t->id();$t->string('member_code')->unique();$t->string('name');$t->string('phone')->nullable();$t->string('room_number')->nullable();$t->string('membership_type')->default('Regular');$t->date('valid_until');$t->string('nfc_uid')->unique();$t->string('status')->default('active');$t->timestamps();}); } public function down(): void {Schema::dropIfExists('members');} };
