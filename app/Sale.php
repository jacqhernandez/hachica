<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
	protected $fillable = ['total_amount','change','payment'];

	public function saleItems()
  {
   	return $this->hasMany('App\SaleItem');
  }
}
