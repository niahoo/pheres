<?php

namespace App\Providers;

use Auth;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\User;
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

        Gate::define('channel-push', function(Authenticatable $cli, FeedChannel $ch) {
            if ($cli instanceof User) {
                return true; // user can do anything on its channel
            }
            return $ch->allowsClientToPush($cli);
        });
        Gate::define('channel-read', function(Authenticatable $cli, FeedChannel $ch) {
            if ($cli instanceof User) {
                return true; // user can do anything on its channel
            }
            return $ch->allowsClientToRead($cli);
        });
    }
}
