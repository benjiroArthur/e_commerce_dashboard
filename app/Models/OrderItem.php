<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;

class OrderItem extends Model
{
    use HasFactory, Uuids, SoftDeletes;

    protected $fillable = ['order_id', 'product_id', 'price', 'quantity'];

    protected $appends = ['encrypted_id'];

    public function getEncryptedIdAttribute(){
        return Crypt::encrypt($this->id);
    }

    public function order(){
        return $this->belongsTo(Order::class);
    }
    public function product(){
        return $this->belongsTo(Product::class);
    }
}
