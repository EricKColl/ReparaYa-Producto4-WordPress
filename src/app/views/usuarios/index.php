<style>
    .usr-page-header {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 24px;
    }

    .usr-kicker {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 8px 13px;
        border-radius: 999px;
        background: #e8f1ff;
        border: 1px solid #cfe0ff;
        color: #1d4ed8;
        font-weight: 700;
        font-size: 0.9rem;
        margin-bottom: 10px;
    }

    .usr-title {
        margin: 0 0 10px 0;
        font-size: clamp(2.4rem, 4vw, 3.8rem);
        line-height: 1.05;
        color: #0f172a;
    }

    .usr-subtitle {
        margin: 0;
        max-width: 980px;
        color: #475569;
        font-size: 1.05rem;
        line-height: 1.72;
    }

    .usr-create-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 50px;
        padding: 12px 18px;
        border-radius: 14px;
        text-decoration: none;
        font-weight: 700;
        color: #ffffff;
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        box-shadow: 0 14px 24px rgba(37, 99, 235, 0.16);
    }

    .usr-create-btn:hover {
        transform: translateY(-2px);
    }

    .usr-table-wrap {
        overflow-x: auto;
        border-radius: 22px;
        background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        border: 1px solid #dbe7ff;
        box-shadow: 0 14px 30px rgba(15, 23, 42, 0.07);
    }

    .usr-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 980px;
    }

    .usr-table thead th {
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        color: #0f172a;
        font-size: 1.02rem;
        font-weight: 700;
        padding: 16px 14px;
        text-align: left;
        border-bottom: 1px solid #cfe0ff;
    }

    .usr-table tbody td {
        padding: 16px 14px;
        border-bottom: 1px solid #e5eefb;
        color: #334155;
        vertical-align: middle;
        font-size: 1rem;
        line-height: 1.55;
    }

    .usr-table tbody tr:hover {
        background: rgba(239, 246, 255, 0.75);
    }

    .usr-id {
        font-weight: 700;
        color: #1d4ed8;
    }

    .usr-name {
        font-weight: 700;
        color: #0f172a;
    }

    .usr-role {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 36px;
        padding: 7px 11px;
        border-radius: 999px;
        font-size: 0.88rem;
        font-weight: 700;
        text-transform: capitalize;
    }

    .usr-role.admin {
        background: #fee2e2;
        color: #b91c1c;
        border: 1px solid #fecaca;
    }

    .usr-role.tecnico {
        background: #e0ecff;
        color: #1d4ed8;
        border: 1px solid #bfdbfe;
    }

    .usr-role.particular {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
    }

    .usr-actions {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 12px;
    }

    .usr-actions a {
        text-decoration: none;
        font-weight: 700;
    }

    .usr-actions .inline-form {
        margin-top: 0;
    }

    .usr-empty {
        padding: 24px;
        border-radius: 22px;
        background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        border: 1px solid #dbe7ff;
        color: #475569;
        box-shadow: 0 14px 30px rgba(15, 23, 42, 0.06);
    }

    @media (max-width: 900px) {
        .usr-page-header {
            align-items: flex-start;
        }

        .usr-title {
            font-size: 2.4rem;
        }
    }
</style>

<div class="usr-page-header">
    <div>
        <div class="usr-kicker">Administración de cuentas del sistema</div>
        <h2 class="usr-title">Gestión de usuarios</h2>
        <p class="usr-subtitle">
            Consulta, organiza y administra los usuarios registrados desde una vista más clara, profesional y preparada para el crecimiento del producto.
        </p>
    </div>

    <a class="usr-create-btn" href="<?= base_url('usuarios/create') ?>">+ Crear nuevo usuario</a>
</div>

<?php if (empty($usuarios)): ?>
    <div class="usr-empty">
        No hay usuarios registrados todavía.
    </div>
<?php else: ?>
    <div class="usr-table-wrap">
        <table class="usr-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Teléfono</th>
                    <th>Fecha de creación</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $usuario): ?>
                    <?php
                        $rol = strtolower($usuario['rol'] ?? 'particular');
                        $rolClass = in_array($rol, ['admin', 'tecnico', 'particular'], true) ? $rol : 'particular';
                    ?>
                    <tr>
                        <td class="usr-id"><?= htmlspecialchars($usuario['id']) ?></td>
                        <td class="usr-name"><?= htmlspecialchars($usuario['nombre']) ?></td>
                        <td><?= htmlspecialchars($usuario['email']) ?></td>
                        <td>
                            <span class="usr-role <?= htmlspecialchars($rolClass) ?>">
                                <?= htmlspecialchars($usuario['rol']) ?>
                            </span>
                        </td>
                        <td><?= htmlspecialchars($usuario['telefono'] ?? '') ?></td>
                        <td><?= htmlspecialchars($usuario['created_at']) ?></td>
                        <td>
                            <div class="usr-actions">
                                <a href="<?= base_url('usuarios/edit') ?>?id=<?= urlencode($usuario['id']) ?>">Editar</a>

                                <form class="inline-form" action="<?= base_url('usuarios/delete') ?>" method="POST">
                                    <input type="hidden" name="id" value="<?= htmlspecialchars($usuario['id']) ?>">
                                    <button type="submit" onclick="return confirm('¿Seguro que quieres eliminar este usuario?');">
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>