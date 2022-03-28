<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;

class Status extends Model
{
    use HasFactory, Uuids, SoftDeletes;

    protected $fillable = ['name'];
    protected $appends = ['encrypted_id'];

    public function getEncryptedIdAttribute(){
        return Crypt::encrypt($this->id);
    }
}
