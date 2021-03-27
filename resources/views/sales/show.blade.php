@extends('layouts.app', ['pageSlug' => 'sales'])
@section('css')
{{-- <link href="{{ asset('black') }}/adminlte.min.css" rel="stylesheet" /> --}}
@endsection
@section('content')
    <h2 class="card-title text-uppercase">
        {{ $sale->name }}
        <small>(Estado: {{ $sale->statusn->name }})</small>
    </h2>
    <div class="row">
        <div class="col-md-12">
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
        </div>
        <div class="col-md-8">
            <form action="{{ route('sales_detail.save') }}" method="post">
                @csrf
                <input type="hidden" value="{{ $sale->id_sale }}" name="id_sale">
                <div class="row">
                    <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
                        <div class="form-group">
                            <label>{{ __('Producto') }}</label>
                            <input type="text" name="id_product" class="select-search-product complete-product" style="width: 100%">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
                        <div class="form-group">
                            <label>{{ __('Observación') }}</label>
                            <input type="text" name="observation" class="form-control" placeholder="Observacion">
                        </div>
                    </div>
                </div>
                @if($sale->status <> '3' && $sale->status <> '5')
                    <div class="row">
                        <div class="col-md-2 col-lg-2 col-xs-12 col-sm-12">
                            <div class="form-group">
                                <label>{{ __('Cantidad') }}</label>
                                <input value="1" type="number" name="quantity" id="cantidad" class="form-control" min="0">
                            </div>
                        </div>
                        <div class="col-md-3 col-lg-3 col-xs-12 col-sm-12">
                            <div class="form-group">
                                <label>{{ __('Precio') }}</label>( <span id="price"></span> )
                                <input id="precio" type="number" name="price" class="form-control" min="0">
                            </div>
                        </div>
                        <div class="col-md-3 col-lg-3 col-xs-12 col-sm-12">
                            <div class="form-group">
                                <label>{{ __('Total') }}</label>( <span id="totalS"></span> )
                                <input id="total" disabled type="text" class="form-control" min="0">
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-4 col-xs-12 col-sm-12">
                            <div class="form-group">
                                <button class="btn mt-4 pull-right">Agregar</button>
                            </div>
                        </div>
                    </div>
                @endif
            </form>
        </div>
        <div class="col-md-4">
            {{-- <form action="" method="post"> --}}
                <label>Saldo Pendiente: </label>
                <h1 style="font-size: 60px">$ {{ number_format(($saleTotal - $nPay < 0 ? 0 : $saleTotal - $nPay) ) }}</h1>
                <div class="row">
                    <div class="col-md-6">
                        <label>Pago Total: </label><h1 style="font-size: 30px">$ {{ number_format($saleTotal) }}</h1>
                    </div>
                    <div class="col-md-6">
                        <label>Pago reportado: </label><h1 style="font-size: 30px">$ {{ number_format($nPay) }}</h1>
                    </div>
                </div>
            {{-- </form> --}}
        </div>
    </div>
    <hr>
    @if($saleTotal > 0 && $sale->status <> '3' && $sale->status <> '5')
        <form action="{{ route('sales.pay') }}" method="post">
            <div class="row">
                @csrf
                @php($nTotal = ($saleTotal - $nPay < 0 ? 0 : $saleTotal - $nPay))
                <input type="hidden" value="{{ $nTotal }}" id="totalSale" name="totalSale">
                <input type="hidden" value="{{ $sale->id_sale }}" name="id_sale">
                <div class="col-md-2 col-sm-12 col-xs-12">
                    <label>Valor</label>
                    <input id="abono" name="recibido" type="number" class="form-control" min="0">
                </div>
                <div class="col-md-2 col-sm-12 col-xs-12">
                    <label>Recibido</label><h1 id="abonoS">$ 0</h1>
                </div>
                <div class="col-md-2 col-sm-12 col-xs-6">
                    <label>Vuelto</label><h1 id="vueltoS">$ 0</h1>
                </div>
                <div class="col-md-2 col-sm-12 col-xs-6">
                    <label>Posponer cuenta:</label><br>
                    <button type="button" onclick="deudaDocument('{{ $sale->id_sale }}')" class="btn btn-danger">DEUDA</button>
                </div>
                <div class="col-md-4 col-sm-12 col-xs-6">
                    <div class="row">
                        <div class="col-md-6">
                            <label>Pagar:</label><br>
                            <button class="btn">PAGAR</button>
                        </div>
                        @if($saleTotal - $nPay < 1 && $sale->status <> '3')
                            <div class="col-md-6">
                                <label>Cerrar cuenta:</label><br>
                                <button class="btn btn-success" onclick="saveDocument('{{ $sale->id_sale }}')" type="button">FINALIZAR</button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    @endif
    <div class="row">
        <div class="col-12 mt-3">
            <div class="card card-chart">
                <div class="card-header ">

                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Codigo</th>
                                            <th>Producto</th>
                                            <th>Observacion</th>
                                            <th>Usuario</th>
                                            <th>Hora</th>
                                            <th>Precio</th>
                                            <th>Cantidad</th>
                                            <th>Total</th>
                                            @if($sale->status <> '3' && $sale->status <> '5')
                                            <th><i class="fa fa-cogs"></i></th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($saleDetail as $key => $value)
                                            <tr>
                                                <td>{{ $value->code }}</td>
                                                <td>{{ $value->product }}</td>
                                                <td>{{ $value->observation }}</td>
                                                <td>{{ $value->user }}</td>
                                                <td>{{ $value->updated_at }}</td>
                                                <td>$ {{ number_format($value->price) }}</td>
                                                <td>{{ $value->quantity }}</td>
                                                <td>$ {{ number_format($value->price * $value->quantity) }}</td>
                                                @if($sale->status <> '3' && $sale->status <> '5')
                                                <td><a href="javascript:void(0)" onclick="deleteItem('{{ $value->id_auto }}')" class="btn btn-link btn-danger btn-icon btn-sm remove"><i class="tim-icons icon-simple-remove"></i></a></td>
                                                @endif
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
    {{-- <script src="{{ asset('black') }}/js/plugins/chartjs.min.js"></script> --}}
    <script>
        function deudaDocument(_idDocument){
            $("#loading-open").addClass('loading-head');
            $.ajax({
                type: "POST",
                url: "{{ route('deuda.sale') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    id_sale: _idDocument
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
        function saveDocument(_idSale){
            $("#loading-open").addClass('loading-head');
            $.ajax({
                type: "POST",
                url: "{{ route('sales.document') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    id_sale: _idSale
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
        function deleteItem(_idAuto){
            $("#loading-open").addClass('loading-head');
            $.ajax({
                type: "POST",
                url: "{{ route('sales.delete.detail') }}",
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
        function changePrice(){
            let _price = $("#precio").val();
            if(!_price){
                _price = 0;
            }
            $("#price").text(numberFomat(_price));
            let _cantidad = $("#cantidad").val();
            if(!_cantidad){
                _cantidad = 0;
            }
            let _total = _cantidad * _price;
            $("#totalS").text(numberFomat(_total));
            $("#total").val(numberFomat(_total));
        }
        function changePay(){
            let abono = $("#abono").val();
            if(abono < 0 || !abono){
                $("#abonoS").text(``);
                $("#vueltoS").text(``);
                return;
            }
            let total = $("#totalSale").val();
            let vuelto = abono - total;
            if(vuelto < 0){
                vuelto = 0;
            }
            $("#abonoS").text(numberFomat(abono));
            $("#vueltoS").text(numberFomat(vuelto));
        }
        $(document).ready(function() {
            $().ready(function() {
                $('body').on('keyup','#precio',function(){
                    changePrice();
                });
                $('body').on('keyup','#cantidad',function(){
                    changePrice();
                });
                $('body').on('keyup','#abono',function(){
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
                                $("#price").text(numberFomat(response.sale_price));
                                $("#precio").val(response.sale_price);
                            }
                            // $("#loading-open").removeClass('loading-head');
                        },error: function(){
                            // $("#loading-open").removeClass('loading-head');
                        }
                    });
                });
                $(".select2").select2();
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

