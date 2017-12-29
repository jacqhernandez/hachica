<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
  protected $fillable = ['barcode','name','description','retail_price', 'wholesale_price', 'last_purchase_price'];

  public function saleItems()
  {
  	return $this->hasMany('App\SaleItem');
  }
}
