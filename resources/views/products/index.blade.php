@extends('layouts.app', ['pageSlug' => 'products'])

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card card-chart">
                <div class="card-header ">
                    <div class="row">
                        <div class="col-sm-6 text-left">
                            <h2 class="card-title">Productos</h2>
                        </div>
                        <div class="col-sm-6">

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mt-3">
                            <ul class="nav nav-tabs md-tabs" id="myTab" role="tablist">
                                <li class="nav-item">
                                  <a class="nav-link active" id="list-tab" data-toggle="tab" href="#list" role="tab" aria-controls="list-md"
                                    aria-selected="true">Listado</a>
                                </li>
                                <li class="nav-item">
                                  <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile"
                                    aria-selected="false">Nuevo</a>
                                </li>
                            </ul>
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
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="list" role="tabpanel" aria-labelledby="list-tab">
                            <div class="table-responsive">
                                <table class="table tablesorter" id="bootstrap-data-table-export">
                                    <thead class=" text-primary">
                                        <tr>
                                            <th>Codigo</th>
                                            <th>Nombre</th>
                                            <th class="text-center">Precio Entrada</th>
                                            <th class="text-center">Precio Venta</th>
                                            <th>Aplica gasto</th>
                                            <th>Fecha creacion</th>
                                            <th>Fecha edición</th>
                                            <th class="text-right"><i class="fa fa-cogs"></i></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($products as $key => $value)
                                        <tr>
                                            <td>{{ $value->code }}</td>
                                            <td>{{ $value->name }}</td>
                                            <td class="text-center">$ {{ number_format($value->entry_price) }}</td>
                                            <td class="text-center">$ {{ number_format($value->sale_price) }}</td>
                                            <td>
                                                @if(!is_null($value->id_type_expense) && !empty($value->id_type_expense))
                                                    {{ $value->expense->name }}
                                                @else
                                                    NO
                                                @endif
                                            </td>
                                            <td>{{ $value->created_at }}</td>

                                            <td>{{ $value->updated_at }}</td>
                                            <td class="td-actions text-right">
                                                <button type="button" rel="tooltip" title="Editar" class="btn btn-link" onclick="location.href='{{ route('products.edit',$value->id_product) }}'">
                                                    <i class="tim-icons icon-pencil"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            <div class="row justify-content-md-center">
                                <div class="col-md-8">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="title">{{ __('Nuevo Producto') }}</h5>
                                        </div>
                                        <form method="post" action="{{ route('products.new') }}" autocomplete="off">
                                            <div class="card-body">
                                                @csrf
                                                @include('alerts.success')

                                                <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                                    <label>{{ __('Nombre') }}</label>
                                                    <input type="text" name="name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Name') }}" value="{{ old('name', '') }}">
                                                    @include('alerts.feedback', ['field' => 'name'])
                                                </div>

                                                <div class="form-group{{ $errors->has('entry_price') ? ' has-danger' : '' }}">
                                                    <label>{{ __('Precio Entrada') }}</label>
                                                    <input type="number" min="0" name="entry_price" class="form-control{{ $errors->has('entry_price') ? ' is-invalid' : '' }}" placeholder="{{ __('Precio Entrada') }}" value="{{ old('entry_price', '') }}">
                                                    @include('alerts.feedback', ['field' => 'entry_price'])
                                                </div>
                                                <div class="form-group{{ $errors->has('sale_price') ? ' has-danger' : '' }}">
                                                    <label>{{ __('Precio Venta') }}</label>
                                                    <input type="number" min="0" name="sale_price" class="form-control{{ $errors->has('sale_price') ? ' is-invalid' : '' }}" placeholder="{{ __('Precio Venta') }}" value="{{ old('sale_price', '') }}">
                                                    @include('alerts.feedback', ['field' => 'sale_price'])
                                                </div>
                                                <div class="form-group{{ $errors->has('id_type_expense') ? ' has-danger' : '' }}">
                                                    <label>{{ __('Aplica gasto') }}</label>
                                                    <select name="id_type_expense" class="form-control{{ $errors->has('id_type_expense') ? ' is-invalid' : '' }}">
                                                        <option value="">NO</option>
                                                        @foreach($oTypes as $item)
                                                        <option value="{{ $item->id_type_expense }}">{{ $item->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @include('alerts.feedback', ['field' => 'id_type_expense'])
                                                </div>
                                            </div>
                                            <div class="card-footer">
                                                <button type="submit" class="btn btn-fill btn-primary">{{ __('Guardar') }}</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
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

