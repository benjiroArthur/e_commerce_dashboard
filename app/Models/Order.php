<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;

class Order extends Model
{
    use HasFactory, Uuids, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'vendor_id',
        'customer_id',
        'order_history_id',
    ];

    protected $appends = ['encrypted_id'];

    public function getEncryptedIdAttribute(){
        return Crypt::encrypt($this->id);
    }

    public function vendor(){
        return $this->belongsTo(User::class, 'vendor_id', 'id');
    }
    public function customer(){
        return $this->belongsTo(User::class, 'customer_id', 'id');
    }
    public function orderHistory(){
        return $this->hasMany(OrderHistory::class);
    }
    public function orderItems(){
        return $this->hasMany(OrderItem::class);
    }
}
