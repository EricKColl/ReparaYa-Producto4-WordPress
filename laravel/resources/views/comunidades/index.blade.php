@extends('layouts.app')

@section('title', 'Comunidades')

@section('content')

@php
    $totalComunidades = $comunidades->count();
    $totalGestoras = $comunidades->pluck('gestora_id')->filter()->unique()->count();
    $totalZonas = $comunidades->pluck('zona')->filter()->unique()->count();
    $totalConTelefono = $comunidades->filter(fn($comunidad) => !empty($comunidad->telefono_contacto))->count();
@endphp

<style>
    .comunidades-shell {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .comunidades-hero {
        position: relative;
        overflow: hidden;
        border-radius: 30px;
        padding: 38px 34px 30px;
        background:
            linear-gradient(135deg, rgba(8, 28, 56, 0.98) 0%, rgba(5, 20, 44, 0.97) 52%, rgba(17, 54, 99, 0.95) 100%);
        box-shadow: 0 20px 48px rgba(2, 6, 23, 0.18);
        border: 1px solid rgba(255, 255, 255, 0.08);
    }

    .comunidades-hero::before {
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

    .comunidades-hero-inner {
        position: relative;
        z-index: 1;
        display: flex;
        flex-direction: column;
        gap: 28px;
    }

    .comunidades-topbar {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 14px;
        flex-wrap: wrap;
    }

    .comunidades-title-wrap {
        text-align: center;
    }

    .comunidades-title {
        margin: 0;
        color: #ffffff;
        font-size: clamp(34px, 5vw, 58px);
        line-height: 1.04;
        letter-spacing: -1.8px;
        font-weight: 900;
    }

    .comunidades-accent {
        width: 140px;
        height: 6px;
        border-radius: 999px;
        margin: 16px auto 0;
        background: linear-gradient(90deg, #0f6fff 0%, #56c7ff 100%);
        box-shadow: 0 0 18px rgba(86, 199, 255, 0.35);
    }

    .comunidades-stats {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 18px;
    }

    .comunidad-stat-card {
        border-radius: 24px;
        padding: 22px 22px 18px;
        background: rgba(255, 255, 255, 0.10);
        border: 1px solid rgba(255, 255, 255, 0.10);
        box-shadow: inset 0 1px 0 rgba(255,255,255,0.06);
        backdrop-filter: blur(8px);
        text-align: center;
    }

    .comunidad-stat-label {
        display: block;
        color: #b9d7ff;
        font-size: 13px;
        font-weight: 900;
        letter-spacing: 0.8px;
        text-transform: uppercase;
        margin-bottom: 12px;
    }

    .comunidad-stat-value {
        display: block;
        color: #ffffff;
        font-size: clamp(27px, 4vw, 40px);
        font-weight: 900;
        letter-spacing: -1px;
        line-height: 1;
    }

    .comunidad-stat-value.highlight {
        color: #56c7ff;
    }

    .comunidades-table-panel {
        background: linear-gradient(180deg, rgba(246, 250, 255, 0.96) 0%, rgba(255,255,255,0.98) 100%);
        border-radius: 28px;
        padding: 22px;
        box-shadow: 0 14px 34px rgba(15, 23, 42, 0.07);
        border: 1px solid rgba(214, 227, 242, 0.8);
    }

    .comunidades-table-header {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 18px;
        text-align: center;
    }

    .comunidades-table-title {
        margin: 0;
        font-size: 28px;
        font-weight: 900;
        letter-spacing: -0.8px;
        color: #0f172a;
    }

    .comunidades-table-wrap {
        overflow-x: auto;
        border-radius: 22px;
    }

    .comunidades-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        background: #ffffff;
        overflow: hidden;
        border-radius: 22px;
        min-width: 1150px;
    }

    .comunidades-table thead th {
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

    .comunidades-table tbody td {
        padding: 18px 16px;
        border-bottom: 1px solid #e9eef5;
        vertical-align: middle;
        color: #0f172a;
        font-size: 15px;
    }

    .comunidades-table tbody tr:last-child td {
        border-bottom: none;
    }

    .comunidad-name {
        font-weight: 900;
        font-size: 16px;
        color: #0f172a;
    }

    .comunidad-id {
        display: block;
        margin-top: 5px;
        color: #64748b;
        font-size: 13px;
        font-weight: 700;
    }

    .comunidad-address,
    .comunidad-phone,
    .comunidad-manager {
        color: #475569;
        font-weight: 700;
    }

    .comunidad-zone {
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

    .comunidad-phone.empty {
        color: #94a3b8;
        font-style: italic;
    }

    .comunidades-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .comunidades-actions form {
        margin: 0;
    }

    .btn-compact {
        min-width: 110px;
    }

    .comunidades-empty {
        border-radius: 22px;
        padding: 22px;
        background: linear-gradient(135deg, #fff8df 0%, #fff2c4 100%);
        border: 1px solid #f0de9c;
        color: #755400;
        font-weight: 800;
        text-align: center;
    }

    @media (max-width: 1250px) {
        .comunidades-stats {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 760px) {
        .comunidades-hero {
            padding: 26px 18px 22px;
            border-radius: 24px;
        }

        .comunidades-stats {
            grid-template-columns: 1fr;
        }

        .comunidades-title {
            font-size: 34px;
        }

        .comunidades-table-panel {
            padding: 14px;
            border-radius: 22px;
        }

        .comunidades-table-title {
            font-size: 22px;
        }

        .comunidades-topbar {
            justify-content: center;
        }

        .comunidades-topbar .btn {
            width: 100%;
        }

        .btn-compact {
            min-width: 94px;
        }
    }
</style>

<div class="comunidades-shell">

    <section class="comunidades-hero">
        <div class="comunidades-hero-inner">

            <div class="comunidades-topbar">
                <a href="{{ route('comunidades.create') }}" class="btn btn-primary">
                    + Nueva comunidad
                </a>
            </div>

            <div class="comunidades-title-wrap">
                <h1 class="comunidades-title">Comunidades</h1>
                <div class="comunidades-accent"></div>
            </div>

            <div class="comunidades-stats">
                <div class="comunidad-stat-card">
                    <span class="comunidad-stat-label">Total comunidades</span>
                    <span class="comunidad-stat-value highlight">{{ $totalComunidades }}</span>
                </div>

                <div class="comunidad-stat-card">
                    <span class="comunidad-stat-label">Gestoras vinculadas</span>
                    <span class="comunidad-stat-value">{{ $totalGestoras }}</span>
                </div>

                <div class="comunidad-stat-card">
                    <span class="comunidad-stat-label">Zonas registradas</span>
                    <span class="comunidad-stat-value">{{ $totalZonas }}</span>
                </div>

                <div class="comunidad-stat-card">
                    <span class="comunidad-stat-label">Con teléfono</span>
                    <span class="comunidad-stat-value">{{ $totalConTelefono }}</span>
                </div>
            </div>

        </div>
    </section>

    <section class="comunidades-table-panel">
        <div class="comunidades-table-header">
            <h2 class="comunidades-table-title">Listado de comunidades</h2>
        </div>

        @if($comunidades->isEmpty())
            <div class="comunidades-empty">
                No hay comunidades registradas en este momento.
            </div>
        @else
            <div class="comunidades-table-wrap">
                <table class="comunidades-table">
                    <thead>
                        <tr>
                            <th>Comunidad</th>
                            <th>Dirección</th>
                            <th>Teléfono</th>
                            <th>Zona</th>
                            <th>Gestora</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($comunidades as $comunidad)
                            <tr>
                                <td>
                                    <div class="comunidad-name">
                                        {{ $comunidad->nombre }}
                                    </div>
                                    <span class="comunidad-id">
                                        ID #{{ $comunidad->id }}
                                    </span>
                                </td>

                                <td>
                                    <div class="comunidad-address">
                                        {{ $comunidad->direccion }}
                                    </div>
                                </td>

                                <td>
                                    @if(!empty($comunidad->telefono_contacto))
                                        <div class="comunidad-phone">
                                            {{ $comunidad->telefono_contacto }}
                                        </div>
                                    @else
                                        <div class="comunidad-phone empty">
                                            Sin teléfono
                                        </div>
                                    @endif
                                </td>

                                <td>
                                    <span class="comunidad-zone">
                                        {{ $comunidad->zona }}
                                    </span>
                                </td>

                                <td>
                                    <div class="comunidad-manager">
                                        {{ $comunidad->gestora->nombre ?? 'Sin gestora' }}
                                    </div>
                                </td>

                                <td>
                                    <div class="comunidades-actions">
                                        <a href="{{ route('comunidades.edit', $comunidad->id) }}" class="btn btn-warning btn-compact">
                                            Editar
                                        </a>

                                        <form
                                            action="{{ route('comunidades.destroy', $comunidad->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('¿Seguro que deseas eliminar esta comunidad?');"
                                        >
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