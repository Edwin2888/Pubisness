<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use DB;
use App\Models\Document;
use App\Models\DocumentDetail;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sqlIncome = Document::join('document_details as det','det.id_document','documents.id_document')
        ->where('documents.id_type','1')
        ->select('det.id_product',DB::raw('sum(det.quantity) as cant'))
        ->groupBy('det.id_product');

        $sqlSale = Document::join('document_details as det','det.id_document','documents.id_document')
        ->where('documents.id_type','2')
        ->select('det.id_product',DB::raw('sum(det.quantity) as cant'))
        ->groupBy('det.id_product');

        $oProducts = Product::leftJoinSub($sqlIncome,'income',function($q){
            $q->on('products.id_product','income.id_product');
        })->leftJoinSub($sqlSale,'sale',function($q){
            $q->on('products.id_product','sale.id_product');
        })
        ->select('products.name','sale.cant as ventas','income.cant as compras')
        ->get();

        return view('inventory.index',compact('oProducts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
