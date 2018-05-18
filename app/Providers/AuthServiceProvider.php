<?php

namespace App\Providers;

use Auth;
use App\ApiClient;
use App\FeedChannel;
use App\Auth\ApiClientProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Custom guard
        Auth::extend('token_only', function ($app, $name, array $config) {
            $users = Auth::createUserProvider($config['provider'] ?? null);
            return new \App\Http\TokenGuard($users, $app['request']);
        });

        // Custom user provider for API
        Auth::provider('api_client', function ($app, array $config) {
            return new ApiClientProvider();
        });

        Gate::define('channel-push', function(ApiClient $cli, FeedChannel $ch) {
            return $ch->allowsClientToPush($cli);
        });
        Gate::define('channel-read', function(ApiClient $cli, FeedChannel $ch) {
            rr($cli->authorizations);
            return $ch->allowsClientToRead($cli);
        });
    }
}
