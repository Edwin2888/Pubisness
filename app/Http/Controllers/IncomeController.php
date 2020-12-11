<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\Document;
use App\Models\DocumentDetail;

class IncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $oDocs = Document::where('id_type','1')->get();
        return view('income.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'code' => 'required|max:255',
            'description' => 'required|max:255',
            'id_product' => 'required',
            'price' => 'required|numeric|min:1|not_in:0',
            'quantity' => 'required|numeric|min:1|not_in:0',
        ]);
        try {
            $oIncome = Document::find($request->id_document);
            if(is_null($oIncome)){
                $oIncome = new Document();
            }
            $oIncome->code = strtoupper($request->code);
            $oIncome->id_type = '1';
            $oIncome->id_user = auth()->user()->id;
            $oIncome->date_document = $request->date_document;
            $oIncome->description = strtoupper($request->description);
            $oIncome->total = (is_null($request->total) ? 0 : $request->total);
            $oIncome->payment = (is_null($request->payment) ? 0 : $request->payment);
            $oIncome->save();

            $oIncomeDetail = new DocumentDetail();
            $oIncomeDetail->id_document = $oIncome->id_document;
            $oIncomeDetail->id_product = $request->id_product;
            $oIncomeDetail->quantity = (is_null($request->quantity) ? 0 : $request->quantity);
            $oIncomeDetail->price = (is_null($request->price) ? 0 : $request->price);
            $oIncomeDetail->id_user = auth()->user()->id;
            $oIncomeDetail->save();

            $nTotal = DocumentDetail::where('id_document',$oIncome->id_document)->sum('price');
            $oIncome->total = $nTotal;
            $oIncome->save();

            return redirect()->route('income.show',['id' => $oIncome->id_document])->with('success','Transaccion exitosa');
        } catch (\Throwable $th) {
            // dd($th);
            return back()->withErrors('No se pudo guardar el registro');
        }
        // dd($request->all());
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
        $document = Document::find($id);
        if(is_null($document)){
            return redirect()->route('income.view')->withErrors('Registro no encontrado');
        }
        $documentDetail = DocumentDetail::where('id_document',$id)
        ->join('products as p','p.id_product','document_details.id_product')->get();
        return view('income.show',compact('document','documentDetail'));
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
