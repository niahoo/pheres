<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
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
        return view('channelIndex', compact(
            'channel', 'user'
        ));
    }

    public function singleItem($channel, $id)
    {
        return $channel
            ->itemQuery(\Auth::user(), $id)
            ->firstOrFail()
            ;
    }

    public function bookmarkletScript($channel)
    {
        return view('bookmarkletScript', compact('channel'));
    }

    public function bookmarkletCssWrapper($channel)
    {
        $view = view('bookmarkletScript', compact('channel'));
        $js = $view->render();
        $js = preg_replace('/[^\S\n]*\n+[^\S\n]*/', '', $js);
        $js = str_replace("'", "\\'", $js);
        return response(view('bookmarkletCssWrapper', compact('js')))
            ->header('Content-Type', 'text/css');
    }

    public function userItemPush(Request $req, $channel)
    {
        $asApi = \App::make('App\Http\Controllers\ApiChannelController');
        return $asApi->push($req, $channel)
            ->header('Access-Control-Allow-Origin', $_SERVER['HTTP_ORIGIN'] ?? '*')
            ->header('Access-Control-Allow-Credentials', 'true')
        ;
    }

    // private function channelPushClients(FeedChannel $channel, User $user)
    // {
    //     return $user->apiClients
    //         ->filter(function($client) use ($channel) {
    //             return $channel->allowsClientToPush($client);
    //         })
    //         ->map(function($client) use ($channel) {
    //             // We prefer a client that can just push to the required channel for
    //             // the bookmarklet. So we penalize other authorizations by giving
    //             // points, so the list will be sorted from less penalized to more
    //             // penalized.
    //             $data = array_only($client->toArray(), ['id', 'name', 'api_key'])
    //                 + [
    //                     'penalty' => 0,
    //                     'warnings' => [],
    //                 ];
    //             if ($client->isAuthorized(FeedChannel::aclTopic($channel->getName(), FeedChannel::ACL_LEVEL_READ))) {
    //                 // Client can read the channel whereas it's not required
    //                 $data['penalty'] += 1;
    //                 $data['warnings'][] = 'read-channel';
    //             }
    //             if ($client->isAuthorized(FeedChannel::aclTopic('*', FeedChannel::ACL_LEVEL_PUSH))) {
    //                 // Client can push to any channel, bad but expected from
    //                 // most users
    //                 $data['penalty'] += 10;
    //                 $data['warnings'][] = 'push-any';
    //             }
    //             if ($client->isAuthorized(FeedChannel::aclTopic('*', FeedChannel::ACL_LEVEL_READ))) {
    //                 // Client can read everything, it's very bad
    //                 $data['penalty'] += 100;
    //                 $data['warnings'][] = 'read-any';
    //             }
    //             return $data;
    //         })
    //         ->sortBy('penalty')
    //         ->values()
    //         ->toArray()
    //         ;
    // }
}
