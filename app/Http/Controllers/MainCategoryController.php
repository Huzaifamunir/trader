<?php

namespace App\Http\Controllers;

use App\MainCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MainCategoryController extends Controller
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
            $MainCategories=MainCategory::all()->unique('name');
            
            return $MainCategories;
        }

        $MainCategories=MainCategory::orderBy('name','asc')->paginate(100);

        return view("main_category/index", compact("MainCategories"));
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
            "name" => "Add MainCategory",
            "submit" => "Save"
        ];

        // $Users=User::all();
        // foreach($Users as $User){
        //     $Users_list[]=array('id'=>$User->id,'name'=>$User->person->first_name." ".$User->person->last_name);
        // }
        // $Users_list=collect($Users_list);

        return view('main_category/form',compact('form','Users_list'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, MainCategory::$rules);

        $MainCategory=MainCategory::create($request->all());

        $request->session()->flash('message.level', 'success');
        $request->session()->flash('message.content', 'New Record Successfully Created !');
        $request->session()->flash('message.link', 'main_category/'.$MainCategory->id);

        return redirect('main_category');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\MainCategory  $mainCategory
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $mainCategory=MainCategory::find($id);

        return view('main_category/single')->with(['MainCategory'=>$mainCategory]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MainCategory  $mainCategory
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $MainCategory=MainCategory::find($id);

        $form=[
            "value" => "update",
            "name" => "Update MainCategory",
            "submit" => "Update"
        ];

        return view('main_category/form',compact('form','MainCategory')); 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MainCategory  $mainCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, MainCategory::$rules);

        $MainCategory=MainCategory::find($id);
        $MainCategory->update($request->all());

        $request->session()->flash('message.level', 'warning');
        $request->session()->flash('message.content', 'Record Updated !');
        $request->session()->flash('message.link', 'main_category/'.$id);
        
        return redirect('main_category');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MainCategory  $mainCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {   
        return redirect('ud');
        $MainCategory=MainCategory::findOrFail($id);
        $MainCategory->delete();

        $request->session()->flash('message.level', 'error');
        $request->session()->flash('message.content', 'Record deleted!');
        
        return Redirect('main_category');
    }

    /**
     * Search specified resource.
     *
     * @param  \App\MainCategory  $mainCategory
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        return view('under_development');
        $column=$request['column'];
        $keyword=$request['keyword'];
        
        if($column=='first_name'||$column=='last_name'||$column=='mobile_no'||$column=='email'){
            $MainCategorys = MainCategory::with(['user.person'])->whereHas('user.person', function($query) use($column, $keyword) {
                $query->where($column, 'like', '%'.$keyword.'%');
            })->paginate(20);

            return view("main_category/index", compact("MainCategorys"));
        }
        else{
            $MainCategorys=MainCategory::where($column, 'LIKE', "%".$keyword."%")->paginate(20);
            
            return view("main_category/index", compact("MainCategorys"));
        }
    }
}
