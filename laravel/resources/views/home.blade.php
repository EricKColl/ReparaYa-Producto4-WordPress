@extends('layouts.app')

@section('title', 'Inicio · ReparaYa')

@section('content')

@php
    $t = $dashboard['totales'] ?? [];
    $p = $dashboard['porcentajes'] ?? [];
    $panel = $homeData ?? ($panelUsuario ?? []);
    $metricas = $panel['metricas'] ?? [];
    $porcentajes = $panel['porcentajes'] ?? [];
    $proxima = $panel['proxima'] ?? null;
@endphp

<style>
    .home-shell {
        display: grid;
        gap: 30px;
    }

    .hero-wide {
        position: relative;
        overflow: hidden;
        border-radius: 34px;
        padding: 54px 36px 42px;
        background:
            radial-gradient(circle at 12% 16%, rgba(86, 199, 255, 0.16), transparent 25%),
            radial-gradient(circle at 88% 12%, rgba(214, 184, 109, 0.13), transparent 20%),
            radial-gradient(circle at 50% 110%, rgba(15, 111, 255, 0.18), transparent 38%),
            linear-gradient(135deg, #031121 0%, #041a33 52%, #08284b 100%);
        border: 1px solid rgba(255,255,255,0.11);
        box-shadow: 0 30px 70px rgba(2, 6, 23, 0.24);
    }

    .hero-wide::before {
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

    .hero-top {
        position: relative;
        z-index: 2;
        text-align: center;
        max-width: 1660px;
        margin: 0 auto;
    }

    .hero-title {
        margin: 0 auto 24px;
        max-width: 1580px;
        color: white;
        font-size: clamp(48px, 5.5vw, 92px);
        line-height: 0.96;
        letter-spacing: -3.2px;
        text-align: center;
    }

    .hero-title strong {
        background: linear-gradient(135deg, #ffffff 0%, #dcecff 25%, #7fd4ff 63%, #e7d49c 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .hero-subtitle {
        max-width: 1240px;
        margin: 0 auto;
        color: #d7e5f3;
        font-size: 21px;
        line-height: 1.78;
        text-align: center;
    }

    .hero-logo-integrated {
        position: relative;
        z-index: 2;
        display: grid;
        justify-items: center;
        margin: 34px auto 28px;
    }

    .hero-logo-wrap {
        position: relative;
        width: 220px;
        height: 220px;
        display: grid;
        place-items: center;
    }

    .hero-logo-wrap::before {
        content: "";
        position: absolute;
        inset: -12px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(15, 111, 255, 0.32), transparent 68%);
        filter: blur(14px);
    }

    .hero-logo-ring {
        position: absolute;
        inset: 0;
        border-radius: 50%;
        border: 1px solid rgba(86, 199, 255, 0.20);
        box-shadow: 0 0 42px rgba(86, 199, 255, 0.18);
    }

    .hero-logo-ring::before {
        content: "";
        position: absolute;
        inset: 18px;
        border-radius: 50%;
        border: 1px solid rgba(255,255,255,0.09);
    }

    .hero-logo-mark {
        position: relative;
        z-index: 1;
        width: 154px;
        height: 154px;
        border-radius: 32px;
        display: grid;
        place-items: center;
        color: white;
        font-size: 58px;
        font-weight: 900;
        letter-spacing: -3px;
        background: linear-gradient(135deg, #0f6fff, #56c7ff);
        box-shadow:
            0 24px 48px rgba(15, 111, 255, 0.34),
            inset 0 1px 0 rgba(255,255,255,0.16);
    }

    .hero-main-card {
        position: relative;
        z-index: 2;
        max-width: 1580px;
        margin: 0 auto;
        border-radius: 30px;
        background: rgba(255,255,255,0.085);
        border: 1px solid rgba(255,255,255,0.11);
        backdrop-filter: blur(12px);
        box-shadow: inset 0 1px 0 rgba(255,255,255,0.05);
        padding: 38px 32px 32px;
    }

    .hero-card-head {
        text-align: center;
        margin-bottom: 28px;
    }

    .hero-card-head h2 {
        margin: 0 0 16px;
        color: white;
        font-size: clamp(30px, 3vw, 46px);
        line-height: 1.08;
        letter-spacing: -1.3px;
    }

    .hero-card-head p {
        margin: 0 auto;
        max-width: 1160px;
        color: #d5e3f0;
        font-size: 18px;
        line-height: 1.82;
    }

    .hero-points {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 18px;
    }

    .hero-point {
        position: relative;
        overflow: hidden;
        padding: 24px 22px 22px;
        border-radius: 22px;
        background: rgba(255,255,255,0.07);
        border: 1px solid rgba(255,255,255,0.09);
        min-height: 160px;
    }

    .hero-point::before {
        content: "";
        position: absolute;
        top: -50px;
        right: -50px;
        width: 130px;
        height: 130px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(86,199,255,0.15), transparent 68%);
    }

    .hero-point strong {
        position: relative;
        z-index: 1;
        display: block;
        margin-bottom: 12px;
        color: white;
        font-size: 20px;
    }

    .hero-point span {
        position: relative;
        z-index: 1;
        display: block;
        color: #ccdae8;
        font-size: 15.5px;
        line-height: 1.72;
    }

    .hero-actions {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 14px;
        margin-top: 30px;
    }

    .hero-btn-secondary,
    .info-chip {
        background: rgba(255,255,255,0.10);
        color: white;
        border: 1px solid rgba(255,255,255,0.14);
    }

    .info-chip {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 12px 18px;
        border-radius: 999px;
        font-size: 15px;
        font-weight: 900;
    }

    .visitor-story {
        position: relative;
        z-index: 2;
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 18px;
        margin-top: 28px;
    }

    .visitor-story-card {
        padding: 24px 22px;
        border-radius: 24px;
        background: rgba(255,255,255,0.06);
        border: 1px solid rgba(255,255,255,0.09);
    }

    .visitor-story-card span {
        display: inline-flex;
        margin-bottom: 14px;
        color: #9adfff;
        font-size: 13px;
        font-weight: 900;
        letter-spacing: 0.55px;
        text-transform: uppercase;
    }

    .visitor-story-card h3 {
        margin: 0 0 10px;
        color: white;
        font-size: 22px;
        line-height: 1.14;
        letter-spacing: -0.6px;
    }

    .visitor-story-card p {
        margin: 0;
        color: #d3e2f0;
        font-size: 15.5px;
        line-height: 1.72;
    }

    .dashboard-zone,
    .role-zone {
        position: relative;
        overflow: hidden;
        border-radius: 34px;
        padding: 34px;
        background:
            radial-gradient(circle at 8% 12%, rgba(86,199,255,0.14), transparent 22%),
            radial-gradient(circle at 92% 10%, rgba(214,184,109,0.12), transparent 20%),
            linear-gradient(135deg, #041225 0%, #061a32 52%, #0b2748 100%);
        border: 1px solid rgba(255,255,255,0.12);
        box-shadow: 0 30px 70px rgba(2, 6, 23, 0.22);
    }

    .dashboard-zone::before,
    .role-zone::before {
        content: "";
        position: absolute;
        inset: 0;
        background-image:
            linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
        background-size: 38px 38px;
        pointer-events: none;
    }

    .section-copy-outside {
        margin: -8px auto -8px;
        max-width: 980px;
        text-align: center;
        color: #53687f;
        font-size: 18px;
        line-height: 1.75;
        font-weight: 700;
    }

    .section-head {
        position: relative;
        z-index: 2;
        margin-bottom: 28px;
        text-align: center;
    }

    .section-head h2 {
        margin: 0;
        color: white;
        font-size: 42px;
        letter-spacing: -1.3px;
        text-align: center;
    }

    .dashboard-grid {
        position: relative;
        z-index: 2;
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 18px;
    }

    .metric-card,
    .metric-card-light {
        padding: 22px;
        border-radius: 24px;
        background: rgba(255,255,255,0.10);
        border: 1px solid rgba(255,255,255,0.12);
        box-shadow: inset 0 1px 0 rgba(255,255,255,0.06);
    }

    .metric-card span,
    .metric-card-light span {
        display: block;
        color: #9fb5d1;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 900;
    }

    .metric-card strong,
    .metric-card-light strong {
        display: block;
        margin-top: 8px;
        color: white;
        font-size: 42px;
        line-height: 1;
        letter-spacing: -1.5px;
    }

    .metric-card small,
    .metric-card-light small {
        display: block;
        margin-top: 10px;
        color: #d5e2ef;
        font-size: 14px;
        line-height: 1.55;
    }

    .analytics-layout {
        position: relative;
        z-index: 2;
        display: grid;
        grid-template-columns: 0.88fr 1.12fr;
        gap: 18px;
        margin-top: 18px;
    }

    .chart-panel {
        padding: 26px;
        border-radius: 26px;
        background: rgba(255,255,255,0.10);
        border: 1px solid rgba(255,255,255,0.12);
        box-shadow: inset 0 1px 0 rgba(255,255,255,0.06);
    }

    .chart-panel h3 {
        margin: 0 0 20px;
        color: white;
        font-size: 24px;
        letter-spacing: -0.7px;
    }

    .donut-row {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 16px;
    }

    .donut-card {
        display: grid;
        justify-items: center;
        gap: 12px;
        padding: 18px;
        border-radius: 22px;
        background: rgba(255,255,255,0.07);
        border: 1px solid rgba(255,255,255,0.10);
        text-align: center;
    }

    .donut {
        width: 122px;
        height: 122px;
        border-radius: 50%;
        display: grid;
        place-items: center;
        background:
            radial-gradient(circle at center, #061a32 0 54%, transparent 55%),
            conic-gradient(#56c7ff calc(var(--value) * 1%), rgba(255,255,255,0.12) 0);
        box-shadow: 0 18px 36px rgba(0,0,0,0.18);
    }

    .donut.gold {
        background:
            radial-gradient(circle at center, #061a32 0 54%, transparent 55%),
            conic-gradient(#d6b86d calc(var(--value) * 1%), rgba(255,255,255,0.12) 0);
    }

    .donut.green {
        background:
            radial-gradient(circle at center, #061a32 0 54%, transparent 55%),
            conic-gradient(#19b36b calc(var(--value) * 1%), rgba(255,255,255,0.12) 0);
    }

    .donut strong {
        color: white;
        font-size: 25px;
        letter-spacing: -0.7px;
    }

    .donut-card span {
        color: #d5e2ef;
        font-size: 14px;
        font-weight: 800;
    }

    .bar-list {
        display: grid;
        gap: 16px;
    }

    .bar-item {
        display: grid;
        gap: 8px;
    }

    .bar-top {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        color: #dce8f6;
        font-weight: 800;
        font-size: 14px;
    }

    .bar-track {
        height: 12px;
        border-radius: 999px;
        background: rgba(255,255,255,0.12);
        overflow: hidden;
    }

    .bar-fill {
        width: calc(var(--value) * 1%);
        height: 100%;
        border-radius: inherit;
        background: linear-gradient(90deg, #0f6fff, #56c7ff);
        box-shadow: 0 0 18px rgba(86,199,255,0.30);
    }

    .bar-fill.gold {
        background: linear-gradient(90deg, #c7972d, #f1e2b1);
    }

    .bar-fill.green {
        background: linear-gradient(90deg, #108f58, #55e6a2);
    }

    .bar-fill.red {
        background: linear-gradient(90deg, #d53f4c, #ff7b8a);
    }

    .role-zone {
        background:
            radial-gradient(circle at 8% 12%, rgba(15,111,255,0.10), transparent 22%),
            radial-gradient(circle at 90% 8%, rgba(86,199,255,0.10), transparent 20%),
            linear-gradient(135deg, #ffffff 0%, #f7fbff 100%);
        border: 1px solid rgba(15,23,42,0.08);
        box-shadow: 0 22px 46px rgba(15,23,42,0.08);
    }

    .role-zone::before {
        opacity: 0.3;
        background-image:
            linear-gradient(rgba(15,23,42,0.035) 1px, transparent 1px),
            linear-gradient(90deg, rgba(15,23,42,0.035) 1px, transparent 1px);
    }

    .role-zone .section-head h2 {
        color: #0f172a;
    }

    .role-summary-layout {
        position: relative;
        z-index: 2;
        display: grid;
        grid-template-columns: 1.15fr 0.85fr;
        gap: 20px;
        align-items: stretch;
    }

    .metric-grid-light {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 16px;
    }

    .metric-card-light {
        background: white;
        border: 1px solid rgba(15,23,42,0.08);
        box-shadow: 0 16px 34px rgba(15,23,42,0.08);
    }

    .metric-card-light span {
        color: #64748b;
    }

    .metric-card-light strong {
        color: #0f172a;
    }

    .metric-card-light small {
        color: #64748b;
    }

    .next-card {
        position: relative;
        overflow: hidden;
        padding: 28px;
        border-radius: 26px;
        background:
            radial-gradient(circle at 100% 0%, rgba(86,199,255,0.18), transparent 30%),
            linear-gradient(135deg, #061a32 0%, #09294d 100%);
        border: 1px solid rgba(255,255,255,0.12);
        color: white;
        box-shadow: 0 18px 34px rgba(15,23,42,0.12);
    }

    .next-card h3 {
        margin: 0 0 18px;
        font-size: 28px;
        letter-spacing: -0.8px;
        text-align: center;
    }

    .next-data {
        display: grid;
        gap: 12px;
        margin-top: 18px;
    }

    .next-data div {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        padding: 11px 0;
        border-bottom: 1px solid rgba(255,255,255,0.10);
    }

    .next-data span {
        color: #9fb5d1;
        font-weight: 800;
    }

    .next-data strong {
        text-align: right;
        color: white;
    }

    .mini-chart-panel {
        margin-top: 20px;
        padding: 22px;
        border-radius: 24px;
        background: rgba(255,255,255,0.85);
        border: 1px solid rgba(15,23,42,0.08);
    }

    .mini-chart-panel h3 {
        margin: 0 0 18px;
        color: #0f172a;
        text-align: center;
        font-size: 24px;
        letter-spacing: -0.7px;
    }

    .mini-chart-panel .bar-top {
        color: #0f172a;
    }

    .mini-chart-panel .bar-track {
        background: #e5edf6;
    }

    @media (max-width: 1450px) {
        .dashboard-grid,
        .metric-grid-light {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .analytics-layout,
        .role-summary-layout {
            grid-template-columns: 1fr;
        }

        .visitor-story {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 1200px) {
        .hero-points {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 900px) {
        .donut-row,
        .dashboard-grid,
        .metric-grid-light {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 760px) {
        .hero-wide,
        .dashboard-zone,
        .role-zone {
            padding: 24px 16px;
            border-radius: 24px;
        }

        .hero-title {
            font-size: clamp(40px, 12vw, 58px);
            line-height: 1;
            letter-spacing: -2px;
        }

        .hero-subtitle {
            font-size: 17px;
        }

        .hero-logo-wrap {
            width: 190px;
            height: 190px;
        }

        .hero-logo-mark {
            width: 132px;
            height: 132px;
            font-size: 48px;
        }

        .hero-main-card {
            padding: 22px 16px;
        }

        .hero-actions .btn,
        .hero-actions .info-chip {
            width: 100%;
        }
    }
</style>

<div class="home-shell">

    <section class="hero-wide">
        <div class="hero-top">
            <h1 class="hero-title">
                <strong>{{ $panel['titulo'] ?? 'ReparaYa' }}</strong>
            </h1>

            <p class="hero-subtitle">
                {{ $panel['subtitulo'] ?? 'Entorno de gestión de reparaciones.' }}
            </p>
        </div>

        <div class="hero-logo-integrated">
            <div class="hero-logo-wrap">
                <div class="hero-logo-ring"></div>
                <div class="hero-logo-mark">RY</div>
            </div>
        </div>

        <div class="hero-main-card">
            <div class="hero-card-head">
                @if($tipoInicio === 'visitante')
                    <h2>Una plataforma pensada para que cada reparación avance con orden, velocidad y confianza</h2>
                    <p>
                        Desde el primer aviso hasta el cierre de la intervención, todo queda localizado, asignado y preparado para actuar.
                        Menos improvisación, menos llamadas innecesarias y una gestión que no solo funciona: también transmite calidad.
                    </p>
                @elseif($tipoInicio === 'admin')
                    <h2>Control total sin perder claridad</h2>
                    <p>
                        Toda la operativa del sistema concentrada en una vista útil: actividad, usuarios, técnicos, incidencias y estado del servicio.
                    </p>
                @elseif($tipoInicio === 'tecnico')
                    <h2>Todo lo importante delante, nada administrativo de más</h2>
                    <p>
                        Una zona de trabajo pensada para actuar rápido, consultar servicios asignados y mantener cada intervención bajo control.
                    </p>
                @else
                    <h2>Tus avisos, claros desde el primer clic</h2>
                    <p>
                        Crea solicitudes, revisa el estado de tus reparaciones y consulta la evolución de cada aviso sin perder tiempo.
                    </p>
                @endif
            </div>

            <div class="hero-points">
                @if($tipoInicio === 'visitante')
                    <div class="hero-point">
                        <strong>Orden que se nota</strong>
                        <span>Cada solicitud queda registrada con contexto, trazabilidad y estructura. Nada de “¿quién llevaba esto?” justo cuando más prisa hay.</span>
                    </div>

                    <div class="hero-point">
                        <strong>Rapidez con criterio</strong>
                        <span>La información importante aparece donde toca para asignar mejor, decidir antes y reducir errores de coordinación.</span>
                    </div>

                    <div class="hero-point">
                        <strong>Confianza desde el primer clic</strong>
                        <span>Una plataforma cuidada comunica profesionalidad antes incluso de resolver la incidencia. La primera impresión también repara.</span>
                    </div>
                @elseif($tipoInicio === 'admin')
                    <div class="hero-point">
                        <strong>Visión global</strong>
                        <span>Controla usuarios, técnicos e incidencias desde una lectura rápida y ordenada.</span>
                    </div>

                    <div class="hero-point">
                        <strong>Decisión rápida</strong>
                        <span>Detecta actividad abierta, carga pendiente y capacidad técnica disponible.</span>
                    </div>

                    <div class="hero-point">
                        <strong>Gestión con criterio</strong>
                        <span>Menos intuición a ciegas y más datos para mantener el servicio en marcha.</span>
                    </div>
                @elseif($tipoInicio === 'tecnico')
                    <div class="hero-point">
                        <strong>Trabajo asignado</strong>
                        <span>Consulta únicamente las intervenciones vinculadas a tu ficha técnica.</span>
                    </div>

                    <div class="hero-point">
                        <strong>Prioridad clara</strong>
                        <span>Identifica servicios en curso, vencidos o pendientes sin navegar por apartados innecesarios.</span>
                    </div>

                    <div class="hero-point">
                        <strong>Actuación enfocada</strong>
                        <span>La información clave queda organizada para que puedas trabajar mejor desde el primer vistazo.</span>
                    </div>
                @else
                    <div class="hero-point">
                        <strong>Crear aviso</strong>
                        <span>Registra una nueva solicitud cuando necesites comunicar una reparación.</span>
                    </div>

                    <div class="hero-point">
                        <strong>Seguir estado</strong>
                        <span>Consulta si tu incidencia está pendiente, asignada, finalizada o cancelada.</span>
                    </div>

                    <div class="hero-point">
                        <strong>Más tranquilidad</strong>
                        <span>La información está ordenada para que no tengas que perseguir actualizaciones.</span>
                    </div>
                @endif
            </div>

            <div class="hero-actions">
                <a href="{{ $panel['url_principal'] ?? route('home') }}" class="btn btn-primary">
                    {{ $panel['accion_principal'] ?? 'Ir al inicio' }}
                </a>

                @if($tipoInicio === 'admin' && !empty($panel['secundaria']))
                    <a href="{{ $panel['url_secundaria'] }}" class="btn hero-btn-secondary">
                        {{ $panel['secundaria'] }}
                    </a>
                @elseif($tipoInicio === 'particular' && !empty($panel['secundaria']))
                    <a href="{{ $panel['url_secundaria'] }}" class="btn hero-btn-secondary">
                        {{ $panel['secundaria'] }}
                    </a>
                @elseif($tipoInicio === 'tecnico' && !empty($panel['secundaria']))
                    <span class="info-chip">
                        {{ $panel['secundaria'] }}
                    </span>
                @endif
            </div>
        </div>

        @if($tipoInicio === 'visitante')
            <div class="visitor-story">
                <div class="visitor-story-card">
                    <span>01 · Captura</span>
                    <h3>El aviso deja de ser una nota suelta</h3>
                    <p>La incidencia entra al sistema con datos útiles, estado definido y una estructura preparada para seguirla sin perder el hilo.</p>
                </div>

                <div class="visitor-story-card">
                    <span>02 · Coordinación</span>
                    <h3>El técnico adecuado llega antes</h3>
                    <p>Especialidad, disponibilidad y servicio se conectan para que la asignación tenga sentido y no dependa de la memoria del día.</p>
                </div>

                <div class="visitor-story-card">
                    <span>03 · Seguimiento</span>
                    <h3>La gestión transmite solvencia</h3>
                    <p>Cuando la información está ordenada, el equipo trabaja mejor y el cliente percibe una empresa más seria desde el primer contacto.</p>
                </div>
            </div>
        @endif
    </section>

    @if($tipoInicio === 'admin')
        <section class="dashboard-zone">
            <div class="section-head">
                <h2>Operativa en tiempo real</h2>
            </div>

            <div class="dashboard-grid">
                <div class="metric-card">
                    <span>Usuarios registrados</span>
                    <strong>{{ $t['usuarios'] ?? 0 }}</strong>
                    <small>{{ $t['particulares'] ?? 0 }} clientes · {{ $t['usuarios_tecnicos'] ?? 0 }} usuarios técnicos · {{ $t['admins'] ?? 0 }} administradores</small>
                </div>

                <div class="metric-card">
                    <span>Técnicos</span>
                    <strong>{{ $t['tecnicos'] ?? 0 }}</strong>
                    <small>{{ $t['tecnicos_disponibles'] ?? 0 }} disponibles · {{ $p['tecnicos_disponibles'] ?? 0 }}% de capacidad activa</small>
                </div>

                <div class="metric-card">
                    <span>Incidencias totales</span>
                    <strong>{{ $t['incidencias'] ?? 0 }}</strong>
                    <small>{{ $t['incidencias_abiertas'] ?? 0 }} abiertas · {{ $t['finalizadas'] ?? 0 }} finalizadas</small>
                </div>

                <div class="metric-card">
                    <span>Especialidades</span>
                    <strong>{{ $t['especialidades'] ?? 0 }}</strong>
                    <small>Catálogo operativo disponible para clasificar y asignar servicios.</small>
                </div>
            </div>

            <div class="analytics-layout">
                <div class="chart-panel">
                    <h3>Indicadores clave</h3>

                    <div class="donut-row">
                        <div class="donut-card">
                            <div class="donut green" style="--value: {{ $p['resolucion'] ?? 0 }};">
                                <strong>{{ $p['resolucion'] ?? 0 }}%</strong>
                            </div>
                            <span>Resolución</span>
                        </div>

                        <div class="donut-card">
                            <div class="donut" style="--value: {{ $p['actividad_abierta'] ?? 0 }};">
                                <strong>{{ $p['actividad_abierta'] ?? 0 }}%</strong>
                            </div>
                            <span>Actividad abierta</span>
                        </div>

                        <div class="donut-card">
                            <div class="donut gold" style="--value: {{ $p['urgentes'] ?? 0 }};">
                                <strong>{{ $p['urgentes'] ?? 0 }}%</strong>
                            </div>
                            <span>Urgentes</span>
                        </div>
                    </div>
                </div>

                <div class="chart-panel">
                    <h3>Estado de incidencias</h3>

                    <div class="bar-list">
                        <div class="bar-item">
                            <div class="bar-top">
                                <span>Pendientes</span>
                                <span>{{ $t['pendientes'] ?? 0 }} · {{ $p['pendientes'] ?? 0 }}%</span>
                            </div>
                            <div class="bar-track">
                                <div class="bar-fill gold" style="--value: {{ $p['pendientes'] ?? 0 }};"></div>
                            </div>
                        </div>

                        <div class="bar-item">
                            <div class="bar-top">
                                <span>Asignadas</span>
                                <span>{{ $t['asignadas'] ?? 0 }} · {{ $p['asignadas'] ?? 0 }}%</span>
                            </div>
                            <div class="bar-track">
                                <div class="bar-fill" style="--value: {{ $p['asignadas'] ?? 0 }};"></div>
                            </div>
                        </div>

                        <div class="bar-item">
                            <div class="bar-top">
                                <span>Finalizadas</span>
                                <span>{{ $t['finalizadas'] ?? 0 }} · {{ $p['finalizadas'] ?? 0 }}%</span>
                            </div>
                            <div class="bar-track">
                                <div class="bar-fill green" style="--value: {{ $p['finalizadas'] ?? 0 }};"></div>
                            </div>
                        </div>

                        <div class="bar-item">
                            <div class="bar-top">
                                <span>Canceladas</span>
                                <span>{{ $t['canceladas'] ?? 0 }} · {{ $p['canceladas'] ?? 0 }}%</span>
                            </div>
                            <div class="bar-track">
                                <div class="bar-fill red" style="--value: {{ $p['canceladas'] ?? 0 }};"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @elseif($tipoInicio === 'particular')
        <div class="section-copy-outside">
            Consulta de un vistazo cómo se encuentran tus reparaciones y qué actividad sigue abierta.
        </div>

        <section class="role-zone">
            <div class="section-head">
                <h2>Resumen de tus avisos</h2>
            </div>

            <div class="role-summary-layout">
                <div>
                    <div class="metric-grid-light">
                        @foreach($metricas as $nombre => $valor)
                            <div class="metric-card-light">
                                <span>{{ $nombre }}</span>
                                <strong>{{ $valor }}</strong>
                                <small>Dato vinculado a tus solicitudes.</small>
                            </div>
                        @endforeach
                    </div>

                    <div class="mini-chart-panel">
                        <h3>Lectura porcentual</h3>

                        <div class="bar-list">
                            @foreach($porcentajes as $nombre => $valor)
                                <div class="bar-item">
                                    <div class="bar-top">
                                        <span>{{ $nombre }}</span>
                                        <span>{{ $valor }}%</span>
                                    </div>
                                    <div class="bar-track">
                                        <div class="bar-fill {{ $loop->first ? 'green' : '' }}" style="--value: {{ $valor }};"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                                <div>
                    <div class="next-card">
                        @if($proxima)
                            @php
                                $fechaProxima = \Carbon\Carbon::parse($proxima->fecha_servicio);
                            @endphp

                            <h3>Próximo aviso en seguimiento</h3>

                            <div class="next-data">
                                <div>
                                    <span>Localizador</span>
                                    <strong>{{ $proxima->localizador }}</strong>
                                </div>

                                <div>
                                    <span>Estado</span>
                                    <strong>{{ $proxima->estado }}</strong>
                                </div>

                                <div>
                                    <span>Especialidad</span>
                                    <strong>{{ $proxima->especialidad->nombre_especialidad ?? 'Sin especialidad' }}</strong>
                                </div>

                                <div>
                                    <span>Técnico</span>
                                    <strong>{{ $proxima->tecnico->nombre_completo ?? 'Pendiente de asignación' }}</strong>
                                </div>

                                <div>
                                    <span>Fecha</span>
                                    <strong>{{ $fechaProxima->format('d/m/Y H:i') }}</strong>
                                </div>
                            </div>

                            <div class="hero-actions" style="margin-top: 22px;">
                                <a href="{{ url('/incidencias') }}" class="btn btn-primary">
                                    Ver detalle
                                </a>
                            </div>
                        @else
                            <h3>Próximo aviso en seguimiento</h3>

                            <div class="next-data">
                                <div>
                                    <span>Estado</span>
                                    <strong>Sin avisos activos</strong>
                                </div>
                            </div>

                            <div class="hero-actions" style="margin-top: 22px;">
                                <a href="{{ url('/incidencias/create') }}" class="btn btn-primary">
                                    Crear incidencia
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    @elseif($tipoInicio === 'tecnico')
        <section class="role-zone">
            <div class="section-head">
                <h2>Resumen de tu actividad técnica</h2>
            </div>

            <div class="role-summary-layout">
                <div>
                    <div class="metric-grid-light">
                        @foreach($metricas as $nombre => $valor)
                            <div class="metric-card-light">
                                <span>{{ $nombre }}</span>
                                <strong>{{ $valor }}</strong>
                                <small>Dato vinculado a tus servicios asignados.</small>
                            </div>
                        @endforeach
                    </div>

                    <div class="mini-chart-panel">
                        <h3>Lectura porcentual</h3>

                        <div class="bar-list">
                            @foreach($porcentajes as $nombre => $valor)
                                <div class="bar-item">
                                    <div class="bar-top">
                                        <span>{{ $nombre }}</span>
                                        <span>{{ $valor }}%</span>
                                    </div>
                                    <div class="bar-track">
                                        <div class="bar-fill {{ $loop->first ? 'green' : '' }}" style="--value: {{ $valor }};"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="next-card">
                    @if($proxima)
                        @php
                            $fechaProxima = \Carbon\Carbon::parse($proxima->fecha_servicio);
                        @endphp

                        <h3>Próximo servicio asignado</h3>

                        <div class="next-data">
                            <div>
                                <span>Localizador</span>
                                <strong>{{ $proxima->localizador }}</strong>
                            </div>

                            <div>
                                <span>Estado</span>
                                <strong>{{ $proxima->estado }}</strong>
                            </div>

                            <div>
                                <span>Especialidad</span>
                                <strong>{{ $proxima->especialidad->nombre_especialidad ?? 'Sin especialidad' }}</strong>
                            </div>

                            <div>
                                <span>Cliente</span>
                                <strong>{{ $proxima->cliente->nombre ?? 'Sin cliente' }}</strong>
                            </div>

                            <div>
                                <span>Fecha</span>
                                <strong>{{ $fechaProxima->format('d/m/Y H:i') }}</strong>
                            </div>
                        </div>

                        <div class="hero-actions" style="margin-top: 22px;">
                            <a href="{{ url('/incidencias') }}" class="btn btn-primary">
                                Ver detalle
                            </a>
                        </div>
                    @else
                        <h3>Próximo servicio asignado</h3>

                        <div class="next-data">
                            <div>
                                <span>Estado</span>
                                <strong>Sin servicios activos</strong>
                            </div>
                        </div>

                        <div class="hero-actions" style="margin-top: 22px;">
                            <a href="{{ url('/incidencias') }}" class="btn btn-primary">
                                Ver histórico
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </section>
       @endif

    @if($tipoInicio !== 'visitante' && !empty($calendarData['calendarTitle']))
        @include('components.reparaya-calendar', [
            'calendarTitle' => $calendarData['calendarTitle'],
            'calendarSubtitle' => $calendarData['calendarSubtitle'] ?? null,
            'calendarEvents' => $calendarData['calendarEvents'] ?? [],
        ])
    @endif

</div>

@endsection