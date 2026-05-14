@extends('layouts.app')

@section('title', 'Nueva Incidencia')

@section('content')

@php
    $esAdmin = session('usuario_rol') === 'admin';
    $esParticular = session('usuario_rol') === 'particular';
@endphp

<style>
    .form-shell {
        display: grid;
        gap: 28px;
    }

    .form-hero {
        position: relative;
        overflow: hidden;
        border-radius: 32px;
        min-height: 210px;
        padding: 34px 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        background:
            radial-gradient(circle at 10% 10%, rgba(86,199,255,0.16), transparent 22%),
            radial-gradient(circle at 92% 12%, rgba(214,184,109,0.12), transparent 20%),
            linear-gradient(135deg, #041225 0%, #061a32 52%, #0b2748 100%);
        border: 1px solid rgba(255,255,255,0.12);
        box-shadow: 0 28px 60px rgba(2, 6, 23, 0.20);
    }

    .form-hero::before {
        content: "";
        position: absolute;
        inset: 0;
        background-image:
            linear-gradient(rgba(255,255,255,0.035) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,0.035) 1px, transparent 1px);
        background-size: 38px 38px;
        pointer-events: none;
    }

    .form-hero-actions {
        position: absolute;
        top: 34px;
        right: 32px;
        z-index: 3;
    }

    .btn-ghost {
        background: rgba(255,255,255,0.10);
        color: white;
        border: 1px solid rgba(255,255,255,0.14);
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

    .form-hero-subtitle {
        position: relative;
        z-index: 2;
        max-width: 860px;
        margin: 18px auto 0;
        color: #d5e3f0;
        text-align: center;
        font-size: 18px;
        line-height: 1.7;
        font-weight: 700;
    }

    .form-hero-content {
        position: relative;
        z-index: 2;
        text-align: center;
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

    .form-layout {
        display: grid;
        grid-template-columns: 1fr 0.42fr;
        gap: 24px;
        align-items: start;
    }

    .form-card,
    .side-card {
        border-radius: 30px;
        padding: 34px;
        background:
            radial-gradient(circle at 8% 0%, rgba(15,111,255,0.08), transparent 24%),
            linear-gradient(180deg, #ffffff 0%, #f7fbff 100%);
        border: 1px solid rgba(15,23,42,0.08);
        box-shadow: 0 22px 46px rgba(15,23,42,0.08);
    }

    .side-card {
        position: sticky;
        top: 112px;
    }

    .section-title {
        margin: 0 0 22px;
        color: #0f172a;
        font-size: 28px;
        line-height: 1.1;
        letter-spacing: -0.8px;
        text-align: center;
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

    .readonly-field {
        background: #eef6ff;
        color: #0f172a;
        font-weight: 800;
    }

    .client-chip {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 18px;
        border-radius: 22px;
        background: white;
        border: 1px solid rgba(15,23,42,0.08);
        box-shadow: 0 12px 26px rgba(15,23,42,0.06);
    }

    .client-avatar {
        width: 48px;
        height: 48px;
        border-radius: 16px;
        display: grid;
        place-items: center;
        flex: 0 0 48px;
        color: white;
        font-weight: 900;
        background: linear-gradient(135deg, #0f6fff, #56c7ff);
        box-shadow: 0 12px 24px rgba(15,111,255,0.20);
    }

    .client-chip strong {
        display: block;
        color: #0f172a;
        font-size: 17px;
    }

    .client-chip span {
        display: block;
        margin-top: 4px;
        color: #64748b;
        font-size: 14px;
    }

    .guide-list {
        display: grid;
        gap: 14px;
        margin-top: 18px;
    }

    .guide-item {
        display: grid;
        grid-template-columns: 38px 1fr;
        gap: 12px;
        align-items: start;
        padding: 15px;
        border-radius: 18px;
        background: white;
        border: 1px solid rgba(15,23,42,0.08);
    }

    .guide-number {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: grid;
        place-items: center;
        color: white;
        font-weight: 900;
        background: linear-gradient(135deg, #0f6fff, #56c7ff);
    }

    .guide-item strong {
        display: block;
        color: #0f172a;
        margin-bottom: 4px;
    }

    .guide-item span {
        display: block;
        color: #64748b;
        font-size: 14px;
        line-height: 1.55;
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

    @media (max-width: 1200px) {
        .form-layout {
            grid-template-columns: 1fr;
        }

        .side-card {
            position: static;
        }
    }

    @media (max-width: 900px) {
        .form-grid {
            grid-template-columns: 1fr;
        }

        .form-hero {
            min-height: 250px;
            padding: 86px 18px 34px;
        }

        .form-hero-actions {
            top: 24px;
            left: 18px;
            right: 18px;
            display: flex;
            justify-content: center;
        }

        .form-card,
        .side-card {
            padding: 22px;
        }
    }
</style>

<div class="form-shell">

    <section class="form-hero">
        <div class="form-hero-actions">
            <a href="{{ route('incidencias.index') }}" class="btn btn-ghost">
                Volver
            </a>
        </div>

        <div class="form-hero-content">
            <h1 class="form-hero-title">Nueva incidencia</h1>

            <p class="form-hero-subtitle">
                Registra el aviso con la información necesaria para que la reparación avance con orden, criterio y trazabilidad desde el primer momento.
            </p>
        </div>
    </section>

    @if ($errors->any())
        <div class="error-panel">
            <strong>No se ha podido crear la incidencia.</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-layout">

        <section class="form-card">
            <h2 class="section-title">Datos de la solicitud</h2>

            <form action="{{ route('incidencias.store') }}" method="POST">
                @csrf

                <div class="form-grid">

                    @if($esAdmin)
                        <div class="form-group form-group-full">
                            <label>Cliente</label>

                            <select name="cliente_id" id="cliente_id" class="form-control" required>
                                @foreach ($clientes as $cliente)
                                    <option value="{{ $cliente->id }}"
                                            data-telefono="{{ $cliente->telefono ?? '' }}"
                                            {{ old('cliente_id') == $cliente->id ? 'selected' : '' }}>
                                        {{ $cliente->nombre }} - {{ $cliente->email }}
                                    </option>
                                @endforeach
                            </select>

                            <span class="field-hint">
                                Selecciona el cliente particular al que pertenece la incidencia.
                            </span>
                        </div>
                    @else
                        <div class="form-group form-group-full">
                            <label>Cliente</label>

                            <div class="client-chip">
                                <div class="client-avatar">RY</div>
                                <div>
                                    <strong>{{ session('usuario_nombre') }}</strong>
                                    <span>La incidencia quedará vinculada automáticamente a tu perfil.</span>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="form-group">
                        <label>Especialidad solicitada</label>

                        <select name="especialidad_id" id="especialidad_id" class="form-control" required>
                            @foreach ($especialidades as $especialidad)
                                <option value="{{ $especialidad->id }}"
                                        {{ old('especialidad_id') == $especialidad->id ? 'selected' : '' }}>
                                    {{ $especialidad->nombre_especialidad }}
                                </option>
                            @endforeach
                        </select>

                        <span class="field-hint">
                            Clasifica el tipo de reparación para facilitar una asignación correcta.
                        </span>
                    </div>

                    @if($esAdmin)
                        <div class="form-group">
                            <label>Técnico asignado</label>

                            <select name="tecnico_id" id="tecnico_id" class="form-control">
                                <option value="">
                                    Sin asignar por ahora
                                </option>

                                @foreach ($tecnicos as $tecnico)
                                    <option value="{{ $tecnico->id }}"
                                            data-especialidad-id="{{ $tecnico->especialidad_id }}"
                                            data-especialidad="{{ $tecnico->especialidad->nombre_especialidad ?? 'Sin especialidad' }}"
                                            {{ old('tecnico_id') == $tecnico->id ? 'selected' : '' }}>
                                        {{ $tecnico->nombre_completo }}
                                        @if ($tecnico->especialidad)
                                            - {{ $tecnico->especialidad->nombre_especialidad }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>

                            <span class="field-hint">
                                Solo son válidos los técnicos que coincidan con la especialidad solicitada.
                            </span>
                        </div>
                    @else
                        <div class="form-group">
                            <label>Asignación técnica</label>

                            <input type="text"
                                   class="form-control readonly-field"
                                   value="Pendiente de revisión"
                                   readonly>

                            <span class="field-hint">
                                Un administrador revisará la solicitud y asignará el técnico adecuado.
                            </span>
                        </div>
                    @endif

                    <div class="form-group">
                        <label>Estado inicial</label>

                        <input type="text"
                               id="estado_visible"
                               class="form-control readonly-field"
                               value="Pendiente"
                               readonly>

                        <span class="field-hint">
                            Si se asigna técnico desde administración, la incidencia pasa automáticamente a Asignada.
                        </span>
                    </div>

                    <div class="form-group">
                        <label>Tipo de urgencia</label>

                        <select name="tipo_urgencia" class="form-control" required>
                            <option value="Estandar" {{ old('tipo_urgencia') == 'Estandar' ? 'selected' : '' }}>
                                Estándar
                            </option>

                            <option value="Urgente" {{ old('tipo_urgencia') == 'Urgente' ? 'selected' : '' }}>
                                Urgente
                            </option>
                        </select>

                        <span class="field-hint">
                            Marca Urgente solo cuando la intervención requiera prioridad real.
                        </span>
                    </div>

                    <div class="form-group form-group-full">
                        <label>Descripción</label>

                        <textarea
                            name="descripcion"
                            class="form-control"
                            rows="5"
                            placeholder="Describe qué ocurre, desde cuándo pasa y cualquier detalle útil para preparar la intervención."
                            required>{{ old('descripcion') }}</textarea>

                        <span class="field-hint">
                            Cuanto mejor explicado esté el problema, menos vueltas dará la reparación.
                        </span>
                    </div>

                    <div class="form-group form-group-full">
                        <label>Dirección del servicio</label>

                        <input type="text"
                               name="direccion"
                               class="form-control"
                               value="{{ old('direccion') }}"
                               placeholder="Calle, número, piso, localidad..."
                               required>

                        <span class="field-hint">
                            Ubicación donde debe realizarse la intervención.
                        </span>
                    </div>

                    <div class="form-group">
                        <label>Teléfono de contacto</label>

                        <input type="text"
                               name="telefono_contacto"
                               id="telefono_contacto"
                               class="form-control"
                               value="{{ old('telefono_contacto') }}"
                               placeholder="Ejemplo: 600123456"
                               required>

                        <span class="field-hint">
                            Teléfono operativo para contactar antes o durante el servicio.
                        </span>
                    </div>

                    <div class="form-group">
                        <label>Fecha y hora prevista</label>

                        <input type="datetime-local"
                               name="fecha_servicio"
                               class="form-control"
                               value="{{ old('fecha_servicio', now()->format('Y-m-d\TH:i')) }}"
                               required>

                        <span class="field-hint">
                            Fecha prevista para planificar o revisar el servicio.
                        </span>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        Crear incidencia
                    </button>

                    <a href="{{ route('incidencias.index') }}" class="btn btn-warning">
                        Cancelar
                    </a>
                </div>
            </form>
        </section>

        <aside class="side-card">
            <h2 class="section-title">Cómo queda el flujo</h2>

            <div class="guide-list">
                <div class="guide-item">
                    <div class="guide-number">1</div>
                    <div>
                        <strong>Entrada del aviso</strong>
                        <span>La solicitud queda registrada con cliente, especialidad, descripción, ubicación y fecha prevista.</span>
                    </div>
                </div>

                <div class="guide-item">
                    <div class="guide-number">2</div>
                    <div>
                        <strong>Asignación coherente</strong>
                        <span>El administrador puede asignar un técnico compatible con la especialidad solicitada.</span>
                    </div>
                </div>

                <div class="guide-item">
                    <div class="guide-number">3</div>
                    <div>
                        <strong>Seguimiento claro</strong>
                        <span>El estado permite saber si la incidencia está pendiente, asignada, finalizada o cancelada.</span>
                    </div>
                </div>
            </div>
        </aside>

    </div>

</div>

<script>
    function actualizarTelefonoCliente(forzarCambio = false) {
        const selectCliente = document.getElementById('cliente_id');
        const telefonoContacto = document.getElementById('telefono_contacto');

        if (!selectCliente || !telefonoContacto) {
            return;
        }

        const opcionSeleccionada = selectCliente.options[selectCliente.selectedIndex];

        if (!opcionSeleccionada) {
            return;
        }

        const telefonoCliente = opcionSeleccionada.dataset.telefono || '';

        if (forzarCambio || telefonoContacto.value.trim() === '') {
            telefonoContacto.value = telefonoCliente;
        }
    }

    function filtrarTecnicosPorEspecialidad() {
        const selectEspecialidad = document.getElementById('especialidad_id');
        const selectTecnico = document.getElementById('tecnico_id');

        if (!selectEspecialidad || !selectTecnico) {
            actualizarEstadoInicial();
            return;
        }

        const especialidadSeleccionada = selectEspecialidad.value;
        const tecnicoSeleccionado = selectTecnico.options[selectTecnico.selectedIndex];

        for (let i = 0; i < selectTecnico.options.length; i++) {
            const opcion = selectTecnico.options[i];

            if (opcion.value === '') {
                opcion.hidden = false;
                opcion.disabled = false;
                continue;
            }

            const coincide = opcion.dataset.especialidadId === especialidadSeleccionada;

            opcion.hidden = !coincide;
            opcion.disabled = !coincide;
        }

        if (
            tecnicoSeleccionado &&
            tecnicoSeleccionado.value !== '' &&
            tecnicoSeleccionado.dataset.especialidadId !== especialidadSeleccionada
        ) {
            selectTecnico.value = '';
        }

        actualizarEstadoInicial();
    }

    function actualizarEstadoInicial() {
        const selectTecnico = document.getElementById('tecnico_id');
        const estadoVisible = document.getElementById('estado_visible');

        if (!estadoVisible) {
            return;
        }

        if (!selectTecnico) {
            estadoVisible.value = 'Pendiente';
            return;
        }

        estadoVisible.value = selectTecnico.value ? 'Asignada' : 'Pendiente';
    }

    document.addEventListener('DOMContentLoaded', function () {
        actualizarTelefonoCliente(false);
        filtrarTecnicosPorEspecialidad();
        actualizarEstadoInicial();

        const selectCliente = document.getElementById('cliente_id');
        const selectEspecialidad = document.getElementById('especialidad_id');
        const selectTecnico = document.getElementById('tecnico_id');

        if (selectCliente) {
            selectCliente.addEventListener('change', function () {
                actualizarTelefonoCliente(true);
            });
        }

        if (selectEspecialidad) {
            selectEspecialidad.addEventListener('change', function () {
                filtrarTecnicosPorEspecialidad();
            });
        }

        if (selectTecnico) {
            selectTecnico.addEventListener('change', function () {
                actualizarEstadoInicial();
            });
        }
    });
</script>

@endsection