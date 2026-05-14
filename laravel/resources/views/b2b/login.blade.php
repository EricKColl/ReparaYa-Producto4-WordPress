@extends('layouts.app')

@section('title', 'Acceso Gestoras B2B')

@section('content')

<div style="max-width:400px; margin:0 auto;">
    <h1 style="text-align:center; margin-bottom:25px;">Acceso Panel Gestora</h1>

    @if($errors->any())
        <div style="background:#f8d7da; padding:10px; border-radius:6px; margin-bottom:15px; color:#842029;">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('b2b.login.submit') }}" method="POST">
        @csrf

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
        </div>

        <div class="form-group">
            <label>Contraseña</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary" style="width:100%;">Entrar</button>
    </form>
</div>

@endsection
