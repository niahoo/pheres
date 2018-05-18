<?php

namespace App;

class FeedChannel {

    const SPEC_STRING_RE = '^[A-Za-z0-9]+(?:[A-Za-z0-9-\.\:]*[A-Za-z0-9])?$';
    const SCOPE_PRIVATE = 1;
    const SCOPE_GLOBAL = 2;
    const ITEMS_TABLE = 'feeditems';

    protected $name;

    public function __construct($spec) {
        $re = '@' . self::SPEC_STRING_RE . '@';
        $match = preg_match($re, $spec, $m);
        if (!$match) {
            throw new \Exception("Invalid channel spec string");
        }
        $name = $m[0];
        $this->name = $name;
    }

    public function getName() {
        return $this->name;
    }

    public function queryAll() {
        // return the raw query for extension, not the records
        return \DB
            ::table(self::ITEMS_TABLE)
            ->where('channel', $this->name)
            ;
    }

    public function push($data, ApiClient $client) {
        $item = new FeedItem($data);
        $item->channel = $this->name;
        $authorizationTopic = 'write-channel';
        if ($client->isAuthorized($authorizationTopic)) {
            $item->apiClient()->associate($client);
            $item->user()->associate($client->user);
            $pushed = $item->save();
            return $pushed;
        } else {
            throw new \Illuminate\Auth\Access\AuthorizationException(
                'Unauthorized: ' . $authorizationTopic
            );
        }
    }
}
