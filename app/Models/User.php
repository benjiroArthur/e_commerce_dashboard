<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Crypt;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, Uuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'gender',
        'phone_number',
        'user_type_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    protected $appends = ['encrypted_id', 'account_type', 'initials'];

    public function getEncryptedIdAttribute(){
        return Crypt::encrypt($this->id);
    }

    public function products(){
        return $this->hasMany(Product::class);
    }
    public function ordersAsVendor(){
        return $this->hasMany(Order::class, 'vendor_id');
    }
    public function ordersAsCustomer(){
        return $this->hasMany(Order::class, 'customer_id');
    }
    public function userType(){
        return $this->belongsTo(UserType::class);
    }

    public function getAccountTypeAttribute(){
        return $this->userType->title;
    }
    public function getInitialsAttribute(){
        $nameArray = explode(' ', $this->name);
        $firstname = $nameArray[0];
        $lastname = $nameArray[(count($nameArray) - 1)];
        return substr($firstname, 0, 1).substr($lastname, 0, 1);
    }
}
