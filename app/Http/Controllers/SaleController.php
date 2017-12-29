<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Sale;
use App\Item;
use App\SaleItem;
use Carbon\Carbon;
use DateTime;
use DB;


class SaleController extends Controller
{
	  public function __construct()
    {
        $this->middleware('auth');  
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	if(isset($_GET['from']) & isset($_GET['to'])){

    		$from = DateTime::createFromFormat('Y-m-d',$_GET['from']);
    		$to = DateTime::createFromFormat('Y-m-d',$_GET['to']);

    		if (($from && $from->format('Y-m-d') == $_GET['from']) & ($to && $to->format('Y-m-d') == $_GET['to'])){
		  		$date_from = Carbon::instance($from)->startOfDay();
		  		$date_to = Carbon::instance($to)->endOfDay();
		  		$sales = Sale::whereBetween('created_at',[$date_from, $date_to])->orderBy('created_at','desc')->paginate(10);
		  		$total_items_sold = DB::table('items')->select(DB::raw('items.id, name, count(*) as total_transactions, sum(quantity) as total_quantity, sum(total) as total_sales'))->rightJoin('sale_items','items.id','=','sale_items.item_id')->whereBetween('sale_items.created_at',[$date_from, $date_to])->groupBy('items.id')->get()->count();
		  		$label = "from <span class='label-date'>".$date_from->toFormattedDateString()."</span> to <span class='label-date'>".$date_to->toFormattedDateString()."</span>";
		  	} else {
		  		if ($from && $from->format('Y-m-d') == $_GET['from']){
		  			$date_from = Carbon::instance($from)->startOfDay();
		  			$sales = Sale::whereDate('created_at','=',$date_from)->orderBy('created_at','desc')->paginate(10);
		  			$total_items_sold = DB::table('items')->select(DB::raw('items.id, name, count(*) as total_transactions, sum(quantity) as total_quantity, sum(total) as total_sales'))->rightJoin('sale_items','items.id','=','sale_items.item_id')->whereDate('sale_items.created_at','=',$date_from)->groupBy('items.id')->get()->count();
		  			$label = "for <span class='label-date'>".$date_from->toFormattedDateString()."</span>";
		  		} elseif ($to && $to->format('Y-m-d') == $_GET['to']) {
		  			$date_to = Carbon::instance($to)->startOfDay();
		  			$sales = Sale::whereDate('created_at','=',$date_to)->orderBy('created_at','desc')->paginate(10);
		  			$total_items_sold = DB::table('items')->select(DB::raw('items.id, name, count(*) as total_transactions, sum(quantity) as total_quantity, sum(total) as total_sales'))->rightJoin('sale_items','items.id','=','sale_items.item_id')->whereDate('sale_items.created_at','=',$date_to)->groupBy('items.id')->get()->count();
		  			$label = "for <span class='label-date'>".$date_to->toFormattedDateString()."</span>";
		  		} else {
		  			$sales = Sale::orderBy('created_at','desc')->paginate(10);
		  			$total_items_sold = DB::table('items')->select(DB::raw('items.id, name, count(*) as total_transactions, sum(quantity) as total_quantity, sum(total) as total_sales'))->rightJoin('sale_items','items.id','=','sale_items.item_id')->groupBy('items.id')->get()->count();
		  		}
		  	}

    	} else {
    		if (isset($_GET['from'])){
    			$from = DateTime::createFromFormat('Y-m-d',$_GET['from']);
    			$date_from = Carbon::instance($from)->startOfDay();
	  			$sales = Sale::whereDate('created_at','=',$date_from)->orderBy('created_at','desc')->paginate(10);
	  			$total_items_sold = DB::table('items')->select(DB::raw('items.id, name, count(*) as total_transactions, sum(quantity) as total_quantity, sum(total) as total_sales'))->rightJoin('sale_items','items.id','=','sale_items.item_id')->whereDate('sale_items.created_at','=',$date_from)->groupBy('items.id')->get()->count();
	  			$label = "for ".$date_from->toFormattedDateString();
    		} elseif (isset($_GET['to'])) {
    			$to = DateTime::createFromFormat('Y-m-d',$_GET['to']);
    			$date_to = Carbon::instance($to)->startOfDay();
	  			$sales = Sale::whereDate('created_at','=',$date_to)->orderBy('created_at','desc')->paginate(10);
	  			$total_items_sold = DB::table('items')->select(DB::raw('items.id, name, count(*) as total_transactions, sum(quantity) as total_quantity, sum(total) as total_sales'))->rightJoin('sale_items','items.id','=','sale_items.item_id')->whereDate('sale_items.created_at','=',$date_to)->groupBy('items.id')->get()->count();
	  			$label = "for ".$date_to->toFormattedDateString();
    		} else {
    			$sales = Sale::orderBy('created_at','desc')->paginate(10);
    			$total_items_sold = DB::table('items')->select(DB::raw('items.id, name, count(*) as total_transactions, sum(quantity) as total_quantity, sum(total) as total_sales'))->rightJoin('sale_items','items.id','=','sale_items.item_id')->groupBy('items.id')->get()->count();
    		}
    	}
    	$total_sales = 0;
    	foreach ($sales as $sale) {
    		$total_sales = $total_sales + $sale->total_amount;
    	}

      return view('sales.index', compact('sales','label','total_sales','total_items_sold'));
    }

    public function search(Request $request){
	    $search = $request->term;
	    $items = Item::where('name','LIKE','%'.$search.'%')->orWhere('description','LIKE','%'.$search.'%')->orWhere('barcode','LIKE','%'.$search.'%')->get();
	    $data = [];
	    foreach ($items as $key => $value){
	    	$data [] = ['id'=>$value->id,'value'=>$value->name." (".$value->description.")",'barcode'=>$value->barcode, 'name'=>$value->name, 'description'=>$value->description, 'retail_price'=>$value->retail_price, 'wholesale_price'=>$value->wholesale_price];
	    }
	    return response($data);
	  }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      return view('sales.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
			// $request->validate([
			// 	'sale-items.*.description' => 'required'
			// ]);

			$sale = Sale::create([
				'total_amount' => $request['sale-total'],
				'payment' =>  $request['sale-cash'],
				'change' =>  $request['sale-change']
			]);

			$sale_items = $request['sale-items'];
			foreach ($sale_items as $sale_item) {
				$new_sale_item = new SaleItem;
				$new_sale_item->sale_id = $sale->id;
				if (is_null($sale_item['item-id'])) {
					$item = Item::create([
						'name' => $sale_item['name'],
						'description' => $sale_item['description'],
						'retail_price'=> $sale_item['price'],
						'wholesale_price' => $sale_item['price']
					]);
					$new_sale_item->item_id = $item->id;
				} else {
					$new_sale_item->item_id = $sale_item['item-id'];
				}
				if ($sale_item['type'] == 1) {
					$new_sale_item->type = "retail";
				} else {
					$new_sale_item->type = "wholesale";
				}
				$new_sale_item->quantity = $sale_item['quantity'];
				$new_sale_item->price = $sale_item['price'];
				$new_sale_item->total = $sale_item['total'];
				$new_sale_item->save();
			}			
			return redirect()->route('sales.create')
        								 ->with('success', 'Sale #'.$sale->id.' created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      $sale = Sale::find($id);
      return view('sales.show', compact('sale'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
