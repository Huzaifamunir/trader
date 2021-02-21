<?php

namespace App\Http\Middleware;

use Closure;
use App\User;

class ApiAuth
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
        if(isset($request->all()['headers'])){
            $User=User::find($request->all()['headers']['user_id']);
            
            if($User == Null){
                return response()->json(['error' => 'Requested user does not exist.', 'status_code' => '193']);
            }
            
            $UserToken = $User->api_token;
            
            if($request->all()['headers']['api_token']==$UserToken){
                return $next($request);
            }
            else{
                return response()->json(['error' => 'Your ApiToken is invalid.', 'status_code' => '199']);
            }
        }
        else{
            return response()->json(['error' => 'Headers are not included with your request.', 'status_code' => '197']);
        }
    }
}
