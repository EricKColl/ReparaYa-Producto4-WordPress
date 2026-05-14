@extends('layouts.app')

@section('title', 'Nueva Especialidad')

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

    @media (max-width: 900px) {
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
            <a href="{{ route('especialidades.index') }}" class="btn hero-btn-secondary">
                Volver
            </a>
        </div>

        <h1 class="form-hero-title">Nueva especialidad</h1>
    </section>

    <section class="form-card">
        <form action="{{ route('especialidades.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label>Nombre de la especialidad</label>
                <input
                    type="text"
                    name="nombre_especialidad"
                    class="form-control"
                    value="{{ old('nombre_especialidad') }}"
                    placeholder="Ejemplo: Electricidad, Fontanería, Climatización..."
                    required
                >
                <span class="field-hint">La especialidad permite clasificar incidencias y asignar técnicos con mayor precisión.</span>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    Guardar especialidad
                </button>

                <a href="{{ route('especialidades.index') }}" class="btn btn-warning">
                    Cancelar
                </a>
            </div>
        </form>
    </section>

</div>

@endsection