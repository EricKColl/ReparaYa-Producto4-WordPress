@extends('layouts.app')

@section('title', 'Liquidaciones')

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

    $totalLiquidar = $liquidaciones->sum('total_comision');
    $totalServicios = $liquidaciones->sum('total_servicios');
    $totalImporte = $liquidaciones->sum('total_importe');
    $gestorasConActividad = $liquidaciones->filter(fn($item) => $item['total_servicios'] > 0)->count();
@endphp

<style>
    .liquidaciones-shell {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .liquidaciones-hero {
        position: relative;
        overflow: hidden;
        border-radius: 30px;
        padding: 38px 34px 30px;
        background:
            linear-gradient(135deg, rgba(8, 28, 56, 0.98) 0%, rgba(5, 20, 44, 0.97) 52%, rgba(17, 54, 99, 0.95) 100%);
        box-shadow: 0 20px 48px rgba(2, 6, 23, 0.18);
        border: 1px solid rgba(255, 255, 255, 0.08);
    }

    .liquidaciones-hero::before {
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

    .liquidaciones-hero-inner {
        position: relative;
        z-index: 1;
        display: flex;
        flex-direction: column;
        gap: 28px;
    }

    .liquidaciones-title-wrap {
        text-align: center;
    }

    .liquidaciones-title {
        margin: 0;
        color: #ffffff;
        font-size: clamp(34px, 5vw, 58px);
        line-height: 1.04;
        letter-spacing: -1.8px;
        font-weight: 900;
    }

    .liquidaciones-accent {
        width: 140px;
        height: 6px;
        border-radius: 999px;
        margin: 16px auto 0;
        background: linear-gradient(90deg, #0f6fff 0%, #56c7ff 100%);
        box-shadow: 0 0 18px rgba(86, 199, 255, 0.35);
    }

    .liquidaciones-filter-panel {
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

    .liquidaciones-filter-group {
        display: flex;
        flex-direction: column;
        gap: 7px;
    }

    .liquidaciones-filter-group label {
        color: #d7e7f8;
        font-size: 13px;
        font-weight: 900;
        letter-spacing: 0.45px;
        text-transform: uppercase;
    }

    .liquidaciones-filter-group select {
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

    .liquidaciones-stats {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 18px;
    }

    .liquidacion-stat-card {
        border-radius: 24px;
        padding: 22px 22px 18px;
        background: rgba(255, 255, 255, 0.10);
        border: 1px solid rgba(255, 255, 255, 0.10);
        box-shadow: inset 0 1px 0 rgba(255,255,255,0.06);
        backdrop-filter: blur(8px);
        text-align: center;
    }

    .liquidacion-stat-label {
        display: block;
        color: #b9d7ff;
        font-size: 13px;
        font-weight: 900;
        letter-spacing: 0.8px;
        text-transform: uppercase;
        margin-bottom: 12px;
    }

    .liquidacion-stat-value {
        display: block;
        color: #ffffff;
        font-size: clamp(27px, 4vw, 40px);
        font-weight: 900;
        letter-spacing: -1px;
        line-height: 1;
    }

    .liquidacion-stat-value.highlight {
        color: #56c7ff;
    }

    .liquidaciones-table-panel {
        background: linear-gradient(180deg, rgba(246, 250, 255, 0.96) 0%, rgba(255,255,255,0.98) 100%);
        border-radius: 28px;
        padding: 22px;
        box-shadow: 0 14px 34px rgba(15, 23, 42, 0.07);
        border: 1px solid rgba(214, 227, 242, 0.8);
    }

    .liquidaciones-table-header {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 18px;
        text-align: center;
    }

    .liquidaciones-table-title {
        margin: 0;
        font-size: 28px;
        font-weight: 900;
        letter-spacing: -0.8px;
        color: #0f172a;
    }

    .liquidaciones-table-wrap {
        overflow-x: auto;
        border-radius: 22px;
    }

    .liquidaciones-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        background: #ffffff;
        overflow: hidden;
        border-radius: 22px;
        min-width: 1050px;
    }

    .liquidaciones-table thead th {
        background: #edf4fb;
        color: #0f172a;
        font-size: 14px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.45px;
        padding: 18px 16px;
        white-space: nowrap;
        text-align: left;
    }

    .liquidaciones-table tbody td {
        padding: 18px 16px;
        border-bottom: 1px solid #e9eef5;
        vertical-align: middle;
        color: #0f172a;
        font-size: 15px;
    }

    .liquidaciones-table tbody tr:last-child td {
        border-bottom: none;
    }

    .liquidacion-gestora {
        font-weight: 900;
        font-size: 16px;
    }

    .liquidacion-email {
        color: #475569;
        font-weight: 700;
    }

    .liquidacion-chip {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 88px;
        padding: 8px 14px;
        border-radius: 999px;
        background: #dfeafb;
        color: #1554c8;
        font-size: 13px;
        font-weight: 900;
        letter-spacing: 0.45px;
        text-transform: uppercase;
    }

    .liquidacion-money {
        font-weight: 900;
        color: #0f172a;
        white-space: nowrap;
    }

    .liquidacion-money.success {
        color: #07834f;
    }

    .liquidacion-total-row td {
        background: #f1f5f9;
        font-weight: 900;
        border-bottom: none;
    }

    .liquidacion-total-label {
        text-align: right;
        color: #0f172a;
        font-size: 16px;
    }

    .liquidaciones-empty {
        border-radius: 22px;
        padding: 22px;
        background: linear-gradient(135deg, #fff8df 0%, #fff2c4 100%);
        border: 1px solid #f0de9c;
        color: #755400;
        font-weight: 800;
        text-align: center;
    }

    @media (max-width: 1250px) {
        .liquidaciones-stats {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 760px) {
        .liquidaciones-hero {
            padding: 26px 18px 22px;
            border-radius: 24px;
        }

        .liquidaciones-stats {
            grid-template-columns: 1fr;
        }

        .liquidaciones-title {
            font-size: 34px;
        }

        .liquidaciones-table-panel {
            padding: 14px;
            border-radius: 22px;
        }

        .liquidaciones-table-title {
            font-size: 22px;
        }

        .liquidaciones-filter-panel {
            align-items: stretch;
        }

        .liquidaciones-filter-group,
        .liquidaciones-filter-group select,
        .liquidaciones-filter-panel .btn {
            width: 100%;
        }
    }
</style>

<div class="liquidaciones-shell">

    <section class="liquidaciones-hero">
        <div class="liquidaciones-hero-inner">

            <div class="liquidaciones-title-wrap">
                <h1 class="liquidaciones-title">Liquidaciones a gestoras</h1>
                <div class="liquidaciones-accent"></div>
            </div>

            <form method="GET" action="{{ route('liquidaciones.index') }}" class="liquidaciones-filter-panel">
                <div class="liquidaciones-filter-group">
                    <label for="mes">Mes</label>
                    <select name="mes" id="mes">
                        @foreach($meses as $numeroMes => $nombreMes)
                            <option value="{{ $numeroMes }}" {{ (int) $mes === (int) $numeroMes ? 'selected' : '' }}>
                                {{ $nombreMes }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="liquidaciones-filter-group">
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
                    Filtrar
                </button>
            </form>

            <div class="liquidaciones-stats">
                <div class="liquidacion-stat-card">
                    <span class="liquidacion-stat-label">Total a liquidar</span>
                    <span class="liquidacion-stat-value highlight">
                        {{ number_format($totalLiquidar, 2) }} €
                    </span>
                </div>

                <div class="liquidacion-stat-card">
                    <span class="liquidacion-stat-label">Servicios finalizados</span>
                    <span class="liquidacion-stat-value">
                        {{ $totalServicios }}
                    </span>
                </div>

                <div class="liquidacion-stat-card">
                    <span class="liquidacion-stat-label">Importe base</span>
                    <span class="liquidacion-stat-value">
                        {{ number_format($totalImporte, 2) }} €
                    </span>
                </div>

                <div class="liquidacion-stat-card">
                    <span class="liquidacion-stat-label">Gestoras activas</span>
                    <span class="liquidacion-stat-value">
                        {{ $gestorasConActividad }}
                    </span>
                </div>
            </div>

        </div>
    </section>

    <section class="liquidaciones-table-panel">
        <div class="liquidaciones-table-header">
            <h2 class="liquidaciones-table-title">
                Periodo: {{ $meses[(int) $mes] ?? $mes }} {{ $anyo }}
            </h2>
        </div>

        @if($liquidaciones->isEmpty())
            <div class="liquidaciones-empty">
                No hay liquidaciones registradas para este periodo.
            </div>
        @else
            <div class="liquidaciones-table-wrap">
                <table class="liquidaciones-table">
                    <thead>
                        <tr>
                            <th>Gestora</th>
                            <th>Email</th>
                            <th>Comisión pactada</th>
                            <th>Servicios finalizados</th>
                            <th>Importe total servicios</th>
                            <th>Total a liquidar</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($liquidaciones as $liquidacion)
                            <tr>
                                <td>
                                    <div class="liquidacion-gestora">
                                        {{ $liquidacion['gestora']->nombre }}
                                    </div>
                                </td>

                                <td>
                                    <div class="liquidacion-email">
                                        {{ $liquidacion['gestora']->email }}
                                    </div>
                                </td>

                                <td>
                                    <span class="liquidacion-chip">
                                        {{ number_format($liquidacion['gestora']->comision, 2) }}%
                                    </span>
                                </td>

                                <td>
                                    <strong>{{ $liquidacion['total_servicios'] }}</strong>
                                </td>

                                <td>
                                    <span class="liquidacion-money">
                                        {{ number_format($liquidacion['total_importe'], 2) }} €
                                    </span>
                                </td>

                                <td>
                                    <span class="liquidacion-money success">
                                        {{ number_format($liquidacion['total_comision'], 2) }} €
                                    </span>
                                </td>
                            </tr>
                        @endforeach

                        <tr class="liquidacion-total-row">
                            <td colspan="4"></td>
                            <td class="liquidacion-total-label">
                                Total a pagar
                            </td>
                            <td>
                                <span class="liquidacion-money success">
                                    {{ number_format($totalLiquidar, 2) }} €
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @endif
    </section>

</div>

@endsection