<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration { public function up(): void { Schema::create('pool_access_logs', function(Blueprint $t){$t->id();$t->foreignId('member_id')->nullable()->constrained('members')->nullOnDelete();$t->string('nfc_uid');$t->string('result');$t->string('reason')->nullable();$t->string('reader_type')->default('usb-keyboard');$t->timestamp('scanned_at')->useCurrent();$t->timestamps();}); } public function down(): void {Schema::dropIfExists('pool_access_logs');} };
