@extends('layouts.app')

@section('title', 'Usuarios')

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

    .user-name {
        font-weight: 900;
        font-size: 18px;
        color: #0f172a;
    }

    .user-sub {
        display: block;
        margin-top: 4px;
        color: #64748b;
        font-size: 14px;
    }

    .role-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 10px 16px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 900;
        letter-spacing: 0.3px;
        border: 1px solid transparent;
    }

    .badge-admin {
        background: #efe3c8;
        color: #9b6b05;
        border-color: rgba(155,107,5,.20);
    }

    .badge-particular {
        background: #e7f0ff;
        color: #1d4ed8;
        border-color: rgba(29,78,216,.20);
    }

    .badge-tecnico {
        background: linear-gradient(135deg, #052e2b, #0f766e);
        color: #ecfeff;
        border-color: rgba(20,184,166,.45);
        box-shadow: 0 10px 20px rgba(15,118,110,.18);
    }

    .badge-tecnico::before {
        content: "⚙";
        margin-right: 7px;
        font-size: 12px;
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
            <a href="{{ route('usuarios.create') }}" class="btn btn-primary">
                Nuevo usuario
            </a>
        </div>

        <h1 class="hero-title-main">Usuarios del sistema</h1>
    </section>

    @if ($usuarios->isEmpty())
        <p class="alert-empty">No hay usuarios registrados.</p>
    @else
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Teléfono</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($usuarios as $u)
                        <tr>
                            <td>#{{ $u->id }}</td>

                            <td>
                                <span class="user-name">{{ $u->nombre }}</span>
                                <span class="user-sub">Perfil registrado en ReparaYa</span>
                            </td>

                            <td>{{ $u->email }}</td>

                            <td>
                                @if ($u->rol === 'tecnico')
                                    <span class="role-badge badge-tecnico">Técnico</span>
                                @elseif ($u->rol === 'admin')
                                    <span class="role-badge badge-admin">Administrador</span>
                                @else
                                    <span class="role-badge badge-particular">Particular</span>
                                @endif
                            </td>

                            <td>{{ $u->telefono }}</td>

                            <td>
                                <div class="actions">
                                    <a href="{{ route('usuarios.edit', $u->id) }}" class="btn btn-warning">
                                        Editar
                                    </a>

                                    <form action="{{ route('usuarios.destroy', $u->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('¿Seguro que quieres eliminar este usuario?');">
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