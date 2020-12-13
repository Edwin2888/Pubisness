@extends('layouts.app', ['pageSlug' => 'income'])
@section('css')
{{-- <link href="{{ asset('black') }}/adminlte.min.css" rel="stylesheet" /> --}}
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-chart">
                <div class="card-header ">
                    <div class="row">
                        <div class="col-sm-6 text-left">
                            <h2 class="card-title">Ingreso de pedidos</h2>
                        </div>
                        <div class="col-sm-6">

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
                    <div class="row justify-content-md-center">
                        <div class="col-md-9">
                            <form action="{{ route('income.new') }}" method="post">
                                @csrf
                                <input type="hidden" name="id_document" value="{{ $document->id_document }}">
                                <input type="hidden" name="code" value="{{ $document->code }}">
                                <div class="card card-chart">
                                    <div class="card-body">
                                        <div class="form-group{{ $errors->has('code') ? ' has-danger' : '' }}">
                                            <label>{{ __('Codigo Factura') }}</label>
                                            <input type="text" disabled class="form-control{{ $errors->has('code') ? ' is-invalid' : '' }}" placeholder="{{ __('Codigo') }}" value="{{ $document->code }}">
                                            @include('alerts.feedback', ['field' => 'code'])
                                        </div>
                                        <div class="form-group{{ $errors->has('date_document') ? ' has-danger' : '' }}">
                                            <label>{{ __('Fecha Factura') }}</label>
                                            <input type="date" class="form-control{{ $errors->has('date_document') ? ' is-invalid' : '' }}" value="{{ $document->date_document }}" name="date_document">
                                            @include('alerts.feedback', ['field' => 'date_document'])
                                        </div>
                                        <div class="form-group{{ $errors->has('description') ? ' has-danger' : '' }}">
                                            <label>{{ __('Descripción Pedido') }}</label>
                                            <input type="text" name="description" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" placeholder="{{ __('Descripción') }}" value="{{ $document->description }}">
                                            @include('alerts.feedback', ['field' => 'description'])
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group{{ $errors->has('id_product') ? ' has-danger' : '' }}">
                                                    <label>{{ __('Producto') }}</label>
                                                    <input type="text" style="width: 100%" name="id_product" class="{{ $errors->has('description') ? ' is-invalid' : '' }} complete-product select-search-product">
                                                    @include('alerts.feedback', ['field' => 'id_product'])
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group{{ $errors->has('price') ? ' has-danger' : '' }}">
                                                    <label>{{ __('Precio') }}</label>
                                                    <input type="number" name="price" class="form-control{{ $errors->has('price') ? ' is-invalid' : '' }}">
                                                    @include('alerts.feedback', ['field' => 'price'])
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group{{ $errors->has('quantity') ? ' has-danger' : '' }}">
                                                    <label>{{ __('Cantidad') }}</label>
                                                    <input type="number" name="quantity" class="form-control{{ $errors->has('quantity') ? ' is-invalid' : '' }}">
                                                    @include('alerts.feedback', ['field' => 'quantity'])
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <button type="submit" class="btn btn-fill btn-primary">{{ __('Agregar') }}</button>
                                        </div>
                                        <div class="col-md-6">
                                            <h1>$ {{ number_format($document->total) }}</h1>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="row justify-content-md-center">
                        <div class="col-md-9">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Codigo</th>
                                        <th>Producto</th>
                                        <th>Precio</th>
                                        <th>Cantidad</th>
                                        <th><i class="fa fa-cogs"></i></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($documentDetail as $key => $value)
                                        <tr>
                                            <td>{{ $value->code }}</td>
                                            <td>{{ $value->name }}</td>
                                            <td>$ {{ number_format($value->price) }}</td>
                                            <td>{{ $value->quantity }}</td>
                                            <td><a href="javascript:void(0)" onclick="deleteItem('{{ $value->id_auto }}')" class="btn btn-link btn-danger btn-icon btn-sm remove"><i class="tim-icons icon-simple-remove"></i></a></td>
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
            $.ajax({
                type: "POST",
                url: "{{ route('sales.run.delete.detail') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    id_auto: _idAuto
                },
                dataType: "jSon",
                success: function (response) {
                    if(response.error){
                        alert(response.error);
                    }
                    if(response.success){
                        location.reload();
                    }
                },error: function(){
                    alert('Error');
                }
            });
        }
        $(document).ready(function() {
            $().ready(function() {
                $("body").on('change','.complete-product',function(){
                    $.ajax({
                        type: "POST",
                        url: "{{ route('getProductPropities') }}",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            id: this.value
                        },
                        dataType: "jSon",
                        success: function (response) {
                            if(response){
                                changePrice();
                                $("#price").text(numberFomat(response.sale_price));
                                $("#precio").val(response.sale_price);
                            }
                            // $("#loading-open").removeClass('loading-head');
                        },error: function(){
                            // $("#loading-open").removeClass('loading-head');
                        }
                    });
                });
                $('.select-search-product').select2({
                    minimumInputLength: 3,
                    ajax: { // instead of writing the function to execute the request we use Select2's convenient helper
                        url: "/products/autocomplete",
                        //dataType: 'jsonp',
                        dataType: 'json',
                        data: function (term, page) {
                            return {
                                q: term,
                            };
                        },

                        results: function (data) {
                            return {
                                results: $.map(data, function (item) {
                                    return {
                                        text: `${item.name} - (${item.code})`,
                                        id: item.id_product
                                    }
                                })
                            };
                        }

                    },
                });
            });
        });
    </script>
@endsection
