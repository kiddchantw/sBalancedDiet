<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\User;

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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    public function loginAPI(Request $request)
    {
        $credentials = $request->only('name', 'password','email');
        if (Auth::attempt($credentials)) {
            do {
                $loginToken = Str::random(60);
                $checkTokenExist = User::where('remember_token', '=', $loginToken)->first();
            } while ($checkTokenExist);

            $userL = User::where('email', '=', $request->email)->first();
            $userL->remember_token =  $loginToken;
            $userL->token_expire_time = date('Y/m/d H:i:s', time() + 10 * 60);
            $userL->save();
            $response = array("remember_token" => $userL->remember_token, "token_expire_time" => $userL->token_expire_time) ;

        } else {
            $response = "login error";
        }

        return response()->json(['message' => $response], 200);
    }


    public function userInfo(Request $request)
    {
        $inputToken = $request->remember_token ;
        if ($inputToken !== null & $inputToken !== "") {
            $userA = User::where('remember_token', '=', $inputToken)->first() ;
            if ($userA ){
                return $userA ;
            }
        }
    }



    public function logoutAPI(Request $request)
    {
        $user = $request->user();
        $user->remember_token = Null;
        $user->token_expire_time = Null;
        $user->save();

        return response()->json(['message' => "logout success!"], 200);
    }



}
