@extends('layouts.app', ['pageSlug' => 'expense'])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-chart">
                <div class="card-header">
                    <div class="row">
                        <div class="col-sm-6 text-left">
                            <h2 class="card-title">Gastos</h2>
                        </div>
                        <div class="col-sm-6 text-left">
                            <button class="btn pull-right" onclick="location.href='{{ route('expense.new') }}'">Nuevo Gasto</button>
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
                        <div class="table-responsive">
                            <table class="table tablesorter" id="bootstrap-data-table-export">
                                <thead class=" text-primary">
                                    <tr>
                                        <th>Fecha</th>
                                        {{-- <th>Descripción</th> --}}
                                        <th>Nombre</th>
                                        <th class="text-center">Precio</th>
                                        <th class="text-center">Cantidad</th>
                                        <th>Tipo de gasto</th>
                                        <th>Fecha creacion</th>
                                        <th>Fecha edición</th>
                                        <th class="text-right"><i class="fa fa-cogs"></i></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($oExpense as $value)
                                        <tr>
                                            <td>{{ $value->expense_date }}</td>
                                            {{-- <td>{{ $value->description }}</td> --}}
                                            <td>{{ $value->name }}</td>
                                            <td>$ {{ number_format($value->price) }}</td>
                                            <td>{{ $value->quantity }}</td>
                                            <td>{{ (is_null($value->type) ? 'NO' : $value->type ) }}</td>
                                            <td>{{ $value->created_at }}</td>
                                            <td>{{ $value->updated_at }}</td>
                                            <td><a href="javascript:void(0)" onclick="deleteItem('{{ $value->id_expense }}')" class="btn btn-link btn-danger btn-icon btn-sm remove"><i class="tim-icons icon-simple-remove"></i></a></td>
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
@endsection
@section('js')
<script>
    function deleteItem(_idAuto){
        $("#loading-open").addClass('loading-head');
            $.ajax({
                type: "POST",
                url: "{{ route('expense.delete') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    id_auto: _idAuto
                },
                dataType: "jSon",
                success: function (response) {
                    $("#loading-open").removeClass('loading-head');
                    if(response.error){
                        alert(response.error);
                    }
                    if(response.success){
                        location.reload();
                    }
                },error: function(){
                    $("#loading-open").removeClass('loading-head');
                    alert('Error');
                }
            });
    }
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
