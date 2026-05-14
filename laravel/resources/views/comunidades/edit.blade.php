@extends('layouts.app')

@section('title', 'Editar Comunidad · ReparaYa')

@section('content')

<style>
    .community-form-shell {
        display: grid;
        gap: 28px;
    }

    .community-hero {
        position: relative;
        overflow: hidden;
        border-radius: 34px;
        padding: 42px 38px;
        background:
            radial-gradient(circle at 12% 16%, rgba(86, 199, 255, 0.18), transparent 26%),
            radial-gradient(circle at 88% 12%, rgba(214, 184, 109, 0.13), transparent 22%),
            linear-gradient(135deg, #031121 0%, #061a32 52%, #08284b 100%);
        border: 1px solid rgba(255,255,255,0.12);
        box-shadow: 0 28px 64px rgba(2, 6, 23, 0.22);
    }

    .community-hero::before {
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

    .community-hero-content {
        position: relative;
        z-index: 2;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 22px;
        flex-wrap: wrap;
    }

    .community-reference {
        display: inline-flex;
        align-items: center;
        width: fit-content;
        padding: 9px 14px;
        margin-bottom: 16px;
        border-radius: 999px;
        background: rgba(255,255,255,0.10);
        color: #9adfff;
        border: 1px solid rgba(255,255,255,0.14);
        font-size: 13px;
        font-weight: 900;
        letter-spacing: 0.55px;
        text-transform: uppercase;
    }

    .community-hero h1 {
        margin: 0;
        color: white;
        font-size: clamp(42px, 5vw, 74px);
        line-height: 0.96;
        letter-spacing: -2.4px;
        text-align: left;
    }

    .community-hero p {
        margin: 16px 0 0;
        max-width: 900px;
        color: #d7e5f3;
        font-size: 18px;
        line-height: 1.7;
    }

    .community-form-card {
        position: relative;
        overflow: hidden;
        border-radius: 34px;
        padding: 34px;
        background:
            radial-gradient(circle at 8% 10%, rgba(86,199,255,0.10), transparent 24%),
            radial-gradient(circle at 92% 10%, rgba(214,184,109,0.08), transparent 22%),
            linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        border: 1px solid rgba(15, 23, 42, 0.08);
        box-shadow: 0 24px 54px rgba(15, 23, 42, 0.10);
    }

    .community-form-card::before {
        content: "";
        position: absolute;
        inset: 0;
        pointer-events: none;
        background-image:
            linear-gradient(rgba(15,111,255,0.026) 1px, transparent 1px),
            linear-gradient(90deg, rgba(15,111,255,0.026) 1px, transparent 1px);
        background-size: 34px 34px;
        mask-image: radial-gradient(circle at center, black 0%, transparent 90%);
    }

    .community-form-content {
        position: relative;
        z-index: 2;
    }

    .community-form-head {
        text-align: center;
        margin-bottom: 28px;
    }

    .community-form-head h2 {
        margin: 0;
        color: #0f172a;
        font-size: 36px;
        line-height: 1.05;
        letter-spacing: -1.2px;
    }

    .community-form-head p {
        margin: 12px auto 0;
        max-width: 860px;
        color: #61748c;
        font-size: 16px;
        line-height: 1.7;
    }

    .community-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 20px;
    }

    .community-field {
        display: grid;
        gap: 8px;
    }

    .community-field.full {
        grid-column: 1 / -1;
    }

    .community-field label {
        color: #0f172a;
        font-weight: 900;
        font-size: 15px;
        letter-spacing: -0.2px;
    }

    .community-field small {
        color: #61748c;
        font-size: 13.5px;
        line-height: 1.45;
    }

    .community-input,
    .community-select {
        width: 100%;
        min-height: 58px;
        padding: 0 16px;
        border-radius: 18px;
        border: 1px solid #d7e2ee;
        background: white;
        color: #0f172a;
        font-family: Arial, sans-serif;
        font-size: 16px;
        outline: none;
        transition: border-color 0.14s ease, box-shadow 0.14s ease;
    }

    .community-input:focus,
    .community-select:focus {
        border-color: rgba(15, 111, 255, 0.48);
        box-shadow: 0 0 0 4px rgba(15, 111, 255, 0.10);
    }

    .community-summary {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 14px;
        margin-top: 28px;
    }

    .community-summary-card {
        padding: 18px;
        border-radius: 22px;
        background: #f3f8ff;
        border: 1px solid rgba(15,111,255,0.10);
    }

    .community-summary-card span {
        display: block;
        color: #64748b;
        font-size: 12px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.45px;
    }

    .community-summary-card strong {
        display: block;
        margin-top: 7px;
        color: #0f172a;
        font-size: 20px;
        line-height: 1.2;
        letter-spacing: -0.5px;
    }

    .community-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 14px;
        flex-wrap: wrap;
        margin-top: 30px;
        padding-top: 22px;
        border-top: 1px solid rgba(15,23,42,0.08);
    }

    .community-error {
        padding: 16px 18px;
        border-radius: 18px;
        background: #fff0f2;
        color: #9f1d2e;
        border: 1px solid rgba(220, 53, 69, 0.22);
        font-weight: 800;
        line-height: 1.55;
    }

    .community-error ul {
        margin: 8px 0 0;
        padding-left: 20px;
    }

    @media (max-width: 900px) {
        .community-grid,
        .community-summary {
            grid-template-columns: 1fr;
        }

        .community-hero,
        .community-form-card {
            padding: 24px 18px;
            border-radius: 26px;
        }

        .community-actions .btn,
        .community-hero-content .btn {
            width: 100%;
        }

        .community-hero h1 {
            font-size: 40px;
        }
    }
