<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ApiClient extends Model
{
    protected $casts = [
        'authorizations' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function generate(array $authorizations)
    {
        $uuid = Str::uuid();
        return new static([
            'id' => $uuid,
            'authorizations' => $authorizations,
        ]);
    }
}
