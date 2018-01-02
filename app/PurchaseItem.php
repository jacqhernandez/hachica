<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{
  protected $fillable = ['item_id','purchase_id', 'quantity', 'price', 'total'];

	public function purchase()
  {
   	return $this->belongsTo('App\Purchase');
  }

  public function item()
  {
   	return $this->belongsTo('App\Item');
  }

}
