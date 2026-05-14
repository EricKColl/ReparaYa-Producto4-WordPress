@extends('layouts.app')

@section('title', 'Editar Incidencia')

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

    .form-hero-content {
        position: relative;
        z-index: 2;
        text-align: center;
    }

    .form-hero-title {
        margin: 0;
        text-align: center;
        color: white;
        font-size: clamp(42px, 4.8vw, 68px);
        letter-spacing: -2px;
        line-height: 1;
    }

    .form-hero-subtitle {
        max-width: 860px;
        margin: 18px auto 0;
        color: #d5e3f0;
        text-align: center;
        font-size: 18px;
        line-height: 1.7;
        font-weight: 700;
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

    .readonly-card {
        border-radius: 22px;
        padding: 20px;
        background: white;
        border: 1px solid rgba(15,23,42,0.08);
        box-shadow: 0 12px 26px rgba(15,23,42,0.06);
    }

    .readonly-card span {
        display: block;
        color: #64748b;
        font-size: 13px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    .readonly-card strong {
        display: block;
        color: #0f172a;
        font-size: 22px;
        line-height: 1.2;
    }

    .status-summary {
        display: grid;
        gap: 14px;
        margin-top: 18px;
    }

    .status-item {
        padding: 16px;
        border-radius: 18px;
        background: white;
        border: 1px solid rgba(15,23,42,0.08);
    }

    .status-item span {
        display: block;
        color: #64748b;
        font-size: 13px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    .status-item strong {
        color: #0f172a;
        font-size: 17px;
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
            <h1 class="form-hero-title">Editar incidencia</h1>

            <p class="form-hero-subtitle">
                Ajusta la asignación, el estado y los datos operativos manteniendo la trazabilidad completa de la intervención.
            </p>
        </div>
    </section>

    @if ($errors->any())
        <div class="error-panel">
            <strong>No se ha podido actualizar la incidencia.</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-layout">

        <section class="form-card">
            <h2 class="section-title">Datos de la intervención</h2>

            <form action="{{ route('incidencias.update', $incidencia->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-grid">
                    <div class="form-group">
                        <label>Cliente</label>

                        <select name="cliente_id" id="cliente_id" class="form-control" required>
                            @foreach ($clientes as $cliente)
                                <option value="{{ $cliente->id }}"
                                        data-telefono="{{ $cliente->telefono ?? '' }}"
                                        {{ old('cliente_id', $incidencia->cliente_id) == $cliente->id ? 'selected' : '' }}>
                                    {{ $cliente->nombre }} - {{ $cliente->email }}
                                </option>
                            @endforeach
                        </select>

                        <span class="field-hint">
                            Cliente particular propietario de la incidencia.
                        </span>
                    </div>

                    <div class="form-group">
                        <label>Especialidad solicitada</label>

                        <select name="especialidad_id" id="especialidad_id" class="form-control" required>
                            @foreach ($especialidades as $especialidad)
                                <option value="{{ $especialidad->id }}"
                                        {{ old('especialidad_id', $incidencia->especialidad_id) == $especialidad->id ? 'selected' : '' }}>
                                    {{ $especialidad->nombre_especialidad }}
                                </option>
                            @endforeach
                        </select>

                        <span class="field-hint">
                            La especialidad condiciona qué técnicos pueden asignarse.
                        </span>
                    </div>

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
                                        {{ old('tecnico_id', $incidencia->tecnico_id) == $tecnico->id ? 'selected' : '' }}>
                                    {{ $tecnico->nombre_completo }}
                                    @if ($tecnico->especialidad)
                                        - {{ $tecnico->especialidad->nombre_especialidad }}
                                    @endif
                                </option>
                            @endforeach
                        </select>

                        <span class="field-hint">
                            El técnico debe pertenecer a la especialidad seleccionada.
                        </span>
                    </div>

                    <div class="form-group">
                        <label>Estado</label>

                        <select name="estado" id="estado" class="form-control" required>
                            <option value="Pendiente"
                                {{ old('estado', $incidencia->estado) == 'Pendiente' ? 'selected' : '' }}>
                                Pendiente
                            </option>

                            <option value="Asignada"
                                {{ old('estado', $incidencia->estado) == 'Asignada' ? 'selected' : '' }}>
                                Asignada
                            </option>

                            <option value="Finalizada"
                                {{ old('estado', $incidencia->estado) == 'Finalizada' ? 'selected' : '' }}>
                                Finalizada
                            </option>

                            <option value="Cancelada"
                                {{ old('estado', $incidencia->estado) == 'Cancelada' ? 'selected' : '' }}>
                                Cancelada
                            </option>
                        </select>

                        <span id="estado_ayuda" class="field-hint"></span>
                    </div>

                    <div class="form-group form-group-full">
                        <label>Descripción</label>

                        <textarea
                            name="descripcion"
                            class="form-control"
                            rows="5"
                            required>{{ old('descripcion', $incidencia->descripcion) }}</textarea>

                        <span class="field-hint">
                            Mantén una descripción útil para comprender la intervención.
                        </span>
                    </div>

                    <div class="form-group form-group-full">
                        <label>Dirección del servicio</label>

                        <input type="text"
                               name="direccion"
                               class="form-control"
                               value="{{ old('direccion', $incidencia->direccion) }}"
                               required>

                        <span class="field-hint">
                            Ubicación donde debe realizarse la reparación.
                        </span>
                    </div>

                    <div class="form-group">
                        <label>Teléfono de contacto</label>

                        <input type="text"
                               name="telefono_contacto"
                               id="telefono_contacto"
                               class="form-control"
                               value="{{ old('telefono_contacto', $incidencia->telefono_contacto) }}"
                               required>

                        <span class="field-hint">
                            Contacto operativo para coordinar la intervención.
                        </span>
                    </div>

                    <div class="form-group">
                        <label>Fecha y hora del servicio</label>

                        <input type="datetime-local"
                               name="fecha_servicio"
                               class="form-control"
                               value="{{ old('fecha_servicio', \Carbon\Carbon::parse($incidencia->fecha_servicio)->format('Y-m-d\TH:i')) }}"
                               required>

                        <span class="field-hint">
                            Fecha prevista para planificar, ejecutar o cerrar la intervención.
                        </span>
                    </div>

                    <div class="form-group form-group-full">
                        <label>Tipo de urgencia</label>

                        <select name="tipo_urgencia" class="form-control" required>
                            <option value="Estandar"
                                {{ old('tipo_urgencia', $incidencia->tipo_urgencia) == 'Estandar' ? 'selected' : '' }}>
                                Estándar
                            </option>

                            <option value="Urgente"
                                {{ old('tipo_urgencia', $incidencia->tipo_urgencia) == 'Urgente' ? 'selected' : '' }}>
                                Urgente
                            </option>
                        </select>

                        <span class="field-hint">
                            La urgencia ayuda a priorizar correctamente la carga de trabajo.
                        </span>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        Actualizar incidencia
                    </button>

                    <a href="{{ route('incidencias.index') }}" class="btn btn-warning">
                        Cancelar
                    </a>
                </div>
            </form>
        </section>

        <aside class="side-card">
            <h2 class="section-title">Resumen actual</h2>

            <div class="readonly-card">
                <span>Localizador</span>
                <strong>{{ $incidencia->localizador }}</strong>
            </div>

            <div class="status-summary">
                <div class="status-item">
                    <span>Cliente actual</span>
                    <strong>{{ $incidencia->cliente->nombre ?? 'Sin cliente' }}</strong>
                </div>

                <div class="status-item">
                    <span>Técnico actual</span>
                    <strong>{{ $incidencia->tecnico->nombre_completo ?? 'Sin técnico asignado' }}</strong>
                </div>

                <div class="status-item">
                    <span>Especialidad actual</span>
                    <strong>{{ $incidencia->especialidad->nombre_especialidad ?? 'Sin especialidad' }}</strong>
                </div>

                <div class="status-item">
                    <span>Estado actual</span>
                    <strong>{{ $incidencia->estado }}</strong>
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

        actualizarOpcionesEstado();
    }

    function actualizarOpcionesEstado() {
        const selectTecnico = document.getElementById('tecnico_id');
        const selectEstado = document.getElementById('estado');
        const estadoAyuda = document.getElementById('estado_ayuda');

        if (!selectTecnico || !selectEstado || !estadoAyuda) {
            return;
        }

        const hayTecnico = selectTecnico.value !== '';

        for (let i = 0; i < selectEstado.options.length; i++) {
            const opcion = selectEstado.options[i];

            opcion.hidden = false;
            opcion.disabled = false;

            if (hayTecnico && opcion.value === 'Pendiente') {
                opcion.hidden = true;
                opcion.disabled = true;
            }

            if (!hayTecnico && (opcion.value === 'Asignada' || opcion.value === 'Finalizada')) {
                opcion.hidden = true;
                opcion.disabled = true;
            }
        }

        if (hayTecnico && selectEstado.value === 'Pendiente') {
            selectEstado.value = 'Asignada';
        }

        if (!hayTecnico && (selectEstado.value === 'Asignada' || selectEstado.value === 'Finalizada')) {
            selectEstado.value = 'Pendiente';
        }

        if (hayTecnico) {
            estadoAyuda.textContent = 'Con técnico asignado, la incidencia puede estar Asignada, Finalizada o Cancelada.';
        } else {
            estadoAyuda.textContent = 'Sin técnico asignado, la incidencia solo puede quedar Pendiente o Cancelada.';
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        actualizarTelefonoCliente(false);
        filtrarTecnicosPorEspecialidad();
        actualizarOpcionesEstado();

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
                actualizarOpcionesEstado();
            });
        }
    });
</script>

@endsection