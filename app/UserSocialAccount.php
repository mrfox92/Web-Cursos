<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSocialAccount extends Model
{
    protected $fillable = [
        'user_id', 'provider', 'provider_uid'
    ];

    public function user () {
        return $this->belongsTo(User::class);
    }
}
