<?php

$appConfig = require __DIR__ . '/../../../config/app.php';
$usuarioSesion = $_SESSION['usuario'] ?? null;
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? $appConfig['name']) ?></title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700;800;900&family=Rajdhani:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --rp-bg-1: #edf3fb;
            --rp-bg-2: #e4ecf8;
            --rp-panel: rgba(255, 255, 255, 0.90);
            --rp-panel-border: rgba(148, 163, 184, 0.18);

            --rp-dark-1: #060f24;
            --rp-dark-2: #0d1c44;
            --rp-blue-1: #173a8a;
            --rp-blue-2: #2563eb;
            --rp-cyan: #8fdcff;

            --rp-text: #0f172a;
            --rp-text-soft: #475569;
            --rp-white: #ffffff;

            --rp-danger: #dc2626;
            --rp-success: #166534;

            --rp-font-brand: 'Orbitron', Arial, sans-serif;
            --rp-font-ui: 'Rajdhani', Arial, sans-serif;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
        }

        body {
            font-family: var(--rp-font-ui);
            color: var(--rp-text);
            background:
                radial-gradient(circle at top right, rgba(37, 99, 235, 0.10), transparent 16%),
                radial-gradient(circle at bottom left, rgba(37, 99, 235, 0.08), transparent 18%),
                linear-gradient(180deg, var(--rp-bg-1) 0%, var(--rp-bg-2) 100%);
            min-height: 100vh;
        }

        .rp-shell {
            width: min(97vw, 1920px);
            margin: 0 auto;
            padding: 18px 0 34px;
        }

        .rp-header {
            position: relative;
            overflow: hidden;
            border-radius: 30px;
            padding: 34px 38px 30px;
            margin-bottom: 28px;
            background:
                radial-gradient(circle at top right, rgba(147, 197, 253, 0.30), transparent 22%),
                linear-gradient(135deg, var(--rp-dark-1) 0%, var(--rp-dark-2) 45%, var(--rp-blue-2) 100%);
            box-shadow:
                0 26px 54px rgba(15, 23, 42, 0.18),
                0 0 0 1px rgba(255, 255, 255, 0.05) inset,
                0 0 34px rgba(37, 99, 235, 0.12);
        }

        .rp-header::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                linear-gradient(rgba(255, 255, 255, 0.035) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.035) 1px, transparent 1px);
            background-size: 42px 42px;
            opacity: 0.10;
            pointer-events: none;
        }

        .rp-header::after {
            content: '';
            position: absolute;
            width: 560px;
            height: 560px;
            top: -220px;
            right: -140px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.18) 0%, rgba(255, 255, 255, 0.05) 42%, transparent 72%);
            filter: blur(8px);
            animation: rp-glow 7s ease-in-out infinite;
            pointer-events: none;
        }

        .rp-header-inner {
            position: relative;
            z-index: 2;
        }

        .rp-brand-block {
            text-align: center;
            margin-bottom: 24px;
        }

        .rp-system-chip {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            margin-bottom: 18px;
            color: #dbeafe;
            font-weight: 700;
            font-size: 0.92rem;
            text-transform: uppercase;
            letter-spacing: 0.10em;
            opacity: 0.95;
        }

        .rp-brand-title {
            margin: 0 0 24px 0;
            font-family: var(--rp-font-brand);
            font-size: clamp(5.3rem, 8.8vw, 8.8rem);
            line-height: 0.92;
            letter-spacing: -0.05em;
            font-weight: 900;
            color: var(--rp-white);
            text-shadow:
                0 0 16px rgba(255, 255, 255, 0.10),
                0 0 34px rgba(191, 219, 254, 0.18);
        }

        .rp-brand-subtitle {
            margin: 0 auto;
            max-width: 1380px;
            color: #dbeafe;
            font-size: 1.3rem;
            line-height: 1.72;
            font-weight: 600;
        }

        .rp-nav-top {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            border-top: 1px solid rgba(255, 255, 255, 0.10);
            padding-top: 20px;
            margin-top: 30px;
        }

        .rp-nav-top.is-public {
            justify-content: center;
        }

        .rp-nav {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .rp-nav a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 50px;
            padding: 11px 18px;
            border-radius: 14px;
            text-decoration: none;
            font-weight: 700;
            font-size: 1rem;
            color: #ffffff;
            background: rgba(255, 255, 255, 0.10);
            border: 1px solid rgba(255, 255, 255, 0.12);
            box-shadow: 0 8px 20px rgba(7, 17, 38, 0.10);
            backdrop-filter: blur(10px);
            transition: 0.22s ease;
            gap: 10px;
        }

        .rp-nav a::before {
            content: '';
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--rp-cyan);
            box-shadow: 0 0 12px rgba(143, 220, 255, 0.95);
            animation: rp-dot 1.8s ease-in-out infinite;
            flex-shrink: 0;
        }

        .rp-nav a:hover {
            transform: translateY(-2px);
            background: rgba(255, 255, 255, 0.16);
        }

        .rp-nav-user {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 50px;
            padding: 11px 18px;
            border-radius: 14px;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.14);
            color: #e0ecff;
            font-weight: 700;
            font-size: 1rem;
            text-align: center;
        }

        .rp-main {
            position: relative;
            overflow: hidden;
            background: var(--rp-panel);
            border-radius: 28px;
            padding: 38px;
            box-shadow:
                0 18px 38px rgba(15, 23, 42, 0.08),
                0 0 0 1px rgba(219, 234, 254, 0.85) inset;
        }

        .rp-main::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.22), transparent 24%);
            pointer-events: none;
        }

        .rp-main>* {
            position: relative;
            z-index: 1;
        }

        h2 {
            margin-top: 0;
            font-size: 2.5rem;
            color: var(--rp-text);
        }

        h3,
        h4 {
            color: var(--rp-text);
        }

        p {
            line-height: 1.74;
            color: #334155;
            font-size: 1.04rem;
        }

        a {
            color: #1d4ed8;
        }

        ul {
            padding-left: 20px;
        }

        li {
            margin-bottom: 10px;
            color: #334155;
            line-height: 1.64;
        }

        label {
            display: inline-block;
            margin-bottom: 4px;
            color: var(--rp-text);
            font-weight: 700;
            font-size: 1rem;
        }

        form {
            margin-top: 12px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="number"],
        input[type="date"],
        input[type="time"],
        input[type="datetime-local"],
        select,
        textarea {
            width: 100%;
            max-width: 540px;
            padding: 14px 16px;
            margin-top: 6px;
            margin-bottom: 16px;
            border: 1px solid #cbd5e1;
            border-radius: 14px;
            background: #f7fbff;
            color: var(--rp-text);
            font-size: 1rem;
            font-family: var(--rp-font-ui);
            box-shadow: inset 0 1px 2px rgba(15, 23, 42, 0.04);
        }

        input[type="checkbox"] {
            transform: scale(1.12);
            margin-right: 8px;
        }

        button,
        input[type="submit"] {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: #ffffff;
            border: none;
            border-radius: 14px;
            padding: 12px 22px;
            font-size: 1rem;
            font-weight: 700;
            font-family: var(--rp-font-ui);
            cursor: pointer;
            transition: 0.22s ease;
            box-shadow: 0 12px 22px rgba(37, 99, 235, 0.18);
        }

        button:hover,
        input[type="submit"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 16px 28px rgba(37, 99, 235, 0.22);
        }

        .inline-form {
            display: inline;
        }

        .inline-form button {
            margin-left: 8px;
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            box-shadow: 0 12px 22px rgba(220, 38, 38, 0.16);
        }

        .inline-form button:hover {
            box-shadow: 0 16px 28px rgba(220, 38, 38, 0.22);
        }

        .action-link {
            display: inline-block;
            margin-right: 8px;
            font-weight: 700;
        }

        .top-link {
            display: inline-block;
            margin-bottom: 18px;
            padding: 10px 14px;
            border-radius: 12px;
            background: #e0ecff;
            color: #1d4ed8;
            text-decoration: none;
            font-weight: 700;
        }

        .message-error {
            background: #fee2e2;
            color: #b91c1c;
            border: 1px solid #fecaca;
            border-radius: 14px;
            padding: 12px 14px;
            margin-bottom: 18px;
            font-weight: 700;
        }

        .message-success {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
            border-radius: 14px;
            padding: 12px 14px;
            margin-bottom: 18px;
            font-weight: 700;
        }

        hr {
            display: none;
        }

        @keyframes rp-glow {
            0%, 100% {
                transform: scale(1);
                opacity: 0.92;
            }

            50% {
                transform: scale(1.06);
                opacity: 1;
            }
        }

        @keyframes rp-dot {
            0%, 100% {
                opacity: 1;
            }

            50% {
                opacity: 0.25;
            }
        }

        @media (max-width: 1100px) {
            .rp-nav-top {
                flex-direction: column;
                align-items: center;
            }
        }

        @media (max-width: 900px) {
            .rp-shell {
                width: min(97vw, 1920px);
                padding-top: 14px;
            }

            .rp-header,
            .rp-main {
                padding: 22px 18px;
            }

            .rp-brand-title {
                font-size: 4.1rem;
            }

            .rp-brand-subtitle {
                font-size: 1rem;
            }

            .rp-nav {
                flex-direction: column;
                align-items: stretch;
                width: 100%;
            }

            .rp-nav a,
            .rp-nav-user {
                width: 100%;
                justify-content: center;
            }

            input[type="text"],
            input[type="email"],
            input[type="password"],
            input[type="number"],
            input[type="date"],
            input[type="time"],
            input[type="datetime-local"],
            select,
            textarea {
                max-width: 100%;
            }
        }
    </style>
