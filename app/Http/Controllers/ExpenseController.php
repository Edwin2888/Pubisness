<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExpenseType;
use App\Models\Expense;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $oExpense = Expense::leftJoin('expense_types as t','t.id_type_expense','expenses.id_type_expense')
        ->select('expenses.expense_date','expenses.name','expenses.price','expenses.quantity',
        't.name as type','expenses.created_at','expenses.updated_at','expenses.id_expense')
        ->get();
        return view('expenses.index',compact('oExpense'));
    }

    public function indexType(){
        $oTypes = ExpenseType::get();
        return view('types_expenses.index',compact('oTypes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'expense_date' => 'required|date',
            'name' => 'required|max:255',
            'price' => 'required',
            'quantity' => 'required',
        ]);
        try {
            // DB::beginTransaction();
            if(is_null($request->id_expense)){
                $oExpense = new Expense();
                $oExpense->expense_date = $request->expense_date;
                $oExpense->id_user = auth()->user()->id;
                $oExpense->name = strtoupper($request->name);
                $oExpense->price = (is_null($request->price) ? 0 : $request->price);
                $oExpense->quantity = (is_null($request->quantity) ? 0 : $request->quantity);
                $oExpense->id_type_expense = $request->id_type_expense;
                $oExpense->save();
            }else{
                // $oExpense = ExpenseType::find($request->id_type_expense);
                // $oExpense->name = strtoupper($request->name);
                // $oExpense->save();
            }
            return redirect()->route('expense.view')->with('success','Registro guardado exitosamente');
        } catch (\Throwable $th) {
            return redirect()->route('expense.new')->withErrors('No se pudo guardar el registro');
        }
    }

    public function createType(Request $request){
        $validatedData = $request->validate([
            'name' => 'required|max:255',
        ]);
        try {
            if(is_null($request->id_type_expense)){
                $oExpense = new ExpenseType();
                $oExpense->name = strtoupper($request->name);
                $oExpense->save();
            }else{
                $oExpense = ExpenseType::find($request->id_type_expense);
                $oExpense->name = strtoupper($request->name);
                $oExpense->save();
            }
            return redirect()->route('expense_type.view')->with('success','Registro guardado exitosamente');
        } catch (\Throwable $th) {
            return redirect()->route('expense_type.view')->withErrors('No se pudo guardar el registro');
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
    public function editType($id){
        $oTypes = ExpenseType::find($id);
        return view('types_expenses.edit',compact('oTypes'));
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
    public function new(){
        $oTypes = ExpenseType::get();
        return view('expenses.new',compact('oTypes'));
    }
    public function delete(Request $request){
        $aMsg = array();

        $oExpense = Expense::find($request->id_auto);
        $oExpense->delete();

        $aMsg['success'] = 'Registro eliminado';
        return response()->json($aMsg);
    }
}
