@extends('layouts.app', ['pageSlug' => 'inventory'])

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-chart">
                <div class="card-header ">
                    <div class="row">
                        <div class="col-sm-6 text-left">
                            <h2 class="card-title">Inventario de productos</h2>
                        </div>
                        <div class="col-sm-6">

                        </div>
                    </div>
                    <hr>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table tablesorter" id="bootstrap-data-table-export">
                                    <thead>
                                        <tr>
                                            <th>Producto</th>
                                            <th>Entradas</th>
                                            <th>Salidas</th>
                                            <th>Saldo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($oProducts as $key => $value)
                                        <tr>
                                            <td>{{ $value->name}} </td>
                                            <td>{{ $value->compras}} </td>
                                            <td>{{ $value->ventas}} </td>
                                            <td>{{ $value->compras - $value->ventas}} </td>
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
