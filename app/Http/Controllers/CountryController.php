<?php

namespace App\Http\Controllers;

use App\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    /**
     * Enforce middlewares.
     */
    public function __construct()
    {
        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Countries = Country::orderBy('name','asc')->paginate(100);
        
        return view("country/index", compact("Countries"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $form=[
            "value" => "add",
            "name" => "Add Country",
            "submit" => "Save"
        ];

        return view('country/form',compact('form'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, Country::$rules);

        $Country=Country::create($request->all());

        $request->session()->flash('message.level', 'success');
        $request->session()->flash('message.content', 'New Record Successfully Created !');
        $request->session()->flash('message.link', 'country/'.$Country->id);

        return redirect('country');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $Country=Country::find($id);
        
        return view('country/single')->with(['Country'=>$Country]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $Country=Country::find($id);

        $form=[
            "value" => "update",
            "name" => "Update Country",
            "submit" => "Update"
        ];

        return view('country/form',compact('form','Country'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $update_rules=Country::$rules;
        $update_rules['name']='required|string|unique:countries,name,'.$id;
        $this->validate($request, $update_rules);

        $Country=Country::find($id);
        $Country->update($request->all());

        $request->session()->flash('message.level', 'warning');
        $request->session()->flash('message.content', 'Record Updated !');
        $request->session()->flash('message.link', 'country/'.$id);
        
        return redirect('country');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $Country=Country::findOrFail($id);
        $Country->delete();

        $request->session()->flash('message.level', 'error');
        $request->session()->flash('message.content', 'Record deleted!');
        
        return Redirect('country');
    }

    /**
     * Search the specified resources.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {   
        $Countries=Country::where($request['column'], 'LIKE', "%".$request['keyword']."%")->paginate(10);
        
        return view("country/index", compact("Countries"));
    }
}
