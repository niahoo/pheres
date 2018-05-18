<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FeedItem extends Model
{
    protected $table = 'feeditems';

    protected $fillable = ['title', 'description'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function apiClient()
    {
        return $this->belongsTo(ApiClient::class);
    }
}
