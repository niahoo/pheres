<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')
            ->except('logout')
            ->except('loginChekTxt')
            ;
    }

    public function loginChekTxt()
    {
        $resp = \Auth::user() ? 'true' : 'false';
        return response($resp)
            ->header('Access-Control-Allow-Origin', $_SERVER['HTTP_ORIGIN'] ?? '*')
            ->header('Access-Control-Allow-Credentials', 'true')
            ;

    }
}
