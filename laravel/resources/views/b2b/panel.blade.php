@extends('layouts.app')

@section('title', 'Panel Gestora · ReparaYa')

@section('content')

@php
    $meses = [
        1 => 'Enero',
        2 => 'Febrero',
        3 => 'Marzo',
        4 => 'Abril',
        5 => 'Mayo',
        6 => 'Junio',
        7 => 'Julio',
        8 => 'Agosto',
        9 => 'Septiembre',
        10 => 'Octubre',
        11 => 'Noviembre',
        12 => 'Diciembre',
    ];

    $totalServicios = $resumen['total_servicios'] ?? 0;
    $pendientes = $resumen['pendientes'] ?? 0;
    $asignados = $resumen['asignados'] ?? 0;
    $finalizados = $resumen['finalizados'] ?? 0;
    $cancelados = $resumen['cancelados'] ?? 0;
    $comunidades = $resumen['comunidades'] ?? 0;
    $totalImporte = $resumen['total_importe'] ?? 0;
    $totalComisiones = $resumen['total_comisiones'] ?? $totalComisiones;
    $porcentajeFinalizados = $resumen['porcentaje_finalizados'] ?? 0;
    $porcentajePendientes = $resumen['porcentaje_pendientes'] ?? 0;
    $proximoServicio = $resumen['proximo_servicio'] ?? null;
@endphp

