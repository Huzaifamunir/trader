<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\User;
use Hash;
use DB;

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
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function login(Request $request)
    {
        // DB::table('people')-> join('user', 'people.id', '=', 'user.id')->where('user_id',$id)->get();
        $username=$request->input('username');
        $password=$request->input('password');
            $users =  DB::table('users')-> join('people', 'users.id', '=', 'people.id')->where('username', $request->username)
                  ->where('password',md5($request->password))
                  ->first();
                  
        if($users){
            
            $request->session()->put('user',$users);
        return redirect('home');
        }else
        {
            dd('incorrect');
        }
    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'username';
    }
}
