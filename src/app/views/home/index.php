<?php
$usuarioSesion = $usuarioSesion ?? ($_SESSION['usuario'] ?? null);
$rolInicio = $rolInicio ?? 'public';
$dashboard = $dashboard ?? [];

function homeFecha(?string $fecha): string
{
    if (!$fecha || strtotime($fecha) === false) {
        return 'Fecha no disponible';
    }

    return date('d/m/Y H:i', strtotime($fecha));
}

function homeEstadoClase(string $estado): string
{
    return match ($estado) {
        'Pendiente' => 'home-badge-pendiente',
        'Asignada' => 'home-badge-asignada',
        'Finalizada' => 'home-badge-finalizada',
        'Cancelada' => 'home-badge-cancelada',
        default => 'home-badge-pendiente',
    };
}

function homeUrgenciaClase(string $urgencia): string
{
    return $urgencia === 'Urgente' ? 'home-badge-urgente' : 'home-badge-estandar';
}
?>

<style>
    .home-wrap {
        display: grid;
        gap: 30px;
    }

    .home-hero {
        position: relative;
        overflow: hidden;
        border-radius: 32px;
        padding: 42px 36px 44px;
        background:
            radial-gradient(circle at top right, rgba(59,130,246,0.14), transparent 25%),
            radial-gradient(circle at bottom left, rgba(37,99,235,0.10), transparent 24%),
            linear-gradient(180deg, #ffffff 0%, #f7faff 100%);
        border: 1px solid #dbe7ff;
        box-shadow: 0 20px 40px rgba(15, 23, 42, 0.07);
        text-align: center;
    }

    .home-hero::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(120deg, transparent 0%, rgba(255,255,255,0.24) 45%, transparent 100%);
        transform: translateX(-120%);
        animation: homeShine 8s linear infinite;
        pointer-events: none;
    }

    .home-hero > * {
        position: relative;
        z-index: 1;
    }

    .home-chip {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 38px;
        padding: 8px 16px;
        border-radius: 999px;
        background: #e8f1ff;
        border: 1px solid #cfe0ff;
        color: #1d4ed8;
        font-weight: 800;
        font-size: 0.92rem;
        margin-bottom: 18px;
    }

    .home-title {
        margin: 0 auto;
        max-width: 1450px;
        font-size: clamp(3rem, 5vw, 5.5rem);
        line-height: 0.98;
        letter-spacing: -0.04em;
        color: #0f172a;
        font-weight: 900;
        text-align: center;
        text-wrap: balance;
    }

    .home-title span {
        display: block;
        background: linear-gradient(90deg, #0f172a 0%, #1d4ed8 55%, #2563eb 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        color: transparent;
    }

    .home-subtitle {
        margin: 20px auto 0;
        max-width: 1200px;
        color: #475569;
        font-size: 1.14rem;
        line-height: 1.95;
        text-wrap: balance;
        text-align: center;
    }

    .home-actions {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 14px;
        margin-top: 26px;
    }

    .home-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 52px;
        padding: 12px 22px;
        border-radius: 16px;
        text-decoration: none;
        font-weight: 800;
        font-size: 1rem;
        transition: 0.22s ease;
    }

    .home-btn-primary {
        color: #ffffff;
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        box-shadow: 0 14px 24px rgba(37, 99, 235, 0.18);
    }

    .home-btn-secondary {
        color: #1d4ed8;
        background: #eaf2ff;
        border: 1px solid #dbeafe;
    }

    .home-btn:hover {
        transform: translateY(-2px);
    }

    .home-grid-4,
    .home-grid-3,
    .home-grid-2 {
        display: grid;
        gap: 18px;
    }

    .home-grid-4 {
        grid-template-columns: repeat(4, minmax(0, 1fr));
    }

    .home-grid-3 {
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }

    .home-grid-2 {
        grid-template-columns: 1.2fr 1fr;
    }

    .home-stat-card,
    .home-panel-card,
    .home-service-card,
    .home-step-card,
    .home-list-card,
    .home-highlight,
    .home-client-section {
        background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        border: 1px solid #dbe7ff;
        border-radius: 24px;
        padding: 24px;
        box-shadow: 0 14px 28px rgba(15, 23, 42, 0.06);
    }

    .home-stat-card {
        position: relative;
        overflow: hidden;
    }

    .home-stat-card::after {
        content: '';
        position: absolute;
        top: -35px;
        right: -35px;
        width: 110px;
        height: 110px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(59,130,246,0.12), transparent 70%);
    }

    .home-stat-card > * {
        position: relative;
        z-index: 1;
    }

    .home-stat-label {
        margin: 0 0 10px 0;
        color: #64748b;
        font-weight: 800;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .home-stat-value {
        margin: 0;
        color: #0f172a;
        font-size: 2.55rem;
        font-weight: 900;
        line-height: 1;
    }

    .home-stat-help {
        margin: 10px 0 0 0;
        color: #64748b;
        font-size: 0.95rem;
        line-height: 1.75;
    }

    .home-section {
        display: grid;
        gap: 18px;
    }

    .home-section-head {
        display: flex;
        flex-wrap: wrap;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
    }

    .home-section-title {
        margin: 0 0 10px 0;
        font-size: clamp(2rem, 3vw, 2.7rem);
        line-height: 1.06;
        color: #0f172a;
    }

    .home-section-text {
        margin: 0;
        color: #64748b;
        font-size: 1rem;
        line-height: 1.85;
        max-width: 1000px;
    }

    .home-panel-card {
        position: relative;
        overflow: hidden;
    }

    .home-panel-card::after {
        content: '';
        position: absolute;
        top: -40px;
        right: -40px;
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(59,130,246,0.14), transparent 70%);
    }

    .home-panel-card > * {
        position: relative;
        z-index: 1;
    }

    .home-panel-kicker,
    .home-client-kicker {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        min-height: 34px;
        padding: 7px 12px;
        border-radius: 999px;
        background: #eef4ff;
        border: 1px solid #dbeafe;
        color: #2563eb;
        font-weight: 800;
        font-size: 0.84rem;
        margin-bottom: 12px;
    }

    .home-client-kicker.recent {
        background: #eefcf4;
        border-color: #ccefdc;
        color: #15803d;
    }

    .home-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        display: inline-block;
        flex-shrink: 0;
    }

    .home-dot-blue { background: #2563eb; }
    .home-dot-green { background: #15803d; }
    .home-dot-red { background: #dc2626; }
    .home-dot-gold { background: #d97706; }

    .home-panel-title {
        margin: 0 0 10px 0;
        font-size: 1.35rem;
        color: #0f172a;
    }

    .home-panel-text {
        margin: 0 0 16px 0;
        color: #475569;
        line-height: 1.8;
    }

    .home-panel-link {
        text-decoration: none;
        font-weight: 800;
        color: #1d4ed8;
    }

    .home-service-card.dark {
        background:
            radial-gradient(circle at top right, rgba(59,130,246,0.12), transparent 26%),
            linear-gradient(135deg, #0f172a 0%, #173a8a 100%);
        border-color: rgba(37,99,235,0.15);
        box-shadow: 0 18px 32px rgba(15, 23, 42, 0.12);
    }

    .home-service-card.dark h3,
    .home-service-card.dark p {
        color: #ffffff;
    }

    .home-service-card.dark p {
        color: #dbeafe;
    }

    .home-service-card h3,
    .home-step-card h3,
    .home-list-card h3,
    .home-highlight h3,
    .home-client-section h3 {
        margin: 0 0 10px 0;
        font-size: 1.2rem;
        color: #0f172a;
    }

    .home-service-card p,
    .home-step-card p,
    .home-list-card p,
    .home-highlight p,
    .home-client-section p {
        margin: 0;
        color: #475569;
        line-height: 1.78;
    }

    .home-list {
        display: grid;
        gap: 14px;
    }

    .home-list-link {
        display: block;
        text-decoration: none;
        color: inherit;
        transition: 0.22s ease;
    }

    .home-list-link:hover {
        transform: translateY(-2px);
    }

    .home-list-item {
        display: grid;
        gap: 8px;
        padding: 18px 18px 16px;
        border-radius: 20px;
        background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        border: 1px solid #dbeafe;
        box-shadow: 0 10px 18px rgba(15, 23, 42, 0.04);
        transition: 0.22s ease;
    }

    .home-list-link:hover .home-list-item {
        border-color: #bfd8ff;
        box-shadow: 0 16px 28px rgba(37, 99, 235, 0.08);
    }

    .home-list-top {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }

    .home-list-code {
        color: #0f172a;
        font-weight: 900;
        font-size: 1rem;
    }

    .home-list-meta {
        color: #475569;
        line-height: 1.72;
        font-size: 0.98rem;
    }

    .home-list-cta {
        color: #1d4ed8;
        font-weight: 800;
        font-size: 0.92rem;
        margin-top: 4px;
    }

    .home-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        min-height: 36px;
        padding: 6px 12px;
        border-radius: 999px;
        font-weight: 800;
        font-size: 0.9rem;
        border: 1px solid transparent;
        white-space: nowrap;
    }

    .home-badge-urgente {
        background: #fee2e2;
        color: #b91c1c;
        border-color: #fecaca;
    }

    .home-badge-estandar {
        background: #dcfce7;
        color: #166534;
        border-color: #bbf7d0;
    }

    .home-badge-pendiente {
        background: #fef3c7;
        color: #92400e;
        border-color: #fde68a;
    }

    .home-badge-asignada {
        background: #dbeafe;
        color: #1d4ed8;
        border-color: #bfdbfe;
    }

    .home-badge-finalizada {
        background: #dcfce7;
        color: #166534;
        border-color: #bbf7d0;
    }

    .home-badge-cancelada {
        background: #fee2e2;
        color: #b91c1c;
        border-color: #fecaca;
    }

    .home-empty {
        padding: 18px;
        border-radius: 18px;
        background: #f8fafc;
        border: 1px dashed #cbd5e1;
        color: #64748b;
        line-height: 1.8;
    }

    .home-highlight {
        display: grid;
        gap: 14px;
        padding: 24px;
        border-radius: 24px;
        background:
            radial-gradient(circle at top right, rgba(59,130,246,0.10), transparent 26%),
            linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        border: 1px solid #dbe7ff;
        box-shadow: 0 14px 28px rgba(15, 23, 42, 0.06);
    }

    .home-marketing-list {
        display: grid;
        gap: 16px;
    }

    .home-marketing-row {
        padding: 18px 20px;
        border-radius: 18px;
        background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        border: 1px solid #dbeafe;
        box-shadow: 0 10px 18px rgba(15, 23, 42, 0.04);
    }

    .home-marketing-row strong {
        color: #0f172a;
    }

    .home-client-stack {
        display: grid;
        gap: 20px;
    }

    .home-client-section {
        position: relative;
        overflow: hidden;
    }

    .home-client-section::after {
        content: '';
        position: absolute;
        top: -36px;
        right: -36px;
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(59,130,246,0.10), transparent 70%);
    }

    .home-client-section > * {
        position: relative;
        z-index: 1;
    }

    .home-client-section.recent::after {
        background: radial-gradient(circle, rgba(22,163,74,0.10), transparent 70%);
    }

    .home-client-head {
        margin-bottom: 18px;
    }

    .home-client-title {
        margin: 0 0 10px 0;
        font-size: clamp(1.7rem, 2.3vw, 2.2rem);
        line-height: 1.06;
        color: #0f172a;
    }

    .home-client-text {
        margin: 0;
        color: #64748b;
        line-height: 1.82;
        max-width: 950px;
    }

    .home-client-agenda-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 16px;
    }

    .home-client-agenda-card {
        display: grid;
        gap: 10px;
        padding: 18px;
        border-radius: 22px;
        background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        border: 1px solid #dbeafe;
        box-shadow: 0 10px 18px rgba(15, 23, 42, 0.04);
        text-decoration: none;
        transition: 0.22s ease;
    }

    .home-client-agenda-card:hover {
        transform: translateY(-2px);
        border-color: #bfd8ff;
        box-shadow: 0 16px 28px rgba(37, 99, 235, 0.08);
    }

    .home-client-agenda-top {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }

    .home-client-agenda-code {
        color: #0f172a;
        font-size: 1rem;
        font-weight: 900;
    }

    .home-client-agenda-meta {
        color: #475569;
        line-height: 1.72;
        font-size: 0.98rem;
    }

    .home-client-agenda-cta {
        margin-top: 2px;
        color: #1d4ed8;
        font-weight: 800;
        font-size: 0.93rem;
    }

    .home-client-recent-list {
        display: grid;
        gap: 14px;
    }

    .home-client-recent-row {
        display: grid;
        grid-template-columns: 1.2fr auto auto;
        gap: 16px;
        align-items: center;
        padding: 18px;
        border-radius: 20px;
        background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        border: 1px solid #dbeafe;
        box-shadow: 0 10px 18px rgba(15, 23, 42, 0.04);
        text-decoration: none;
        transition: 0.22s ease;
    }

    .home-client-recent-row:hover {
        transform: translateY(-2px);
        border-color: #bfe8cf;
        box-shadow: 0 16px 28px rgba(22, 163, 74, 0.08);
    }

    .home-client-recent-main {
        display: grid;
        gap: 6px;
    }

    .home-client-recent-title {
        color: #0f172a;
        font-weight: 900;
        font-size: 1rem;
    }

    .home-client-recent-meta {
        color: #475569;
        line-height: 1.72;
        font-size: 0.98rem;
    }

    .home-client-recent-cta {
        color: #15803d;
        font-weight: 800;
        font-size: 0.92rem;
        white-space: nowrap;
    }

    @keyframes homeShine {
        0% { transform: translateX(-120%); }
        100% { transform: translateX(120%); }
    }

    @media (max-width: 1200px) {
        .home-grid-4 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .home-grid-2 {
            grid-template-columns: 1fr;
        }

        .home-client-agenda-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 950px) {
        .home-client-recent-row {
            grid-template-columns: 1fr;
            align-items: start;
        }
    }

    @media (max-width: 850px) {
        .home-grid-4,
        .home-grid-3 {
            grid-template-columns: 1fr;
        }

        .home-hero,
        .home-stat-card,
        .home-panel-card,
        .home-service-card,
        .home-step-card,
        .home-list-card,
        .home-highlight,
        .home-client-section {
            padding: 20px;
        }

        .home-title {
            font-size: 2.8rem;
        }

        .home-subtitle {
            font-size: 1rem;
        }
    }
</style>

<div class="home-wrap">

    <?php if ($rolInicio === 'admin'): ?>
        <section class="home-hero">
            <div class="home-chip">Inicio de administración · Dirección operativa</div>
            <h2 class="home-title">
                Una visión centralizada
                <span>para dirigir el servicio con agilidad</span>
            </h2>
            <p class="home-subtitle">
                Supervisa incidencias, coordina recursos, prioriza servicios y mantén el control del negocio desde un entorno pensado para tomar decisiones rápidas, claras y profesionales.
            </p>

            <div class="home-actions">
                <a href="<?= base_url('admin') ?>" class="home-btn home-btn-primary">Abrir incidencias</a>
                <a href="<?= base_url('admin/calendario') ?>" class="home-btn home-btn-secondary">Ver calendario operativo</a>
                <a href="<?= base_url('usuarios') ?>" class="home-btn home-btn-secondary">Gestionar usuarios</a>
            </div>
        </section>

        <section class="home-grid-4">
            <article class="home-stat-card">
                <p class="home-stat-label">Avisos activos</p>
                <p class="home-stat-value"><?= $dashboard['stats']['activas'] ?? 0 ?></p>
                <p class="home-stat-help">Incidencias abiertas que siguen pendientes de atención o ejecución.</p>
            </article>

            <article class="home-stat-card">
                <p class="home-stat-label">Pendientes</p>
                <p class="home-stat-value"><?= $dashboard['stats']['pendientes'] ?? 0 ?></p>
                <p class="home-stat-help">Solicitudes todavía sin cerrar y con necesidad de seguimiento administrativo.</p>
            </article>

            <article class="home-stat-card">
                <p class="home-stat-label">Urgentes</p>
                <p class="home-stat-value"><?= $dashboard['stats']['urgentes'] ?? 0 ?></p>
                <p class="home-stat-help">Servicios marcados con prioridad urgente dentro del flujo actual.</p>
            </article>

            <article class="home-stat-card">
                <p class="home-stat-label">Técnicos disponibles</p>
                <p class="home-stat-value"><?= $dashboard['stats']['tecnicos_disponibles'] ?? 0 ?></p>
                <p class="home-stat-help">Profesionales disponibles para nuevas asignaciones en el servicio.</p>
            </article>
        </section>

        <section class="home-grid-3">
            <article class="home-panel-card">
                <div class="home-panel-kicker"><span class="home-dot home-dot-blue"></span>Incidencias</div>
                <h3 class="home-panel-title">Operativa central del negocio</h3>
                <p class="home-panel-text">Gestiona altas, estados, prioridades y técnicos desde un único punto de control más claro y eficiente.</p>
                <a href="<?= base_url('admin') ?>" class="home-panel-link">Ir al panel de incidencias</a>
            </article>

            <article class="home-panel-card">
                <div class="home-panel-kicker"><span class="home-dot home-dot-green"></span>Equipo técnico</div>
                <h3 class="home-panel-title">Organización del personal</h3>
                <p class="home-panel-text">Administra técnicos, disponibilidad, cuentas vinculadas y especialidades para una asignación más precisa.</p>
                <a href="<?= base_url('tecnicos') ?>" class="home-panel-link">Gestionar técnicos</a>
            </article>

            <article class="home-panel-card">
                <div class="home-panel-kicker"><span class="home-dot home-dot-gold"></span>Estructura del negocio</div>
                <h3 class="home-panel-title">Usuarios y catálogo de servicios</h3>
                <p class="home-panel-text">Mantén ordenadas las cuentas de acceso y la cartera de especialidades disponibles para el servicio.</p>
                <a href="<?= base_url('especialidades') ?>" class="home-panel-link">Ver especialidades</a>
            </article>
        </section>

        <section class="home-grid-2">
            <article class="home-list-card">
                <h3>Próximos servicios programados</h3>
                <p style="margin-bottom:16px;">Resumen inmediato de las intervenciones próximas para anticipar carga de trabajo y prioridades.</p>

                <?php if (empty($dashboard['proximas'])): ?>
                    <div class="home-empty">No hay servicios próximos registrados en este momento.</div>
                <?php else: ?>
                    <div class="home-list">
                        <?php foreach ($dashboard['proximas'] as $inc): ?>
                            <a href="<?= base_url('admin') ?>" class="home-list-link">
                                <div class="home-list-item">
                                    <div class="home-list-top">
                                        <span class="home-list-code"><?= htmlspecialchars($inc['localizador']) ?></span>
                                        <span class="home-badge <?= homeUrgenciaClase($inc['tipo_urgencia'] ?? 'Estandar') ?>">
                                            <span class="home-dot <?= ($inc['tipo_urgencia'] ?? '') === 'Urgente' ? 'home-dot-red' : 'home-dot-green' ?>"></span>
                                            <?= htmlspecialchars(($inc['tipo_urgencia'] ?? '') === 'Urgente' ? 'Urgente' : 'Estándar') ?>
                                        </span>
                                    </div>
                                    <div class="home-list-meta">
                                        <strong><?= htmlspecialchars($inc['nombre_especialidad'] ?? 'Servicio') ?></strong> ·
                                        <?= htmlspecialchars($inc['cliente_nombre'] ?? 'Cliente') ?><br>
                                        <?= htmlspecialchars(homeFecha($inc['fecha_servicio'] ?? null)) ?>
                                    </div>
                                    <div class="home-list-cta">Abrir panel de incidencias</div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </article>

            <article class="home-highlight">
                <h3>Lectura rápida del negocio</h3>
                <p>Usuarios registrados: <strong><?= $dashboard['stats']['usuarios'] ?? 0 ?></strong></p>
                <p>Técnicos dados de alta: <strong><?= $dashboard['stats']['tecnicos'] ?? 0 ?></strong></p>
                <p>Especialidades configuradas: <strong><?= $dashboard['stats']['especialidades'] ?? 0 ?></strong></p>
                <p>Servicios previstos para hoy: <strong><?= $dashboard['stats']['servicios_hoy'] ?? 0 ?></strong></p>
                <p>Incidencias canceladas en histórico: <strong><?= $dashboard['stats']['canceladas'] ?? 0 ?></strong></p>
            </article>
        </section>

    <?php elseif ($rolInicio === 'tecnico'): ?>
        <section class="home-hero">
            <div class="home-chip">Inicio técnico · Agenda operativa</div>
            <h2 class="home-title">
                Tu agenda técnica
                <span>lista para una jornada más ágil y organizada</span>
            </h2>
            <p class="home-subtitle">
                Consulta tus intervenciones asignadas, prioriza desplazamientos y accede de inmediato a la información esencial de cada servicio desde un inicio pensado para trabajar con rapidez, orden y claridad.
            </p>

            <div class="home-actions">
                <a href="<?= base_url('mi-agenda') ?>" class="home-btn home-btn-primary">Abrir mi agenda</a>
                <a href="<?= base_url('perfil') ?>" class="home-btn home-btn-secondary">Actualizar mi perfil</a>
            </div>
        </section>

        <?php if (!empty($dashboard['sin_vinculo'])): ?>
            <section class="home-highlight">
                <h3>Cuenta pendiente de vinculación</h3>
                <p>Tu usuario tiene rol técnico, pero todavía no está asociado a una ficha del maestro de técnicos.</p>
                <p>Para ver servicios asignados y utilizar correctamente tu inicio operativo, el administrador debe completar esa vinculación.</p>
            </section>
        <?php else: ?>
            <section class="home-grid-4">
                <article class="home-stat-card">
                    <p class="home-stat-label">Servicios hoy</p>
                    <p class="home-stat-value"><?= $dashboard['stats']['hoy'] ?? 0 ?></p>
                    <p class="home-stat-help">Intervenciones planificadas para la jornada actual.</p>
                </article>

                <article class="home-stat-card">
                    <p class="home-stat-label">Próximos servicios</p>
                    <p class="home-stat-value"><?= $dashboard['stats']['proximas'] ?? 0 ?></p>
                    <p class="home-stat-help">Avisos futuros pendientes de ejecución dentro de tu agenda.</p>
                </article>

                <article class="home-stat-card">
                    <p class="home-stat-label">Urgentes</p>
                    <p class="home-stat-value"><?= $dashboard['stats']['urgentes'] ?? 0 ?></p>
                    <p class="home-stat-help">Intervenciones con prioridad urgente asignadas a tu cuenta técnica.</p>
                </article>

                <article class="home-stat-card">
                    <p class="home-stat-label">Finalizadas</p>
                    <p class="home-stat-value"><?= $dashboard['stats']['finalizadas'] ?? 0 ?></p>
                    <p class="home-stat-help">Servicios cerrados visibles como histórico operativo.</p>
                </article>
            </section>

            <section class="home-grid-2">
                <article class="home-list-card">
                    <h3>Siguientes intervenciones</h3>
                    <p style="margin-bottom:16px;">Tus próximos servicios programados en orden cronológico.</p>

                    <?php if (empty($dashboard['siguientes'])): ?>
                        <div class="home-empty">No tienes intervenciones próximas asignadas en este momento.</div>
                    <?php else: ?>
                        <div class="home-list">
                            <?php foreach ($dashboard['siguientes'] as $inc): ?>
                                <a href="<?= base_url('mi-agenda') ?>" class="home-list-link">
                                    <div class="home-list-item">
                                        <div class="home-list-top">
                                            <span class="home-list-code"><?= htmlspecialchars($inc['localizador']) ?></span>
                                            <span class="home-badge <?= homeUrgenciaClase($inc['tipo_urgencia'] ?? 'Estandar') ?>">
                                                <span class="home-dot <?= ($inc['tipo_urgencia'] ?? '') === 'Urgente' ? 'home-dot-red' : 'home-dot-green' ?>"></span>
                                                <?= htmlspecialchars(($inc['tipo_urgencia'] ?? '') === 'Urgente' ? 'Urgente' : 'Estándar') ?>
                                            </span>
                                        </div>
                                        <div class="home-list-meta">
                                            <strong><?= htmlspecialchars($inc['nombre_especialidad'] ?? 'Servicio') ?></strong> ·
                                            <?= htmlspecialchars($inc['cliente_nombre'] ?? 'Cliente') ?><br>
                                            <?= htmlspecialchars(homeFecha($inc['fecha_servicio'] ?? null)) ?><br>
                                            <?= htmlspecialchars($inc['direccion'] ?? 'Dirección no disponible') ?>
                                        </div>
                                        <div class="home-list-cta">Abrir agenda operativa</div>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </article>

                <article class="home-highlight">
                    <h3>Acceso directo a tu operativa diaria</h3>
                    <p>Este inicio está pensado para ayudarte a trabajar con mayor rapidez, menos fricción y una mejor visión de cada desplazamiento.</p>
                    <p><strong>Técnico:</strong> <?= htmlspecialchars($dashboard['tecnico']['nombre_completo'] ?? '') ?></p>
                    <p><strong>Especialidad:</strong> <?= htmlspecialchars($dashboard['tecnico']['nombre_especialidad'] ?? 'No disponible') ?></p>
                    <p>Todo lo esencial para tu jornada, concentrado en un único espacio de trabajo.</p>
                </article>
            </section>
        <?php endif; ?>

    <?php elseif ($rolInicio === 'particular'): ?>
        <section class="home-hero">
            <div class="home-chip">Área cliente · Servicio y seguimiento</div>
            <h2 class="home-title">
                Gestiona tus incidencias
                <span>con seguimiento profesional y total claridad</span>
            </h2>
            <p class="home-subtitle">
                Registra nuevos servicios, revisa el estado de cada aviso y mantén toda tu información organizada desde un espacio diseñado para ofrecer una experiencia más cómoda, clara y profesional.
            </p>

            <div class="home-actions">
                <a href="<?= base_url('nueva-solicitud') ?>" class="home-btn home-btn-primary">Crear nueva solicitud</a>
                <a href="<?= base_url('mis-avisos') ?>" class="home-btn home-btn-secondary">Ver mis avisos</a>
                <a href="<?= base_url('perfil') ?>" class="home-btn home-btn-secondary">Gestionar mi perfil</a>
            </div>
        </section>

        <section class="home-grid-4">
            <article class="home-stat-card">
                <p class="home-stat-label">Avisos registrados</p>
                <p class="home-stat-value"><?= $dashboard['stats']['total'] ?? 0 ?></p>
                <p class="home-stat-help">Número total de solicitudes creadas desde tu cuenta.</p>
            </article>

            <article class="home-stat-card">
                <p class="home-stat-label">Activos</p>
                <p class="home-stat-value"><?= $dashboard['stats']['activas'] ?? 0 ?></p>
                <p class="home-stat-help">Avisos que siguen abiertos o en proceso de atención.</p>
            </article>

            <article class="home-stat-card">
                <p class="home-stat-label">Finalizados</p>
                <p class="home-stat-value"><?= $dashboard['stats']['finalizadas'] ?? 0 ?></p>
                <p class="home-stat-help">Intervenciones completadas y ya cerradas dentro del sistema.</p>
            </article>

            <article class="home-stat-card">
                <p class="home-stat-label">Urgentes</p>
                <p class="home-stat-value"><?= $dashboard['stats']['urgentes'] ?? 0 ?></p>
                <p class="home-stat-help">Solicitudes urgentes registradas y todavía visibles en tu historial.</p>
            </article>
        </section>

        <section class="home-client-stack">
            <article class="home-client-section">
                <div class="home-client-head">
                    <div class="home-client-kicker">
                        <span class="home-dot home-dot-blue"></span>
                        Agenda próxima
                    </div>
                    <h3 class="home-client-title">Tus próximos servicios programados</h3>
                    <p class="home-client-text">
                        Aquí tienes los avisos más cercanos con acceso directo al registro exacto de cada incidencia para que puedas revisar su seguimiento completo sin perder tiempo buscando dentro del panel.
                    </p>
                </div>

                <?php if (empty($dashboard['proximos'])): ?>
                    <div class="home-empty">No tienes avisos futuros programados en este momento.</div>
                <?php else: ?>
                    <div class="home-client-agenda-grid">
                        <?php foreach ($dashboard['proximos'] as $aviso): ?>
                            <a href="<?= base_url('mis-avisos') ?>#aviso-<?= htmlspecialchars($aviso['id']) ?>" class="home-client-agenda-card">
                                <div class="home-client-agenda-top">
                                    <span class="home-client-agenda-code"><?= htmlspecialchars($aviso['localizador']) ?></span>
                                    <span class="home-badge <?= homeEstadoClase($aviso['estado'] ?? 'Pendiente') ?>">
                                        <span class="home-dot <?= ($aviso['estado'] ?? '') === 'Asignada' ? 'home-dot-blue' : (($aviso['estado'] ?? '') === 'Finalizada' ? 'home-dot-green' : (($aviso['estado'] ?? '') === 'Cancelada' ? 'home-dot-red' : 'home-dot-gold')) ?>"></span>
                                        <?= htmlspecialchars($aviso['estado'] ?? 'Pendiente') ?>
                                    </span>
                                </div>

                                <div class="home-client-agenda-meta">
                                    <strong><?= htmlspecialchars($aviso['nombre_especialidad'] ?? 'Servicio') ?></strong><br>
                                    <?= htmlspecialchars(homeFecha($aviso['fecha_servicio'] ?? null)) ?><br>
                                    <?= htmlspecialchars($aviso['direccion'] ?? 'Dirección no disponible') ?>
                                </div>

                                <div class="home-client-agenda-cta">Abrir este aviso concreto</div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </article>

            <article class="home-client-section recent">
                <div class="home-client-head">
                    <div class="home-client-kicker recent">
                        <span class="home-dot home-dot-green"></span>
                        Actividad reciente
                    </div>
                    <h3 class="home-client-title">Últimos movimientos de tu cuenta</h3>
                    <p class="home-client-text">
                        Este bloque reúne tus avisos más recientes para que identifiques rápidamente qué has solicitado, qué urgencia tiene cada servicio y qué técnico figura actualmente como referencia del aviso.
                    </p>
                </div>

                <?php if (empty($dashboard['recientes'])): ?>
                    <div class="home-empty">Aún no hay actividad registrada en tu cuenta.</div>
                <?php else: ?>
                    <div class="home-client-recent-list">
                        <?php foreach ($dashboard['recientes'] as $aviso): ?>
                            <a href="<?= base_url('mis-avisos') ?>#aviso-<?= htmlspecialchars($aviso['id']) ?>" class="home-client-recent-row">
                                <div class="home-client-recent-main">
                                    <div class="home-client-recent-title"><?= htmlspecialchars($aviso['localizador']) ?></div>
                                    <div class="home-client-recent-meta">
                                        <?= htmlspecialchars($aviso['nombre_especialidad'] ?? 'Servicio') ?> ·
                                        <?= htmlspecialchars($aviso['tecnico_nombre'] ?: 'Pendiente de asignación') ?><br>
                                        <?= htmlspecialchars(homeFecha($aviso['fecha_servicio'] ?? null)) ?>
                                    </div>
                                </div>

                                <span class="home-badge <?= homeUrgenciaClase($aviso['tipo_urgencia'] ?? 'Estandar') ?>">
                                    <span class="home-dot <?= ($aviso['tipo_urgencia'] ?? '') === 'Urgente' ? 'home-dot-red' : 'home-dot-green' ?>"></span>
                                    <?= htmlspecialchars(($aviso['tipo_urgencia'] ?? '') === 'Urgente' ? 'Urgente' : 'Estándar') ?>
                                </span>

                                <span class="home-client-recent-cta">Ver aviso exacto</span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </article>
        </section>

    <?php else: ?>
        <section class="home-hero">
            <div class="home-chip">Asistencia técnica doméstica · Gestión integral</div>
            <h2 class="home-title">
                La plataforma profesional
                <span>para coordinar asistencia técnica con más control y mejor imagen</span>
            </h2>
            <p class="home-subtitle">
                ReparaYa reúne en un único entorno la atención al cliente, la coordinación operativa y la gestión técnica para ofrecer un servicio más ordenado, más rápido y mucho más profesional.
            </p>

            <div class="home-actions">
                <a href="<?= base_url('login') ?>" class="home-btn home-btn-primary">Acceder a la plataforma</a>
                <a href="<?= base_url('register') ?>" class="home-btn home-btn-secondary">Crear cuenta de cliente</a>
            </div>
        </section>

        <section class="home-grid-3">
            <article class="home-service-card dark">
                <h3>Más orden en cada servicio</h3>
                <p>Centraliza incidencias, estados, equipo técnico y seguimiento del cliente en una misma plataforma clara y bien estructurada.</p>
            </article>

            <article class="home-service-card dark">
                <h3>Una imagen más profesional</h3>
                <p>Ofrece una experiencia moderna, cuidada y coherente para cliente, técnico y administración desde el primer acceso.</p>
            </article>

            <article class="home-service-card dark">
                <h3>Mayor control operativo</h3>
                <p>Mejora la coordinación diaria del servicio con una visión unificada de avisos, agenda, prioridades y recursos disponibles.</p>
            </article>
        </section>

        <section class="home-grid-2">
            <article class="home-list-card">
                <h3>Por qué elegir ReparaYa</h3>
                <div class="home-marketing-list">
                    <div class="home-marketing-row">
                        <strong>Convierte la gestión del servicio en una ventaja competitiva.</strong><br>
                        Una operativa más clara transmite confianza, orden y profesionalidad desde la primera solicitud.
                    </div>
                    <div class="home-marketing-row">
                        <strong>Mejora la experiencia de cliente y del equipo.</strong><br>
                        Toda la información importante queda accesible, organizada y preparada para trabajar con más fluidez.
                    </div>
                    <div class="home-marketing-row">
                        <strong>Refuerza la imagen de tu negocio.</strong><br>
                        Una plataforma bien diseñada no solo organiza el trabajo: también proyecta una marca más sólida y moderna.
                    </div>
                </div>
            </article>

            <article class="home-highlight">
                <h3>Haz que tu servicio se vea tan profesional como realmente es</h3>
                <p>ReparaYa está pensada para negocios que quieren ofrecer una atención más seria, más ordenada y más convincente.</p>
                <p>Si buscas una herramienta que ayude a gestionar mejor el día a día y al mismo tiempo transmita una imagen de calidad, este es el punto de partida adecuado.</p>
                <p>Accede o regístrate para descubrir una forma más profesional de organizar tu servicio técnico.</p>
            </article>
        </section>
    <?php endif; ?>

</div>