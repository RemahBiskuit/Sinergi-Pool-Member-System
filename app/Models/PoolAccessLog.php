<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class PoolAccessLog extends Model {
    protected $fillable=['member_id','nfc_uid','result','reason','reader_type','scanned_at'];
    protected $casts=['scanned_at'=>'datetime'];
    public function member(){return $this->belongsTo(Member::class);}
}
