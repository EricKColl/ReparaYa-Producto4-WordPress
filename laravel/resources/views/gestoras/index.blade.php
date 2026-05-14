@extends('layouts.app')

@section('title', 'Gestoras')

@section('content')

@php
    $totalGestoras = $gestoras->count();
    $mediaComision = $totalGestoras > 0 ? round($gestoras->avg('comision'), 2) : 0;
    $totalServicios = $gestoras->sum(function ($gestora) {
        return $gestora->incidencias_count ?? ($gestora->incidencias->count() ?? 0);
    });
@endphp

<style>
    .gestoras-shell {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .gestoras-hero {
        position: relative;
        overflow: hidden;
        border-radius: 30px;
        padding: 38px 34px 30px;
        background:
            linear-gradient(135deg, rgba(8, 28, 56, 0.98) 0%, rgba(5, 20, 44, 0.97) 52%, rgba(17, 54, 99, 0.95) 100%);
        box-shadow: 0 20px 48px rgba(2, 6, 23, 0.18);
        border: 1px solid rgba(255, 255, 255, 0.08);
    }

    .gestoras-hero::before {
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

    .gestoras-hero-inner {
        position: relative;
        z-index: 1;
        display: flex;
        flex-direction: column;
        gap: 28px;
    }

    .gestoras-title-wrap {
        text-align: center;
    }

    .gestoras-title {
        margin: 0;
        color: #ffffff;
        font-size: clamp(34px, 5vw, 58px);
        line-height: 1.04;
        letter-spacing: -1.8px;
        font-weight: 900;
    }

    .gestoras-accent {
        width: 120px;
        height: 6px;
        border-radius: 999px;
        margin: 16px auto 0;
        background: linear-gradient(90deg, #0f6fff 0%, #56c7ff 100%);
        box-shadow: 0 0 18px rgba(86, 199, 255, 0.35);
    }

    .gestoras-topbar {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 14px;
        flex-wrap: wrap;
    }

    .gestoras-stats {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 18px;
    }

    .gestora-stat-card {
        position: relative;
        z-index: 1;
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
        font-size: 14px;
        font-weight: 800;
        letter-spacing: 0.8px;
        text-transform: uppercase;
        margin-bottom: 12px;
    }

    .gestora-stat-value {
        display: block;
        color: #ffffff;
        font-size: clamp(28px, 4vw, 42px);
        font-weight: 900;
        letter-spacing: -1px;
        line-height: 1;
    }

    .gestoras-table-panel {
        background: linear-gradient(180deg, rgba(246, 250, 255, 0.96) 0%, rgba(255,255,255,0.98) 100%);
        border-radius: 28px;
        padding: 22px;
        box-shadow: 0 14px 34px rgba(15, 23, 42, 0.07);
        border: 1px solid rgba(214, 227, 242, 0.8);
    }

    .gestoras-table-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 18px;
        flex-wrap: wrap;
    }

    .gestoras-table-title {
        margin: 0;
        font-size: 28px;
        font-weight: 900;
        letter-spacing: -0.8px;
        color: #0f172a;
    }

    .gestoras-table-wrap {
        overflow-x: auto;
        border-radius: 22px;
    }

    .gestoras-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        background: #ffffff;
        overflow: hidden;
        border-radius: 22px;
    }

    .gestoras-table thead th {
        background: #edf4fb;
        color: #0f172a;
        font-size: 14px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.45px;
        padding: 18px 16px;
        white-space: nowrap;
    }

    .gestoras-table tbody td {
        padding: 18px 16px;
        border-bottom: 1px solid #e9eef5;
        vertical-align: middle;
        color: #0f172a;
        font-size: 15px;
    }

    .gestoras-table tbody tr:last-child td {
        border-bottom: none;
    }

    .gestora-name {
        font-weight: 900;
        font-size: 16px;
    }

    .gestora-email,
    .gestora-phone {
        color: #475569;
        font-weight: 700;
    }

    .gestora-chip {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 92px;
        padding: 8px 14px;
        border-radius: 999px;
        background: #dfeafb;
        color: #1554c8;
        font-size: 13px;
        font-weight: 900;
        letter-spacing: 0.45px;
        text-transform: uppercase;
    }

    .gestora-services {
        font-weight: 900;
        font-size: 17px;
        color: #0f172a;
    }

    .gestoras-empty {
        border-radius: 22px;
        padding: 22px;
        background: linear-gradient(135deg, #fff8df 0%, #fff2c4 100%);
        border: 1px solid #f0de9c;
        color: #755400;
        font-weight: 800;
        text-align: center;
    }

    .gestoras-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .gestoras-actions form {
        margin: 0;
    }

    .btn-compact {
        min-width: 110px;
    }

    @media (max-width: 1100px) {
        .gestoras-stats {
            grid-template-columns: 1fr;
        }

        .gestoras-topbar {
            justify-content: center;
        }

        .gestoras-table-header {
            justify-content: center;
            text-align: center;
        }
    }

    @media (max-width: 760px) {
        .gestoras-hero {
            padding: 26px 18px 22px;
            border-radius: 24px;
        }

        .gestoras-table-panel {
            padding: 14px;
            border-radius: 22px;
        }

        .gestoras-title {
            font-size: 34px;
        }

        .gestoras-table-title {
            font-size: 22px;
        }

        .btn-compact {
            min-width: 94px;
        }
    }
</style>

<div class="gestoras-shell">

    @if(session('success'))
        <div class="flash-message flash-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="flash-message flash-error">
            {{ session('error') }}
        </div>
    @endif

    <section class="gestoras-hero">
        <div class="gestoras-hero-inner">

            <div class="gestoras-topbar">
                <a href="{{ route('gestoras.create') }}" class="btn btn-primary">
                    + Nueva gestora
                </a>
            </div>

            <div class="gestoras-title-wrap">
                <h1 class="gestoras-title">Gestoras B2B</h1>
                <div class="gestoras-accent"></div>
            </div>

            <div class="gestoras-stats">
                <div class="gestora-stat-card">
                    <span class="gestora-stat-label">Total gestoras</span>
                    <span class="gestora-stat-value">{{ $totalGestoras }}</span>
                </div>

                <div class="gestora-stat-card">
                    <span class="gestora-stat-label">Comisión media</span>
                    <span class="gestora-stat-value">{{ number_format($mediaComision, 2) }}%</span>
                </div>

                <div class="gestora-stat-card">
                    <span class="gestora-stat-label">Servicios gestionados</span>
                    <span class="gestora-stat-value">{{ $totalServicios }}</span>
                </div>
            </div>
        </div>
    </section>

    <section class="gestoras-table-panel">
        <div class="gestoras-table-header">
            <h2 class="gestoras-table-title">Listado de gestoras</h2>
        </div>

        @if($gestoras->isEmpty())
            <div class="gestoras-empty">
                No hay gestoras registradas en este momento.
            </div>
        @else
            <div class="gestoras-table-wrap">
                <table class="gestoras-table">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Comisión</th>
                            <th>Servicios</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($gestoras as $gestora)
                            @php
                                $numServicios = $gestora->incidencias_count ?? ($gestora->incidencias->count() ?? 0);
                            @endphp
                            <tr>
                                <td>
                                    <div class="gestora-name">{{ $gestora->nombre }}</div>
                                </td>

                                <td>
                                    <div class="gestora-email">{{ $gestora->email }}</div>
                                </td>

                                <td>
                                    <div class="gestora-phone">{{ $gestora->telefono }}</div>
                                </td>

                                <td>
                                    <span class="gestora-chip">
                                        {{ number_format($gestora->comision, 2) }}%
                                    </span>
                                </td>

                                <td>
                                    <span class="gestora-services">{{ $numServicios }}</span>
                                </td>

                                <td>
                                    <div class="gestoras-actions">
                                        <a href="{{ route('gestoras.edit', $gestora->id) }}" class="btn btn-warning btn-compact">
                                            Editar
                                        </a>

                                        <form action="{{ route('gestoras.destroy', $gestora->id) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar esta gestora?');">
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