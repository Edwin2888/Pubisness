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
    Route::group(['middleware' => ['permission:sales_permission']], function() {
        // Sales
        Route::prefix('/sales')->group(function () {
            Route::get('/','SaleController@index')->name('sales.view');
            Route::post('/','SaleController@create')->name('sales.new');
            Route::post('/filter','SaleController@filter')->name('sales.filter');
            Route::get('/table/{id}','SaleController@show')->name('sales.show');
            Route::post('/sales_detail/new','SaleController@detailNew')->name('sales_detail.save');
            Route::post('/sales_detail/pay','SaleController@salePay')->name('sales.pay');
            Route::post('/sales/document','SaleController@saleDocument')->name('sales.document');
            // Run
            Route::get('/run','SaleController@indexRun')->name('sales_run.view');
            Route::post('/run/new','SaleController@createRun')->name('sales.run.new');
            Route::get('/run/show/{id}','SaleController@showRun')->name('sales.run.show');
            Route::post('/run/pay','SaleController@payRun')->name('sales.run.pay');
            Route::post('/run/delete/detail','SaleController@deleteDetailRun')->name('sales.run.delete.detail');
        });
    });
    Route::group(['middleware' => ['permission:product_permission']], function() {
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
    });

    Route::group(['middleware' => ['permission:income_permission']], function() {
        Route::prefix('/income')->group(function () {
            Route::get('/','IncomeController@index')->name('income.view');
            Route::post('/new','IncomeController@create')->name('income.new');
            Route::get('/show/{id}','IncomeController@show')->name('income.show');
        });
    });

    Route::group(['middleware' => ['permission:document_permission']], function() {
        Route::prefix('/documents')->group(function () {
            Route::get('/','DocumentController@index')->name('documents.view');
            Route::post('/filter','DocumentController@filter')->name('documents.filter');
        });
    });

    Route::group(['middleware' => ['permission:inventory_permission']], function() {
        Route::prefix('/inventory')->group(function () {
            Route::get('/','InventoryController@index')->name('inventory.view');
        });
    });

    Route::group(['middleware' => ['permission:roles_permission']], function() {
        Route::prefix('/roles')->group(function () {
            Route::get('/','RolController@index')->name('roles.view');
            Route::post('/filter','RolController@assign')->name('roles.assign');
        });
    });
});
