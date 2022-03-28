<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;

class Image extends Model
{
    use HasFactory, SoftDeletes, Uuids;

    protected $fillable = ['imageable_type', 'imageable_id', 'file'];

    protected $appends = ['encrypted_id'];

    public function getEncryptedIdAttribute()
    {
        return Crypt::encrypt($this->id);
    }


    public function imageable()
    {
        return $this->morphTo();
    }

}
