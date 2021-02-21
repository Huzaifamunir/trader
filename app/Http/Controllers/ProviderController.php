<?php

namespace App\Http\Controllers;

use App\City;
use App\User;
use App\Person;
use App\Provider;
use Illuminate\Http\Request;

class ProviderController extends Controller
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
    public function index(Request $request)
    {
        
        if($request->ajax()){
            $Providers=Provider::with('user')->get();

            return $Providers;
        }

        $Providers=Provider::orderBy('updated_at','desc')->paginate(20);
   
        return view("provider/index", compact("Providers"));
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
            "name" => "Add Provider",
            "submit" => "Save"
        ];

        $Cities = City::all();
        
        return view('provider/form',compact('form','Cities'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $person = Person::create($input);
        $user = $person->user()->create($input);
        $provider = $user->provider()->create($input);

        $request->session()->flash('message.level', 'success');
        $request->session()->flash('message.content', 'New Record Successfully Created !');
        $request->session()->flash('message.link', 'provider/'.$provider->id);

        return redirect('provider');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $Provider=Provider::find($id);
        
        return view('provider/single')->with(['Provider'=>$Provider]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $provider=Provider::find($id);

        $form=[
            "value" => "update",
            "name" => "Update Provider",
            "submit" => "Update"
        ];

        $Cities = City::all();

        return view('provider/form',compact('form','provider','Cities'));
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
        $input = $request->all();

        $Provider=Provider::find($id);
        $Provider->update($input);
        $Provider->user()->update($input);
        $Provider->user->person()->update($input);

        $request->session()->flash('message.level', 'warning');
        $request->session()->flash('message.content', 'Record Updated !');
        $request->session()->flash('message.link', 'provider/'.$id);
        
        return redirect('provider');
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
        $Provider=Provider::findOrFail($id);
        $Provider->delete();

        $request->session()->flash('message.level', 'error');
        $request->session()->flash('message.content', 'Record deleted!');
        
        return Redirect('provider');
    }

    /**
     * Search the specified resources.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {   
        $column=$request['column'];
        $keyword=$request['keyword'];
        
        if($column=='first_name'||$column=='last_name'||$column=='mobile_no'||$column=='email'){
            $Providers = Provider::with(['user.person'])->whereHas('user.person', function($query) use($column, $keyword) {
                $query->where($column, 'like', '%'.$keyword.'%');
            })->paginate(10);

            return view("provider/index", compact("Providers"));
        }
        else{
            $Providers = Provider::where($column, 'LIKE', "%".$keyword."%")->paginate(20);
            
            return view("provider/index", compact("Providers"));
        }
    }
}
