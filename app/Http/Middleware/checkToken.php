<?php

namespace App\Http\Middleware;

use Closure;
use App\User;

class checkToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
//        return $next($request);
        $inputToken = $request->remember_token;

        if ($inputToken !== null & $inputToken !== "") {

            $user = User::where('remember_token', '=', $inputToken)->first();

            if ($user) {
                $nowTimeStr = strtotime(date('Y/m/d H:i:s', time()));
                $tokenTimeStr = strtotime($user->token_expire_time);
                if ($tokenTimeStr > $nowTimeStr) {
                    $request->merge(['user' => $user]);
                    $request->setUserResolver(function () use ($user) {
                        return $user;
                    });
                    return $next($request);
                }else{
                    return response()->json(['message' => 'User Token expired '], 404);
                }
            } else {
                return response()->json(['message' => 'User Token not found in checkToken!'], 404);
            }
        } else {
            return response()->json(['message' => 'User Token ?'], 404);
        }
    }
}
