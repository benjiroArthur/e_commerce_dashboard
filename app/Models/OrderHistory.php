<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;

class OrderHistory extends Model
{
    use HasFactory, Uuids, SoftDeletes;

    protected $fillable = ['status_id', 'datetime', 'order_id'];

    protected $appends = ['encrypted_id'];

    public function getEncryptedIdAttribute(){
        return Crypt::encrypt($this->id);
    }

    public function order(){
        return $this->belongsTo(Order::class);
    }
    public function status(){
        return $this->belongsTo(Status::class);
    }
}
