<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;

class Product extends Model
{
    use HasFactory, Uuids, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_category_id',
        'name',
        'description',
        'price_grouping_id',
        'discount_id',
        'user_id'
    ];

    protected $appends = ['encrypted_id'];
    //protected $with = ['images'];

    public function getEncryptedIdAttribute(){
        return Crypt::encrypt($this->id);
    }

    public function productCategory(){
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }
    public function priceGrouping(){
        return $this->belongsTo(PriceGrouping::class, 'price_grouping_id');
    }
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
    public function discounts(){
        return $this->belongsToMany(Discount::class)->latest();
    }
    public function images(){
        return $this->morphMany(Image::class, 'imageable');
    }
}