<style>
    .gestora-panel-shell {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .gestora-panel-hero {
        position: relative;
        overflow: hidden;
        border-radius: 30px;
        padding: 38px 34px 30px;
        background:
            radial-gradient(circle at 14% 18%, rgba(86, 199, 255, 0.18), transparent 28%),
            radial-gradient(circle at 88% 10%, rgba(214, 184, 109, 0.12), transparent 24%),
            linear-gradient(135deg, rgba(8, 28, 56, 0.98) 0%, rgba(5, 20, 44, 0.97) 52%, rgba(17, 54, 99, 0.95) 100%);
        box-shadow: 0 20px 48px rgba(2, 6, 23, 0.18);
        border: 1px solid rgba(255, 255, 255, 0.08);
    }

    .gestora-panel-hero::before {
        content: "";
        position: absolute;
        inset: 0;
        background:
            linear-gradient(rgba(255,255,255,0.04) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,0.04) 1px, transparent 1px);
        background-size: 40px 40px;
        opacity: 0.28;
        pointer-events: none;
    }

    .gestora-panel-hero-inner {
        position: relative;
        z-index: 1;
        display: flex;
        flex-direction: column;
        gap: 28px;
    }

    .gestora-panel-topbar {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 14px;
        flex-wrap: wrap;
    }

    .gestora-panel-title-wrap {
        text-align: center;
    }

    .gestora-panel-title {
        margin: 0;
        color: #ffffff;
        font-size: clamp(34px, 5vw, 62px);
        line-height: 1.04;
        letter-spacing: -1.9px;
        font-weight: 900;
    }

    .gestora-panel-subtitle {
        margin: 12px auto 0;
        max-width: 780px;
        color: #d7e7f8;
        font-size: 17px;
        line-height: 1.65;
        font-weight: 700;
    }

    .gestora-panel-accent {
        width: 150px;
        height: 6px;
        border-radius: 999px;
        margin: 18px auto 0;
        background: linear-gradient(90deg, #0f6fff 0%, #56c7ff 100%);
        box-shadow: 0 0 18px rgba(86, 199, 255, 0.35);
    }

    .gestora-filter-panel {
        display: flex;
        justify-content: center;
        align-items: flex-end;
        gap: 14px;
        flex-wrap: wrap;
        padding: 20px;
        border-radius: 24px;
        background: rgba(255,255,255,0.10);
        border: 1px solid rgba(255,255,255,0.10);
        box-shadow: inset 0 1px 0 rgba(255,255,255,0.06);
    }

    .gestora-filter-group {
        display: flex;
        flex-direction: column;
        gap: 7px;
    }

    .gestora-filter-group label {
        color: #d7e7f8;
        font-size: 13px;
        font-weight: 900;
        letter-spacing: 0.45px;
        text-transform: uppercase;
    }

    .gestora-filter-group select {
        min-width: 140px;
        min-height: 52px;
        padding: 0 14px;
        border-radius: 16px;
        border: 1px solid rgba(255,255,255,0.16);
        background: rgba(255,255,255,0.94);
        color: #0f172a;
        font-size: 15px;
        font-weight: 800;
        outline: none;
    }

    .gestora-main-stats {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 18px;
    }

    .gestora-stat-card {
        border-radius: 24px;
        padding: 22px 22px 18px;
        background: rgba(255, 255, 255, 0.10);
        border: 1px solid rgba(255, 255, 255, 0.10);
        box-shadow: inset 0 1px 0 rgba(255,255,255,0.06);
        backdrop-filter: blur(8px);
        text-align: center;
    }

    .gestora-stat-label {
        display: block;
        color: #b9d7ff;
        font-size: 13px;
        font-weight: 900;
        letter-spacing: 0.8px;
        text-transform: uppercase;
        margin-bottom: 12px;
    }

    .gestora-stat-value {
        display: block;
        color: #ffffff;
        font-size: clamp(27px, 4vw, 40px);
        font-weight: 900;
        letter-spacing: -1px;
        line-height: 1;
    }

    .gestora-stat-value.highlight {
        color: #56c7ff;
    }

    .gestora-panel-grid {
        display: grid;
        grid-template-columns: 0.9fr 1.1fr;
        gap: 24px;
    }

    .gestora-card {
        position: relative;
        overflow: hidden;
        border-radius: 28px;
        padding: 26px;
        background:
            radial-gradient(circle at 8% 10%, rgba(86,199,255,0.10), transparent 24%),
            linear-gradient(180deg, rgba(246, 250, 255, 0.96) 0%, rgba(255,255,255,0.98) 100%);
        box-shadow: 0 14px 34px rgba(15, 23, 42, 0.07);
        border: 1px solid rgba(214, 227, 242, 0.8);
    }

    .gestora-card-title {
        margin: 0 0 22px;
        color: #0f172a;
        font-size: 28px;
        line-height: 1.08;
        letter-spacing: -0.8px;
        font-weight: 900;
        text-align: center;
    }

    .gestora-kpi-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
    }

    .gestora-kpi {
        padding: 18px;
        border-radius: 22px;
        background: #ffffff;
        border: 1px solid #e4edf7;
        text-align: center;
    }

    .gestora-kpi span {
        display: block;
        color: #64748b;
        font-size: 12px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.45px;
        margin-bottom: 9px;
    }

    .gestora-kpi strong {
        display: block;
        color: #0f172a;
        font-size: 28px;
        line-height: 1;
        letter-spacing: -0.8px;
        font-weight: 900;
    }

    .gestora-progress-list {
        display: grid;
        gap: 18px;
    }

    .gestora-progress-item {
        display: grid;
        gap: 8px;
    }

    .gestora-progress-top {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        color: #0f172a;
        font-size: 14px;
        font-weight: 900;
    }

    .gestora-progress-track {
        height: 13px;
        border-radius: 999px;
        overflow: hidden;
        background: #dfeafb;
    }

    .gestora-progress-fill {
        width: calc(var(--value) * 1%);
        min-width: 0;
        height: 100%;
        border-radius: inherit;
        background: linear-gradient(90deg, #0f6fff, #56c7ff);
        box-shadow: 0 0 18px rgba(86,199,255,0.30);
    }

    .gestora-progress-fill.green {
        background: linear-gradient(90deg, #07834f, #55e6a2);
    }

    .gestora-next-box {
        min-height: 100%;
        border-radius: 24px;
        padding: 24px;
        background:
            radial-gradient(circle at 20% 10%, rgba(86,199,255,0.14), transparent 26%),
            linear-gradient(135deg, #031121 0%, #08284b 100%);
        color: white;
        display: grid;
        align-content: center;
        text-align: center;
        border: 1px solid rgba(255,255,255,0.12);
        box-shadow: inset 0 1px 0 rgba(255,255,255,0.06);
    }

    .gestora-next-label {
        display: block;
        color: #9adfff;
        font-size: 13px;
        font-weight: 900;
        letter-spacing: 0.7px;
        text-transform: uppercase;
        margin-bottom: 12px;
    }

    .gestora-next-title {
        margin: 0;
        color: white;
        font-size: 28px;
        line-height: 1.1;
        letter-spacing: -0.8px;
        font-weight: 900;
    }

    .gestora-next-meta {
        margin-top: 14px;
        color: #d7e7f8;
        font-size: 16px;
        line-height: 1.6;
        font-weight: 700;
    }

    .gestora-table-panel {
        background: linear-gradient(180deg, rgba(246, 250, 255, 0.96) 0%, rgba(255,255,255,0.98) 100%);
        border-radius: 28px;
        padding: 22px;
        box-shadow: 0 14px 34px rgba(15, 23, 42, 0.07);
        border: 1px solid rgba(214, 227, 242, 0.8);
    }

    .gestora-table-header {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 18px;
        text-align: center;
    }

    .gestora-table-title {
        margin: 0;
        color: #0f172a;
        font-size: 28px;
        line-height: 1.1;
        letter-spacing: -0.8px;
        font-weight: 900;
    }

    .gestora-table-wrap {
        overflow-x: auto;
        border-radius: 22px;
    }

    .gestora-table {
        width: 100%;
        min-width: 1250px;
        border-collapse: separate;
        border-spacing: 0;
        background: #ffffff;
        overflow: hidden;
        border-radius: 22px;
    }

    .gestora-table thead th {
        background: #edf4fb;
        color: #0f172a;
        font-size: 13px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.45px;
        padding: 18px 16px;
        white-space: nowrap;
        text-align: left;
    }

    .gestora-table tbody td {
        padding: 18px 16px;
        border-bottom: 1px solid #e9eef5;
        vertical-align: middle;
        color: #0f172a;
        font-size: 15px;
    }

    .gestora-table tbody tr:last-child td {
        border-bottom: none;
    }

    .gestora-localizador {
        font-weight: 900;
        color: #0f172a;
        font-size: 16px;
    }

    .gestora-row-small {
        display: block;
        margin-top: 5px;
        color: #64748b;
        font-size: 13px;
        font-weight: 700;
    }

    .gestora-money {
        color: #0f172a;
        font-weight: 900;
        white-space: nowrap;
    }

    .gestora-money.success {
        color: #07834f;
    }

    .gestora-money.pending {
        color: #94a3b8;
    }

    .gestora-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 96px;
        padding: 8px 14px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 900;
        letter-spacing: 0.15px;
        white-space: nowrap;
    }

    .gestora-badge.estado-pendiente {
        background: #fff3cd;
        color: #856404;
    }

    .gestora-badge.estado-asignada {
        background: #dbeafe;
        color: #1554c8;
    }

    .gestora-badge.estado-finalizada {
        background: #d1e7dd;
        color: #0f5132;
    }

    .gestora-badge.estado-cancelada {
        background: #e2e3e5;
        color: #41464b;
    }

    .gestora-badge.urgente {
        background: #ffe1e8;
        color: #b4233a;
    }

    .gestora-badge.estandar {
        background: #e0f2fe;
        color: #075985;
    }

    .gestora-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .gestora-actions form {
        margin: 0;
    }

    .btn-compact {
        min-width: 106px;
    }

    .gestora-empty {
        border-radius: 22px;
        padding: 26px;
        background: linear-gradient(135deg, #fff8df 0%, #fff2c4 100%);
        border: 1px solid #f0de9c;
        color: #755400;
        font-weight: 800;
        text-align: center;
    }

    @media (max-width: 1250px) {
        .gestora-main-stats {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .gestora-panel-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 760px) {
        .gestora-panel-hero {
            padding: 26px 18px 22px;
            border-radius: 24px;
        }

        .gestora-main-stats,
        .gestora-kpi-grid {
            grid-template-columns: 1fr;
        }

        .gestora-panel-title {
            font-size: 34px;
        }

        .gestora-panel-topbar {
            justify-content: center;
        }

        .gestora-panel-topbar .btn,
        .gestora-filter-group,
        .gestora-filter-group select,
        .gestora-filter-panel .btn {
            width: 100%;
        }

        .gestora-card,
        .gestora-table-panel {
            padding: 16px;
            border-radius: 22px;
        }

        .gestora-card-title,
        .gestora-table-title {
            font-size: 22px;
        }
    }
</style>

<div class="gestora-panel-shell">

    <section class="gestora-panel-hero">
        <div class="gestora-panel-hero-inner">

            <div class="gestora-panel-topbar">
                <a href="{{ route('b2b.create_aviso') }}" class="btn btn-primary">
                    + Crear aviso
                </a>
            </div>

            <div class="gestora-panel-title-wrap">
                <h1 class="gestora-panel-title">{{ $gestora->nombre }}</h1>
                <p class="gestora-panel-subtitle">
                    Panel operativo B2B para controlar avisos, servicios finalizados y comisiones del periodo.
                </p>
                <div class="gestora-panel-accent"></div>
            </div>

            <form method="GET" action="{{ route('b2b.panel') }}" class="gestora-filter-panel">
                <div class="gestora-filter-group">
                    <label for="mes">Mes</label>
                    <select name="mes" id="mes">
                        @foreach($meses as $numeroMes => $nombreMes)
                            <option value="{{ $numeroMes }}" {{ (int) $mes === (int) $numeroMes ? 'selected' : '' }}>
                                {{ $nombreMes }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="gestora-filter-group">
                    <label for="anyo">Año</label>
                    <select name="anyo" id="anyo">
                        @foreach($anyos as $a)
                            <option value="{{ $a }}" {{ (int) $a === (int) $anyo ? 'selected' : '' }}>
                                {{ $a }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">
                    Aplicar filtro
                </button>
            </form>

            <div class="gestora-main-stats">
                <div class="gestora-stat-card">
                    <span class="gestora-stat-label">Total avisos</span>
                    <span class="gestora-stat-value">{{ $totalServicios }}</span>
                </div>

                <div class="gestora-stat-card">
                    <span class="gestora-stat-label">Finalizados</span>
                    <span class="gestora-stat-value">{{ $finalizados }}</span>
                </div>

                <div class="gestora-stat-card">
                    <span class="gestora-stat-label">Importe base</span>
                    <span class="gestora-stat-value">{{ number_format($totalImporte, 2) }} €</span>
                </div>

                <div class="gestora-stat-card">
                    <span class="gestora-stat-label">Comisión</span>
                    <span class="gestora-stat-value highlight">{{ number_format($totalComisiones, 2) }} €</span>
                </div>
            </div>

        </div>
    </section>

    <section class="gestora-panel-grid">

        <div class="gestora-card">
            <h2 class="gestora-card-title">Resumen del periodo</h2>

            <div class="gestora-kpi-grid">
                <div class="gestora-kpi">
                    <span>Pendientes</span>
                    <strong>{{ $pendientes }}</strong>
                </div>

                <div class="gestora-kpi">
                    <span>Asignados</span>
                    <strong>{{ $asignados }}</strong>
                </div>

                <div class="gestora-kpi">
                    <span>Cancelados</span>
                    <strong>{{ $cancelados }}</strong>
                </div>

                <div class="gestora-kpi">
                    <span>Comunidades</span>
                    <strong>{{ $comunidades }}</strong>
                </div>
            </div>
        </div>

        <div class="gestora-card">
            <h2 class="gestora-card-title">Seguimiento</h2>

            @if($totalServicios > 0)
                <div class="gestora-progress-list">
                    <div class="gestora-progress-item">
                        <div class="gestora-progress-top">
                            <span>Servicios finalizados</span>
                            <span>{{ $porcentajeFinalizados }}%</span>
                        </div>
                        <div class="gestora-progress-track">
                            <div class="gestora-progress-fill green" style="--value: {{ $porcentajeFinalizados }};"></div>
                        </div>
                    </div>

                    <div class="gestora-progress-item">
                        <div class="gestora-progress-top">
                            <span>Actividad abierta</span>
                            <span>{{ $porcentajePendientes }}%</span>
                        </div>
                        <div class="gestora-progress-track">
                            <div class="gestora-progress-fill" style="--value: {{ $porcentajePendientes }};"></div>
                        </div>
                    </div>
                </div>
            @else
                <div class="gestora-next-box">
                    <span class="gestora-next-label">Seguimiento</span>
                    <h3 class="gestora-next-title">Sin avisos en este periodo</h3>
                    <div class="gestora-next-meta">
                        Selecciona otro mes o crea un nuevo aviso para iniciar actividad.
                    </div>
                </div>
            @endif
        </div>

    </section>

    <section class="gestora-card">
        <h2 class="gestora-card-title">Próxima actuación</h2>

        @if($proximoServicio)
            <div class="gestora-next-box">
                <span class="gestora-next-label">{{ $proximoServicio->localizador }}</span>
                <h3 class="gestora-next-title">
                    {{ $proximoServicio->comunidad->nombre ?? 'Comunidad sin asignar' }}
                </h3>
                <div class="gestora-next-meta">
                    {{ \Carbon\Carbon::parse($proximoServicio->fecha_servicio)->format('d/m/Y H:i') }}
                    · {{ $proximoServicio->especialidad->nombre_especialidad ?? 'Sin especialidad' }}
                    · {{ $proximoServicio->estado }}
                </div>
            </div>
        @else
            <div class="gestora-next-box">
                <span class="gestora-next-label">Seguimiento</span>
                <h3 class="gestora-next-title">No hay avisos abiertos en este periodo</h3>
                <div class="gestora-next-meta">
                    La actividad filtrada no tiene servicios pendientes.
                </div>
            </div>
        @endif
    </section>

    @if(!empty($calendarData['calendarTitle']))
        @include('components.reparaya-calendar', [
            'calendarTitle' => $calendarData['calendarTitle'],
            'calendarSubtitle' => $calendarData['calendarSubtitle'] ?? null,
            'calendarEvents' => $calendarData['calendarEvents'] ?? [],
        ])
    @endif

    <section class="gestora-table-panel">
        <div class="gestora-table-header">
            <h2 class="gestora-table-title">Avisos gestionados</h2>
        </div>

        @if($serviciosConComision->isEmpty())
            <div class="gestora-empty">
                No hay avisos registrados para el periodo seleccionado.
            </div>
        @else
            <div class="gestora-table-wrap">
                <table class="gestora-table">
                    <thead>
                        <tr>
                            <th>Localizador</th>
                            <th>Comunidad</th>
                            <th>Descripción</th>
                            <th>Fecha y hora</th>
                            <th>Estado</th>
                            <th>Urgencia</th>
                            <th>Precio base</th>
                            <th>Comisión</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($serviciosConComision as $servicio)
                            @php
                                $estadoClase = match($servicio->estado) {
                                    'Finalizada' => 'estado-finalizada',
                                    'Asignada' => 'estado-asignada',
                                    'Cancelada' => 'estado-cancelada',
                                    default => 'estado-pendiente',
                                };

                                $urgenciaClase = $servicio->tipo_urgencia === 'Urgente' ? 'urgente' : 'estandar';
                            @endphp

                            <tr>
                                <td>
                                    <div class="gestora-localizador">
                                        {{ $servicio->localizador }}
                                    </div>
                                    <span class="gestora-row-small">
                                        ID #{{ $servicio->id }}
                                    </span>
                                </td>

                                <td>
                                    <strong>{{ $servicio->comunidad->nombre ?? 'Sin comunidad' }}</strong>
                                    <span class="gestora-row-small">
                                        {{ $servicio->direccion }}
                                    </span>
                                </td>

                                <td>
                                    {{ \Illuminate\Support\Str::limit($servicio->descripcion, 70) }}
                                </td>

                                <td>
                                    <strong>{{ \Carbon\Carbon::parse($servicio->fecha_servicio)->format('d/m/Y') }}</strong>
                                    <span class="gestora-row-small">
                                        {{ \Carbon\Carbon::parse($servicio->fecha_servicio)->format('H:i') }}
                                    </span>
                                </td>

                                <td>
                                    <span class="gestora-badge {{ $estadoClase }}">
                                        {{ $servicio->estado }}
                                    </span>
                                </td>

                                <td>
                                    <span class="gestora-badge {{ $urgenciaClase }}">
                                        {{ $servicio->tipo_urgencia === 'Estandar' ? 'Estándar' : 'Urgente' }}
                                    </span>
                                </td>

                                <td>
                                    <span class="gestora-money">
                                        {{ number_format($servicio->precio_base, 2) }} €
                                    </span>
                                </td>

                                <td>
                                    @if($servicio->estado === 'Finalizada')
                                        <span class="gestora-money success">
                                            {{ number_format($servicio->comision_calculada, 2) }} €
                                        </span>
                                    @else
                                        <span class="gestora-money pending">
                                            Pendiente
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    <div class="gestora-actions">
                                        <a href="{{ route('b2b.edit_aviso', $servicio->id) }}" class="btn btn-warning btn-compact">
                                            Editar
                                        </a>

                                        <form action="{{ route('b2b.destroy_aviso', $servicio->id) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar este aviso?');">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-danger btn-compact">
                                                Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>

</div>

@endsection