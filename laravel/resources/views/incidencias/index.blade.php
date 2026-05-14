@extends('layouts.app')

@section('title', 'Incidencias')

@section('content')

@php
    $total = $incidencias->count();
    $pendientes = $incidencias->where('estado', 'Pendiente')->count();
    $asignadas = $incidencias->where('estado', 'Asignada')->count();
    $finalizadas = $incidencias->where('estado', 'Finalizada')->count();
    $urgentes = $incidencias->where('tipo_urgencia', 'Urgente')->count();
@endphp

<style>
    .index-shell {
        display: grid;
        gap: 28px;
    }

    .hero-clean {
        position: relative;
        overflow: hidden;
        border-radius: 32px;
        padding: 36px 32px 30px;
        background:
            radial-gradient(circle at 10% 10%, rgba(86,199,255,0.14), transparent 20%),
            linear-gradient(135deg, #041225 0%, #061a32 52%, #0b2748 100%);
        border: 1px solid rgba(255,255,255,0.12);
        box-shadow: 0 28px 60px rgba(2, 6, 23, 0.20);
    }

    .hero-clean::before {
        content: "";
        position: absolute;
        inset: 0;
        background-image:
            linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
        background-size: 38px 38px;
        pointer-events: none;
    }

    .hero-actions-top {
        position: relative;
        z-index: 2;
        display: flex;
        justify-content: flex-end;
        margin-bottom: 10px;
    }

    .hero-title-main {
        position: relative;
        z-index: 2;
        margin: 0;
        text-align: center;
        color: white;
        font-size: clamp(42px, 5vw, 70px);
        letter-spacing: -2px;
        line-height: 1;
    }

    .stats-grid {
        position: relative;
        z-index: 2;
        margin-top: 26px;
        display: grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 14px;
    }

    .stats-card {
        padding: 18px 20px;
        border-radius: 20px;
        background: rgba(255,255,255,0.08);
        border: 1px solid rgba(255,255,255,0.10);
    }

    .stats-card span {
        display: block;
        color: #a9bfd9;
        font-size: 12px;
        font-weight: 900;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    .stats-card strong {
        display: block;
        margin-top: 8px;
        color: white;
        font-size: 40px;
        line-height: 1;
    }

    .table-wrap {
        overflow-x: auto;
        border-radius: 24px;
    }

    .status-pill,
    .urgency-pill,
    .tracking-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 8px 14px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 900;
        letter-spacing: 0.3px;
        white-space: nowrap;
    }

    .status-pendiente { background: #fff4d6; color: #936500; }
    .status-asignada { background: #dff2ff; color: #0a5ea8; }
    .status-finalizada { background: #ddf8ea; color: #0b7a48; }
    .status-cancelada { background: #eceef1; color: #59636e; }

    .urgency-urgente { background: #ffe1e5; color: #b42334; }
    .urgency-estandar { background: #e5f2ff; color: #245ea8; }

    .tracking-ok { background: #dff7ff; color: #055160; }
    .tracking-warn { background: #fff3cd; color: #856404; }
    .tracking-danger { background: #f8d7da; color: #842029; }
    .tracking-done { background: #d1e7dd; color: #0f5132; }
    .tracking-off { background: #e2e3e5; color: #41464b; }

    @media (max-width: 1250px) {
        .stats-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 760px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }

        .hero-actions-top {
            justify-content: center;
        }
    }
</style>

<div class="index-shell">

    <section class="hero-clean">
        <div class="hero-actions-top">
            @if(session('usuario_rol') !== 'tecnico')
                <a href="{{ route('incidencias.create') }}" class="btn btn-primary">
                    Nueva incidencia
                </a>
            @endif
        </div>

        <h1 class="hero-title-main">Incidencias</h1>

        <div class="stats-grid">
            <div class="stats-card">
                <span>Total</span>
                <strong>{{ $total }}</strong>
            </div>

            <div class="stats-card">
                <span>Pendientes</span>
                <strong>{{ $pendientes }}</strong>
            </div>

            <div class="stats-card">
                <span>Asignadas</span>
                <strong>{{ $asignadas }}</strong>
            </div>

            <div class="stats-card">
                <span>Finalizadas</span>
                <strong>{{ $finalizadas }}</strong>
            </div>

            <div class="stats-card">
                <span>Urgentes</span>
                <strong>{{ $urgentes }}</strong>
            </div>
        </div>
    </section>

    @if ($incidencias->isEmpty())
        <p class="alert-empty">No hay incidencias registradas.</p>
    @else
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Localizador</th>
                        <th>Cliente</th>
                        <th>Técnico</th>
                        <th>Especialidad</th>
                        <th>Estado</th>
                        <th>Urgencia</th>
                        <th>Fecha y hora</th>
                        <th>Seguimiento</th>
                        @if(session('usuario_rol') === 'admin')
                            <th>Acciones</th>
                        @endif
                    </tr>
                </thead>

                <tbody>
                    @foreach ($incidencias as $i)
                        @php
                            $fechaServicio = \Carbon\Carbon::parse($i->fecha_servicio);
                            $incidenciaAbierta = !in_array($i->estado, ['Finalizada', 'Cancelada']);
                            $fechaVencida = $fechaServicio->isPast() && $incidenciaAbierta;
                        @endphp

                        <tr>
                            <td>{{ $i->localizador }}</td>
                            <td>{{ $i->cliente->nombre ?? 'Sin cliente' }}</td>
                            <td>{{ $i->tecnico->nombre_completo ?? 'Sin técnico' }}</td>
                            <td>{{ $i->especialidad->nombre_especialidad ?? 'Sin especialidad' }}</td>

                            <td>
                                @if ($i->estado === 'Pendiente')
                                    <span class="status-pill status-pendiente">Pendiente</span>
                                @elseif ($i->estado === 'Asignada')
                                    <span class="status-pill status-asignada">Asignada</span>
                                @elseif ($i->estado === 'Finalizada')
                                    <span class="status-pill status-finalizada">Finalizada</span>
                                @else
                                    <span class="status-pill status-cancelada">Cancelada</span>
                                @endif
                            </td>

                            <td>
                                @if ($i->tipo_urgencia === 'Urgente')
                                    <span class="urgency-pill urgency-urgente">Urgente</span>
                                @else
                                    <span class="urgency-pill urgency-estandar">Estándar</span>
                                @endif
                            </td>

                            <td>{{ $fechaServicio->format('d/m/Y H:i') }}</td>

                            <td>
                                @if ($fechaVencida && $i->estado === 'Asignada')
                                    <span class="tracking-pill tracking-warn">Pendiente de cierre</span>
                                @elseif ($fechaVencida && $i->estado === 'Pendiente')
                                    <span class="tracking-pill tracking-danger">Fecha vencida</span>
                                @elseif ($i->estado === 'Finalizada')
                                    <span class="tracking-pill tracking-done">Servicio cerrado</span>
                                @elseif ($i->estado === 'Cancelada')
                                    <span class="tracking-pill tracking-off">Cancelada</span>
                                @else
                                    <span class="tracking-pill tracking-ok">En plazo</span>
                                @endif
                            </td>

                            @if(session('usuario_rol') === 'admin')
                                <td>
                                    <div class="actions">
                                        <a href="{{ route('incidencias.edit', $i->id) }}" class="btn btn-warning">
                                            Editar
                                        </a>

                                        <form action="{{ route('incidencias.destroy', $i->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('¿Seguro que quieres eliminar esta incidencia?');">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-danger">
                                                Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

</div>

@endsection