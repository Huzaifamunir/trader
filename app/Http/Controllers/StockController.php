<?php

namespace App\Http\Controllers;

use File;
use Auth;
use App\Item;
use App\Stock;
use App\Product;
use App\StockItems;
use Illuminate\Http\Request;

class StockController extends Controller
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
            $stock_items=Item::has('product')->where('state','=','stock')->get();
            
            return $stock_items;
        }
            
        $Stocks=Stock::with('user','provider')->orderBy('updated_at','desc')->paginate(20);
        //return $Stocks;
        return view("stock/index")->with(["Stocks" => $Stocks]);
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
            "name" => "Add Stock",
            "submit" => "Save"
        ];

        return view('stock/form',compact('form'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        // dd($request->all());
        $input =  $request->all();
        // dd($input);
        $stock_items = [];

        foreach($input['product_id'] as $key => $item){
            $stock_items[] = [
                'product_id' => $input['product_id'][$key],
                'price_per_unit' => $input['price_per_unit'][$key],
                'quantity' => $input['quantity'][$key],
                'sub_total' => $input['sub_total'][$key],
            ];  

            $product = Product::find($input['product_id'][$key]);
        
            // update stock
            $product->increment('current_stock',$input['quantity'][$key]);

            // update purchase price
            $product->update(['purchase_price'=>$input['price_per_unit'][$key]]);
        }
        
        // Validation phase
        $Stock = Stock::create($input);

        $Stock->items()->createMany($stock_items);

        $request->session()->flash('message.level', 'success');
        $request->session()->flash('message.content', 'New Record Successfully Created !');
        $request->session()->flash('message.link', 'stock/'.$Stock->id);
        
        return redirect('stock');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $Stock = Stock::with('items','provider')->find($id);
        
        return view('stock/single', compact('Stock'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
        $stock = Stock::with('provider')->find($id);

        $form=[
            "value" => "update",
            "name" => "Update Stock",
            "submit" => "Update"
        ];

        return view('stock/form',compact('form','stock'));
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
        $this->validate($request, Stock::$rules);
        $input = $request->all();

        $Stock = Stock::find($id);

        $old_items = $Stock['items'];
        $new_items = [];
        
        // delete old items
        foreach ($old_items as $key => $item) {
            // add product back to stock
            $product = Product::find($item['product_id']);
            $new_stock = $product['current_stock']-$item['quantity'];
            $product->update(['current_stock'=>$new_stock]);

            // delete item
            $sale_item = StockItems::find($item['id']);        
            $sale_item->delete();
        }

        foreach($input['product_id'] as $key => $item) {
            $new_items[] = [
                'product_id' => $item,
                'price_per_unit' => $input['price_per_unit'][$key],
                'quantity' => $input['quantity'][$key],
                'sub_total' => $input['sub_total'][$key],
            ];                                

            $product = Product::find($item);
            $new_stock = $product['current_stock']+$input['quantity'][$key];
            $product->update(['current_stock'=>$new_stock]);
        }

        $Stock->update($input);
        $Stock->items()->createMany($new_items);

        $request->session()->flash('message.level', 'warning');
        $request->session()->flash('message.content', 'Record Updated !');
        $request->session()->flash('message.link', 'stock/'.$id);
        
        return redirect('stock');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {   return redirect('ud');
        $Stock=Stock::findOrFail($id);
        $Stock->delete();

        $request->session()->flash('message.level', 'error');
        $request->session()->flash('message.content', 'Record deleted!');
        
        return Redirect('stock');
    }

    /**
     * Search the specified resources.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {   return redirect('ud');
        $Stocks=Stock::where($request['column'], 'LIKE', "%".$request['keyword']."%")->paginate(10);
        
        return view("stock/index", compact("Stocks"));
    }

    /**
     * Print the specfied resource.
     *
     * @param  \App\invoice  $id
     * @return \Illuminate\Http\Response
     */
    public function print_stock($id)
    {   return redirect('ud');
        $Stock=Stock::findOrFail($id);
        //return $Stock;
        return view('stock.print')->with(['Stock'=>$Stock]);
    }

    /**
     * List all products with respective stock.
     *
     * @return \Illuminate\Http\Response
     */
    public function products_list(Request $request)
    {
        if($request->ajax()){
            $products = Product::where('current_stock','>=',1)->get();

            return $products;
        }    
    }

    /**
     * List products having stock.
     *
     * @return \Illuminate\Http\Response
     */
    public function product_stock(Request $request)
    {
        if($request->ajax()){
            $items = [];
            $products = Product::all();

            foreach($products as $product){
                $product_stock = Item::where('state','stock')->where('product_id',$product['id'])->count();
                $stock_price = Item::with('stock_item')->where('product_id',$product['id'])->first();
                $stock_price = $stock_price['stock_item']['stock_price'];

                if($product_stock>0){
                    $items[] = [
                        'id' => $product['id'],
                        'name' => $product['name'],
                        'model' => $product['model'],
                        'stock' => $product_stock,
                        'stock_price' => $stock_price
                    ];
                }
            }

            return $items;
        }    
    }
}

