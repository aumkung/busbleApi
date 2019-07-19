<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Support\Facades\Storage;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    protected $fillable = [
        'name', 'telno', 'email', 'gender', 'thumbnail', 'password', 'username', 'email_verify', 'telno_verify'
    ];

    protected $appends = ['thumbnail_url'];

    public function getThumbnailUrlAttribute()
    {
        if (empty($this->attributes['thumbnail'])) {
            return 'https://dummyimage.com/100x100/#fff/#fff.png';
        } else {
            return Storage::disk('public')->url($this->attributes['thumbnail']);
        }
    }
}
