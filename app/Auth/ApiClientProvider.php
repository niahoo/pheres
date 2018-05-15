<?php
namespace App\Auth;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;
use Illuminate\Contracts\Auth\Authenticatable;

class ApiClientProvider implements UserProvider
{
    /**
     * Retrieve a user by their unique identifier.
     *
     * @param  mixed  $identifier
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveById($identifier) {
        throw new Exception("retrieveById unsupported");
    }

    /**
     * Retrieve a user by their unique identifier and "remember me" token.
     *
     * @param  mixed   $identifier
     * @param  string  $token
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByToken($identifier, $token) {
        throw new Exception("retrieveByToken unsupported");
    }

    /**
     * Update the "remember me" token for the given user in storage.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  string  $token
     * @return void
     */
    public function updateRememberToken(Authenticatable $user, $token) {
        throw new Exception("updateRememberToken unsupported");
    }

    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array  $credentials
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials) {
        $isApiAuth = count($credentials) === 1
                   && array_key_exists('api_token', $credentials);
        if (!$isApiAuth) {
            throw new Exception("bad api credentials");
        }
        rr($credentials);
    }


    @todo créer table pour les api keys avec :
    - un uuid : la clé elle-même
    - un user_id pour trouver les feeditems appartenant à l'user
    - une liste de droits dans un simple champ texte, virgules sep' :
      read:*,push:articles,read:notifs,push:*


    /**
     * Validate a user against the given credentials.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  array  $credentials
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, array $credentials) {
        throw new Exception("validateCredentials unsupported");
    }
}
