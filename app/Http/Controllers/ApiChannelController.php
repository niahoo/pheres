<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Gate;
use App\FeedItem;

class ApiChannelController extends Controller
{

    const DEFAULT_OUTPUT_FORMAT = 'rss';

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function list($channel, $format = self::DEFAULT_OUTPUT_FORMAT)
    {
        $gateName = 'channel-read';
        if (!Gate::allows($gateName, $channel)) {
            return $this->authorizationFail($gateName);
        }
        $feed = \App::make('feed');
        $items = $channel->userItemsQuery(\Auth::user()->user)
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

    public function push(Request $req, $channel)
    {
        $gateName = 'channel-push';
        if (Gate::allows($gateName, $channel)) {
            $item = FeedItem::fromExt($req->all())->setOwner(\Auth::user());
            $item = $channel->push($item);
            $resp = response('ok '.$item->id, 201);
        } else {
            $resp =$this->authorizationFail($gateName);
        }
        return $resp->header('Content-Type', 'text/plain');
        // Framework will stop here in case of error
    }

    public static function authorizationFail($gateName)
    {
        return response("Unauthorized: $gateName", 403);
    }
}
