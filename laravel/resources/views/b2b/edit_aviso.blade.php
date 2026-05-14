@extends('layouts.app')

@section('title', 'Editar Aviso · ReparaYa')

@section('content')

<style>
    .b2b-form-shell {
        display: grid;
        gap: 28px;
    }

    .b2b-form-hero {
        position: relative;
        overflow: hidden;
        border-radius: 34px;
        padding: 44px 38px;
        background:
            radial-gradient(circle at 12% 16%, rgba(86, 199, 255, 0.18), transparent 27%),
            radial-gradient(circle at 88% 10%, rgba(214, 184, 109, 0.12), transparent 24%),
            linear-gradient(135deg, #031121 0%, #061a32 54%, #08284b 100%);
        border: 1px solid rgba(255,255,255,0.12);
        box-shadow: 0 30px 70px rgba(2, 6, 23, 0.24);
    }

    .b2b-form-hero::before {
        content: "";
        position: absolute;
        inset: 0;
        pointer-events: none;
        background-image:
            linear-gradient(rgba(255,255,255,0.035) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,0.035) 1px, transparent 1px);
        background-size: 38px 38px;
        mask-image: radial-gradient(circle at center, black 0%, transparent 88%);
    }

    .b2b-form-hero-content {
        position: relative;
        z-index: 2;
        display: grid;
        grid-template-columns: minmax(0, 1fr) auto;
        align-items: center;
        gap: 24px;
    }

    .b2b-form-hero-title {
        display: grid;
        gap: 14px;
    }

    .b2b-form-hero h1 {
        margin: 0;
        color: white;
        font-size: clamp(44px, 5vw, 78px);
        line-height: 0.96;
        letter-spacing: -2.8px;
    }

    .b2b-form-hero p {
        margin: 0;
        max-width: 950px;
        color: #d7e5f3;
        font-size: 18px;
        line-height: 1.7;
    }

    .b2b-reference {
        display: inline-flex;
        width: fit-content;
        align-items: center;
        padding: 9px 14px;
        border-radius: 999px;
        background: rgba(255,255,255,0.10);
        color: #9adfff;
        border: 1px solid rgba(255,255,255,0.14);
        font-size: 13px;
        font-weight: 900;
        letter-spacing: 0.55px;
        text-transform: uppercase;
    }

    .b2b-form-card {
        position: relative;
        overflow: hidden;
        border-radius: 34px;
        padding: 34px;
        background:
            radial-gradient(circle at 8% 10%, rgba(86,199,255,0.10), transparent 24%),
            radial-gradient(circle at 92% 10%, rgba(214,184,109,0.08), transparent 22%),
            linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        border: 1px solid rgba(15, 23, 42, 0.08);
        box-shadow: 0 24px 54px rgba(15, 23, 42, 0.10);
    }

    .b2b-form-card::before {
        content: "";
        position: absolute;
        inset: 0;
        pointer-events: none;
        background-image:
            linear-gradient(rgba(15,111,255,0.026) 1px, transparent 1px),
            linear-gradient(90deg, rgba(15,111,255,0.026) 1px, transparent 1px);
        background-size: 34px 34px;
        mask-image: radial-gradient(circle at center, black 0%, transparent 92%);
    }

    .b2b-form-content {
        position: relative;
        z-index: 2;
    }

    .b2b-form-head {
        text-align: center;
        margin-bottom: 30px;
    }

    .b2b-form-head h2 {
        margin: 0;
        color: #0f172a;
        font-size: 36px;
        line-height: 1.05;
        letter-spacing: -1.2px;
    }

    .b2b-form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 20px;
    }

    .b2b-form-field {
        display: grid;
        gap: 8px;
    }

    .b2b-form-field.full {
        grid-column: 1 / -1;
    }

    .b2b-form-field label {
        color: #0f172a;
        font-weight: 900;
        font-size: 15px;
        letter-spacing: -0.2px;
    }

    .b2b-form-field small {
        color: #61748c;
        font-size: 13.5px;
        line-height: 1.45;
    }

    .b2b-input,
    .b2b-select,
    .b2b-textarea {
        width: 100%;
        border-radius: 18px;
        border: 1px solid #d7e2ee;
        background: white;
        color: #0f172a;
        font-family: Arial, sans-serif;
        font-size: 16px;
        outline: none;
        transition: border-color 0.14s ease, box-shadow 0.14s ease;
    }

    .b2b-input,
    .b2b-select {
        min-height: 58px;
        padding: 0 16px;
    }

    .b2b-textarea {
        min-height: 140px;
        padding: 16px;
        resize: vertical;
        line-height: 1.6;
    }

    .b2b-input:focus,
    .b2b-select:focus,
    .b2b-textarea:focus {
        border-color: rgba(15, 111, 255, 0.48);
        box-shadow: 0 0 0 4px rgba(15, 111, 255, 0.10);
    }

    .b2b-auto-box {
        margin-top: 8px;
        padding: 16px;
        border-radius: 20px;
        background: #f3f8ff;
        border: 1px solid rgba(15,111,255,0.10);
        color: #0f172a;
        display: grid;
        gap: 6px;
    }

    .b2b-auto-box span {
        color: #64748b;
        font-size: 12px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.45px;
    }

    .b2b-auto-box strong {
        font-size: 16px;
        line-height: 1.45;
    }

    .b2b-status-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 14px;
        margin-top: 28px;
    }

    .b2b-status-card {
        padding: 18px;
        border-radius: 22px;
        background: #f3f8ff;
        border: 1px solid rgba(15,111,255,0.10);
        text-align: center;
    }

    .b2b-status-card span {
        display: block;
        color: #64748b;
        font-size: 12px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.45px;
    }

    .b2b-status-card strong {
        display: block;
        margin-top: 7px;
        color: #0f172a;
        font-size: 20px;
        line-height: 1.2;
        letter-spacing: -0.5px;
    }

    .b2b-form-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 14px;
        flex-wrap: wrap;
        margin-top: 30px;
        padding-top: 22px;
        border-top: 1px solid rgba(15,23,42,0.08);
    }

    .b2b-error {
        padding: 16px 18px;
        border-radius: 18px;
        background: #fff0f2;
        color: #9f1d2e;
        border: 1px solid rgba(220, 53, 69, 0.22);
        font-weight: 800;
        line-height: 1.55;
    }

    .b2b-error ul {
        margin: 8px 0 0;
        padding-left: 20px;
    }

    @media (max-width: 1000px) {
        .b2b-form-hero-content {
            grid-template-columns: 1fr;
            justify-items: center;
            text-align: center;
        }

        .b2b-form-grid,
        .b2b-status-grid {
            grid-template-columns: 1fr;
        }

        .b2b-form-hero,
        .b2b-form-card {
            padding: 24px 18px;
            border-radius: 26px;
        }

        .b2b-form-hero h1 {
            font-size: 42px;
            letter-spacing: -1.8px;
        }

        .b2b-form-actions .btn,
        .b2b-form-hero-content .btn {
            width: 100%;
        }

        .b2b-reference {
            margin: 0 auto;
        }
    }
