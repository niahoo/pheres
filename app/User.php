<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function apiClients()
    {
        return $this->hasMany(ApiClient::class);
    }

    public function createApiClient($attributes)
    {
        $client = new ApiClient($attributes);
        return $this->apiClients()->save($client);
    }

    public function feedItems()
    {
        return $this->hasMany(FeedItem::class);
    }
}
