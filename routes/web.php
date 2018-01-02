<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Auth::routes();

Route::get('/', 'HomeController@index')->name('index');
Route::get('reports/salesToday', 'HomeController@salesToday')->name('reports.sales_today');
Route::get('reports/salesThisMonth', 'HomeController@salesMonth')->name('reports.sales_month');
Route::get('reports/itemsSoldToday', 'HomeController@itemsToday')->name('reports.items_today');
Route::resource('items', 'ItemController');


Route::resource('sales', 'SaleController');
Route::get('sales-search','SaleController@search')->name('sales.search');

Route::resource('purchases', 'PurchaseController');
Route::get('purchases-search','PurchaseController@search')->name('purchases.search');