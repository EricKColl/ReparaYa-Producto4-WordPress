<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ReparaYa')</title>

    <style>
        :root {
            --bg-page: #eef3f9;
            --bg-panel: rgba(255, 255, 255, 0.98);
            --text-main: #0f172a;
            --text-soft: #64748b;

            --blue-1: #0f6fff;
            --blue-2: #56c7ff;
            --blue-3: #061a32;

            --danger: #dc3545;
            --warning: #f1b90b;
            --success: #19b36b;

            --shadow-panel: 0 18px 42px rgba(2, 6, 23, 0.10);
            --shadow-soft: 0 10px 24px rgba(15, 23, 42, 0.07);

            --radius-xl: 34px;
            --radius-lg: 24px;
            --radius-md: 18px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            color: var(--text-main);
            background:
                radial-gradient(circle at top left, rgba(15, 111, 255, 0.10), transparent 18%),
                linear-gradient(180deg, #f7faff 0%, #eef3f9 100%);
        }

        a {
            text-decoration: none;
        }

        header {
            position: sticky;
            top: 0;
            z-index: 50;
            background: linear-gradient(90deg, #020817 0%, #04142d 45%, #07192f 100%);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 10px 24px rgba(2, 6, 23, 0.14);
        }

        .topbar {
            width: min(1800px, calc(100% - 28px));
            margin: 0 auto;
            min-height: 92px;
            display: grid;
            grid-template-columns: minmax(240px, 1fr) auto minmax(240px, 1fr);
            align-items: center;
            gap: 18px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 14px;
            min-width: 0;
        }

        .brand-link {
            width: fit-content;
            text-decoration: none;
            border-radius: 22px;
            transition: transform 0.14s ease, opacity 0.14s ease, filter 0.14s ease;
        }

        .brand-link:hover {
            transform: translateY(-1px);
            opacity: 0.96;
            filter: drop-shadow(0 10px 18px rgba(15, 111, 255, 0.16));
        }

        .brand-mark {
            width: 54px;
            height: 54px;
            border-radius: 18px;
            display: grid;
            place-items: center;
            background: linear-gradient(135deg, var(--blue-1), var(--blue-2));
            color: white;
            font-size: 18px;
            font-weight: 900;
            letter-spacing: -1px;
            box-shadow: 0 10px 20px rgba(15, 111, 255, 0.22);
            flex: 0 0 54px;
        }

        .brand-title {
            color: white;
            font-size: 31px;
            font-weight: 900;
            letter-spacing: -1.2px;
            line-height: 1;
            white-space: nowrap;
        }

        .topbar-nav {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            min-width: 0;
            width: fit-content;
            max-width: 100%;
            justify-self: center;
            flex-wrap: wrap;
        }

        .topbar-nav a,
        .session-actions a {
            color: rgba(255, 255, 255, 0.92);
            font-weight: 800;
            padding: 13px 18px;
            border-radius: 999px;
            transition: background 0.14s ease, color 0.14s ease, transform 0.14s ease;
            white-space: nowrap;
        }

        .topbar-nav a:hover,
        .topbar-nav a.nav-pill,
        .session-actions a:hover,
        .session-actions a.nav-pill {
            background: rgba(255, 255, 255, 0.08);
            color: white;
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.14);
        }

        .session-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 12px;
            min-width: 0;
            justify-self: end;
        }

        .session-name {
            color: rgba(255, 255, 255, 0.92);
            font-size: 14px;
            font-weight: 900;
            white-space: nowrap;
        }

        .logout-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 16px;
            border-radius: 999px;
            background: rgba(220, 53, 69, 0.20);
            color: white;
            font-weight: 800;
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.12);
        }

        main {
            width: min(1800px, calc(100% - 28px));
            margin: 28px auto 24px;
            background: var(--bg-panel);
            border-radius: var(--radius-xl);
            padding: 28px;
            box-shadow: var(--shadow-panel);
            border: 1px solid rgba(255, 255, 255, 0.70);
        }

        footer {
            text-align: center;
            padding: 18px 20px 30px;
            color: #5c6f87;
            font-size: 18px;
            font-weight: 800;
            letter-spacing: -0.4px;
        }

        .flash-message {
            margin-bottom: 22px;
            padding: 16px 18px;
            border-radius: 18px;
            font-weight: 800;
            line-height: 1.55;
            box-shadow: var(--shadow-soft);
        }

        .flash-success {
            background: #e8fff3;
            color: #0b6b3f;
            border: 1px solid rgba(25, 179, 107, 0.22);
        }

        .flash-error {
            background: #fff0f2;
            color: #9f1d2e;
            border: 1px solid rgba(220, 53, 69, 0.22);
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            gap: 16px;
            flex-wrap: wrap;
        }

        .page-header h1 {
            margin: 0;
            font-size: 36px;
            letter-spacing: -1px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 18px;
            border-radius: 999px;
            border: none;
            text-decoration: none;
            font-size: 15px;
            cursor: pointer;
            font-weight: 800;
            transition: transform 0.12s ease, box-shadow 0.12s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--blue-1), var(--blue-2));
            color: white;
            box-shadow: 0 10px 20px rgba(15, 111, 255, 0.18);
        }

        .btn-warning {
            background: linear-gradient(135deg, #ffd14d, #f1b90b);
            color: #1e293b;
        }

        .btn-danger {
            background: linear-gradient(135deg, #e14a59, #c52134);
            color: white;
        }

        .table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 18px;
            overflow: hidden;
            border-radius: 20px;
            background: white;
            box-shadow: var(--shadow-soft);
        }

        .table th,
        .table td {
            border-bottom: 1px solid #e8edf4;
            padding: 16px 14px;
            text-align: left;
            vertical-align: middle;
        }

        .table th {
            background: #edf4fb;
            color: #0f172a;
            font-size: 14px;
        }

        .actions {
            display: flex;
            gap: 8px;
            align-items: center;
            flex-wrap: wrap;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            font-weight: 800;
            margin-bottom: 8px;
            color: #0f172a;
        }

        .form-control {
            width: 100%;
            padding: 13px 14px;
            border: 1px solid #d7e0ea;
            border-radius: 14px;
            background: #ffffff;
            font-size: 15px;
            color: #0f172a;
            transition: border-color 0.14s ease, box-shadow 0.14s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: rgba(15, 111, 255, 0.45);
            box-shadow: 0 0 0 4px rgba(15, 111, 255, 0.10);
        }

        .alert-empty {
            background: #fff7dd;
            padding: 14px 16px;
            border-radius: 14px;
            color: #715400;
            border: 1px solid #f3e1a4;
            font-weight: 700;
        }

        .tag {
            display: inline-flex;
            align-items: center;
            padding: 8px 14px;
            border-radius: 999px;
            background: #dfeafb;
            color: #1554c8;
            font-size: 13px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.45px;
        }

        .tag-gold {
            background: #efe3c8;
            color: #9b6b05;
        }

        @media (max-width: 1480px) {
            .topbar {
                grid-template-columns: minmax(220px, 1fr) auto minmax(220px, 1fr);
                gap: 14px;
            }

            .topbar-nav {
                gap: 8px;
            }

            .topbar-nav a,
            .session-actions a {
                padding: 12px 14px;
                font-size: 14px;
            }
        }

        @media (max-width: 1180px) {
            .topbar {
                grid-template-columns: 1fr auto;
                padding: 14px 0;
                align-items: center;
            }

            .topbar-nav {
                grid-column: 1 / -1;
                grid-row: 2;
                justify-content: center;
                width: 100%;
                padding-top: 4px;
            }

            .session-actions {
                grid-column: 2;
                grid-row: 1;
            }
        }

        @media (max-width: 760px) {
            .topbar {
                width: min(100% - 16px, 1800px);
                grid-template-columns: 1fr;
                gap: 14px;
            }

            .brand-title {
                font-size: 24px;
            }

            .session-actions {
                grid-column: 1;
                grid-row: auto;
                justify-content: flex-start;
                flex-wrap: wrap;
            }

            .topbar-nav {
                grid-column: 1;
                justify-content: flex-start;
                flex-wrap: nowrap;
                overflow-x: auto;
                padding-bottom: 4px;
                scrollbar-width: none;
            }

            .topbar-nav::-webkit-scrollbar {
                display: none;
            }

            main {
                width: min(100% - 16px, 1800px);
                margin-top: 16px;
                padding: 16px;
                border-radius: 24px;
            }

            .page-header h1 {
                font-size: 28px;
            }

            .table {
                display: block;
                overflow-x: auto;
            }
        }
    </style>
</head>

<body>

@php
    $usuarioAutenticado = session()->has('usuario_id');
    $gestoraAutenticada = session()->has('gestora_id');

    $rolSesion = session('usuario_rol');
    $nombreUsuario = session('usuario_nombre');
    $nombreGestora = session('gestora_nombre') ?? session('gestora_email') ?? 'Gestora';
@endphp

<header>
    <div class="topbar">

        <a href="{{ route('home') }}" class="brand brand-link" aria-label="Ir al inicio de ReparaYa">
            <div class="brand-mark">RY</div>
            <div class="brand-title">ReparaYa</div>
        </a>

        <nav class="topbar-nav" aria-label="Navegación principal">

            @if($usuarioAutenticado && $rolSesion === 'admin')

                <a href="{{ url('/usuarios') }}" class="{{ request()->routeIs('usuarios.*') ? 'nav-pill' : '' }}">
                    Usuarios
                </a>

                <a href="{{ url('/tecnicos') }}" class="{{ request()->routeIs('tecnicos.*') ? 'nav-pill' : '' }}">
                    Técnicos
                </a>

                <a href="{{ url('/especialidades') }}" class="{{ request()->routeIs('especialidades.*') ? 'nav-pill' : '' }}">
                    Especialidades
                </a>

                <a href="{{ url('/incidencias') }}" class="{{ request()->routeIs('incidencias.*') ? 'nav-pill' : '' }}">
                    Incidencias
                </a>

                <a href="{{ url('/gestoras') }}" class="{{ request()->routeIs('gestoras.*') ? 'nav-pill' : '' }}">
                    Gestoras
                </a>

                <a href="{{ url('/comunidades') }}" class="{{ request()->routeIs('comunidades.*') ? 'nav-pill' : '' }}">
                    Comunidades
                </a>

                <a href="{{ url('/liquidaciones') }}" class="{{ request()->routeIs('liquidaciones.*') ? 'nav-pill' : '' }}">
                    Liquidaciones
                </a>

            @elseif($usuarioAutenticado && $rolSesion === 'particular')

                <a href="{{ url('/incidencias') }}" class="{{ request()->routeIs('incidencias.index') ? 'nav-pill' : '' }}">
                    Mis incidencias
                </a>

                <a href="{{ url('/incidencias/create') }}" class="{{ request()->routeIs('incidencias.create') ? 'nav-pill' : '' }}">
                    Nueva incidencia
                </a>

            @elseif($usuarioAutenticado && $rolSesion === 'tecnico')

                <a href="{{ url('/incidencias') }}" class="{{ request()->routeIs('incidencias.*') ? 'nav-pill' : '' }}">
                    Mis servicios
                </a>

            @elseif($gestoraAutenticada)

                <a href="{{ url('/b2b/panel') }}" class="{{ request()->is('b2b/*') ? 'nav-pill' : '' }}">
                    Panel gestora
                </a>

            @endif

        </nav>

        <div class="session-actions">

            @if($usuarioAutenticado)

                <span class="session-name">
                    {{ $nombreUsuario }}
                </span>

                <a href="{{ url('/logout') }}" class="logout-btn">
                    Salir
                </a>

            @elseif($gestoraAutenticada)

                <span class="session-name">
                    {{ $nombreGestora }}
                </span>

                <a href="{{ url('/b2b/logout') }}" class="logout-btn">
                    Salir
                </a>

            @else

                <a href="{{ url('/login') }}" class="{{ request()->routeIs('login') ? 'nav-pill' : '' }}">
                    Login
                </a>

            @endif

        </div>

    </div>
</header>

<main>
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

    @yield('content')
</main>

<footer>
    ReparaYa
</footer>

</body>

</html>