</style>

<div class="b2b-form-shell">

    <section class="b2b-form-hero">
        <div class="b2b-form-hero-content">
            <div class="b2b-form-hero-title">
                <div class="b2b-reference">
                    {{ $aviso->localizador }}
                </div>

                <h1>Editar aviso</h1>

                <p>
                    Actualización del servicio gestionado por {{ $gestora->nombre }}.
                </p>
            </div>

            <a href="{{ route('b2b.panel') }}" class="btn btn-primary">
                ← Volver al panel
            </a>
        </div>
    </section>

    @if($errors->any())
        <div class="b2b-error">
            <strong>No se ha podido actualizar el aviso.</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <section class="b2b-form-card">
        <div class="b2b-form-content">

            <div class="b2b-form-head">
                <h2>Datos del aviso</h2>
            </div>

            <form action="{{ route('b2b.update_aviso', $aviso->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="b2b-form-grid">

                    <div class="b2b-form-field full">
                        <label for="comunidad_id">Comunidad</label>

                        <select name="comunidad_id" id="comunidad_id" class="b2b-select" required>
                            <option value="">-- Seleccionar comunidad --</option>

                            @foreach($comunidades as $comunidad)
                                <option
                                    value="{{ $comunidad->id }}"
                                    data-direccion="{{ $comunidad->direccion }}"
                                    data-telefono="{{ $comunidad->telefono_contacto }}"
                                    data-zona="{{ $comunidad->zona }}"
                                    {{ old('comunidad_id', $aviso->comunidad_id) == $comunidad->id ? 'selected' : '' }}
                                >
                                    {{ $comunidad->nombre }} · {{ $comunidad->direccion }}
                                </option>
                            @endforeach
                        </select>

                        <div class="b2b-auto-box">
                            <span>Comunidad seleccionada</span>
                            <strong id="comunidad_resumen_texto">
                                Selecciona una comunidad para cargar sus datos operativos.
                            </strong>
                        </div>
                    </div>

                    <div class="b2b-form-field">
                        <label for="especialidad_id">Especialidad</label>

                        <select name="especialidad_id" id="especialidad_id" class="b2b-select" required>
                            <option value="">-- Seleccionar especialidad --</option>

                            @foreach($especialidades as $especialidad)
                                <option
                                    value="{{ $especialidad->id }}"
                                    {{ old('especialidad_id', $aviso->especialidad_id) == $especialidad->id ? 'selected' : '' }}
                                >
                                    {{ $especialidad->nombre_especialidad }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="b2b-form-field">
                        <label for="telefono_contacto">Teléfono de contacto</label>

                        <input
                            type="text"
                            name="telefono_contacto"
                            id="telefono_contacto"
                            class="b2b-input"
                            value="{{ old('telefono_contacto', $aviso->telefono_contacto) }}"
                            required
                        >

                        <small>
                            Si cambias la comunidad, se puede cargar automáticamente el teléfono registrado de esa comunidad.
                        </small>
                    </div>

                    <div class="b2b-form-field">
                        <label for="fecha_servicio">Fecha y hora del servicio</label>

                        <input
                            type="datetime-local"
                            name="fecha_servicio"
                            id="fecha_servicio"
                            class="b2b-input"
                            value="{{ old('fecha_servicio', \Carbon\Carbon::parse($aviso->fecha_servicio)->format('Y-m-d\TH:i')) }}"
                            required
                        >
                    </div>

                    <div class="b2b-form-field">
                        <label for="tipo_urgencia">Urgencia</label>

                        <select name="tipo_urgencia" id="tipo_urgencia" class="b2b-select" required>
                            <option value="Estandar" {{ old('tipo_urgencia', $aviso->tipo_urgencia) == 'Estandar' ? 'selected' : '' }}>
                                Estándar
                            </option>

                            <option value="Urgente" {{ old('tipo_urgencia', $aviso->tipo_urgencia) == 'Urgente' ? 'selected' : '' }}>
                                Urgente
                            </option>
                        </select>
                    </div>

                    <div class="b2b-form-field">
                        <label for="precio_base">Precio base del servicio (€)</label>

                        <input
                            type="number"
                            name="precio_base"
                            id="precio_base"
                            class="b2b-input"
                            step="0.01"
                            min="0"
                            value="{{ old('precio_base', $aviso->precio_base) }}"
                            required
                        >
                    </div>

                    <div class="b2b-form-field">
                        <label for="estado">Estado del aviso</label>

                        <select name="estado" id="estado" class="b2b-select" required>
                            <option value="Pendiente" {{ old('estado', $aviso->estado) == 'Pendiente' ? 'selected' : '' }}>
                                Pendiente
                            </option>

                            <option value="Finalizada" {{ old('estado', $aviso->estado) == 'Finalizada' ? 'selected' : '' }}>
                                Finalizada
                            </option>

                            <option value="Cancelada" {{ old('estado', $aviso->estado) == 'Cancelada' ? 'selected' : '' }}>
                                Cancelada
                            </option>
                        </select>

                        <small>
                            Las comisiones solo se calculan sobre avisos finalizados.
                        </small>
                    </div>

                    <div class="b2b-form-field full">
                        <label for="descripcion">Descripción del problema</label>

                        <textarea
                            name="descripcion"
                            id="descripcion"
                            class="b2b-textarea"
                            required
                        >{{ old('descripcion', $aviso->descripcion) }}</textarea>
                    </div>

                </div>

                <div class="b2b-status-grid">
                    <div class="b2b-status-card">
                        <span>Gestora</span>
                        <strong>{{ $gestora->nombre }}</strong>
                    </div>

                    <div class="b2b-status-card">
                        <span>Comisión pactada</span>
                        <strong>{{ number_format($gestora->comision, 2) }}%</strong>
                    </div>

                    <div class="b2b-status-card">
                        <span>Localizador</span>
                        <strong>{{ $aviso->localizador }}</strong>
                    </div>
                </div>

                <div class="b2b-form-actions">
                    <a href="{{ route('b2b.panel') }}" class="btn btn-warning">
                        Cancelar
                    </a>

                    <button type="submit" class="btn btn-primary">
                        Actualizar aviso
                    </button>
                </div>

            </form>

        </div>
    </section>

</div>

<script>
    function actualizarDatosComunidad(forzarTelefono = false) {
        const selectComunidad = document.getElementById('comunidad_id');
        const telefonoInput = document.getElementById('telefono_contacto');
        const resumenTexto = document.getElementById('comunidad_resumen_texto');

        if (!selectComunidad || !telefonoInput || !resumenTexto) {
            return;
        }

        const opcion = selectComunidad.options[selectComunidad.selectedIndex];

        if (!opcion || !opcion.value) {
            resumenTexto.textContent = 'Selecciona una comunidad para cargar sus datos operativos.';

            if (forzarTelefono) {
                telefonoInput.value = '';
            }

            return;
        }

        const direccion = opcion.dataset.direccion || 'Sin dirección';
        const telefono = opcion.dataset.telefono || '';
        const zona = opcion.dataset.zona || 'Sin zona';

        resumenTexto.textContent = direccion + ' · Zona: ' + zona + ' · Teléfono: ' + (telefono || 'Sin teléfono registrado');

        if (forzarTelefono) {
            telefonoInput.value = telefono;
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        actualizarDatosComunidad(false);

        const selectComunidad = document.getElementById('comunidad_id');

        if (selectComunidad) {
            selectComunidad.addEventListener('change', function () {
                actualizarDatosComunidad(true);
            });
        }
    });
</script>

@endsection