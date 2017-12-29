<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
	protected $fillable = ['item_id','sale_id','type','quantity', 'price', 'total'];

	public function sale()
  {
   	return $this->belongsTo('App\Sale');
  }

  public function item()
  {
   	return $this->belongsTo('App\Item');
  }
}
