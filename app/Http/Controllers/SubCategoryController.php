<?php

namespace App\Http\Controllers;

use App\SubCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SubCategoryController extends Controller
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
            $SubCategories=SubCategory::all()->unique('name');
            
            return $SubCategories;
        }

        $SubCategories=SubCategory::orderBy('name','asc')->paginate(100);
        
        return view("sub_category/index", compact("SubCategories"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $form=[
            "value"  => "add",
            "name"   => "Add SubCategory",
            "submit" => "Save"
        ];

        // $Users=User::all();
        // foreach($Users as $User){
        //     $Users_list[]=array('id'=>$User->id,'name'=>$User->person->first_name." ".$User->person->last_name);
        // }
        // $Users_list=collect($Users_list);

        return view('sub_category/form',compact('form','Users_list'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, SubCategory::$rules);

        $SubCategory=SubCategory::create($request->all());

        $request->session()->flash('message.level', 'success');
        $request->session()->flash('message.content', 'New Record Successfully Created !');
        $request->session()->flash('message.link', 'sub_category/'.$SubCategory->id);

        return redirect('sub_category');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SubCategory  $subCategory
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $SubCategory=SubCategory::find($id);
        
        return view('sub_category/single')->with(['SubCategory'=>$SubCategory]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SubCategory  $subCategory
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $SubCategory=SubCategory::find($id);

        $form=[
            "value" => "update",
            "name" => "Update SubCategory",
            "submit" => "Update"
        ];

        return view('sub_category/form',compact('form','SubCategory'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SubCategory  $subCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, SubCategory::$rules);

        $SubCategory=SubCategory::find($id);
        $SubCategory->update($request->all());

        $request->session()->flash('message.level', 'warning');
        $request->session()->flash('message.content', 'Record Updated !');
        $request->session()->flash('message.link', 'sub_category/'.$id);
        
        return redirect('sub_category');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SubCategory  $subCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return redirect('ud');
        $SubCategory=SubCategory::findOrFail($id);
        $SubCategory->delete();

        $request->session()->flash('message.level', 'error');
        $request->session()->flash('message.content', 'Record deleted!');
        
        return Redirect('sub_category');
    }

    /**
     * Search specified resource.
     *
     * @param  \App\SubCategory  $mainCategory
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        return redirect('ud');
    }
}
