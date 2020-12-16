<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalesTable;
use App\Models\SalesTableDetail;
use DB;
use App\Models\SaleTablePay;
use App\Models\Document;
use App\Models\DocumentDetail;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sDate = date('Y-m-d');
        $sDateAnt = date("Y-m-d",strtotime($sDate."- 1 days"));
        $sDateDes = date("Y-m-d",strtotime($sDate."+ 1 days"));
        // Join sub
        $oPay = SaleTablePay::select(DB::raw('sum(payment) as pay'),'id_sale')->groupBy('id_sale');
        $oTotal = SalesTableDetail::select(DB::raw('sum(price * quantity) as total'),'id_sale')->groupBy('id_sale');

        $sAll = $request->all;
        $sales = SalesTable::when(is_null($sAll),function($q){
            $q->whereIn('status',[1,2]);
        })->leftJoinSub($oPay,'pay',function($join){
            $join->on('pay.id_sale','sales_tables.id_sale');
        })->leftJoinSub($oTotal,'total',function($join){
            $join->on('total.id_sale','sales_tables.id_sale');
        })->where('sales_tables.sale_date',$sDate)
        ->orderBy('status','asc')->orderBy('id_sale','desc')
        ->get(['sales_tables.*',DB::raw('(total.total - pay.pay) as tota'),'total.total','pay.pay']);
        return view('sales.index',compact('sales','sAll','sDate','sDateAnt','sDateDes'));
    }
    public function filter(Request $request)
    {
        $sAll = $request->all;
        if(is_null($sAll) && is_null($request->dia)){
            return redirect()->route('sales.view');
        }
        $sDate = (is_null($request->dia) ? date('Y-m-d') : $request->dia);
        $sDateAnt = date("Y-m-d",strtotime($sDate."- 1 days"));
        $sDateDes = date("Y-m-d",strtotime($sDate."+ 1 days"));

        $oPay = SaleTablePay::select(DB::raw('sum(payment) as pay'),'id_sale')->groupBy('id_sale');

        $oTotal = SalesTableDetail::select(DB::raw('sum(price * quantity) as total'),'id_sale')->groupBy('id_sale');
        $sales = SalesTable::leftJoinSub($oPay,'pay',function($join){
            $join->on('pay.id_sale','sales_tables.id_sale');
        })->leftJoinSub($oTotal,'total',function($join){
            $join->on('total.id_sale','sales_tables.id_sale');
        })->when(is_null($sAll),function($q){
            $q->whereIn('status',[1,2]);
        })->when(is_null($request->dia),function($q){
            $q->where('sales_tables.sale_date',date('Y-m-d'));
        })->when(!is_null($request->dia),function($q)use($request){
            $q->where('sales_tables.sale_date',$request->dia);
        })
        ->orderBy('status','asc')->orderBy('id_sale','desc')
        ->get(['sales_tables.*',DB::raw('(total.total - pay.pay) as tota'),'total.total','pay.pay']);
        return view('sales.index',compact('sales','sAll','sDate','sDateAnt','sDateDes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'sale_date' => 'required|date'
        ]);
        try {
            // Consultamos si el nombre esta ocupado
            $oSales = SalesTable::where('name',$request->name)
            ->where('sale_date',date('Y-m-d'))
            ->whereIn('status',['1','2'])->first();
            if(!is_null($oSales)){
                return redirect()->route('sales.view')->withErrors('La mesa '.$request->name.' esta en uso actualmente');
            }
            $oSale = new SalesTable();
            $oSale->name = $request->name;
            $oSale->sale_date = $request->sale_date;
            $oSale->id_user = auth()->user()->id;
            $oSale->total = 0;
            $oSale->status = 1;
            $oSale->save();
            return redirect()->route('sales.view')->with('success','Registro guardado exitosamente');
        } catch (\Throwable $th) {
            // dd($th);
            return redirect()->route('sales.view')->withErrors('No se pudo guardar el registro');
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $sale = SalesTable::find($id);
        if(is_null($sale)){
            return redirect()->route('sales.view')->withErrors('Registro no encontrado');
        }
        $saleDetail = SalesTableDetail::join('products as p','p.id_product','sales_table_details.id_product')
        ->where('id_sale',$id)->get([
            'sales_table_details.*','p.name as product','p.sale_price','p.code'
        ]);
        $nPay = SaleTablePay::where('id_sale',$id)->sum('payment');
        $saleTotal = SalesTableDetail::where('id_sale',$id)
            ->sum(DB::raw('price * quantity'));
        return view('sales.show',compact('sale','saleTotal','saleDetail','nPay'));
    }
    public function detailNew(Request $request){
        $validatedData = $request->validate([
            'id_product' => 'required',
            'quantity' => 'required|numeric|min:1|not_in:0',
            'price' => 'required|numeric|min:1|not_in:0',
        ]);
        try {
            // Consultamos si el nombre esta ocupado
            // $oSale = SalesTable::find($request->id_sale);
            // if(!is_null($oSales)){
            //     return redirect()->route('sales.view')->withErrors('La mesa '.$request->name.' esta en uso actualmente');
            // }
            $oSaleDetail = new SalesTableDetail();
            $oSaleDetail->id_sale = $request->id_sale;
            $oSaleDetail->id_product = $request->id_product;
            $oSaleDetail->id_user = auth()->user()->id;
            $oSaleDetail->price = $request->price;
            $oSaleDetail->observation = $request->observation;
            $oSaleDetail->quantity = $request->quantity;
            $oSaleDetail->state = 1;
            $oSaleDetail->save();
            return redirect()->route('sales.show',['id' => $request->id_sale])->with('success','Registro guardado exitosamente');
        } catch (\Throwable $th) {
            // dd($th);
            return redirect()->route('sales.show',['id' => $request->id_sale])->withErrors('No se pudo guardar el registro');
        }
    }
    public function salePay(Request $request){
        $validatedData = $request->validate([
            'id_sale' => 'required',
            'totalSale' => 'required|numeric|min:1|not_in:0',
            'recibido' => 'required|numeric|min:1|not_in:0',
        ]);
        try {
            // Consultamos el valor pagado

            $saleTotal = SalesTableDetail::where('id_sale',$request->id_sale)
                ->sum(DB::raw('price * quantity'));
            $nPay = SaleTablePay::where('id_sale',$request->id_sale)->sum('payment');
            if($nPay >= $saleTotal){
                $nPayment = $request->recibido;
            }else{
                if($request->recibido > $request->totalSale){
                    $nPayment = $request->totalSale;
                }else{
                    $nPayment = $request->recibido;
                }
            }
            if($nPayment > 0){
                $oSalePay = new SaleTablePay();
                $oSalePay->id_sale = $request->id_sale;
                $oSalePay->payment = $nPayment;
                $oSalePay->save();
            }

            $oSale = SalesTable::find($request->id_sale);
            $oSale->status = 2;
            $oSale->save();
            return redirect()->route('sales.show',['id' => $request->id_sale])->with('success','Pago recibido');
            // $nPayment = $request->recibido + $oSale->payment;
            // $nPago = $oSale->payment;
            // if($nPago >= $request->totalSale){
            //     $nPago = 0;
            // }
            // $nPay = $request->recibido - $request->totalSale + $nPago;
            // if($nPay > 0){
            //     $nPayment = $request->totalSale + $nPago;
            // }
            // $oSale->payment = $nPayment;
            // $oSale->status = ($nPayment >= $request->totalSale ? 3 : 2);
        } catch (\Throwable $th) {
            // dd($th);
            return redirect()->route('sales.show',['id' => $request->id_sale])->withErrors('No se pudo realizar el pago');
        }
    }
    public function saleDocument(Request $request){
        $sale = SalesTable::find($request->id_sale);
        $aMsg = array();
        if(!is_null($sale)){

            $saleTotal = SalesTableDetail::where('id_sale',$request->id_sale)->sum(DB::raw('price * quantity'));
            $nPay = SaleTablePay::where('id_sale',$request->id_sale)->sum('payment');
            // Si ya esta pago todo se cierra la venta
            if($saleTotal <= $nPay){
                $sale->status = '3';
                $sale->save();
            }
            $saleDocument = Document::where('id_sale',$sale->id_sale)->first();
            if(!is_null($saleDocument)){
                $aMsg['error'] = 'Ya se finalizo la venta';
                return response()->json($aMsg);
            }
            $document = new Document();
            $document->description = $sale->name;
            $document->total = (is_null($saleTotal) ? 0 : $saleTotal);
            $document->payment = (is_null($nPay) ? 0 : $nPay);
            $document->id_status = $sale->status;
            $document->id_sale = $sale->id_sale;
            $document->id_user = $sale->id_user;
            $document->date_document = $sale->sale_date;
            $document->id_type = '2';
            $document->save();
            $detail = SalesTableDetail::where('id_sale',$request->id_sale)->get();
            $detail->each(function($item, $key)use($document){
                $documentDetail = new DocumentDetail();
                $documentDetail->id_product = $item->id_product;
                $documentDetail->price = $item->price;
                $documentDetail->quantity = $item->quantity;
                $documentDetail->id_user = $item->id_user;
                $documentDetail->id_document = $document->id_document;
                $documentDetail->save();
            });
            $aMsg['success'] = 'Venta finalizada';
            return response()->json($aMsg);
        }
        dd($request->all());
    }
    public function indexRun(){
        return view('sales.run.index');
    }
    public function createRun(Request $request){
        $validatedData = $request->validate([
            'date_document' => 'required|date',
            // 'id_product' => 'required',
            // 'price' => 'required',
            // 'quantity' => 'required',
        ]);
        try {
            $oDocument = Document::find($request->id_document);
            if(is_null($oDocument)){
                $oDocument = new Document();
            }
            // $oDocument->code = strtoupper($request->code);
            $oDocument->id_type = '2';
            $oDocument->id_status = '1';
            $oDocument->id_user = auth()->user()->id;
            $oDocument->date_document = $request->date_document;
            $oDocument->description = strtoupper($request->description);
            $oDocument->total = (is_null($request->total) ? 0 : $request->total);
            $oDocument->payment = (is_null($request->payment) ? 0 : $request->payment);
            $oDocument->save();

            if(!is_null($request->id_product)){
                $validatedData = $request->validate([
                    'id_product' => 'required',
                    'price' => 'required',
                    'quantity' => 'required',
                ]);
                $oDocumentDetail = new DocumentDetail();
                $oDocumentDetail->id_document = $oDocument->id_document;
                $oDocumentDetail->id_product = $request->id_product;
                $oDocumentDetail->quantity = (is_null($request->quantity) ? 0 : $request->quantity);
                $oDocumentDetail->price = (is_null($request->price) ? 0 : $request->price);
                $oDocumentDetail->id_user = auth()->user()->id;
                $oDocumentDetail->save();
            }

            $nTotal = DocumentDetail::where('id_document',$oDocument->id_document)->sum(DB::raw('price * quantity'));
            $oDocument->total = $nTotal;
            $oDocument->save();

            return redirect()->route('sales.run.show',['id' => $oDocument->id_document])->with('success','Transaccion exitosa');
        } catch (\Throwable $th) {
            dd($th);
            return back()->withErrors('No se pudo guardar el registro');
        }
    }
    public function showRun($id){
        $document = Document::find($id);
        if(is_null($document)){
            return redirect()->route('sales_run.view')->withErrors('Registro no encontrado');
        }
        $documentDetail = DocumentDetail::where('id_document',$id)
        ->join('products as p','p.id_product','document_details.id_product')
        ->select('document_details.*','p.name','p.code')->get();
        return view('sales.run.show',compact('document','documentDetail'));
    }
    public function payRun(Request $request){
        $validatedData = $request->validate([
            'id_document' => 'required',
            // 'total_sale' => 'required|numeric',
            'recibe' => 'required|numeric|min:1|not_in:0',
        ]);
        try {
            // Consultamos el valor pagado
            $document = Document::find($request->id_document);
            if(is_null($document)){
                return redirect()->route('sales_run.view')->withErrors('Registro no encontrado');
            }
            if($document->total <= $request->recibe + $document->payment){
                $document->payment = $document->total;
                $document->id_status = '3';
                $document->save();
            }else{
                $document->payment = $request->recibe + $document->payment;
                $document->id_status = '2';
                $document->save();
            }
            return redirect()->route('sales.run.show',['id' => $document->id_document])->with('success','Pago recibido');
        } catch (\Throwable $th) {
            // dd($th);
            return redirect()->route('sales.run.show',['id' => $document->id_document])->withErrors('No se pudo realizar el pago');
        }
    }
    public function deleteDetailRun(Request $request){
        $aMsg = array();
        // Obtenemos el documento
        $oDocumentDetail = DocumentDetail::find($request->id_auto);
        $document = $oDocumentDetail->id_document;
        $oDocumentDetail->delete();
        // DocumentDetail::destroy($request->id_auto);
        $oDocument = Document::find($document);
        if(is_null($document)){
            $aMsg['error'] = 'No se encontro un documento';
            return response()->json($aMsg);
        }
        $nTotal = DocumentDetail::where('id_document',$oDocument->id_document)->sum(DB::raw('price * quantity'));
        $oDocument->total = $nTotal;
        $oDocument->payment = $nTotal;
        $oDocument->save();
        $aMsg['success'] = 'Registro eliminado';
        return response()->json($aMsg);
    }
    public function deleteDetail(Request $request){
        $aMsg = array();
        // Obtenemos el documento
        $oSaleDetail = SalesTableDetail::find($request->id_auto);
        $sale = $oSaleDetail->id_sale;
        $oSaleDetail->delete();
        // DocumentDetail::destroy($request->id_auto);
        $oSale = SalesTable::find($sale);
        if(is_null($oSale)){
            $aMsg['error'] = 'No se encontro una venta';
            return response()->json($aMsg);
        }
        $nTotal = SalesTableDetail::where('id_sale',$oSale->id_sale)->sum(DB::raw('price * quantity'));
        $oSale->total = $nTotal;
        $oSale->save();
        $aMsg['success'] = 'Registro eliminado';
        return response()->json($aMsg);
    }
    public function deuda(Request $request){
        $sale = SalesTable::find($request->id_sale);

        $saleTotal = SalesTableDetail::where('id_sale',$request->id_sale)->sum(DB::raw('price * quantity'));
        $nPay = SaleTablePay::where('id_sale',$request->id_sale)->sum('payment');


        if($saleTotal <= $nPay){
            $sale->status = '3';
            $sale->save();
        }else{
            $sale->status = '5';
            $sale->save();
        }
        $saleDocument = Document::where('id_sale',$sale->id_sale)->first();
        if(!is_null($saleDocument)){
            $aMsg['error'] = 'Ya se cerro la venta';
            return response()->json($aMsg);
        }
        $document = new Document();
        $document->description = $sale->name;
        $document->total = (is_null($saleTotal) ? 0 : $saleTotal);
        $document->payment = (is_null($nPay) ? 0 : $nPay);
        $document->id_status = '1';
        $document->id_sale = $sale->id_sale;
        $document->id_user = $sale->id_user;
        $document->date_document = $sale->sale_date;
        $document->id_type = '2';
        $document->save();

        $detail = SalesTableDetail::where('id_sale',$request->id_sale)->get();
        $detail->each(function($item, $key)use($document){
            $documentDetail = new DocumentDetail();
            $documentDetail->id_product = $item->id_product;
            $documentDetail->price = $item->price;
            $documentDetail->quantity = $item->quantity;
            $documentDetail->id_user = $item->id_user;
            $documentDetail->id_document = $document->id_document;
            $documentDetail->save();
        });

        // $oDocument
        $aMsg = array();
        $aMsg['success'] = 'Deuda adocionada';
        return response()->json($aMsg);
    }
    public function indexProduced(Request $request){
        $date = date('Y-m-d');
        $nPayment = Document::where('date_document',$date)->where('id_type','2')->sum('payment');
        // $nTotal = DocumentDetail::where(DB::raw('cast(created_at as date)'))
        $nTotal = Document::where('date_document',$date)->where('id_type','2')->sum('total');
        $nDeuda = Document::where('date_document',$date)->where('id_type','2')->where(function($q){
            $q->whereNull('payment')->orWhere('payment','<=','0');
        })->sum('total');

        return view('produced.index',compact('nPayment','nTotal','nDeuda','date'));
    }
}
