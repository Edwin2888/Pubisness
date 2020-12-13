@extends('layouts.app', ['pageSlug' => 'expense'])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-chart">
                <div class="card-header">
                    <div class="row">
                        <div class="col-sm-6 text-left">
                            <h2 class="card-title">Ingresar Gastos</h2>
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
                        <div class="col-md-10">
                            <form method="post" action="{{ route('expense.create') }}" autocomplete="off">
                                @csrf
                                @include('alerts.success')
                                <div class="form-group{{ $errors->has('expense_date') ? ' has-danger' : '' }}">
                                    <label>{{ __('Fecha del Gasto') }}</label>
                                    <input type="date" class="form-control{{ $errors->has('expense_date') ? ' is-invalid' : '' }}" value="{{ old('expense_date',date('Y-m-d')) }}" name="expense_date">
                                    @include('alerts.feedback', ['field' => 'expense_date'])
                                </div>
                                {{-- <div class="form-group{{ $errors->has('description') ? ' has-danger' : '' }}">
                                    <label>{{ __('Descripcion del gasto') }}</label>
                                    <input type="text" name="description" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" placeholder="{{ __('Descripción') }}" value="{{ old('description', '') }}">
                                    @include('alerts.feedback', ['field' => 'description'])
                                </div> --}}

                                <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                    <label>{{ __('Nombre') }}</label>
                                    <input type="text" name="name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Name') }}" value="{{ old('name', '') }}">
                                    @include('alerts.feedback', ['field' => 'name'])
                                </div>

                                <div class="form-group{{ $errors->has('price') ? ' has-danger' : '' }}">
                                    <label>{{ __('Precio') }}</label>
                                    <input type="number" min="0" name="price" class="form-control{{ $errors->has('price') ? ' is-invalid' : '' }}" placeholder="{{ __('Precio') }}" value="{{ old('price', '') }}">
                                    @include('alerts.feedback', ['field' => 'price'])
                                </div>
                                <div class="form-group{{ $errors->has('quantity') ? ' has-danger' : '' }}">
                                    <label>{{ __('Cantidad') }}</label>
                                    <input type="number" min="0" name="quantity" class="form-control{{ $errors->has('quantity') ? ' is-invalid' : '' }}" placeholder="{{ __('Cantidad') }}" value="{{ old('quantity', '') }}">
                                    @include('alerts.feedback', ['field' => 'quantity'])
                                </div>
                                <div class="form-group{{ $errors->has('id_type_expense') ? ' has-danger' : '' }}">
                                    <label>{{ __('Seguimiento de gastos de productos') }}</label>
                                    <select name="id_type_expense" class="form-control{{ $errors->has('id_type_expense') ? ' is-invalid' : '' }}">
                                        <option value="">NO</option>
                                        @foreach($oTypes as $item)
                                        <option value="{{ $item->id_type_expense }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    @include('alerts.feedback', ['field' => 'id_type_expense'])
                                </div>
                                <button type="submit" class="btn btn-fill btn-primary pull-right">{{ __('Guardar') }}</button>
                                <button type="button" class="btn btn-fill" onclick="location.href='{{ route('expense.view') }}'">{{ __('Cancelar') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
