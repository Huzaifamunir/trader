<?php

namespace App\Http\Controllers;

use App\Product;
use App\Ecommerce;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EcommerceController extends Controller
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
            $Products=Product::all('id','name','sale_price');

            return $Products;
        }

        $Products=Product::orderBy('updated_at','desc')->paginate(20);
        
        return view("ecommerce/index", compact("Products"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
     * @param  \App\Ecommerce  $ecommerce
     * @return \Illuminate\Http\Response
     */
    public function show(Ecommerce $ecommerce)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Ecommerce  $ecommerce
     * @return \Illuminate\Http\Response
     */
    public function edit(Ecommerce $ecommerce)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Ecommerce  $ecommerce
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ecommerce $ecommerce)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Ecommerce  $ecommerce
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ecommerce $ecommerce)
    {
        //
    }
}
