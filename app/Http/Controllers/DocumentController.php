<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\DocumentDetail;
use DB;
use App\Models\TypeDocument;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sType = null;
        $sDetalle = null;
        $sDateIni = date('Y-m-d');
        $sDateFin = date('Y-m-d');
        $types = TypeDocument::get();
        $oDocument = Document::where('date_document',date('Y-m-d'))
        ->join('type_documents as t','t.id_type','documents.id_type')
        ->join('status_sales as s','s.id_status','documents.id_status')
        ->get([
            'documents.*','s.name as estado','t.name as tipo'
        ]);
        return view('documents.index',compact('oDocument','types','sDateIni','sDateFin','sType','sDetalle'));
    }
    public function filter(Request $request){
        $types = TypeDocument::get();
        $oDocument = Document::when(!is_null($request->fecha_ini) && !is_null($request->fecha_fin),function($q)use($request){
            $q->whereBetween('date_document',[$request->fecha_ini,$request->fecha_fin]);
        })->when(!is_null($request->id_type),function($q)use($request){
            $q->where('t.id_type',$request->id_type);
        })->when(!is_null($request->fecha_ini) && is_null($request->fecha_fin), function($q)use($request){
            $q->where('date_document',$request->fecha_ini);
        })->when(is_null($request->fecha_ini) && !is_null($request->fecha_fin), function($q)use($request){
            $q->where('date_document',$request->fecha_fin);
        })->when(is_null($request->fecha_ini) && is_null($request->fecha_fin), function($q)use($request){
            $q->where('date_document',date('Y-m-d'));
        })->when(!is_null($request->detalle),function($q){
            $q->leftJoin('document_details as d','d.id_document','documents.id_document')
            ->leftJoin('products as p','p.id_product','d.id_product');
        })
        ->join('type_documents as t','t.id_type','documents.id_type')
        ->join('status_sales as s','s.id_status','documents.id_status')
        ->select('documents.*','s.name as estado','t.name as tipo')
        ->when(!is_null($request->detalle),function($q){
            $q->addSelect('p.name as producto','d.price','d.quantity');
        })
        ->get();
        $sDateIni = date('Y-m-d');
        $sDateFin = date('Y-m-d');
        $sType = $request->id_type;
        $sDetalle = $request->detalle;
        // dd($sDetalle);
        if(!is_null($request->fecha_ini)){
            $sDateIni = $request->fecha_ini;
        }
        if(!is_null($request->fecha_fin)){
            $sDateFin = $request->fecha_fin;
        }
        return view('documents.index',compact('oDocument','types','sDateIni','sDateFin','sType','sDetalle'));
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
    public function deuda(Request $request){
        $oDocument = Document::find($request->id_document);
        $oDocument->id_status = '5';
        $oDocument->save();
        $aMsg = array();
        $aMsg['success'] = 'Registro actualizado';
        return response()->json($aMsg);
    }
}
