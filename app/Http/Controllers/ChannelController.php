<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChannelController extends Controller
{
    public function list($channel, $format) {
        $feed = \App::make('feed');
        $items = $channel->queryAll()
            ->orderBy('created_at', 'desc')
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
            $itemLink = route('singleItem', [
                'channel' => $channel->getName(),
                'id' => $item->id,
            ]);
            $feed->add(
                $item->title, null,
                $itemLink,
                $item->created_at,
                $item->description
            );
        }
        return $feed->render($format, -1);
    }

    public function push(Request $req, $channel) {
        $validated = $req->validate([
            'title' => 'required|max:255',
            'description' => 'required|max:4095',
        ]);
        // Framework will stop here in case of error
        $channel->push($validated);
        return 'ok';
    }
}
