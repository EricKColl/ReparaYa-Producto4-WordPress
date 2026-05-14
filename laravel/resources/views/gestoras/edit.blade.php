@extends('layouts.app')

@section('title', 'Editar Gestora')

@section('content')

<div class="page-header">
    <h1>Editar Gestora: {{ $gestora->nombre }}</h1>
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

<form action="{{ route('gestoras.update', $gestora->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="form-group">
        <label>Nombre de la empresa</label>
        <input type="text" name="nombre" class="form-control"
               value="{{ old('nombre', $gestora->nombre) }}" required>
    </div>

    <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" class="form-control"
               value="{{ old('email', $gestora->email) }}" required>
    </div>

    <div class="form-group">
        <label>Nueva contraseña <small style="font-weight:normal;">(dejar vacío para no cambiarla)</small></label>
        <input type="password" name="password" class="form-control">
    </div>

    <div class="form-group">
        <label>Teléfono</label>
        <input type="text" name="telefono" class="form-control"
               value="{{ old('telefono', $gestora->telefono) }}">
    </div>

    <div class="form-group">
        <label>Comisión (%)</label>
        <input type="number" name="comision" class="form-control" step="0.01" min="0" max="100"
               value="{{ old('comision', $gestora->comision) }}" required>
    </div>

    <button type="submit" class="btn btn-warning">Actualizar</button>
</form>

@endsection
