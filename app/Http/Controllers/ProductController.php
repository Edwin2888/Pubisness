<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::get();
        return view('products.index',compact('products'));
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
            if(is_null($request->id_product)){
                $oProduct = new Product();
                $oProduct->name = strtoupper($request->name);
                $oProduct->sale_price = (is_null($request->sale_price) ? 0 : $request->sale_price);
                $oProduct->entry_price = (is_null($request->entry_price) ? 0 : $request->entry_price);
                $oProduct->save();
                $oProduct->code = 'COD'.$oProduct->id_product;
                $oProduct->save();
            }else{
                $oProduct = Product::find($request->id_product);
                $oProduct->name = strtoupper($request->name);
                $oProduct->sale_price = (is_null($request->sale_price) ? 0 : $request->sale_price);
                $oProduct->entry_price = (is_null($request->entry_price) ? 0 : $request->entry_price);
                $oProduct->save();
            }
            return redirect()->route('products.view')->with('success','Registro guardado exitosamente');
        } catch (\Throwable $th) {
            return redirect()->route('products.view')->withErrors('No se pudo guardar el registro');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        dd(10);
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
        $oProduct = Product::find($id);
        return view('products.edit',compact('oProduct'));
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
    public function autocomplete(Request $request){
        return Product::where('name','like','%'.$request->q.'%')
        ->orWhere('code','like','%'.$request->q.'%')->get(['name','code','id_product']);
    }
    public function propities(Request $request){
        $product = Product::find($request->id);
        return $product;
    }
}
