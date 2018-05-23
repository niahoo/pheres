<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use App\User;
use App\ApiClient;

class FeedChannel {

    const SPEC_STRING_RE = '^[A-Za-z0-9]+(?:[A-Za-z0-9-\.\:]*[A-Za-z0-9])?$';
    const SCOPE_PRIVATE = 1;
    const SCOPE_GLOBAL = 2;
    const ITEMS_TABLE = 'feeditems';
    const ACL_LEVEL_READ = 'read';
    const ACL_LEVEL_PUSH = 'push';

    protected $name;

    public function __construct($spec)
    {
        $re = '@' . self::SPEC_STRING_RE . '@';
        $match = preg_match($re, $spec, $m);
        if (!$match) {
            throw new \Exception("Invalid channel spec string");
        }
        $name = $m[0];
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function userItemsQuery(User $user)
    {
        // return the raw query for extension, not the records
        return \DB::table(self::ITEMS_TABLE)
            ->where('channel', $this->name)
            ->where($user->feedItems()->getForeignKeyName(), $user->getKey())
            ;
    }

    public static function userCurrentChannels(User $user)
    {
        // return the raw query for extension, not the records
        return \DB::table(self::ITEMS_TABLE)
            ->select('channel')
            ->groupBy('channel')
            ->where($user->feedItems()->getForeignKeyName(), $user->getKey())
            ->get()
            ->pluck('channel')
            ->toArray()
            ;
    }

    public function itemQuery(User $user, $itemId)
    {
        // return the raw query for extension, not the records
        return FeedItem::whereKey($itemId)
            ->where('channel', $this->name)
            ->where($user->feedItems()->getForeignKeyName(), $user->getKey())
            ;
    }

    public function push(FeedItem $item)
    {
        $item->channel = $this->name;
        $pushed = $item->save();
        return $item;
    }

    public function allowsClientTo(ApiClient $client, $level = self::ACL_LEVEL_READ)
    {
        return $client->isAuthorized(static::aclTopic('*', $level))
            || $client->isAuthorized(static::aclTopic($this->name, $level));
    }

    public function allowsClientToRead(ApiClient $client)
    {
        return static::allowsClientTo($client, 'read');
    }

    public function allowsClientToPush(ApiClient $client)
    {
        return static::allowsClientTo($client, 'push');
    }

    public static function aclTopic(string $channelName, $accessLevel)
    {
        return "channel:$channelName:$accessLevel";
    }
}
