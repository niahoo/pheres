<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class FeedItem extends Model
{
    protected $table = 'feeditems';

    protected $fillable = ['title', 'description', 'content', 'link'];

    public static function fromExt($data)
    {
        $rules = [
            'title' => 'required|max:255',
            'description' => 'max:191',
            'content' => 'required|max:4095',
            'link' => 'url',
        ];
        $validated = validator()->validate($data, $rules);
        return new static($validated);
    }

    public function setOwner(Authenticatable $owner)
    {
        if ($owner instanceof ApiClient) {
            $user = $client->user();
            $client = $owner->user();
        } elseif ($owner instanceof User) {
            $user = $owner;
            $client = null;
        } else {
            throw new \Exception("Bad owner : " . get_class($owner));
        }
        if ($client) {
            $this->apiClient()->associate($client);
        }
        $this->user()->associate($user);
        return $this;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function apiClient()
    {
        return $this->belongsTo(ApiClient::class);
    }
}
