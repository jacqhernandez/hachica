<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sale;
use App\Item;
use Carbon\Carbon;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	$sales_today = Sale::where('created_at','>=',Carbon::today())->orderBy('created_at','desc')->get();
    	$total_sales_today = 0;
    	foreach ($sales_today as $sale) {
    		$total_sales_today = $total_sales_today + $sale->total_amount;
    	}

    	$sales_month = Sale::whereMonth('created_at','=',Carbon::now()->month)->whereYear('created_at','=',Carbon::now()->year)->orderBy('created_at','desc')->get();
    	$total_sales_month = 0;
    	foreach ($sales_month as $sale) {
    		$total_sales_month = $total_sales_month + $sale->total_amount;
    	}

    	$total_items_sold_today = DB::table('items')->select(DB::raw('items.id, name, count(*) as total_transactions, sum(quantity) as total_quantity, sum(total) as total_sales'))->rightJoin('sale_items','items.id','=','sale_items.item_id')->where('sale_items.created_at','>=',Carbon::today())->groupBy('items.id')->orderBy('total_quantity','desc')->get()->count();
    	// DB::raw('select items.id, name, count(*) as total_transactions, sum(quantity) as total_quantity, sum(total) as total_sales from items right join sale_items on items.id = sale_items.item_id where date(sale_items.created_at) = curdate() group by items.id order by total_quantity desc');
    	//dd($total_items_sold_today);

    	$sellable_items = DB::table('items')->select(DB::raw('items.id, name, count(*) as total_transactions, sum(quantity) as total_quantity, sum(total) as total_sales'))->rightJoin('sale_items','items.id','=','sale_items.item_id')->groupBy('items.id')->orderBy('total_quantity','desc')->paginate(10);
    	$items_not_selling = DB::table('items')->select(DB::raw('items.id, name, items.created_at as item_created_date, count(*) as total_transactions, sum(quantity) as total_quantity, sum(total) as total_sales'))->leftJoin('sale_items','items.id','=','sale_items.item_id')->groupBy('items.id')->orderBy('total_quantity','asc')->orderBy('item_created_date', 'asc')->paginate(10); //i still included those that have sales but they're at the end of the list since this is ordered by quantity

    	$transactions_last_thirty_days = [];
    	$sales_last_thirty_days = [];
    	for ($i=0; $i<=29; $i++){
    		$total_transactions = Sale::whereDate('created_at','=',Carbon::today()->subDays($i))->get();
    		$total_transactions_count = $total_transactions->count();
    		$transactions_last_thirty_days = array_prepend($transactions_last_thirty_days, $total_transactions_count);

    		$total_sales = 0;
    		foreach($total_transactions as $total_transaction){
    			$total_sales = $total_sales + $total_transaction->total_amount;
    		}
    		$sales_last_thirty_days = array_prepend($sales_last_thirty_days, $total_sales);
    	}

    	$transactions_last_twelve_weeks = [];
    	$sales_last_twelve_weeks = [];
    	for ($i=0; $i<12; $i++){
    		$total_transactions = Sale::
    		whereBetween('created_at',[Carbon::today()->subWeeks($i)->startOfWeek(),Carbon::today()->subWeeks($i)->endOfWeek()])->get();

    		$total_transactions_count = $total_transactions->count();
    		$transactions_last_twelve_weeks = array_prepend($transactions_last_twelve_weeks, $total_transactions_count);

    		$total_sales = 0;
    		foreach($total_transactions as $total_transaction){
    			$total_sales = $total_sales + $total_transaction->total_amount;
    		}
    		$sales_last_twelve_weeks = array_prepend($sales_last_twelve_weeks, $total_sales);
    	}

    	$transactions_last_twelve_months = [];
    	$sales_last_twelve_months = [];
    	for ($i=0; $i<12; $i++){
    		$total_transactions = Sale::
    		whereBetween('created_at',[Carbon::today()->subMonths($i)->startOfMonth(),Carbon::today()->subMonths($i)->endOfMonth()])->get();

    		$total_transactions_count = $total_transactions->count();
    		$transactions_last_twelve_months = array_prepend($transactions_last_twelve_months, $total_transactions_count);

    		$total_sales = 0;
    		foreach($total_transactions as $total_transaction){
    			$total_sales = $total_sales + $total_transaction->total_amount;
    		}
    		$sales_last_twelve_months = array_prepend($sales_last_twelve_months, $total_sales);
    	}

      return view('index', compact(['sales_today', 'total_sales_today','total_sales_month','item_count','sellable_items','items_not_selling','total_items_sold_today','transactions_last_thirty_days','sales_last_thirty_days','transactions_last_twelve_weeks','sales_last_twelve_weeks','transactions_last_twelve_months','sales_last_twelve_months']));
    }

    public function salesToday(){
    	$sales_today = Sale::where('created_at','>=',Carbon::today())->orderBy('created_at','desc')->paginate(20);
    	$total_sales_today = 0;
    	foreach ($sales_today as $sale) {
    		$total_sales_today = $total_sales_today + $sale->total_amount;
    	}
    	$total_items_sold_today = DB::table('items')->select(DB::raw('items.id, name, count(*) as total_transactions, sum(quantity) as total_quantity, sum(total) as total_sales'))->rightJoin('sale_items','items.id','=','sale_items.item_id')->where('sale_items.created_at','>=',Carbon::today())->groupBy('items.id')->orderBy('total_quantity','desc')->get()->count();
    	return view('reports.sales_today', compact(['sales_today', 'total_sales_today','total_items_sold_today']));
    }

    public function salesMonth(){
    	$sales_month = Sale::whereMonth('created_at','=',Carbon::now()->month)->whereYear('created_at','=',Carbon::now()->year)->orderBy('created_at','desc')->paginate(20);
    	$total_sales_month = 0;
    	foreach ($sales_month as $sale) {
    		$total_sales_month = $total_sales_month + $sale->total_amount;
    	}
    	$total_items_sold_month = DB::table('items')->select(DB::raw('items.id, name, count(*) as total_transactions, sum(quantity) as total_quantity, sum(total) as total_sales'))->rightJoin('sale_items','items.id','=','sale_items.item_id')->whereMonth('sale_items.created_at','=',Carbon::now()->month)->whereYear('sale_items.created_at','=',Carbon::now()->year)->groupBy('items.id')->orderBy('total_quantity','desc')->get()->count();
    	return view('reports.sales_month', compact(['sales_month', 'total_sales_month','total_items_sold_month']));
    }

    public function itemsToday()
    {
    	$sales_today = Sale::where('created_at','>=',Carbon::today())->orderBy('created_at','desc')->paginate(20);
    	$total_sales_today = 0;
    	foreach ($sales_today as $sale) {
    		$total_sales_today = $total_sales_today + $sale->total_amount;
    	}
    	$items_sold_today = DB::table('items')->select(DB::raw('items.id, name, count(*) as total_transactions, sum(quantity) as total_quantity, sum(total) as total_sales'))->rightJoin('sale_items','items.id','=','sale_items.item_id')->where('sale_items.created_at','>=',Carbon::today())->groupBy('items.id')->orderBy('total_quantity','desc')->paginate(10);
    	return view('reports.items_today', compact(['sales_today', 'total_sales_today','items_sold_today']));
    }
    
}

