@extends('layouts.app')

@section('title', 'Nuevo Usuario')

@section('content')

<style>
    .form-shell {
        display: grid;
        gap: 28px;
    }

    .form-hero {
        position: relative;
        overflow: hidden;
        border-radius: 32px;
        min-height: 190px;
        padding: 34px 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        background:
            radial-gradient(circle at 10% 10%, rgba(86,199,255,0.14), transparent 20%),
            linear-gradient(135deg, #041225 0%, #061a32 52%, #0b2748 100%);
        border: 1px solid rgba(255,255,255,0.12);
        box-shadow: 0 28px 60px rgba(2, 6, 23, 0.20);
    }

    .form-hero::before {
        content: "";
        position: absolute;
        inset: 0;
        background-image:
            linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
        background-size: 38px 38px;
        pointer-events: none;
    }

    .form-hero-actions {
        position: absolute;
        top: 34px;
        right: 32px;
        z-index: 3;
    }

    .form-hero-title {
        position: relative;
        z-index: 2;
        margin: 0;
        text-align: center;
        color: white;
        font-size: clamp(42px, 4.8vw, 68px);
        letter-spacing: -2px;
        line-height: 1;
    }

    .form-card {
        border-radius: 30px;
        padding: 34px;
        background:
            radial-gradient(circle at 8% 0%, rgba(15,111,255,0.08), transparent 24%),
            linear-gradient(180deg, #ffffff 0%, #f7fbff 100%);
        border: 1px solid rgba(15,23,42,0.08);
        box-shadow: 0 22px 46px rgba(15,23,42,0.08);
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 22px;
    }

    .form-group {
        margin-bottom: 0;
    }

    .form-group-full {
        grid-column: 1 / -1;
    }

    .field-hint {
        display: block;
        margin-top: 7px;
        color: #64748b;
        font-size: 13px;
        line-height: 1.5;
    }

    .form-actions {
        display: flex;
        justify-content: center;
        gap: 14px;
        flex-wrap: wrap;
        margin-top: 30px;
        padding-top: 24px;
        border-top: 1px solid rgba(15,23,42,0.08);
    }

    .error-panel {
        border-radius: 22px;
        padding: 20px 22px;
        background: #fff0f2;
        color: #9f1d2e;
        border: 1px solid rgba(220,53,69,0.22);
        box-shadow: 0 14px 30px rgba(159,29,46,0.08);
        font-weight: 700;
    }

    .error-panel strong {
        display: block;
        margin-bottom: 8px;
        font-size: 17px;
    }

    .error-panel ul {
        margin: 8px 0 0;
        padding-left: 20px;
    }

    @media (max-width: 900px) {
        .form-grid {
            grid-template-columns: 1fr;
        }

        .form-hero {
            min-height: 220px;
            padding: 86px 18px 34px;
        }

        .form-hero-actions {
            top: 24px;
            left: 18px;
            right: 18px;
            display: flex;
            justify-content: center;
        }

        .form-card {
            padding: 22px;
        }
    }
</style>

<div class="form-shell">

    <section class="form-hero">
        <div class="form-hero-actions">
            <a href="{{ route('usuarios.index') }}" class="btn hero-btn-secondary">
                Volver
            </a>
        </div>

        <h1 class="form-hero-title">Nuevo usuario</h1>
    </section>

    @if ($errors->any())
        <div class="error-panel">
            <strong>No se ha podido crear el usuario.</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <section class="form-card">
        <form action="{{ route('usuarios.store') }}" method="POST">
            @csrf

            <div class="form-grid">
                <div class="form-group">
                    <label>Nombre</label>
                    <input
                        type="text"
                        name="nombre"
                        class="form-control"
                        value="{{ old('nombre') }}"
                        placeholder="Ejemplo: Laura Martínez"
                        required
                    >
                    <span class="field-hint">Nombre visible del perfil dentro de ReparaYa.</span>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        value="{{ old('email') }}"
                        placeholder="usuario@email.com"
                        required
                    >
                    <span class="field-hint">Se usará para iniciar sesión en la plataforma.</span>
                </div>

                <div class="form-group">
                    <label>Contraseña</label>
                    <input
                        type="password"
                        name="password"
                        class="form-control"
                        placeholder="Mínimo 6 caracteres"
                        required
                    >
                    <span class="field-hint">Debe tener al menos 6 caracteres.</span>
                </div>

                <div class="form-group">
                    <label>Rol</label>
                    <select name="rol" class="form-control" required>
                        <option value="particular" {{ old('rol') == 'particular' ? 'selected' : '' }}>
                            Particular
                        </option>

                        <option value="tecnico" {{ old('rol') == 'tecnico' ? 'selected' : '' }}>
                            Técnico
                        </option>

                        <option value="admin" {{ old('rol') == 'admin' ? 'selected' : '' }}>
                            Administrador
                        </option>
                    </select>
                    <span class="field-hint">Define qué puede ver y gestionar este usuario.</span>
                </div>

                <div class="form-group form-group-full">
                    <label>Teléfono</label>
                    <input
                        type="text"
                        name="telefono"
                        class="form-control"
                        value="{{ old('telefono') }}"
                        placeholder="Ejemplo: 600123456"
                    >
                    <span class="field-hint">Dato útil para contactar con clientes o personal técnico.</span>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    Crear usuario
                </button>

                <a href="{{ route('usuarios.index') }}" class="btn btn-warning">
                    Cancelar
                </a>
            </div>
        </form>
    </section>

</div>

@endsection