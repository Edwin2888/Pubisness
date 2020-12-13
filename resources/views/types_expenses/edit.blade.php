@extends('layouts.app', ['pageSlug' => 'type_expense'])

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-chart">
                <div class="card-header ">
                    <div class="row">
                        <div class="col-sm-6 text-left">
                            <h2 class="card-title">Editar tipos de gasto</h2>
                        </div>
                        <div class="col-sm-6">

                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('expense_type.new') }}" autocomplete="off">
                        <div class="row">
                            <div class="col-md-12">
                                @csrf
                                <input type="hidden" name="id_type_expense" value="{{ $oTypes->id_type_expense }}">
                                @include('alerts.success')

                                <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                    <label>{{ __('Nombre') }}</label>
                                    <input type="text" name="name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Name') }}" value="{{ old('name', $oTypes->name) }}">
                                    @include('alerts.feedback', ['field' => 'name'])
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
