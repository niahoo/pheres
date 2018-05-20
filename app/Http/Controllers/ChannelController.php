<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Gate;
use App\FeedItem;

class ChannelController extends Controller
{

    const DEFAULT_OUTPUT_FORMAT = 'rss';

    public function list($channel, $format = self::DEFAULT_OUTPUT_FORMAT) {
        $gateName = 'channel-read';
        if (!Gate::allows($gateName, $channel)) {
            $this->authorizationFail($gateName);
        }
        $feed = \App::make('feed');
        $items = $channel->queryForUser(\Auth::user()->user)
            ->orderBy('created_at', 'asc')
            ->get()
            ;
        $feed->title = 'Unnamed feed';
        $feed->description = 'No description provided';
        if (count($items)) {
            $feed->pubdate = $items[0]->created_at;
        }
        $feed->setShortening(false);
        $feed->setTextLimit(4095);
        foreach ($items as $item) {
            $itemLink = $item->link ?? route('singleItem', [
                'channel' => $channel->getName(),
                'id' => $item->id,
            ]);
            $feed->add(
                $item->title, null,
                $itemLink,
                $item->created_at,
                $item->description,
                $item->content
            );
        }
        return $feed->render($format, -1);
    }

    public function push(Request $req, $channel) {
        $validatedData = $req->validate([
            'title' => 'required|max:255',
            'description' => 'max:191',
            'content' => 'required|max:4095',
            'link' => 'url',
        ]);
        $gateName = 'channel-push';
        if (Gate::allows($gateName, $channel)) {
            $client = \Auth::user();
            $item = new FeedItem($validatedData);
            $item->apiClient()->associate($client);
            $item->user()->associate($client->user);
            $channel->push($item, \Auth::user());
            return 'ok '.$item->id;
        } else {
            $this->authorizationFail($gateName);
        }
        // Framework will stop here in case of error
    }

    public function single($channel, $id)
    {
        return $channel
            ->itemQuery(\Auth::user(), $id)
            ->firstOrFail()
            ;
    }

    public static function authorizationFail($gateName)
    {
        throw new \Illuminate\Auth\Access\AuthorizationException(
            'Unauthorized: ' . $gateName
        );
        die(1);
    }
}
