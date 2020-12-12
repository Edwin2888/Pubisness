@extends('layouts.app', ['pageSlug' => 'roles'])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-chart">
                <div class="card-header">
                    <h2 class="card-title">Administración de Roles y Permisos</h2>
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
                        <div class="col-md-12">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Email</th>
                                        <th>Nombre</th>
                                        <th>Rol</th>
                                        <th><i class="fa fa-cogs"></i></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($oUsers as $key => $value)
                                        <tr>
                                            <td>{{ $value->email }}</td>
                                            <td>{{ $value->name }}</td>
                                            <td>{{ $value->roles()->first()->name }}</td>
                                            <td>
                                                <form action="{{ route('roles.assign') }}" method="POST">
                                                    <input type="hidden" value="{{ $value->id }}" name="id_user">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <select name="rol" class="form-control">
                                                                <option value="">-- Seleccionar --</option>
                                                                @foreach($oRoles as $item)
                                                                    @if($item->id == $value->roles()->first()->id)
                                                                    <option selected="selected" value="{{ $item->id }}">{{ $item->name }}</option>
                                                                    @else
                                                                    <option value="{{ $item->name }}">{{ $item->name }}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <button class="btn">Asignar</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </td>
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
