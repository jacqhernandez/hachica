<?php

use Illuminate\Database\Seeder;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
    	DB::table('items')->insert([[
        'barcode' => '123',
        'name' => 'Bear Brand',
        'description' => 'Milk',
        'retail_price' => 29,
        'wholesale_price' => 28,
        'last_purchase_price' => 27.5
      ],
      [
      	'barcode' => '124',
        'name' => 'Nescafe',
        'description' => 'Coffee',
        'retail_price' => 30,
        'wholesale_price' => 29,
        'last_purchase_price' => 28.4
      ],
      [
      	'barcode' => '125',
        'name' => 'Bingo',
        'description' => 'Snacks',
        'retail_price' => 31,
        'wholesale_price' => 30,
        'last_purchase_price' => 29.7
      ]
    	]);
    }
}
