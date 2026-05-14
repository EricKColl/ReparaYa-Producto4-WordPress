<style>
    .tec-page-header {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 24px;
    }

    .tec-kicker {
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

    .tec-title {
        margin: 0 0 10px 0;
        font-size: clamp(2.4rem, 4vw, 3.6rem);
        line-height: 1.05;
        color: #0f172a;
    }

    .tec-subtitle {
        margin: 0;
        max-width: 980px;
        color: #475569;
        font-size: 1.05rem;
        line-height: 1.72;
    }

    .tec-create-btn {
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

    .tec-create-btn:hover {
        transform: translateY(-2px);
    }

    .tec-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 18px;
    }

    .tec-card {
        position: relative;
        overflow: hidden;
        background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        border: 1px solid #dbe7ff;
        border-radius: 22px;
        padding: 22px;
        box-shadow: 0 14px 30px rgba(15, 23, 42, 0.07);
    }

    .tec-card::after {
        content: '';
        position: absolute;
        top: -36px;
        right: -36px;
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(59,130,246,0.15), transparent 70%);
    }

    .tec-card > * {
        position: relative;
        z-index: 1;
    }

    .tec-card-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 14px;
        margin-bottom: 14px;
    }

    .tec-card h3 {
        margin: 0 0 6px 0;
        font-size: 1.42rem;
        color: #0f172a;
    }

    .tec-card p {
        margin: 0;
        color: #475569;
        line-height: 1.68;
    }

    .tec-status {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 38px;
        padding: 8px 12px;
        border-radius: 999px;
        font-weight: 700;
        font-size: 0.9rem;
        text-align: center;
        white-space: nowrap;
    }

    .tec-status.ok {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
    }

    .tec-status.off {
        background: #fee2e2;
        color: #b91c1c;
        border: 1px solid #fecaca;
    }

    .tec-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 12px;
    }

    .tec-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 38px;
        padding: 8px 12px;
        border-radius: 12px;
        background: #eff6ff;
        border: 1px solid #dbeafe;
        color: #1d4ed8;
        font-weight: 700;
        font-size: 0.9rem;
    }

    .tec-account {
        margin-bottom: 16px;
        color: #334155;
        line-height: 1.65;
    }

    .tec-account strong {
        color: #0f172a;
    }

    .tec-actions {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 12px;
    }

    .tec-actions a {
        text-decoration: none;
        font-weight: 700;
    }

    .tec-actions .inline-form {
        margin-top: 0;
    }

    .tec-empty {
        padding: 24px;
        border-radius: 22px;
        background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        border: 1px solid #dbe7ff;
        color: #475569;
        box-shadow: 0 14px 30px rgba(15, 23, 42, 0.06);
    }

    @media (max-width: 900px) {
        .tec-page-header {
            align-items: flex-start;
        }

        .tec-title {
            font-size: 2.4rem;
        }
    }
</style>

<div class="tec-page-header">
    <div>
        <div class="tec-kicker">Módulo de administración técnica</div>
        <h2 class="tec-title">Gestión de técnicos</h2>
        <p class="tec-subtitle">
            Administra profesionales, disponibilidad, especialidades y la cuenta real de acceso de cada técnico para preparar correctamente su futura agenda de trabajo.
        </p>
    </div>

    <a class="tec-create-btn" href="<?= base_url('tecnicos/create') ?>">+ Nuevo técnico</a>
</div>

<?php if (isset($_GET['ok']) && $_GET['ok'] === 'creado'): ?>
    <div class="message-success">Técnico creado correctamente.</div>
<?php endif; ?>

<?php if (isset($_GET['ok']) && $_GET['ok'] === 'actualizado'): ?>
    <div class="message-success">Técnico actualizado correctamente.</div>
<?php endif; ?>

<?php if (isset($_GET['ok']) && $_GET['ok'] === 'eliminado'): ?>
    <div class="message-success">Técnico eliminado correctamente.</div>
<?php endif; ?>

<?php if (isset($_GET['error']) && $_GET['error'] === 'en_uso'): ?>
    <div class="message-error">No se puede eliminar el técnico porque está relacionado con otros elementos del sistema.</div>
<?php endif; ?>

<?php if (empty($tecnicos)): ?>
    <div class="tec-empty">
        No hay técnicos registrados todavía.
    </div>
<?php else: ?>
    <div class="tec-grid">
        <?php foreach ($tecnicos as $tecnico): ?>
            <article class="tec-card">
                <div class="tec-card-head">
                    <div>
                        <h3><?= htmlspecialchars($tecnico['nombre_completo']) ?></h3>
                        <p>Profesional registrado dentro del sistema.</p>
                    </div>

                    <span class="tec-status <?= !empty($tecnico['disponible']) ? 'ok' : 'off' ?>">
                        <?= !empty($tecnico['disponible']) ? 'Disponible' : 'No disponible' ?>
                    </span>
                </div>

                <div class="tec-meta">
                    <span class="tec-pill">Especialidad: <?= htmlspecialchars($tecnico['nombre_especialidad'] ?? 'Sin especialidad') ?></span>
                </div>

                <div class="tec-account">
                    <strong>Cuenta vinculada:</strong>
                    <?php if (!empty($tecnico['usuario_email'])): ?>
                        <?= htmlspecialchars($tecnico['usuario_nombre'] . ' (' . $tecnico['usuario_email'] . ')') ?>
                    <?php else: ?>
                        <span style="color:#b91c1c;font-weight:700;">Sin vincular</span>
                    <?php endif; ?>
                </div>

                <div class="tec-actions">
                    <a href="<?= base_url('tecnicos/edit') ?>?id=<?= urlencode($tecnico['id']) ?>">Editar</a>

                    <form class="inline-form" action="<?= base_url('tecnicos/delete') ?>" method="POST">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($tecnico['id']) ?>">
                        <button type="submit" onclick="return confirm('¿Seguro que quieres eliminar este técnico?');">
                            Eliminar
                        </button>
                    </form>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
<?php endif; ?>