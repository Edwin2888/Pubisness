@extends('layouts.app', ['pageSlug' => 'sales_run'])
@section('css')
{{-- <link href="{{ asset('black') }}/adminlte.min.css" rel="stylesheet" />
<link href="{{ asset('black') }}/ionicons.min.css" rel="stylesheet" /> --}}
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
                                <small>({{ $document->statusn->name }})</small>
                            </h2>
                        </div>
                        <div class="col-sm-6">
                            <button class="btn pull-right" onclick="location.href='{{ route('sales_run.view') }}'">Nueva</button>
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
                                <input type="hidden" value="{{ $document->id_document }}" name="id_document">
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
                                                    <input type="text" name="description" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" placeholder="{{ __('Descripción') }}" value="{{ old('description', $document->description) }}" autocomplete="off">
                                                    @include('alerts.feedback', ['field' => 'description'])
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <label for="">Total:</label>
                                                <h1 id="price"></h1>
                                            </div>
                                        </div>
                                        @if($document->id_status == '1')
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
                                        @endif
                                    </div>
                                </div>
                                <div class="card-footer">
                                    @if($document->id_status == '1' || $document->id_status == '5' || $document->id_status == '2')
                                        <button type="submit" class="pull-right btn btn-fill btn-primary">{{ __('Agregar') }}</button>
                                    @endif
                                </div>
                            </form>
                        </div>
                        <div class="col-md-4">
                            <label>Saldo Total: </label>
                            <h1 style="font-size: 50px">$ {{ number_format($document->total) }}</h1>
                            @if($document->id_status == '3' || $document->id_status == '5' || $document->id_status == '2')
                            <label>Saldo pagado: </label>
                            <h1 style="font-size: 50px">$ {{ number_format($document->payment) }}</h1>
                            <input type="hidden" id="payment" value="{{ $document->payment }}">
                            @endif
                            @if($document->id_status == '1' || $document->id_status == '5' || $document->id_status == '2')
                                <div class="row">
                                    <div class="col-md-12">
                                        <label>Vuelto: </label><h1 id="vuelto" style="font-size: 30px"></h1>
                                    </div>
                                </div>
                                <form action="{{ route('sales.run.pay') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="id_document" value="{{ $document->id_document }}">
                                    <div class="row">
                                        <input type="hidden" id="total_sale" name="payment" value="{{ $document->total }}">
                                        <div class="col-md-4">
                                            <label>Recibe: </label>
                                            <input type="text" id="total_recibe" name="recibe" class="form-control" autocomplete="off">
                                        </div>
                                        <div class="col-md-8">
                                            <label>$: </label>
                                            <h1 id="recibeh" style="font-size: 30px"></h1>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Cerrar cuenta:</label><br>
                                            <button class="btn btn-success">PAGAR</button>
                                        </div>
                                        {{-- @if($document->id_status <> '5')
                                        <div class="col-md-6">
                                            <label>Posponer cuenta:</label><br>
                                            <button type="button" onclick="deudaDocument('{{ $document->id_document }}')" class="btn btn-danger">DEUDA</button>
                                        </div>
                                        @endif --}}
                                    </div>
                                </form>
                            @endif
                        </div>
                    </div>
                    <div class="row justify-content-md-center">
                        <div class="col-md-11 mt-3">
                            <div class="table-responsive ps">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Codigo</th>
                                            <th>Producto</th>
                                            <th>Hora</th>
                                            <th>Precio</th>
                                            <th>Cantidad</th>
                                            <th>Total</th>
                                            <th><i class="fa fa-cogs"></i></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($documentDetail as $key => $value)
                                            <tr>
                                                <td>{{ $value->code }}</td>
                                                <td>{{ $value->name }}</td>
                                                <td>{{ $value->created_at }}</td>
                                                <td>$ {{ number_format($value->price) }}</td>
                                                <td>{{ $value->quantity }}</td>
                                                <td>$ {{ number_format($value->price * $value->quantity) }}</td>
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
    </div>
@endsection

@push('js')
    {{-- <script src="{{ asset('black') }}/js/plugins/chartjs.min.js"></script> --}}
    <script>
        function deudaDocument(_idDocument){
            $.ajax({
                type: "POST",
                url: "{{ route('deuda.document') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    id_document: _idDocument
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
        function changePay(){
            let abono = $("#total_recibe").val();
            if(abono < 0 || !abono){
                $("#vuelto").text(``);
                return;
            }
            let total = $("#total_sale").val();
            let pago = $("#payment").val();
            if(!pago){
                pago = 0;
            }
            let vuelto = parseInt(pago) + parseInt(abono) - parseInt(total);
            if(vuelto < 0){
                vuelto = 0;
            }
            $("#vuelto").text(numberFomat(vuelto));
        }
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
                $('body').on('keyup','#precio',function(){
                    changePrice();
                });
                $('body').on('keyup','#cantidad',function(){
                    changePrice();
                });
                $('body').on('keyup','#total_recibe',function(){
                    let n_recibe = $("#total_recibe").val();
                    $("#recibeh").text(numberFomat(n_recibe));
                    changePay();
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

