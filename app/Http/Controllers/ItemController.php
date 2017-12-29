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
    			$items = Item::where('name','LIKE','%'.$search.'%')->orWhere('description','LIKE','%'.$search.'%')->orWhere('barcode','LIKE','%'.$search.'%')->paginate(10);
    		} else {
    			$items = Item::paginate(10);
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
      $sale_items = $item->saleItems()->orderBy('created_at','desc')->paginate(10);
      $sale_items_total_amount = 0;
      $sale_items_total_quantity = 0;
      foreach ($sale_items as $sale_item){
      	$sale_items_total_quantity = $sale_items_total_quantity + $sale_item->quantity;
      	$sale_items_total_amount = $sale_items_total_amount + $sale_item->total;
      }
      return view('items.show', compact(['item', 'sale_items', 'sale_items_total_amount', 'sale_items_total_quantity']));
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
