<?php

namespace App\Http\Controllers;

use App\User;
use App\City;
use App\Sale;
use App\Person;
use App\Client;
use App\Payment;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function __construct()
    {
        
    }

    public function index(Request $request)
    {
    
        if($request->ajax()){
            $Clients = Client::with(['user'])->get();
            
            return $Clients;
        }

        $Clients=Client::orderBy('id','desc')->paginate(1000);
          //$Clients=Client::all();
         //$Clients = $Clients->sortBy('id');
        //dd($Clients);
        //var_dump($Clients);exit;
         
        return view("client/index", compact("Clients"));
    
    }


    public function create()
    {
        $form=[
            "value" => "add",
            "name" => "Add Client",
            "submit" => "Save"
        ];
        
        $Cities = City::all();

        return view('client/form',compact('form','Cities'));
    }

    public function store(Request $request)
    {   
        $this->validate($request, Client::$rules);
        
        $input = $request->all();

        $person = Person::create($input);

        $user = $person->user()->create($input);

        $input['current_bal'] = $input['start_bal'];
        $client = $user->client()->create($input);

        $request->session()->flash('message.level', 'success');
        $request->session()->flash('message.content', 'New Record Successfully Created !');
        $request->session()->flash('message.link', 'client/'.$client->id);

        return redirect('client');
    }

    public function show($id)
    {
        $Client=Client::find($id);
        
        return view('client/single')->with(['Client'=>$Client]);
    }

    public function edit($id)
    {
        $client = Client::with('user.person.city')->find($id);

        $form=[
            "value" => "update",
            "name" => "Update Client",
            "submit" => "Update"
        ];

        $Cities = City::all();

        return view('client/form',compact('form','client','Cities'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, Client::$rules);

        $Client = Client::find($id);

        $Client->update($request->all());
        $Client->user()->update($request->all());
        $Client->user->person()->update($request->all());

        $request->session()->flash('message.level', 'warning');
        $request->session()->flash('message.content', 'Record Updated !');
        $request->session()->flash('message.link', 'client/'.$id);
        
        $request->flash();

        return redirect('client');
    }

    public function destroy(Request $request, $id)
    {
        $Client=Client::findOrFail($id);
        $Client->delete();

        $request->session()->flash('message.level', 'error');
        $request->session()->flash('message.content', 'Record deleted!');
        
        return Redirect('client');
    }

    public function search(Request $request)
    {   
        $column=$request['column'];
        $keyword=$request['keyword'];
        
        if($column=='first_name'||$column=='last_name'||$column=='mobile_no'||$column=='email'){
            $Clients = Client::with(['user.person'])->whereHas('user.person', function($query) use($column, $keyword) {
                $query->where($column, 'like', '%'.$keyword.'%');
            })->paginate(20);

            return view("client/index", compact("Clients"));
        }
        else{
            $Clients = Client::where($column, 'LIKE', "%".$keyword."%")->paginate(20);
            
            return view("client/index", compact("Clients"));
        }
    }

    public function ledger($id)
    {   
        $client = Client::with('user.person.city')->find($id);

        $sales = Sale::with('seller','payment')->where('client_id',$id)->get();
        $total_sales = Sale::with('seller','payment')->where('client_id',$id)->sum('total_amount');

        $payments = Payment::with('receiver','payer')->where('payer_id',$client['user_id'])->get();
        $total_payments = Payment::with('receiver','payer')->where('payer_id',$client['user_id'])->sum('amount');

        return view("client/ledger", compact("client","sales","payments","total_sales","total_payments"));
    }
    public function clientReport($id)
    {
       $Clients=Client::orderBy('id','desc')->paginate(1000);
       $totalBalance=Client::whereNotNull('id')->sum('current_bal');
       //dd($totalBalance);
       return view("client/client-reports", compact("Clients","totalBalance"));
    }
}
