<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Item;

class AutocompleteController extends Controller
{
  public function index()
  {
  	return view('autocompleteindex');
  }

  public function search(Request $request){
    $search = $request->term;

    $items = Item::where('name','LIKE','%'.$search.'%')->orWhere('description','LIKE','%'.$search.'%')->get();
    $data = [];
    foreach ($items as $key => $value){
    	$data [] = ['id'=>$value->id,'value'=>$value->name." ".$value->description, 'barcode'=>$value->barcode, 'name'=>$value->name, 'description'=>$value->description, 'retail_price'=>$value->retail_price, 'wholesale_price'=>$value->wholesale_price];
    }
    return response($data);
  }
}
