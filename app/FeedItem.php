<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FeedItem extends Model
{
    protected $table = 'feeditems';

    protected $fillable = ['title', 'description'];
}
