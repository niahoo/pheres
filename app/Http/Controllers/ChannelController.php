<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Gate;
use App\FeedItem;

class ChannelController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($channel)
    {
        return view('channelIndex', compact('channel'));
    }

    public function singleItem($channel, $id)
    {
        return $channel
            ->itemQuery(\Auth::user(), $id)
            ->firstOrFail()
            ;
    }
}
