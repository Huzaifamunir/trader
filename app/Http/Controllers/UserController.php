<?php

namespace App\Http\Controllers;

use Auth;
use App\City;
use App\User;
use App\Person;
use App\Client;
use App\Salesman;
use App\Reseller;
use App\Provider;
use App\Setting;
use App\Color;
use App\Font;
use App\Permissions;
use Illuminate\Http\Request;

class UserController extends Controller
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
            $Users=User::all();

            return $Users;
        }

        $Users = [];

        $People = Person::with('user.client')->orderBy('first_name','asc')->paginate(100);

        foreach($People as $Person){
            $Users[] = [
                'id' => $Person['user']['id'],
                'name' => $Person['first_name'].' '.$Person['last_name'],
                'company' => $Person['user']['client']['company_name'],
                'username' => $Person['user']['username']
            ];
        }
        $Users = collect($Users);
        //$Users = $Users->paginate(10);
        //$Users=User::with('salesman')->has('salesman')->get();
        //$Users=User::all()->except($Users);
        //return $Users;
        
        return view("auth/index", compact("Users"));
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
            "name" => "Add User",
            "submit" => "Save"
        ];

        $Cities=City::all();

        return view('user/form',compact('form','Cities'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $this->validate($request, Person::$rules);
        
        if($request->all()['user_type']=='salesman'){
            $this->validate($request, [
                'assigned_area' => 'required|numeric',
                'comment' => 'nullable|string'
            ]);
        }
        elseif($request->all()['user_type']=='reseller'){
            $this->validate($request, [
                //'user_id' => 'required|numeric',
                'comment' => 'nullable|string'
            ]);
        }
        elseif($request->all()['user_type']=='provider'){
            $this->validate($request, [
                //'user_id' => 'required|numeric',
                'provider_company_name' => 'required|string',
                'comment' => 'nullable|string',
            ]);
        }
        elseif($request->all()['user_type']=='client'){
            $this->validate($request, [
                'client_company_name' => 'required|string',
                'tax_no' => 'required|string',
                'hrb_no' => 'required|string',
                'start_bal' => 'required|regex:/^\d*(\.\d{2})?$/',
                'current_bal' => 'nullable|regex:/^\d*(\.\d{2})?$/',
                'comment' => 'nullable|string'
            ]);
        }
        
        $this->validate($request, User::$rules);
        
        $Person=Person::create($request->all());

        $user=[
            'person_id' => $Person->id,
            'user_type' => $request->all()['user_type'],
            //'title' => $request->all()['title'],
            //'user_group' => $request->all()['user_group'],
            'api_token' => str_random(60),
            'username' => $request->all()['username'],
            'password' => bcrypt($request->all()['password']),
        ];
        $User=User::create($user);

        if($request->all()['user_type']=='salesman'){
            $salesman=[
                "user_id" => $User->id,
                "city_id" => $request->all()['assigned_area'],
                "comment" => $request->all()['comment']
            ];
            $Salesman=Salesman::create($salesman);
        }
        elseif($request->all()['user_type']=='reseller'){
            $reseller=[
                "user_id" => $User->id,
                "comment" => $request->all()['comment']
            ];
            $Reseller=Reseller::create($reseller);
        }
        elseif($request->all()['user_type']=='provider'){
            $provider=[
                "user_id" => $User->id,
                "company_name" => $request->all()['provider_company_name'],
                "comment" => $request->all()['comment'],
            ];
            $Provider=Provider::create($provider);
        }
        elseif($request->all()['user_type']=='client'){
            $client=[
                "user_id" => $User->id,
                "parent_id" => Auth::User()->id,
                "company_name" => $request->all()['client_company_name'],
                "tax_no" => $request->all()['tax_no'],
                "hrb_no" => $request->all()['hrb_no'],
                "start_bal" => $request->all()['start_bal'],
                "current_bal" => 0.00,
                "comment" => $request->all()['comment']
            ];
            $Client=Client::create($client);
        }

        factory(Setting::class, 1)
        ->create([
            'user_id' => $User->id
        ]);

        factory(Color::class, 1)
        ->create([
            'user_id' => $User->id
        ]);

        factory(Font::class, 1)
        ->create([
            'user_id' => $User->id
        ]);

        foreach(User::$entities as $entity){
            factory(Permissions::class)->create([
                'user_id' => $User->id,
                'entity'  => $entity
            ]);    
        }

        $request->session()->flash('message.level', 'success');
        $request->session()->flash('message.content', 'New Record Successfully Created !');
        $request->session()->flash('message.link', 'user/'.$User->id);
        
        return redirect('user');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $User=User::find($id);
        
        return view('auth/single')->with(['User'=>$User]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $User=User::find($id);

        $form=[
            "value" => "update",
            "name" => "Update User",
            "submit" => "Update"
        ];

        return view('user/form',compact('form','User'));
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
        $this->validate($request, User::$rules);

        $User=User::find($id);
        $User->update($request->all());

        $request->session()->flash('message.level', 'warning');
        $request->session()->flash('message.content', 'Record Updated !');
        $request->session()->flash('message.link', 'user/'.$id);
        
        return redirect('user');
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
        $User=User::findOrFail($id);
        $User->delete();

        $request->session()->flash('message.level', 'error');
        $request->session()->flash('message.content', 'Record deleted!');
        
        return Redirect('user');
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
        
        if($column=='first_name'||'last_name'||'email'){
            $Users = User::with(['person'])->whereHas('person', function($query) use($column, $keyword) {
                $query->where($column, 'like', '%'.$keyword.'%');
            })->orWhere('title','LIKE','%'.$keyword.'%')->paginate(20);

            return view("auth/index", compact("Users"));
        }
        else{
            $Users=User::where($column, 'LIKE', "%".$keyword."%")->paginate(20);
            
            return view("auth/index", compact("Users"));
        }
    }
}
