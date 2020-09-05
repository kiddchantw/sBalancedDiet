<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function registerAPI(Request $request)
    {
        try {
            $rules = [
                "name" => "required|string | between:6,12 ",
                "email" => "required| email | unique:users,email",
                "password" => "required|string| between:6,12 | regex:/^[A-Za-z0-9]+$/",
            ];
            $message = [   // 欄位名稱.驗證方法名稱
                "name.required" => "請輸入名稱",
                "email.required" => "請輸入email",
                "password.required" => "請輸入密碼",
                "name.between" => "name 字數需6~12",
                "password.between" => "password 字數需6~12",
                "email.email" => "email格式錯誤",
                "password.regex" => "請勿輸入特殊符號",
                "email.unique" => "email已使用",
            ];
            $validResult = $request->validate($rules, $message);
        } catch (ValidationException $exception) {

            $errorObject = $exception->validator->getMessageBag()->getMessages();
            $errorMessage = '';
            foreach($errorObject as $key => $value) {
                $errorMessage =  $value[0];
            }
            return response()->json(['message' => $errorMessage], 201);
        }

        //method 1 ok
//        $newRegister = new User;
//        $newRegister->name = $request->name;
//        $newRegister->email = $request->email;
//        $newRegister->password = $request->password;
//        $newRegister->save();

        //method 2 可以寫入但會報
        //SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry '' for key 'users.users_email_unique'
        //在model加入 guarded 屬性（因為 Eloquent 預設會防止批量賦值）。才不會報錯
        //protected $guarded = ['id', 'account_id'];

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ]);


        return response()->json(['message' => 'register success , please login '], 201);
    }
}