</style>

<div class="community-form-shell">

    <section class="community-hero">
        <div class="community-hero-content">
            <div>
                <div class="community-reference">
                    Comunidad #{{ $comunidad->id }}
                </div>

                <h1>Editar comunidad</h1>

                <p>
                    Actualiza los datos operativos de la comunidad, incluyendo el teléfono de contacto que se utilizará automáticamente al crear avisos desde el panel de gestora.
                </p>
            </div>

            <a href="{{ route('comunidades.index') }}" class="btn btn-primary">
                ← Volver
            </a>
        </div>
    </section>

    @if($errors->any())
        <div class="community-error">
            <strong>No se ha podido actualizar la comunidad.</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <section class="community-form-card">
        <div class="community-form-content">

            <div class="community-form-head">
                <h2>Datos de la comunidad</h2>
                <p>
                    Mantén actualizada la gestora responsable, la dirección, la zona y el teléfono para que los avisos B2B se creen con información fiable.
                </p>
            </div>

            <form action="{{ route('comunidades.update', $comunidad->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="community-grid">

                    <div class="community-field full">
                        <label for="gestora_id">Gestora responsable</label>

                        <select name="gestora_id" id="gestora_id" class="community-select" required>
                            <option value="">-- Seleccionar gestora --</option>

                            @foreach($gestoras as $gestora)
                                <option
                                    value="{{ $gestora->id }}"
                                    {{ old('gestora_id', $comunidad->gestora_id) == $gestora->id ? 'selected' : '' }}
                                >
                                    {{ $gestora->nombre }}
                                </option>
                            @endforeach
                        </select>

                        <small>
                            Si cambias la gestora, esta comunidad pasará a estar disponible para otra empresa gestora.
                        </small>
                    </div>

                    <div class="community-field">
                        <label for="nombre">Nombre de la comunidad</label>

                        <input
                            type="text"
                            name="nombre"
                            id="nombre"
                            class="community-input"
                            value="{{ old('nombre', $comunidad->nombre) }}"
                            required
                        >
                    </div>

                    <div class="community-field">
                        <label for="zona">Zona</label>

                        <input
                            type="text"
                            name="zona"
                            id="zona"
                            class="community-input"
                            value="{{ old('zona', $comunidad->zona) }}"
                            required
                        >

                        <small>
                            La zona se utiliza para el Web Service REST de servicios por área.
                        </small>
                    </div>

                    <div class="community-field full">
                        <label for="direccion">Dirección</label>

                        <input
                            type="text"
                            name="direccion"
                            id="direccion"
                            class="community-input"
                            value="{{ old('direccion', $comunidad->direccion) }}"
                            required
                        >
                    </div>

                    <div class="community-field full">
                        <label for="telefono_contacto">Teléfono de contacto</label>

                        <input
                            type="text"
                            name="telefono_contacto"
                            id="telefono_contacto"
                            class="community-input"
                            value="{{ old('telefono_contacto', $comunidad->telefono_contacto) }}"
                            required
                        >

                        <small>
                            Este número se rellenará automáticamente en el formulario de nuevo aviso de la gestora.
                        </small>
                    </div>

                </div>

                <div class="community-summary">
                    <div class="community-summary-card">
                        <span>Gestora actual</span>
                        <strong>{{ $comunidad->gestora->nombre ?? 'Sin gestora' }}</strong>
                    </div>

                    <div class="community-summary-card">
                        <span>Zona registrada</span>
                        <strong>{{ $comunidad->zona }}</strong>
                    </div>

                    <div class="community-summary-card">
                        <span>Teléfono actual</span>
                        <strong>{{ $comunidad->telefono_contacto ?? 'Sin teléfono' }}</strong>
                    </div>
                </div>

                <div class="community-actions">
                    <a href="{{ route('comunidades.index') }}" class="btn btn-warning">
                        Cancelar
                    </a>

                    <button type="submit" class="btn btn-primary">
                        Actualizar comunidad
                    </button>
                </div>

            </form>

        </div>
    </section>

</div>

@endsection