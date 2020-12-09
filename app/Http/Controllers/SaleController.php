<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalesTable;
use App\Models\SalesTableDetail;
use DB;
use App\Models\SaleTablePay;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $date = date('Y-m-d H:m:s');
        $hh = date('H');
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
        })->when($hh < '5',function($q)use($hh){
            $q->whereBetween('sales_tables.sale_date',[DB::raw("(CURRENT_TIMESTAMP - INTERVAL 1 DAY)"),DB::raw('DATE_ADD(now(), INTERVAL 5 HOUR)')]);
        })->when($hh > '5',function($q)use($hh){
            $q->whereBetween('sales_tables.sale_date',[DB::raw("(CURRENT_TIMESTAMP - INTERVAL 1 DAY) - INTERVAL CURRENT_TIME HOUR_SECOND"),DB::raw('DATE_ADD((CURRENT_TIMESTAMP) - INTERVAL CURRENT_TIME HOUR_SECOND, INTERVAL 24 HOUR )')]);
        })
        ->orderBy('status','asc')->orderBy('id_sale','desc')
        ->get(['sales_tables.*',DB::raw('(total.total - pay.pay) as tota'),'total.total','pay.pay']);
        return view('sales.index',compact('sales','sAll'));
    }
    public function filter(Request $request)
    {
        $sAll = $request->all;
        if(is_null($sAll)){
            return redirect()->route('sales.view');
        }
        $oPay = SaleTablePay::select(DB::raw('sum(payment) as pay'),'id_sale')->groupBy('id_sale');

        $oTotal = SalesTableDetail::select(DB::raw('sum(price * quantity) as total'),'id_sale')->groupBy('id_sale');
        $sales = SalesTable::leftJoinSub($oPay,'pay',function($join){
            $join->on('pay.id_sale','sales_tables.id_sale');
        })->leftJoinSub($oTotal,'total',function($join){
            $join->on('total.id_sale','sales_tables.id_sale');
        })
        ->orderBy('status','asc')->orderBy('id_sale','desc')
        ->get(['sales_tables.*',DB::raw('(total.total - pay.pay) as total')]);
        return view('sales.index',compact('sales','sAll'));
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
        ]);
        try {
            // Consultamos si el nombre esta ocupado
            $oSales = SalesTable::where('name',$request->name)
            ->where(DB::raw('cast(sale_date as date)'),DB::raw('cast(now() as date)'))
            ->whereIn('status',['1','2'])->first();
            if(!is_null($oSales)){
                return redirect()->route('sales.view')->withErrors('La mesa '.$request->name.' esta en uso actualmente');
            }
            $oSale = new SalesTable();
            $oSale->name = $request->name;
            $oSale->sale_date = DB::raw('CURRENT_TIMESTAMP');
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
}
