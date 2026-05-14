<style>
    .esp-page-header {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 24px;
    }

    .esp-kicker {
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

    .esp-title {
        margin: 0 0 10px 0;
        font-size: clamp(2.4rem, 4vw, 3.6rem);
        line-height: 1.05;
        color: #0f172a;
    }

    .esp-subtitle {
        margin: 0;
        max-width: 980px;
        color: #475569;
        font-size: 1.05rem;
        line-height: 1.72;
    }

    .esp-create-btn {
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

    .esp-create-btn:hover {
        transform: translateY(-2px);
    }

    .esp-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 18px;
    }

    .esp-card {
        position: relative;
        overflow: hidden;
        background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        border: 1px solid #dbe7ff;
        border-radius: 22px;
        padding: 22px;
        box-shadow: 0 14px 30px rgba(15, 23, 42, 0.07);
    }

    .esp-card::after {
        content: '';
        position: absolute;
        top: -36px;
        right: -36px;
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(59,130,246,0.15), transparent 70%);
    }

    .esp-card > * {
        position: relative;
        z-index: 1;
    }

    .esp-card h3 {
        margin: 0 0 12px 0;
        font-size: 1.42rem;
        color: #0f172a;
    }

    .esp-card p {
        margin: 0 0 16px 0;
        color: #475569;
        line-height: 1.68;
    }

    .esp-actions {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 12px;
    }

    .esp-actions a {
        text-decoration: none;
        font-weight: 700;
    }

    .esp-actions .inline-form {
        margin-top: 0;
    }

    .esp-empty {
        padding: 24px;
        border-radius: 22px;
        background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        border: 1px solid #dbe7ff;
        color: #475569;
        box-shadow: 0 14px 30px rgba(15, 23, 42, 0.06);
    }

    @media (max-width: 900px) {
        .esp-page-header {
            align-items: flex-start;
        }

        .esp-title {
            font-size: 2.4rem;
        }
    }
</style>

<div class="esp-page-header">
    <div>
        <div class="esp-kicker">Catálogo técnico del sistema</div>
        <h2 class="esp-title">Gestión de especialidades</h2>
        <p class="esp-subtitle">
            Organiza las especialidades técnicas del servicio desde una vista más limpia, visual y preparada para mantener la coherencia interna del sistema.
        </p>
    </div>

    <a class="esp-create-btn" href="<?= base_url('especialidades/create') ?>">+ Nueva especialidad</a>
</div>

<?php if (isset($_GET['ok']) && $_GET['ok'] === 'creada'): ?>
    <div class="message-success">Especialidad creada correctamente.</div>
<?php endif; ?>

<?php if (isset($_GET['ok']) && $_GET['ok'] === 'actualizada'): ?>
    <div class="message-success">Especialidad actualizada correctamente.</div>
<?php endif; ?>

<?php if (isset($_GET['ok']) && $_GET['ok'] === 'eliminada'): ?>
    <div class="message-success">Especialidad eliminada correctamente.</div>
<?php endif; ?>

<?php if (isset($_GET['error']) && $_GET['error'] === 'en_uso'): ?>
    <div class="message-error">No se puede eliminar la especialidad porque está asignada a uno o más técnicos.</div>
<?php endif; ?>

<?php if (empty($especialidades)): ?>
    <div class="esp-empty">
        No hay especialidades registradas todavía.
    </div>
<?php else: ?>
    <div class="esp-grid">
        <?php foreach ($especialidades as $especialidad): ?>
            <article class="esp-card">
                <h3><?= htmlspecialchars($especialidad['nombre_especialidad']) ?></h3>
                <p>Especialidad registrada en la estructura técnica del sistema.</p>

                <div class="esp-actions">
                    <a href="<?= base_url('especialidades/edit') ?>?id=<?= urlencode($especialidad['id']) ?>">Editar</a>

                    <form class="inline-form" action="<?= base_url('especialidades/delete') ?>" method="POST">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($especialidad['id']) ?>">
                        <button type="submit" onclick="return confirm('¿Seguro que quieres eliminar esta especialidad?');">
                            Eliminar
                        </button>
                    </form>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
<?php endif; ?>