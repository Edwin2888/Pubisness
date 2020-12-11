@extends('layouts.app', ['pageSlug' => 'sales_run'])
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
                                Venta
                                <small>(Rápida)</small>
                            </h2>
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
                    <div class="row">
                        <div class="col-md-8">
                            <form action="{{ route('sales.run.new') }}" method="post">
                                @csrf
                                <div class="card card-chart">
                                    <div class="card-body">
                                        <div class="form-group{{ $errors->has('date_document') ? ' has-danger' : '' }}">
                                            <label>{{ __('Fecha de Venta') }}</label>
                                            <input type="date" class="form-control{{ $errors->has('date_document') ? ' is-invalid' : '' }}" value="{{ old('date_document', date('Y-m-d')) }}" name="date_document">
                                            @include('alerts.feedback', ['field' => 'date_document'])
                                        </div>
                                        <div class="row">
                                            <div class="col-md-7">
                                                <div class="form-group{{ $errors->has('description') ? ' has-danger' : '' }}">
                                                    <label>{{ __('Descripción Venta') }}</label>
                                                    <input type="text" name="description" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" placeholder="{{ __('Descripción') }}" value="{{ old('description', '') }}">
                                                    @include('alerts.feedback', ['field' => 'description'])
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <label for="">Total:</label>
                                                <h1 id="price"></h1>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group{{ $errors->has('id_product') ? ' has-danger' : '' }}">
                                                    <label>{{ __('Producto') }}</label>
                                                    <input type="text" name="id_product" style="width: 100%" class="{{ $errors->has('description') ? ' is-invalid' : '' }} complete-product select-search-product">
                                                    @include('alerts.feedback', ['field' => 'id_product'])
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group{{ $errors->has('price') ? ' has-danger' : '' }}">
                                                    <label>{{ __('Precio') }}</label>
                                                    <input type="number" id="precio" name="price" class="form-control{{ $errors->has('price') ? ' is-invalid' : '' }}">
                                                    @include('alerts.feedback', ['field' => 'price'])
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group{{ $errors->has('quantity') ? ' has-danger' : '' }}">
                                                    <label>{{ __('Cantidad') }}</label>
                                                    <input type="number" name="quantity" id="cantidad" class="form-control{{ $errors->has('quantity') ? ' is-invalid' : '' }}" value="1">
                                                    @include('alerts.feedback', ['field' => 'quantity'])
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-fill btn-primary">{{ __('Agregar') }}</button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    {{-- <script src="{{ asset('black') }}/js/plugins/chartjs.min.js"></script> --}}
    <script>
        function changePrice(){
            let _price = $("#precio").val();
            if(!_price){
                _price = 0;
            }
            // $("#price").text(numberFomat(_price));
            let _cantidad = $("#cantidad").val();
            if(!_cantidad){
                _cantidad = 0;
            }
            let _total = _cantidad * _price;
            $("#price").text(numberFomat(_total));
            // $("#total").val(numberFomat(_total));
        }
        $(document).ready(function() {
            $().ready(function() {
                $('body').on('keyup','#precio',function(){
                    changePrice();
                });
                $('body').on('keyup','#cantidad',function(){
                    changePrice();
                });
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
                                $("#cantidad").val('1');
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
@endpush

