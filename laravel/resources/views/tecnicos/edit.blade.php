@extends('layouts.app')

@section('title', 'Editar Técnico')

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
            <a href="{{ route('tecnicos.index') }}" class="btn hero-btn-secondary">
                Volver
            </a>
        </div>

        <h1 class="form-hero-title">Editar técnico</h1>
    </section>

    <section class="form-card">
        <form action="{{ route('tecnicos.update', $tecnico->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <div class="form-group form-group-full">
                    <label>Usuario asociado</label>
                    <select name="usuario_id" class="form-control" required>
                        @foreach ($usuarios as $u)
                            <option value="{{ $u->id }}"
                                {{ old('usuario_id', $tecnico->usuario_id) == $u->id ? 'selected' : '' }}>
                                {{ $u->nombre }} - {{ $u->email }}
                            </option>
                        @endforeach
                    </select>
                    <span class="field-hint">Usuario técnico vinculado a esta ficha operativa.</span>
                </div>

                <div class="form-group">
                    <label>Nombre completo</label>
                    <input
                        type="text"
                        name="nombre_completo"
                        class="form-control"
                        value="{{ old('nombre_completo', $tecnico->nombre_completo) }}"
                        required
                    >
                    <span class="field-hint">Nombre profesional mostrado en las incidencias asignadas.</span>
                </div>

                <div class="form-group">
                    <label>Especialidad</label>
                    <select name="especialidad_id" class="form-control" required>
                        @foreach ($especialidades as $e)
                            <option value="{{ $e->id }}"
                                {{ old('especialidad_id', $tecnico->especialidad_id) == $e->id ? 'selected' : '' }}>
                                {{ $e->nombre_especialidad }}
                            </option>
                        @endforeach
                    </select>
                    <span class="field-hint">La especialidad debe ser coherente con las incidencias asignadas.</span>
                </div>

                <div class="form-group form-group-full">
                    <label>Disponibilidad</label>
                    <select name="disponible" class="form-control" required>
                        <option value="1" {{ old('disponible', $tecnico->disponible) == 1 ? 'selected' : '' }}>
                            Disponible
                        </option>

                        <option value="0" {{ old('disponible', $tecnico->disponible) == 0 ? 'selected' : '' }}>
                            No disponible
                        </option>
                    </select>
                    <span class="field-hint">Controla si el técnico puede aparecer como opción de asignación activa.</span>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    Actualizar técnico
                </button>

                <a href="{{ route('tecnicos.index') }}" class="btn btn-warning">
                    Cancelar
                </a>
            </div>
        </form>
    </section>

</div>

@endsection