<?php

namespace Enhacudima\DynamicExtract\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Http\Response;
use Enhacudima\DynamicExtract\DataBase\Model\Access;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cookie;
class AuthController extends Controller
{


    public function __construct()
    {
        $this->prefix = config('dynamic-extract.prefix');
    }

    public function welcome()
    {
        return  view('extract-view::welcome');
    }
    public function signIn(Request $request, $user)
    {
        // Check if the URL is valid
        if (!$request->hasValidSignature()) {
            abort(401);
        }

        // Authenticate the user
        $user = Access::findOrFail($request->user);
        if($user){
            $token = (string) Str::uuid();
            $minutes = 120;
            $cookie = cookie('access_user_token', $token, $minutes);
            return redirect($this->prefix.'/report/new')->withCookie($cookie);
        }
        // Redirect to homepage
        return redirect($this->prefix);
    }

    public function logout(Request $request)
    {
        // logout
        Cookie::queue(
            Cookie::forget('access_user_token')
        );
        // Redirect to homepage
        return redirect($this->prefix);
    }

}
