<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/',function(){
    return redirect('/login');
});

Auth::routes();

Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => 'auth'], function() {
    // Sales
    Route::prefix('/sales')->group(function () {
        Route::get('/','SaleController@index')->name('sales.view');
        Route::post('/','SaleController@create')->name('sales.new');
        Route::post('/filter','SaleController@filter')->name('sales.filter');
        Route::get('/table/{id}','SaleController@show')->name('sales.show');
        Route::post('/sales_detail/new','SaleController@detailNew')->name('sales_detail.save');
        Route::post('/sales_detail/pay','SaleController@salePay')->name('sales.pay');
    });
    // Products
    Route::prefix('/products')->group(function () {
        Route::get('/','ProductController@index')->name('products.view');
        Route::get('/autocomplete','ProductController@autocomplete');
        Route::post('/','ProductController@create')->name('products.new');
        Route::post('/propities','ProductController@propities')->name('getProductPropities');
        // Route::get('/', function(){
        //     return view('products.index');
        // })->name('products.view');
    });

    Route::prefix('/income')->group(function () {
        Route::get('/','IncomeController@index')->name('income.view');
        Route::post('/new','IncomeController@create')->name('income.new');
    });
});
