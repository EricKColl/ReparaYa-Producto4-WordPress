@extends('layouts.app')

@section('title', 'Login · ReparaYa')

@section('content')

<style>
    .login-page {
        min-height: 560px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 34px 16px;
    }

    .login-card {
        position: relative;
        overflow: hidden;
        width: min(540px, 100%);
        border-radius: 34px;
        padding: 42px 34px 34px;
        background:
            radial-gradient(circle at 14% 10%, rgba(86, 199, 255, 0.22), transparent 28%),
            radial-gradient(circle at 86% 8%, rgba(15, 111, 255, 0.12), transparent 24%),
            linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        border: 1px solid rgba(15, 23, 42, 0.08);
        box-shadow: 0 28px 70px rgba(15, 23, 42, 0.14);
    }

    .login-card::before {
        content: "";
        position: absolute;
        inset: 0;
        pointer-events: none;
        background-image:
            linear-gradient(rgba(15, 111, 255, 0.035) 1px, transparent 1px),
            linear-gradient(90deg, rgba(15, 111, 255, 0.035) 1px, transparent 1px);
        background-size: 34px 34px;
        mask-image: radial-gradient(circle at center, black 0%, transparent 86%);
    }

    .login-content {
        position: relative;
        z-index: 2;
    }

    .login-logo {
        width: 84px;
        height: 84px;
        margin: 0 auto 24px;
        border-radius: 24px;
        display: grid;
        place-items: center;
        color: white;
        font-size: 31px;
        font-weight: 900;
        letter-spacing: -1px;
        background: linear-gradient(135deg, #0f6fff, #56c7ff);
        box-shadow:
            0 18px 34px rgba(15, 111, 255, 0.26),
            0 0 0 14px rgba(15, 111, 255, 0.06);
    }

    .login-title {
        margin: 0 0 26px;
        text-align: center;
        color: #0f172a;
        font-size: 38px;
        line-height: 1;
        letter-spacing: -1.3px;
    }

    .login-tabs {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
        padding: 7px;
        margin-bottom: 24px;
        border-radius: 999px;
        background: #edf4fb;
        border: 1px solid rgba(15, 23, 42, 0.07);
    }

    .login-tab {
        border: none;
        cursor: pointer;
        padding: 12px 10px;
        border-radius: 999px;
        background: transparent;
        color: #566b84;
        font-size: 14px;
        font-weight: 900;
        font-family: Arial, sans-serif;
        transition: background 0.14s ease, color 0.14s ease, box-shadow 0.14s ease;
    }

    .login-tab.active {
        color: white;
        background: linear-gradient(135deg, #0f6fff, #56c7ff);
        box-shadow: 0 12px 24px rgba(15, 111, 255, 0.18);
    }

    .login-form {
        display: none;
    }

    .login-form.active {
        display: block;
    }

    .login-group {
        margin-bottom: 18px;
    }

    .login-group label {
        display: block;
        margin-bottom: 8px;
        color: #0f172a;
        font-weight: 900;
        font-size: 15px;
    }

    .login-input {
        display: flex;
        align-items: center;
        gap: 10px;
        min-height: 58px;
        padding: 0 14px;
        border-radius: 18px;
        border: 1px solid #d7e2ee;
        background: #ffffff;
        transition: border-color 0.14s ease, box-shadow 0.14s ease;
    }

    .login-input:focus-within {
        border-color: rgba(15, 111, 255, 0.50);
        box-shadow: 0 0 0 4px rgba(15, 111, 255, 0.10);
    }

    .login-icon {
        color: #6b7f98;
        font-size: 17px;
        flex: 0 0 auto;
    }

    .login-input input {
        width: 100%;
        border: none;
        outline: none;
        background: transparent;
        color: #0f172a;
        font-size: 16px;
        font-family: Arial, sans-serif;
    }

    .login-input input::placeholder {
        color: #8aa0b8;
    }

    .password-toggle {
        border: none;
        cursor: pointer;
        padding: 8px 11px;
        border-radius: 999px;
        background: #edf4ff;
        color: #0f6fff;
        font-size: 13px;
        font-weight: 900;
        font-family: Arial, sans-serif;
    }

    .login-submit {
        width: 100%;
        min-height: 58px;
        margin-top: 8px;
        border: none;
        cursor: pointer;
        border-radius: 18px;
        color: white;
        font-size: 16px;
        font-weight: 900;
        font-family: Arial, sans-serif;
        background: linear-gradient(135deg, #0f6fff, #56c7ff);
        box-shadow: 0 16px 32px rgba(15, 111, 255, 0.22);
        transition: transform 0.14s ease, box-shadow 0.14s ease;
    }

    .login-submit:hover {
        transform: translateY(-1px);
        box-shadow: 0 20px 38px rgba(15, 111, 255, 0.28);
    }

    .login-errors {
        margin-bottom: 20px;
        padding: 15px 17px;
        border-radius: 18px;
        color: #9f1d2e;
        background: #fff0f2;
        border: 1px solid rgba(220, 53, 69, 0.22);
        font-weight: 800;
        line-height: 1.55;
    }

    .login-errors ul {
        margin: 8px 0 0;
        padding-left: 20px;
        font-weight: 700;
    }

    @media (max-width: 640px) {
        .login-page {
            padding: 16px 0;
        }

        .login-card {
            padding: 32px 20px 24px;
            border-radius: 26px;
        }

        .login-title {
            font-size: 32px;
        }

        .login-tabs {
            grid-template-columns: 1fr;
            border-radius: 24px;
        }
    }
</style>

<div class="login-page">
    <section class="login-card">
        <div class="login-content">

            <div class="login-logo">RY</div>

            <h1 class="login-title">Iniciar sesión</h1>

            @if ($errors->any())
                <div class="login-errors">
                    <strong>No se ha podido iniciar sesión.</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="login-tabs">
                <button type="button" class="login-tab active" data-login-target="usuario">
                    Usuario
                </button>

                <button type="button" class="login-tab" data-login-target="gestora">
                    Gestora
                </button>
            </div>

            <form id="login-usuario" class="login-form active" action="{{ route('login.submit') }}" method="POST">
                @csrf

                <div class="login-group">
                    <label for="usuario_email">Correo electrónico</label>
                    <div class="login-input">
                        <span class="login-icon">✉</span>
                        <input
                            id="usuario_email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="Introduce tu correo electrónico"
                            autocomplete="email"
                            required
                        >
                    </div>
                </div>

                <div class="login-group">
                    <label for="usuario_password">Contraseña</label>
                    <div class="login-input">
                        <span class="login-icon">⌘</span>
                        <input
                            id="usuario_password"
                            type="password"
                            name="password"
                            placeholder="Introduce tu contraseña"
                            autocomplete="current-password"
                            required
                        >
                        <button type="button" class="password-toggle" data-password-target="usuario_password">
                            Mostrar
                        </button>
                    </div>
                </div>

                <button type="submit" class="login-submit">
                    Entrar al sistema
                </button>
            </form>

            <form id="login-gestora" class="login-form" action="{{ url('/b2b/login') }}" method="POST">
                @csrf

                <div class="login-group">
                    <label for="gestora_email">Correo electrónico</label>
                    <div class="login-input">
                        <span class="login-icon">✉</span>
                        <input
                            id="gestora_email"
                            type="email"
                            name="email"
                            placeholder="Introduce el correo de la gestora"
                            autocomplete="email"
                            required
                        >
                    </div>
                </div>

                <div class="login-group">
                    <label for="gestora_password">Contraseña</label>
                    <div class="login-input">
                        <span class="login-icon">⌘</span>
                        <input
                            id="gestora_password"
                            type="password"
                            name="password"
                            placeholder="Introduce la contraseña"
                            autocomplete="current-password"
                            required
                        >
                        <button type="button" class="password-toggle" data-password-target="gestora_password">
                            Mostrar
                        </button>
                    </div>
                </div>

                <button type="submit" class="login-submit">
                    Entrar como gestora
                </button>
            </form>

        </div>
    </section>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tabs = document.querySelectorAll('[data-login-target]');
        const forms = {
            usuario: document.getElementById('login-usuario'),
            gestora: document.getElementById('login-gestora')
        };

        tabs.forEach(function (tab) {
            tab.addEventListener('click', function () {
                const target = tab.dataset.loginTarget;

                tabs.forEach(function (item) {
                    item.classList.remove('active');
                });

                tab.classList.add('active');

                Object.keys(forms).forEach(function (key) {
                    forms[key].classList.toggle('active', key === target);
                });
            });
        });

        document.querySelectorAll('[data-password-target]').forEach(function (button) {
            button.addEventListener('click', function () {
                const input = document.getElementById(button.dataset.passwordTarget);

                if (!input) {
                    return;
                }

                const mostrar = input.type === 'password';
                input.type = mostrar ? 'text' : 'password';
                button.textContent = mostrar ? 'Ocultar' : 'Mostrar';
            });
        });
    });
</script>

