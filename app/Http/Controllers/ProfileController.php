<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;
class ProfileController extends Controller
{
    public function __construct(){

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $User=Auth::user();


        return view('profile.index')->with(['User'=>$User]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $id=session()->get('user')->id;
        
        $user = User::find($id);
        $form=[
            "value" => "update",
            "name" => "Edit Profile",
            "submit" => "Save"
        ];

        return view('profile.form',compact('user','form'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {   
        $id=session()->get('user')->id;
        $rules =
            [
                'password' => 'nullable|alpha_num|between:6,20|confirmed'
            ];
        $this->validate($request,$rules);
        $user = User::find($id);
        $user->person->first_name=$request->first_name;
        $user->person->last_name=$request->last_name;
        $user->person->mobile_no=$request->mobile_no;
        $user->person->address=$request->address;
        $user->person->save();
        if(!empty($request->password)){

            $user->password=md5($request->password);
            $user->save();
        }


        return redirect('profile')->with('status', 'Profile updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
