@extends('layouts.app', ['pageSlug' => 'produced'])
    <link href="{{ asset('black') }}/adminlte.min.css" rel="stylesheet" />
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-chart">
                <div class="card-header">
                    <div class="col-sm-3 text-left">
                        <h2 class="card-title">Producido</h2>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row justify-content-md-center">
                        <div class="col-sm-3 col-xs-6">
                            <div class="description-block border-right">
                                <span class="description-percentage text-green"><i class="fa fa-caret-up"></i> 100%</span>
                                <h5 class="description-header">$ {{ number_format($nTotal) }}</h5>
                                <span class="description-text">TOTAL ESTIMADO DEL DÍA</span>
                            </div>
                          <!-- /.description-block -->
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-3 col-xs-6">
                            <div class="description-block border-right">
                                @php($nPay = 0)
                                @if($nTotal > 0)
                                    @php($nPay = round(($nPayment / $nTotal) * 100, 2))
                                @endif
                                @if($nPay > 96)
                                <span class="description-percentage text-green"><i class="fa fa-caret-up"></i> {{ $nPay }}%</span>
                                @elseif($nPay <=95)
                                <span class="description-percentage text-yellow"><i class="fa fa-caret-left"></i> {{ $nPay }}%</span>
                                @else
                                <span class="description-percentage text-red"><i class="fa fa-caret-down"></i> {{ $nPay }}%</span>
                                @endif
                                <h5 class="description-header">$ {{ number_format($nPayment) }}</h5>
                                <span class="description-text">TOTAL PAGADO</span>
                            </div>
                          <!-- /.description-block -->
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-3 col-xs-6">
                            <div class="description-block border-right">
                                @php($nNumber = 0)
                                @if($nTotal > 0)
                                    @php($nNumber = round(($nDeuda / $nTotal) * 100 , 2))
                                @endif
                                @if($nNumber > 96)
                                <span class="description-percentage text-green"><i class="fa fa-caret-up"></i> {{ $nNumber }}%</span>
                                @elseif($nNumber <=95 && $nNumber > 60)
                                <span class="description-percentage text-yellow"><i class="fa fa-caret-left"></i> {{ $nNumber }}%</span>
                                @else
                                <span class="description-percentage text-red"><i class="fa fa-caret-down"></i> {{ $nNumber }}%</span>
                                @endif
                                <h5 class="description-header">$ {{ number_format($nDeuda) }}</h5>
                                <span class="description-text">DEUDA TOTAL</span>
                            </div>
                          <!-- /.description-block -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
