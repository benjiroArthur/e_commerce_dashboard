<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;

class Payment extends Model
{
    use HasFactory, Uuids, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'payment_type_id',
        'payment_details',
        'status_id',
        'order_id'
    ];

    protected $appends = ['encrypted_id'];

    public function getEncryptedIdAttribute(){
        return Crypt::encrypt($this->id);
    }

    protected $casts = [
        'payment_details' => 'array'
    ];

    public function paymentType(){
        return $this->belongsTo(PaymentType::class);
    }
    public function status(){
        return $this->belongsTo(Status::class);
    }
    public function order(){
        return $this->belongsTo(Order::class);
    }
}
