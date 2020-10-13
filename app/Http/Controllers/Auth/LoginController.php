<?php

namespace App\Http\Controllers\Auth;

use App\bioProfile;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\userDiet;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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


    public function customValidate(Request $request, array $rulesInput)
    {
        try {
            $this->validate($request, $rulesInput, $this->messageValidate);
        } catch (ValidationException $exception) {
            $errorMessage = $exception->validator->errors()->first();
//            $errorMessage = $exception->validator->getMessageBag();
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
//            return response()->json(['message' => $validResult], 400);
            return response()->json(['success' => false, 'message' => $validResult, 'data' => null], 400);
        }


        $credentials = $request->only('name', 'password', 'email');
        if (Auth::attempt($credentials)) {
            do {
                $loginToken = Str::random(60);
                $checkTokenExist = User::where('remember_token', '=', $loginToken)->first();
            } while ($checkTokenExist);

            $userL = User::where('email', '=', $request->email)->first();
            $userL->remember_token = $loginToken;
            $userL->token_expire_time = date('Y/m/d H:i:s', time() + 24 * 60 * 60);  //10 * 60
            $userL->save();


            $userL_id = $userL->id;
//            $userL_standard = userDiet::where([['user_id', '=', $userL_id], ['kind', '=', 1]])->get();
//            $userL_weight = bioProfile
//                //::select(DB::raw('weight'))
//                ::where('user_id', '=', $userL_id)
//                ->orderBy('updated_at', 'desc')
//                ->first()->weight;
//            dd($userL_weight);

            $response = array(
                "remember_token" => $userL->remember_token,
                "token_expire_time" => $userL->token_expire_time,
                "user_id" => $userL_id,
//                "height_is_null"=> is_null($userL->height),
//                'weight_is_null'=> is_null($userL_weight),
//                "gender_is_null"=> is_null($userL->gender),
//                "birthday_is_null"=> is_null($userL->birthday),
//                "diet_standard_is_null" =>  is_null($userL_standard)
            );
            return response()->json(['success' => true, 'message' => "", 'data' => $response], 200);


        } else {
            $response = "login error";
            return response()->json(['success' => false, 'message' => $response, 'data' => null], 400);

        }

//        return response()->json(['message' => $response], 200);
    }

//API_3
    public function userInfo(Request $request)
    {
        $inputToken = $request->remember_token;
        if ($inputToken !== null & $inputToken !== "") {
            $userA = User::where('remember_token', '=', $inputToken)->first();
            if ($userA) {
//                return $userA;

                $userW = $userA->currentBio();//->weight;

                $response = array(
                    "id" => $userA->id,
                    "name" => $userA->name,
                    "email" => $userA->email,
                    "created_at" => $userA->created_at,
                    "image_path" => $userA->image_path,
                    "height" => $userA->height,
                    "weight" => (is_null($userW)) ? null:$userW->weight,
                    "gender" => $userA->gender,
                    "birthday" => $userA->birthday,
                    "diet_standard" => $userA->currentStandard()
                );
                return response()->json(['success' => true, 'message' => "", 'data' => $response], 200);

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
        return response()->json(['success' => true, 'message' => "logout success!", 'data' => null], 200);
//        return response()->json(['message' => "logout success!"], 200);
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
//        return response()->json(['message' => $response], 200);
        return response()->json(['success' => true, 'message' => "upload success", 'data' => $response], 200);

    }




    //API_6 reset
    public function resetPasswordAPI(Request $request)
    {
        $rules6 = [
//            "email" => "nullable| email | unique:users,email",
            "password" => "required|string | between:6,12 | regex:/^[A-Za-z0-9]+$/"
        ];
        $validResult = $this->customValidate($request, $rules6);
        if ($validResult != Null) {
            return response()->json(['success' => false, 'message' => $validResult, 'data' => null], 400);
        }


        $userId = $request->user()->id;
//        dd($request->user());
//        $userId = $request->id;

        $resetPW = Hash::make($request->password);
        User::where('id', '=', $userId)->update(['password' => $resetPW]);

        User::where('id', '=', $userId)->update(['token_expire_time' => Null]);

        return response()->json(['success' => true, 'message' => "reset password success", 'data' => null], 200);

    }


    //API_07_forgetPassword
    public function forgetPasswordAPI(Request $request)
    {
        $rules6 = [
            "email" => "required| email | exists:users",
            "password" => "required|string | between:6,12 | regex:/^[A-Za-z0-9]+$/"
        ];
        $validResult = $this->customValidate($request, $rules6);
        if ($validResult != Null) {
            return response()->json(['success' => false, 'message' => $validResult, 'data' => null], 400);
        }

        $resetPW = Hash::make($request->password);
        User::where('email', '=', $request->email)
            ->update(['password' => $resetPW], ['token_expire_time' => Null]);

        return response()->json(['success' => true, 'message' => "new password is ready, please login again", 'data' => null], 200);

    }


    public $messageValidate = [
        "password.required" => "請輸入password",
        "password.regex" => "請確認password符合 A-Za-z0-9 ",
        "password.between" => "password 字數需6~12",
        "email.required" => "請輸入email",
        "email.email" => "請確認格式",
        "email.exists" => "請確認email",
        "name.required" => "請輸入name",
        "name.unique" => "name exist",
        "height.numeric" => "請輸入數字",
        "gender.in" => "請確認性別",
        "birthday.date_format" => "請確認日期格式",
        "name.between" => "name 字數需6~12",
        "height.between" => "請輸入數字0~250.0",
        "height.regex" => "小數點後1位",


    ];


    //API_08_userProfile
    public static $profileColumn = ['name','height', 'gender', 'birthday'];

    public function editUserProfile(Request $request)
    {
        $rules6 = [
            "height" => "nullable | numeric | regex:/^[0-9]+(\.[0-9]??)?$/ |  between:0,250.0",
            "gender" => "nullable| in:male,female,others",
            "birthday" => "nullable| date_format:Y-m-d",
            'name' => "nullable | between:1,10"
        ];
        $validResult = $this->customValidate($request, $rules6);
        if ($validResult != Null) {
            return response()->json(['success' => false, 'message' => $validResult, 'data' => null], 400);
        }


        $userId = $request->user()->id;
        $strAlert = "";
        foreach (self::$profileColumn as $value) {
            if ($request->filled($value)) {
                //$userDiet->$value = $request->$value;
                $userP = User::where('id', '=', $userId)->update([$value =>$request->$value]);
                if ($userP == true) {
                    $strAlert = $value.",".$strAlert;
                }
            }
        }

        if ($strAlert == ""){
            return response()->json(['success' => false, 'message' => "update  error", 'data' => null], 400);
        }else{
            return response()->json(['success' => true, 'message' => $strAlert."update success", 'data' => null], 400);
        }
    }

}
