<?php

namespace App\Http;

use Illuminate\Auth\TokenGuard as BaseTokenGuard;
/**
 * In this extended version, we only look for a token, not in inputs.
 */
class TokenGuard extends BaseTokenGuard
{
}
