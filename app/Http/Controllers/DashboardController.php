<?php

namespace App\Http\Controllers;

use DB;
use Carbon;
use App\Sale;
use App\Client; 
use App\Product;
use App\Payment;
use App\Voucher;
use App\Salesman;
use App\SaleItems;
use App\Dashboard;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Enforce middlewares.
     */
    public function __construct()
    {
        
    }

    /**
     * List of dashboard options.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        
        $date = Carbon\Carbon::now();

        $clients = Client::with('user')->orderBy('current_bal','Desc')->limit(7)->get();

        $sales = Sale::with('seller','client')
            ->whereBetween('created_at', [$date->format('Y-m-d')." 00:00:00", $date->format('Y-m-d')." 23:59:59"])
            ->limit(8)
            ->get();
            
        
        $payments = Payment::with('receiver','payer')
            ->whereBetween('created_at', [$date->format('Y-m-d')." 00:00:00", $date->format('Y-m-d')." 23:59:59"])
            ->limit(8)
            ->get();
            
 $hot_sales = DB::table('sale_items')
            ->select('product_id', DB::raw('sum(sub_total) as total_sale'))
            ->groupBy('product_id')
            ->orderBy('total_sale','desc')
            ->limit(10)->get();

        $hot_products = [];
        foreach($hot_sales as $item){
            $product = Product::find($item->product_id);

            $hot_products[] = [
                'product_id' => $product['id'],
                'product_name' => $product['name'],
                'total_sales' => $item->total_sale,
            ];
            
        }
        
        
        return view('dashboard.index',compact('clients','sales','payments','hot_products'));
    }

    /**
     * List of today sales and payments.
     *
     * @return \Illuminate\Http\Response
     */
    public function today_sales(Request $request)
    {
        if($request->ajax()){
            $date = Carbon\Carbon::now();
            
            $total_sales = Sale::whereBetween('created_at', [$date->format('Y-m-d')." 00:00:00", $date->format('Y-m-d')." 23:59:59"])
                ->sum('total_amount');

            $total_payments = Payment::whereBetween('created_at', [$date->format('Y-m-d')." 00:00:00", $date->format('Y-m-d')." 23:59:59"])
                ->sum('amount');

            $data = [
                'total_sales' => $total_sales,
                'total_payments' => $total_payments,
                'total' => $total_sales+$total_payments,
            ];
            return $data;
        }

        $date1 = Carbon\Carbon::now();
        $date2 = Carbon\Carbon::now();

        $report_date = Carbon\Carbon::now()->format('Y-M-d');

        $sales = Sale::whereBetween('created_at', [$date1->format('Y-m-d')." 00:00:00", $date2->format('Y-m-d')." 23:59:59"])->get();
        $total_sales = Sale::whereBetween('created_at', [$date1->format('Y-m-d')." 00:00:00", $date2->format('Y-m-d')." 23:59:59"])
            ->sum('total_amount');

        $payments = Payment::whereBetween('created_at', [$date1->format('Y-m-d')." 00:00:00", $date2->format('Y-m-d')." 23:59:59"])->get();
        $total_payments = Payment::whereBetween('created_at', [$date1->format('Y-m-d')." 00:00:00", $date2->format('Y-m-d')." 23:59:59"])->sum('amount');

        // return $today_payments;
        return view("dashboard/sale_payment", compact('report_date','sales','total_sales','payments','total_payments'));
    }

    /**
     * List of sales and payment date wise.
     *
     * @return \Illuminate\Http\Response
     */
    public function date_sales(Request $request)
    {
        $date = $request->input('date');

        $date1 = Carbon\Carbon::parse($date);
        $date2 = Carbon\Carbon::parse($date);

        $report_date = Carbon\Carbon::parse($date)->format('Y-M-d');
        
        $sales = Sale::whereBetween('created_at', [$date1->format('Y-m-d')." 00:00:00", $date2->format('Y-m-d')." 23:59:59"])->get();
        $total_sales = Sale::whereBetween('created_at', [$date1->format('Y-m-d')." 00:00:00", $date2->format('Y-m-d')." 23:59:59"])
            ->sum('total_amount');

        $payments = Payment::whereBetween('created_at', [$date1->format('Y-m-d')." 00:00:00", $date2->format('Y-m-d')." 23:59:59"])->get();
        $total_payments = Payment::whereBetween('created_at', [$date1->format('Y-m-d')." 00:00:00", $date2->format('Y-m-d')." 23:59:59"])->sum('amount');

        //return $sales;
        return view("dashboard/sale_payment", compact('report_date','sales','total_sales','payments','total_payments'));
    }

    /**
     * Client sale history between two dates.
     *
     * @return \Illuminate\Http\Response
     */
    public function client_sales(Request $request)
    {
        $input = $request->all();

        if(!isset($input['date1'])){
            return view('dashboard.client_sales');
        }

        $date1 = Carbon\Carbon::parse($input['date1']);
        $date2 = Carbon\Carbon::parse($input['date2']);
        $client = Client::with('user')->find($input['client_id']);
        
        $sales = Sale::where('client_id', $client['id'])
            ->whereBetween('created_at', [$date1->format('Y-m-d')." 00:00:00", $date2->format('Y-m-d')." 23:59:59"])
            ->get();
        $total_sales = Sale::where('client_id', $client['id'])
            ->whereBetween('created_at', [$date1->format('Y-m-d')." 00:00:00", $date2->format('Y-m-d')." 23:59:59"])
            ->sum('total_amount');

        $payments = Payment::where('payer_id',$client['user_id'])
            ->whereBetween('created_at', [$date1->format('Y-m-d')." 00:00:00", $date2->format('Y-m-d')." 23:59:59"])
            ->get();
        $total_payments = Payment::where('payer_id',$client['user_id'])
            ->whereBetween('created_at', [$date1->format('Y-m-d')." 00:00:00", $date2->format('Y-m-d')." 23:59:59"])
            ->sum('amount');

        return view("dashboard/client_sales", compact('date1','date2','client','sales','total_sales','payments','total_payments'));
    }

    /**
     * Profit/Loss report between two dates.
     *
     * @return \Illuminate\Http\Response
     */
    public function profit_loss(Request $request)
    {
        $input = $request->all();

        if(!isset($input['date1'])){
            return view('dashboard.profit_loss');
        }

        $date1 = Carbon\Carbon::parse($input['date1']);
        $date2 = Carbon\Carbon::parse($input['date2']);
        
        $sales = Sale::whereBetween('created_at', [$date1->format('Y-m-d')." 00:00:00", $date2->format('Y-m-d')." 23:59:59"])
            ->get();
        $total_sales = Sale::whereBetween('created_at', [$date1->format('Y-m-d')." 00:00:00", $date2->format('Y-m-d')." 23:59:59"])
            ->sum('total_amount');
        $total_profit = Sale::whereBetween('created_at', [$date1->format('Y-m-d')." 00:00:00", $date2->format('Y-m-d')." 23:59:59"])
            ->sum('total_profit');
        
        return view("dashboard/profit_loss", compact('date1','date2','sales','total_sales','total_profit'));
    }
}
