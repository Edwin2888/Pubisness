@extends('layouts.app', ['pageSlug' => 'sales'])
@section('css')
<link href="{{ asset('black') }}/adminlte.min.css" rel="stylesheet" />
<link href="{{ asset('black') }}/ionicons.min.css" rel="stylesheet" />
@endsection
@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card card-chart">
                <div class="card-header ">
                    <div class="row">
                        <div class="col-sm-6 text-left">
                            <h2 class="card-title">
                                Ventas
                                <small>(Cuentas pendientes)</small>
                            </h2>
                        </div>
                        <div class="col-sm-6">
                            <div class="btn-group pull-right">
                                <form action="{{ route('sales.filter') }}" method="post">
                                    @csrf
                                    @if(!is_null($sAll))
                                        <input type="hidden" name="all" value="all">
                                    @endif
                                    <input type="hidden" value="{{ $sDateAnt }}" name="dia">
                                    <button class="btn btn-sm">&lt;</button>
                                </form>
                                <span class="btn btn-sm" unselectable="on" style="-moz-user-select: none;">
                                    {{ $sDate}}
                                </span>
                                <form action="{{ route('sales.filter') }}" method="post">
                                    @csrf
                                    @if(!is_null($sAll))
                                        <input type="hidden" name="all" value="all">
                                    @endif
                                    <input type="hidden" value="{{ $sDateDes }}" name="dia">
                                    <button class="btn btn-sm">&gt;</button>
                                </form>
                            </div>
                            <form action="{{ route('sales.filter') }}" method="post">
                                @csrf
                                @if(is_null($sAll))
                                <input type="hidden" name="all" value="all">
                                <input type="hidden" value="{{ $sDate }}" name="dia">
                                <button class="btn btn-sm">Mostrar Todas</button>
                                <button type="button" class="btn btn-sm" onclick="location.reload()">Actualizar</button>
                                @else
                                <button class="btn btn-sm">Mostrar Pendientes</button>
                                <button type="button" class="btn btn-sm" onclick="location.reload()">Actualizar</button>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if(session()->has('success'))
                        <div class="alert alert-success">
                            @if(is_array(session('success')))
                            <ul>
                                @foreach (session('success') as $success)
                                    <li>{{ $success }}</li>
                                @endforeach
                            </ul>
                            @else
                            {{ session('success') }}
                            @endif
                        </div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    {{-- <div class="chart-area"> --}}
                        <div class="row">
                            @foreach($sales as $key => $value)
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="info-box" title="{{ $value->name }}" style="cursor: pointer" onclick="location.href='{{ route('sales.show',$value->id_sale) }}'">
                                    @if($value->status == 1)
                                    <span class="info-box-icon bg-red"><i class="tim-icons icon-wallet-43"></i></span>
                                    @elseif($value->status == 2)
                                    <span class="info-box-icon bg-orange"><i class="tim-icons icon-wallet-43"></i></span>
                                    @elseif($value->status == 3)
                                    <span class="info-box-icon bg-green"><i class="tim-icons icon-wallet-43"></i></span>
                                    @elseif($value->status == 4)
                                    <span class="info-box-icon bg-black"><i class="tim-icons icon-simple-remove"></i></span>
                                    @elseif($value->status == 5)
                                    <span class="info-box-icon bg-red"><i class="tim-icons icon-wallet-43"></i></span>
                                    @endif
                                    <div class="info-box-content">
                                        <span class="info-box-text">{{ $value->name }}</span>
                                        <span class="info-box-number">${{ number_format($value->total - $value->pay) }}</span>
                                        @if($value->status == 1)
                                        <span class="badge badge-red pull-right">{{ $value->statusn->name }}</span>
                                        @elseif($value->status == 2)
                                        <span class="badge badge-warning pull-right">{{ $value->statusn->name }}</span>
                                        @elseif($value->status == 3)
                                        <span class="badge badge-success pull-right">{{ $value->statusn->name }}</span>
                                        @elseif($value->status == 4)
                                        <span class="badge badge-dark pull-right">{{ $value->statusn->name }}</span>
                                        @elseif($value->status == 5)
                                        <span class="badge badge-red pull-right">{{ $value->statusn->name }}</span>
                                        @endif
                                        <span class="pull-left"><small>{{ substr($value->updated_at,11,19) }}</small></span>
                                        {{-- @if($value->status != 3)
                                        <button class="btn btn-round btn-sm"><i class="fa fa-credit-card"></i> Pagar</button>
                                        @endif --}}
                                    </div>
                                  <!-- /.info-box-content -->
                                </div>
                                <!-- /.info-box -->
                            </div>
                            <div class="clearfix visible-sm-block"></div>
                            @endforeach
                            <!-- /.col -->
                          </div>
                    {{-- </div> --}}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    {{-- <script src="{{ asset('black') }}/js/plugins/chartjs.min.js"></script> --}}
    <script>
        $(document).ready(function() {
        //   demo.initDashboardPageCharts();
        });
    </script>
@endpush

