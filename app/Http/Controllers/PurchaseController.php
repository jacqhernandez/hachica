<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Item;
use App\Purchase;
use App\PurchaseItem;
use DateTime;
use Carbon\Carbon;

class PurchaseController extends Controller
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
		  		$purchases = Purchase::whereBetween('purchase_date',[$date_from, $date_to])->orderBy('purchase_date','desc')->paginate(20);
		  		$label = "from <span class='label-date'>".$date_from->toFormattedDateString()."</span> to <span class='label-date'>".$date_to->toFormattedDateString()."</span>";
		  	} else {
		  		if ($from && $from->format('Y-m-d') == $_GET['from']){
		  			$date_from = Carbon::instance($from)->startOfDay();
		  			$purchases = Purchase::whereDate('purchase_date','=',$date_from)->orderBy('purchase_date','desc')->paginate(20);
		  			$label = "for <span class='label-date'>".$date_from->toFormattedDateString()."</span>";
		  		} elseif ($to && $to->format('Y-m-d') == $_GET['to']) {
		  			$date_to = Carbon::instance($to)->startOfDay();
		  			$purchases = Purchase::whereDate('purchase_date','=',$date_to)->orderBy('purchase_date','desc')->paginate(20);
		  			$label = "for <span class='label-date'>".$date_to->toFormattedDateString()."</span>";
		  		} else {
		  			$purchases = Purchase::orderBy('purchase_date','desc')->paginate(20);
		  		}
		  	}

    	} else {
    		if (isset($_GET['from'])){
    			$from = DateTime::createFromFormat('Y-m-d',$_GET['from']);
    			$date_from = Carbon::instance($from)->startOfDay();
	  			$purchases = Purchase::whereDate('purchase_date','=',$date_from)->orderBy('purchase_date','desc')->paginate(20);
	  			$label = "for ".$date_from->toFormattedDateString();
    		} elseif (isset($_GET['to'])) {
    			$to = DateTime::createFromFormat('Y-m-d',$_GET['to']);
    			$date_to = Carbon::instance($to)->startOfDay();
	  			$purchases = Purchase::whereDate('purchase_date','=',$date_to)->orderBy('purchase_date','desc')->paginate(20);
	  			$label = "for ".$date_to->toFormattedDateString();
    		} else {
    			$purchases = Purchase::orderBy('purchase_date','desc')->paginate(20);
    		}
    	}
    	$total_purchases = 0;
    	foreach ($purchases as $purchase) {
    		$total_purchases = $total_purchases + $purchase->total_amount;
    	}

      return view('purchases.index', compact('purchases','label','total_purchases'));
    }

    public function search(Request $request){
	    $search = $request->term;
	    $items = Item::where('name','LIKE','%'.$search.'%')->orWhere('description','LIKE','%'.$search.'%')->orWhere('barcode','LIKE','%'.$search.'%')->get();
	    $data = [];
	    foreach ($items as $key => $value){
	    	$data [] = ['id'=>$value->id,'value'=>$value->name." (".$value->description.")",'barcode'=>$value->barcode, 'name'=>$value->name, 'description'=>$value->description];
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
      return view('purchases.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $purchase = Purchase::create([
				'total_amount' => $request['purchase-total'],
				'payment' =>  $request['purchase-cash'],
				'change' =>  $request['purchase-change'],
				'supplier' => $request['purchase-supplier'],
				'purchase_date' => DateTime::createFromFormat('Y-m-d',$request['purchase-date'])
			]);
			
			$purchase_items = $request['purchase-items'];
			foreach ($purchase_items as $purchase_item) {
				$new_purchase_item = new PurchaseItem;
				$new_purchase_item->purchase_id = $purchase->id;
				if (is_null($purchase_item['item-id'])) {
					$item = Item::create([
						'name' => $purchase_item['name'],
						'description' => $purchase_item['description'],
						'retail_price'=> 999.99,
						'wholesale_price' => 999.99
					]);
					$new_purchase_item->item_id = $item->id;
				} else {
					$new_purchase_item->item_id = $purchase_item['item-id'];
				}
				$new_purchase_item->quantity = $purchase_item['quantity'];
				$new_purchase_item->price = $purchase_item['price'];
				$new_purchase_item->total = $purchase_item['total'];
				$new_purchase_item->save();
			}			
			return redirect()->route('purchases.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      $purchase = Purchase::find($id);
      return view('purchases.show', compact('purchase'));
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
