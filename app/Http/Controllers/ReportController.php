<?php

namespace App\Http\Controllers;

use DB;
use App\Sale;
use App\Stock;
use App\Report;
use App\Product;
use App\StockItems;
use App\SubCategory;
use App\MainCategory;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct()
    {
        
    }

    public function current_stock()
    {
        $products = Product::with('sub_category')->get();
        $total_products = Product::sum('current_stock');

        $series = [];
        $drilldown = [];
        $data_products = [];

        foreach($products as $product){
            $data_products[] = [
                'name' => $product['model'].' ('.$product['current_stock'].')',
                'y' => floatval($product['current_stock']),
            ];
        }

        $series[] = [
            'name' => 'Quantity',
            'colorByPoint' => true,
            'data' => $data_products,
        ];

        $series = collect($series);
        $drilldown = collect($drilldown);
        
        //return $series;
        //return $drilldown;

        return view('reports.stock',compact('series','drilldown','products','total_products'));
    }

    public function business_worth()
    {
        // some algo

        return view('reports.product_stock_report', compact('products_list'));
    }
}
