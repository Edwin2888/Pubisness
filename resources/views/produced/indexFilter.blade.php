@extends('layouts.app', ['pageSlug' => 'produced'])
    <link href="{{ asset('black') }}/adminlte.min.css" rel="stylesheet" />
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-chart">
            <div class="card-header">
                <div class="col-sm-3 text-left">
                    <h2 class="card-title">Balance</h2>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('producedFilter.view') }}" method="post">
                    @csrf
                    <div class="row justify-content-md-center">
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
                                    <div class="form-group">
                                        <input type="date" value="{{ $dDate }}" name="date_expense" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
                                    <div class="form-group">
                                        <select name="type" class="form-control">
                                            <option {{ ($type == 'month' ? 'selected' : '' )  }} value="month">Mensual</option>
                                            {{-- <option {{ ($type == 'week' ? 'selected' : '' )  }} value="week">Semanal</option> --}}
                                            <option {{ ($type == 'year' ? 'selected' : '' )  }} value="year">Anual</option>
                                            <option {{ ($type == 'day' ? 'selected' : '' )  }} value="day">Diario</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
                                    <div class="form-group">
                                        <button class="btn">Filtrar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="row justify-content-md-center">
                    <div class="col-sm-3 col-xs-6">
                        <div class="description-block border-right">
                            {{-- <span class="description-percentage text-green"><i class="fa fa-caret-up"></i> 100%</span> --}}
                            <h5 class="description-header">$ {{ number_format($nIncome + $nExpense) }}</h5>
                            <span class="description-text">INVERSIÓN</span>
                        </div>
                    </div>
                    <div class="col-sm-3 col-xs-6">
                        <div class="description-block border-right">
                            {{-- <span class="description-percentage text-green"><i class="fa fa-caret-up"></i> 100%</span> --}}
                            <h5 class="description-header">$ {{ number_format($nPayment) }}</h5>
                            <span class="description-text">GANANCIA</span>
                        </div>
                    </div>
                    <div class="col-sm-3 col-xs-6">
                        <div class="description-block border-right">
                            {{-- <span class="description-percentage text-green"><i class="fa fa-caret-up"></i> 100%</span> --}}
                            <h5 class="description-header">$ {{ number_format($nDeuda) }}</h5>
                            <span class="description-text">DEUDA</span>
                        </div>
                    </div>
                    <div class="col-sm-3 col-xs-6">
                        <div class="description-block border-right">
                            {{-- <span class="description-percentage text-green"><i class="fa fa-caret-up"></i> 100%</span> --}}
                            <h5 class="description-header">$ {{ number_format($nPayment - ($nIncome + $nExpense)) }}</h5>
                            <span class="description-text">SALDO</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
