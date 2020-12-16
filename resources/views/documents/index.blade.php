@extends('layouts.app', ['pageSlug' => 'documents'])

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-chart">
                <div class="card-header ">
                    <div class="row">
                        <div class="col-sm-3 text-left">
                            <h2 class="card-title">Documentos</h2>
                        </div>
                        <div class="col-sm-9">
                            <form action="{{ route('documents.filter') }}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-md-3">
                                        <select name="id_type" class="select2 mt-2" style="width: 100%">
                                            @foreach($types as $key => $value)
                                                @if($value->id_type == $sType)
                                                    <option value="{{ $value->id_type }}" selected>{{ $value->name }}</option>
                                                @else
                                                    <option value="{{ $value->id_type }}">{{ $value->name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="date" class="form-control" name="fecha_ini" value="{{ $sDateIni }}">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="date" class="form-control" name="fecha_fin" value="{{ $sDateFin }}">
                                    </div>
                                    <div class="col-md-1">
                                        <label for="">Detalle</label><br>
                                        <input type="checkbox" name="detalle" {{ (!is_null($sDetalle)) ? 'checked' : '' }}>
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn pull-right">Filtrar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <hr>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form action="{{ route('documents.filter') }}" method="post">
                                @csrf
                                <input type="hidden" name="pendientes" value="SI">
                                <button class="pull-right btn">Traer cuentas pendientes</button>
                            </form>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table tablesorter" id="bootstrap-data-table-export">
                                    <thead>
                                        <tr>
                                            <th>Documento</th>
                                            <th>Tipo Documento</th>
                                            <th>Codigo</th>
                                            <th>Fecha</th>
                                            @if(!is_null($sDetalle))
                                            <th>Producto</th>
                                            <th>Precio</th>
                                            <th>Cantidad</th>
                                            @endif
                                            @if(is_null($sDetalle))
                                            <th>Valor a pagar</th>
                                            <th>Pagado</th>
                                            @endif
                                            <th>Estado</th>
                                            <th>Descripción</th>
                                            <th><i class="fa fa-cogs"></i></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($oDocument as $key => $value)
                                            <tr>
                                                <td>
                                                    {{ $value->id_document }}
                                                </td>
                                                <td>{{ $value->tipo }}</td>
                                                <td>{{ $value->code }}</td>
                                                <td>{{ $value->date_document }}</td>
                                                @if(!is_null($sDetalle))
                                                <td>{{ $value->producto }}</td>
                                                <td>$ {{  number_format($value->price) }}</td>
                                                <td>{{ $value->quantity }}</td>
                                                @endif
                                                @if(is_null($sDetalle))
                                                @if($value->id_type == '2')
                                                <td>$ {{ number_format($value->total) }}</td>
                                                <td>$ {{ number_format($value->payment) }}</td>
                                                @else
                                                <td colspan="2" class="text-center">$ {{ number_format($value->total) }}</td>
                                                <td style="display: none;"></td>
                                                @endif
                                                @endif
                                                <td>{{ $value->estado }}</td>
                                                <td>{{ $value->description }}</td>
                                                <td>
                                                    @if($value->id_type == '1')
                                                        <a class="text-white" href="{{ route('income.show',$value->id_document) }}"><i class="fa fa-eye"></i></a>
                                                    @elseif($value->id_type == '2')
                                                        <a class="text-white" href="{{ route('sales.run.show',$value->id_document) }}"><i class="fa fa-eye"></i></a>
                                                    @else
                                                        <i class="fa fa-eye"></i>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
<script>
    $(".select2").select2();
</script>
<script src="{{ asset('black') }}/js/lib/data-table/datatables.min.js"></script>
<script src="{{ asset('black') }}/js/lib/data-table/dataTables.bootstrap.min.js"></script>
<script src="{{ asset('black') }}/js/lib/data-table/dataTables.buttons.min.js"></script>
<script src="{{ asset('black') }}/js/lib/data-table/buttons.bootstrap.min.js"></script>
<script src="{{ asset('black') }}/js/lib/data-table/jszip.min.js"></script>
<script src="{{ asset('black') }}/js/lib/data-table/vfs_fonts.js"></script>
<script src="{{ asset('black') }}/js/lib/data-table/buttons.html5.min.js"></script>
<script src="{{ asset('black') }}/js/lib/data-table/buttons.print.min.js"></script>
<script src="{{ asset('black') }}/js/lib/data-table/buttons.colVis.min.js"></script>
<script src="{{ asset('black') }}/js/init/datatables-init.js"></script>
@endsection
