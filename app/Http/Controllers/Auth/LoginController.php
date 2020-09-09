<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\User;
use Illuminate\Validation\ValidationException;


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


    public $messageValidate = [
        "password.required" => "請輸入password",
        "password.regex" => "請確認password符合 A-Za-z0-9 ",
        "password.between" => "password 字數需6~12",
        "email.required" => "請輸入email",
        "email.email" => "請確認格式",
        "name.required" => "請輸入name",
        "name.unique" => "name exist",
    ];


    public function customValidate(Request $request, array $rulesInput)
    {
        try {
            $this->validate($request, $rulesInput, $this->messageValidate);
        } catch (ValidationException $exception) {
//            $errorMessage = $exception->validator->errors()->first();
            $errorMessage = $exception->validator->getMessageBag();

            return $errorMessage;
        }
    }

    //API_2
    public function loginAPI(Request $request)
    {
        $rules = [
            "email" => "required| email ",
            "password" => "required|string | between:6,12 | regex:/^[A-Za-z0-9]+$/",
        ];
        $validResult = $this->customValidate($request, $rules);
        if ($validResult != Null) {
            return response()->json(['message' => $validResult], 400);
        }


        $credentials = $request->only('name', 'password', 'email');
        if (Auth::attempt($credentials)) {
            do {
                $loginToken = Str::random(60);
                $checkTokenExist = User::where('remember_token', '=', $loginToken)->first();
            } while ($checkTokenExist);

            $userL = User::where('email', '=', $request->email)->first();
            $userL->remember_token = $loginToken;
            $userL->token_expire_time = date('Y/m/d H:i:s', time() + 10 * 60);
            $userL->save();
            $response = array("remember_token" => $userL->remember_token, "token_expire_time" => $userL->token_expire_time);

        } else {
            $response = "login error";
        }

        return response()->json(['message' => $response], 200);
    }

//API_3
    public function userInfo(Request $request)
    {
        $inputToken = $request->remember_token;
        if ($inputToken !== null & $inputToken !== "") {
            $userA = User::where('remember_token', '=', $inputToken)->first();
            if ($userA) {
                return $userA;
            }
        }
    }


//API_4
    public function logoutAPI(Request $request)
    {
        $user = $request->user();
        $user->remember_token = Null;
        $user->token_expire_time = Null;
        $user->save();

        return response()->json(['message' => "logout success!"], 200);
    }



    //API_5
    public function uploadImageAPI(Request $request)
    {
        $userPhotoId = $request->user()->id;
        $image = $request->file('photo');
//        $filename = $image->getClientOriginalName();
        $filename = $userPhotoId . "_" . $image->getClientOriginalName();

        Storage::disk('publicUser')->put($filename, file_get_contents($image->getRealPath()));
        $photoURL = Storage::disk('publicUser')->url($filename);
        User::where('id', '=', $userPhotoId)->update(['image_path' => $photoURL]);

        $response = array('url' => $photoURL);
        return response()->json(['message' => $response], 200);
    }


    //API_6 reset
    public function resetPasswordAPI (Request $request)
    {
        $rules6 = [
            "password" => "required|string | between:6,12 | regex:/^[A-Za-z0-9]+$/"
        ];
        $validResult = $this->customValidate($request, $rules6);
        if ($validResult != Null) {
            return response()->json(['message' => $validResult], 400);
        }

        $userId = $request->user()->id;
//        dd($request->user());
//        $userId = $request->id;

        $resetPW = Hash::make($request->password );
        User::where('id', '=', $userId)->update(['password' => $resetPW]);

//        $this->logout($request);
//message": "Session store not set on request.",
//    "exception": "RuntimeException",
//    "file": "/usr/local/var/wwwa/gcp/balancedDiet/vendor/laravel/framework/src/Illuminate/Http/Request.php",
//    "line": 483,
        User::where('id', '=', $userId)->update(['token_expire_time' => Null]);

//        $user = $request->user();
//        $user->remember_token = Null;
//        $user->token_expire_time = Null;
//        $user->save();

        return response()->json(['message' => "reset success , please login "], 200);



    }


}
