@extends('layouts.app')

@section('title', 'Nuevo Técnico')

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

    .empty-warning {
        border-radius: 22px;
        padding: 20px 22px;
        background: #fff7dd;
        color: #715400;
        border: 1px solid #f3e1a4;
        font-weight: 700;
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

        <h1 class="form-hero-title">Nuevo técnico</h1>
    </section>

    @if ($usuarios->isEmpty())
        <div class="empty-warning">
            No hay usuarios con rol técnico disponibles para vincular. Primero crea un usuario con rol Técnico.
        </div>
    @endif

    <section class="form-card">
        <form action="{{ route('tecnicos.store') }}" method="POST">
            @csrf

            <div class="form-grid">
                <div class="form-group form-group-full">
                    <label>Usuario asociado</label>
                    <select name="usuario_id" class="form-control" required>
                        @foreach ($usuarios as $u)
                            <option value="{{ $u->id }}" {{ old('usuario_id') == $u->id ? 'selected' : '' }}>
                                {{ $u->nombre }} - {{ $u->email }}
                            </option>
                        @endforeach
                    </select>
                    <span class="field-hint">Solo aparecen usuarios con rol Técnico que todavía no tienen ficha técnica.</span>
                </div>

                <div class="form-group">
                    <label>Nombre completo</label>
                    <input
                        type="text"
                        name="nombre_completo"
                        class="form-control"
                        value="{{ old('nombre_completo') }}"
                        placeholder="Ejemplo: Pau Parals Martínez"
                        required
                    >
                    <span class="field-hint">Nombre profesional que aparecerá en las asignaciones.</span>
                </div>

                <div class="form-group">
                    <label>Especialidad</label>
                    <select name="especialidad_id" class="form-control" required>
                        @foreach ($especialidades as $e)
                            <option value="{{ $e->id }}" {{ old('especialidad_id') == $e->id ? 'selected' : '' }}>
                                {{ $e->nombre_especialidad }}
                            </option>
                        @endforeach
                    </select>
                    <span class="field-hint">Define qué tipo de incidencias puede recibir este técnico.</span>
                </div>

                <div class="form-group form-group-full">
                    <label>Disponibilidad</label>
                    <select name="disponible" class="form-control" required>
                        <option value="1" {{ old('disponible') === '1' ? 'selected' : '' }}>
                            Disponible
                        </option>

                        <option value="0" {{ old('disponible') === '0' ? 'selected' : '' }}>
                            No disponible
                        </option>
                    </select>
                    <span class="field-hint">Solo los técnicos disponibles podrán ser asignados desde la creación de incidencias.</span>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary" {{ $usuarios->isEmpty() ? 'disabled' : '' }}>
                    Crear técnico
                </button>

                <a href="{{ route('tecnicos.index') }}" class="btn btn-warning">
                    Cancelar
                </a>
            </div>
        </form>
    </section>

</div>

@endsection