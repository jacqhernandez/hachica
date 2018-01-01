<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Item;
use App\Http\Requests;

class ItemController extends Controller
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
    		if (isset($_GET["search"])){
    			$search = $_GET["search"];
    			$items = Item::where('name','LIKE','%'.$search.'%')->orWhere('description','LIKE','%'.$search.'%')->orWhere('barcode','LIKE','%'.$search.'%')->orderBy('name','asc')->paginate(20);
    		} else {
    			$items = Item::orderBy('name','asc')->paginate(20);
    		}
        return view('items.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('items.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\StoreItemRequest $request)
    {
        // $input = $request->all();
        // dd($input);
        // $item = new Item;
        // $item->name = $input['name'];
        // $item->unit = $input['unit'];
        // $item->description = $input['description'];
        // $item->save();
    		Item::create($request->all());
        return redirect()->route('items.index')
        								 ->with('success', 'Item created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      $item = Item::find($id);
      $sale_items = $item->saleItems()->orderBy('created_at','desc')->paginate(20);

      $sale_item_retail_price_changes_prices = [];
      $sale_item_retail_price_changes_dates = [];
      
      $sale_items_for_retail_price_trends = $item->saleItems()->where('type','retail')->get();
      if (count($sale_items_for_retail_price_trends) > 0){
      	$sale_item_retail_price_changes_prices = [$sale_items_for_retail_price_trends[0]->price];
      	$sale_item_retail_price_changes_dates = [$sale_items_for_retail_price_trends[0]->created_at->toFormattedDateString()];
      	for($i = 1; $i < count($sale_items_for_retail_price_trends); $i++ ){
	      	if ($sale_items_for_retail_price_trends[$i]){
	      		if ($sale_items_for_retail_price_trends[$i]->price !== $sale_item_retail_price_changes_prices[$i-1]){
	      			array_push($sale_item_retail_price_changes_prices, $sale_items_for_retail_price_trends[$i]->price);
	      			array_push($sale_item_retail_price_changes_dates, $sale_items_for_retail_price_trends[$i]->created_at->toFormattedDateString());
	      		}
	      	}
	      }
      }

      $sale_item_wholesale_price_changes_prices = [];
      $sale_item_wholesale_price_changes_dates = [];
      
      $sale_items_for_wholesale_price_trends = $item->saleItems()->where('type','wholesale')->get();
      if (count($sale_items_for_wholesale_price_trends) > 0){
      	$sale_item_wholesale_price_changes_prices = [$sale_items_for_wholesale_price_trends[0]->price];
      	$sale_item_wholesale_price_changes_dates = [$sale_items_for_wholesale_price_trends[0]->created_at->toFormattedDateString()];
      	for($i = 1; $i < count($sale_items_for_wholesale_price_trends); $i++ ){
	      	if ($sale_items_for_wholesale_price_trends[$i]){
	      		if ($sale_items_for_wholesale_price_trends[$i]->price !== $sale_item_wholesale_price_changes_prices[$i-1]){
	      			array_push($sale_item_wholesale_price_changes_prices, $sale_items_for_wholesale_price_trends[$i]->price);
	      			array_push($sale_item_wholesale_price_changes_dates, $sale_items_for_wholesale_price_trends[$i]->created_at->toFormattedDateString());
	      		}
	      	}
	      }
      }

      $sale_items_total_amount = 0;
      $sale_items_total_quantity = 0;
      foreach ($sale_items as $sale_item){
      	$sale_items_total_quantity = $sale_items_total_quantity + $sale_item->quantity;
      	$sale_items_total_amount = $sale_items_total_amount + $sale_item->total;
      }
      return view('items.show', compact(['item', 'sale_items', 'sale_items_total_amount', 'sale_items_total_quantity','sale_item_retail_price_changes_prices','sale_item_retail_price_changes_dates','sale_item_wholesale_price_changes_prices','sale_item_wholesale_price_changes_dates']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item = Item::findOrFail($id);
        return view('items.edit', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\StoreItemRequest $request, $id)
    {
        Item::findOrFail($id)->update($request->all());
        return redirect()->route('items.index')
        								 ->with('success', 'Item updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = Item::findOrFail($id);
        $item->delete('set null');
        return redirect()->route('items.index')
        							   ->with('success', 'Item deleted successfully.');
    }
}
