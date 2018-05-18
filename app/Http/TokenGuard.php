<?php

namespace App\Http;

use Illuminate\Auth\TokenGuard as BaseTokenGuard;
/**
 * In this extended version, we only look for a token in the URL.
 */
class TokenGuard extends BaseTokenGuard
{
    /*public function getTokenForRequest()
    {
        // @todo look for an api token in the query string or in the routes parameters
    }*/
}
