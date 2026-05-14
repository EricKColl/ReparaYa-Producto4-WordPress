@extends('layouts.app')

@section('title', 'Técnicos')

@section('content')

<style>
    .index-shell {
        display: grid;
        gap: 28px;
    }

    .hero-clean {
    position: relative;
    overflow: hidden;
    border-radius: 32px;
    min-height: 190px;
    padding: 34px 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    background:
        radial-gradient(circle at 10% 10%, rgba(86,199,255,0.14), transparent 20%),
        linear-gradient(135deg, #041225 0%, #061a32 52%, #0b2748 100%);
    border: 1px solid rgba(255,255,255,0.12);
    box-shadow: 0 28px 60px rgba(2, 6, 23, 0.20);
}

.hero-actions-top {
    position: absolute;
    top: 34px;
    right: 32px;
    z-index: 3;
    display: flex;
    justify-content: flex-end;
    margin: 0;
}

.hero-title-main {
    position: relative;
    z-index: 2;
    width: 100%;
    max-width: 1100px;
    margin: 0 auto;
    text-align: center;
    color: white;
    font-size: clamp(42px, 4.8vw, 68px);
    letter-spacing: -2px;
    line-height: 1;
}

    .table-wrap {
        overflow-x: auto;
        border-radius: 24px;
    }

    .availability-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 10px 16px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 900;
        letter-spacing: 0.3px;
    }

    .availability-yes {
        background: #ddf8ea;
        color: #0b7a48;
    }

    .availability-no {
        background: #ffe5e8;
        color: #b42334;
    }

    @media (max-width: 760px) {
    .hero-clean {
        min-height: 220px;
        padding: 86px 18px 34px;
    }

    .hero-actions-top {
        top: 24px;
        left: 18px;
        right: 18px;
        justify-content: center;
    }

    .hero-title-main {
        font-size: clamp(34px, 10vw, 46px);
        letter-spacing: -1.3px;
    }
}
</style>

<div class="index-shell">

    <section class="hero-clean">
        <div class="hero-actions-top">
            <a href="{{ route('tecnicos.create') }}" class="btn btn-primary">
                Nuevo técnico
            </a>
        </div>

        <h1 class="hero-title-main">Equipo técnico</h1>
    </section>

    @if ($tecnicos->isEmpty())
        <p class="alert-empty">No hay técnicos registrados.</p>
    @else
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Usuario asociado</th>
                        <th>Especialidad</th>
                        <th>Disponible</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($tecnicos as $t)
                        <tr>
                            <td>{{ $t->id }}</td>
                            <td>{{ $t->nombre_completo }}</td>
                            <td>{{ $t->usuario->nombre ?? 'Sin usuario' }}</td>
                            <td>{{ $t->especialidad->nombre_especialidad ?? 'Sin especialidad' }}</td>

                            <td>
                                @if($t->disponible)
                                    <span class="availability-pill availability-yes">Sí</span>
                                @else
                                    <span class="availability-pill availability-no">No</span>
                                @endif
                            </td>

                            <td>
                                <div class="actions">
                                    <a href="{{ route('tecnicos.edit', $t->id) }}" class="btn btn-warning">
                                        Editar
                                    </a>

                                    <form action="{{ route('tecnicos.destroy', $t->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('¿Seguro que quieres eliminar este técnico?');">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-danger">
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

</div>

@endsection