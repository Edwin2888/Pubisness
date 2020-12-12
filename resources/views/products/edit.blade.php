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
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('products.new') }}" autocomplete="off">
                        <div class="row">
                            <div class="col-md-12">
                                @csrf
                                <input type="hidden" name="id_product" value="{{ $oProduct->id_product }}">
                                @include('alerts.success')

                                <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                    <label>{{ __('Nombre') }}</label>
                                    <input type="text" name="name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Name') }}" value="{{ old('name', $oProduct->name) }}">
                                    @include('alerts.feedback', ['field' => 'name'])
                                </div>

                                <div class="form-group{{ $errors->has('entry_price') ? ' has-danger' : '' }}">
                                    <label>{{ __('Precio Entrada') }}</label>
                                    <input type="number" min="0" name="entry_price" class="form-control{{ $errors->has('entry_price') ? ' is-invalid' : '' }}" placeholder="{{ __('Precio Entrada') }}" value="{{ old('entry_price', $oProduct->entry_price) }}">
                                    @include('alerts.feedback', ['field' => 'entry_price'])
                                </div>
                                <div class="form-group{{ $errors->has('sale_price') ? ' has-danger' : '' }}">
                                    <label>{{ __('Precio Venta') }}</label>
                                    <input type="number" min="0" name="sale_price" class="form-control{{ $errors->has('sale_price') ? ' is-invalid' : '' }}" placeholder="{{ __('Precio Venta') }}" value="{{ old('sale_price', $oProduct->sale_price) }}">
                                    @include('alerts.feedback', ['field' => 'price'])
                                </div>
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
@endsection
