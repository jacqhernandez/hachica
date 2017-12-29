<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Item;

class SearchController extends Controller
{
	  public function index()
    {
    	return view('searchautocomplete');
    }

    public function autocomplete()
		{
			dd("meh");
			$term = request('term');
      $result = Item::whereName($term)->orWhere('name', 'LIKE', '%' . $term . '%')->get(['id', 'name as value']);
      return response()->json($term);
		}
}
