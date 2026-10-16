<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = ['member_code', 'name', 'created_date', 'nik', 'phone', 'address', 'room_number', 'membership_type', 'valid_until', 'nfc_uid', 'status', 'scan_count'];
    protected $casts = ['created_date' => 'date', 'valid_until' => 'date'];
}