</head>

<body>
    <div class="rp-shell">
        <header class="rp-header">
            <div class="rp-header-inner">
                <div class="rp-brand-block">
                    <div class="rp-system-chip">
                        Plataforma integral de asistencia técnica
                    </div>

                    <h1 class="rp-brand-title"><?= htmlspecialchars($appConfig['name']) ?></h1>

                    <p class="rp-brand-subtitle">
                        Gestión centralizada de incidencias, coordinación técnica, atención al cliente y control operativo del servicio en un único entorno profesional.
                    </p>
                </div>

                <div class="rp-nav-top<?= $usuarioSesion ? '' : ' is-public' ?>">
                    <nav class="rp-nav">
                        <a href="<?= base_url() ?>">Inicio</a>

                        <?php if ($usuarioSesion): ?>
                            <a href="<?= base_url('perfil') ?>">Mi perfil</a>

                            <?php if (($usuarioSesion['rol'] ?? '') === 'admin'): ?>
                                <a href="<?= base_url('admin') ?>">Incidencias</a>
                                <a href="<?= base_url('usuarios') ?>">Usuarios</a>
                                <a href="<?= base_url('tecnicos') ?>">Técnicos</a>
                                <a href="<?= base_url('especialidades') ?>">Especialidades</a>
                            <?php elseif (($usuarioSesion['rol'] ?? '') === 'tecnico'): ?>
                                <a href="<?= base_url('mi-agenda') ?>">Mi agenda</a>
                            <?php elseif (($usuarioSesion['rol'] ?? '') === 'particular'): ?>
                                <a href="<?= base_url('mis-avisos') ?>">Mis avisos</a>
                                <a href="<?= base_url('nueva-solicitud') ?>">Nueva solicitud</a>
                            <?php endif; ?>

                            <a href="<?= base_url('logout') ?>">Cerrar sesión</a>
                        <?php else: ?>
                            <a href="<?= base_url('login') ?>">Iniciar sesión</a>
                            <a href="<?= base_url('register') ?>">Registrarse</a>
                        <?php endif; ?>
                    </nav>

                    <?php if ($usuarioSesion): ?>
                        <div class="rp-nav-user">
                            <?php
                            $rolTexto = $usuarioSesion['rol'] ?? '';

                            if ($rolTexto === 'tecnico') {
                                $rolTexto = 'técnico';
                            } elseif ($rolTexto === 'particular') {
                                $rolTexto = 'cliente';
                            }
                            ?>
                            Hola, <?= htmlspecialchars($usuarioSesion['nombre']) ?> (<?= htmlspecialchars($rolTexto) ?>)
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </header>

        <main class="rp-main">
            <?= $content ?? '' ?>
        </main>
    </div>
</body>

</html>