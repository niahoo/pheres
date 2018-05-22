<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Gate;
use App\FeedItem;
use App\FeedChannel;
use App\User;

class ChannelController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($channel)
    {
        $user = \Auth::user();
        $bookmarkletWidgetData = [
            'apiClients' => $this->channelPushClients($channel, $user),
        ];
        return view('channelIndex', compact(
            'channel', 'user', 'bookmarkletWidgetData'
        ));
    }

    public function singleItem($channel, $id)
    {
        return $channel
            ->itemQuery(\Auth::user(), $id)
            ->firstOrFail()
            ;
    }

    private function channelPushClients(FeedChannel $channel, User $user)
    {
        return $user->apiClients
            ->filter(function($client) use ($channel) {
                return $channel->allowsClientToPush($client);
            })
            ->map(function($client) use ($channel) {
                // We prefer a client that can just push to the required channel for
                // the bookmarklet. So we penalize other authorizations by giving
                // points, so the list will be sorted from less penalized to more
                // penalized.
                $data = array_only($client->toArray(), ['id', 'name', 'api_key'])
                    + [
                        'penalty' => 0,
                        'warnings' => [],
                    ];
                if ($client->isAuthorized(FeedChannel::aclTopic($channel->getName(), FeedChannel::ACL_LEVEL_READ))) {
                    // Client can read the channel whereas it's not required
                    $data['penalty'] += 1;
                    $data['warnings'][] = 'read-channel';
                }
                if ($client->isAuthorized(FeedChannel::aclTopic('*', FeedChannel::ACL_LEVEL_PUSH))) {
                    // Client can push to any channel, bad but expected from
                    // most users
                    $data['penalty'] += 10;
                    $data['warnings'][] = 'push-any';
                }
                if ($client->isAuthorized(FeedChannel::aclTopic('*', FeedChannel::ACL_LEVEL_READ))) {
                    // Client can read everything, it's very bad
                    $data['penalty'] += 100;
                    $data['warnings'][] = 'read-any';
                }
                return $data;
            })
            ->sortBy('penalty')
            ->values()
            ->toArray()
            ;
    }
}
