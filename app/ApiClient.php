<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class ApiClient extends Authenticatable
{
    protected $casts = [
        'authorizations' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
