<?php

namespace App\Http\Controllers;

use Redirect;
use App\Item;
use App\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Enforce middlewares.
     */
    public function __construct()
    {
        
    }
    public function product_detail(Request $request)
    {
        if($request->ajax()){
            $Products=Product::all();

            return $Products;
        }

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {  
        
        $Products=Product::orderBy('name','asc')->paginate(100);
        
        return view("product/index", compact("Products"));
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
            "name" => "Add Product",
            "submit" => "Save"
        ];

        return view('product/form',compact('form'));
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

        $this->validate($request, Product::$rules);
        
        if(isset($input['image'])){
            $image = $request->image;
            $destinationPath = public_path('/img/product/');
            $image_name = time()."_gtl.".$image->getClientOriginalExtension();
            $image->move($destinationPath,$image_name);
            $input['image'] = $image_name;
        }

        $Product = Product::create($input);

        $request->session()->flash('message.level', 'success');
        $request->session()->flash('message.content', 'New Record Successfully Created !');
        $request->session()->flash('message.link', 'product/'.$Product->id);

        return Redirect('product');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        if($request->ajax()){
            $Product = Product::find($id)->toArray();

            return $Product;
        }

        $Product = Product::with('sub_category.main_category')->find($id);
        
        return view('product/single', compact('Product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $Product=Product::find($id);

        $form=[
            "value" => "update",
            "name" => "Update Product",
            "submit" => "Update"
        ];

        return view('product/form',compact('form','Product'));
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
        
        $update_rules=Product::$rules;
        $update_rules['model']='required|string|unique:products,model,'.$id;
        $this->validate($request, $update_rules);

        if(isset($input['image'])){
            $image = $request->image;
            $destinationPath = public_path('/img/product/');
            $image_name = time()."_gtl.".$image->getClientOriginalExtension();
            $image->move($destinationPath,$image_name);
            $input['image'] = $image_name;
        }

        $Product=Product::find($id);
        $Product->update($input);

        $request->session()->flash('message.level', 'warning');
        $request->session()->flash('message.content', 'Record Updated !');
        $request->session()->flash('message.link', 'product/'.$id);
        
        return Redirect('product');
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
        $Product = Product::with('sales')->findOrFail($id);

        if($Product['sales']->isEmpty()){
            $Product->delete();
            
            $request->session()->flash('message.level', 'error');
            $request->session()->flash('message.content', 'Record deleted!');

            return Redirect('product');
        }
        else{
            $request->session()->flash('message.level', 'error');
            $request->session()->flash('message.content', 'Product is refered in sale.');

            return Redirect::back();
        }

        return Redirect('product');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {   
        $Products=Product::where($request['column'], 'LIKE', "%".$request['keyword']."%")->paginate(10);
        
        return view("product/index", compact("Products"));
    }
}
