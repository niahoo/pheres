<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\FeedChannel;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = \Auth::user();
        $apiClients = $user
            ->apiClients()
            ->orderBy('created_at', 'desc')
            ->get()
            ;
        $usedChannels = FeedChannel::userCurrentChannels($user);
        return view('home', compact('apiClients', 'usedChannels'));
    }

    public function createClient()
    {
        $client = \Auth::user()->createApiClient([
            'api_key' => Str::uuid()->toString(),
            'authorizations' => [
                FeedChannel::aclTopic('*', FeedChannel::ACL_LEVEL_READ),
            ]
        ]);
        return redirect()->route('home')->with('status', 'Client added');
    }

    public function updateClient($clientId, Request $req)
    {
        $user = \Auth::user();
        // find the client and check if it is related to the user
        $client = \Auth::user()
            ->apiClients()
            ->whereKey(intval($clientId))
            ->first()
            ;
        if (! $client) {
            return redirect()->route('home')->with('errmsg', 'Client not found');
        }
        if ($req->input('delete_client') === 'delete') {
            $client->delete();
            return redirect()->route('home')->with('status', 'Deleted');
        }
        if ($req->input('update_client') !== 'update') {
            return redirect()->route('home')->with('errmsg', 'Unknown action');
        }
        // Updating
        $readTopic = FeedChannel::aclTopic('*', FeedChannel::ACL_LEVEL_READ);
        $client->toggleAuthorization($readTopic, $req->input('channel_read') === 'on');

        $pushTopic = FeedChannel::aclTopic('*', FeedChannel::ACL_LEVEL_PUSH);
        $client->toggleAuthorization($pushTopic, $req->input('channel_push') === 'on');

        $client->save();
        return redirect()->route('home')->with('status', 'Updated');
    }
}
