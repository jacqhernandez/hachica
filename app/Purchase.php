<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
  protected $fillable = ['total_amount','change','payment','supplier','purchase_date'];

	public function purchaseItems()
  {
   	return $this->hasMany('App\PurchaseItem');
  }
}
