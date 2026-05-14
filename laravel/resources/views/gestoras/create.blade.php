@extends('layouts.app')

@section('title', 'Nueva Gestora')

@section('content')

<div class="page-header">
    <h1>Nueva Gestora</h1>
    <a href="{{ route('gestoras.index') }}" class="btn btn-primary">← Volver</a>
</div>

@if($errors->any())
    <div style="background:#f8d7da; padding:10px; border-radius:6px; margin-bottom:15px; color:#842029;">
        <ul style="margin:0; padding-left:20px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('gestoras.store') }}" method="POST">
    @csrf

    <div class="form-group">
        <label>Nombre de la empresa</label>
        <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}" required>
    </div>

    <div class="form-group">
        <label>Email (acceso al panel)</label>
        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
    </div>

    <div class="form-group">
        <label>Contraseña</label>
        <input type="password" name="password" class="form-control" required>
    </div>

    <div class="form-group">
        <label>Teléfono</label>
        <input type="text" name="telefono" class="form-control" value="{{ old('telefono') }}">
    </div>

    <div class="form-group">
        <label>Comisión (%)</label>
        <input type="number" name="comision" class="form-control" step="0.01" min="0" max="100"
               value="{{ old('comision', 5) }}" required>
        <small style="color:#666;">Porcentaje que se le paga a la gestora por cada servicio finalizado.</small>
    </div>

    <button type="submit" class="btn btn-primary">Guardar Gestora</button>
</form>

@endsection
