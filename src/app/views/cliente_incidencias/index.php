<?php
$incidencias = $incidencias ?? [];

function clienteFecha(?string $fecha): string
{
    if (!$fecha || strtotime($fecha) === false) {
        return 'Fecha no disponible';
    }

    return date('d/m/Y H:i', strtotime($fecha));
}

function clienteEstadoClase(string $estado): string
{
    return match ($estado) {
        'Pendiente' => 'cli-badge-pendiente',
        'Asignada' => 'cli-badge-asignada',
        'Finalizada' => 'cli-badge-finalizada',
        'Cancelada' => 'cli-badge-cancelada',
        default => 'cli-badge-pendiente',
    };
}

function clienteUrgenciaClase(string $urgencia): string
{
    return $urgencia === 'Urgente' ? 'cli-badge-urgente' : 'cli-badge-estandar';
}

$totalAvisos = count($incidencias);
$activos = 0;
$finalizados = 0;
$cancelados = 0;
$urgentes = 0;

$seguimiento = [];
$historico = [];
$eventosCalendario = [];

foreach ($incidencias as $inc) {
    if (in_array(($inc['estado'] ?? ''), ['Pendiente', 'Asignada'], true)) {
        $activos++;
        $seguimiento[] = $inc;
    }

    if (($inc['estado'] ?? '') === 'Finalizada') {
        $finalizados++;
        $historico[] = $inc;
    }

    if (($inc['estado'] ?? '') === 'Cancelada') {
        $cancelados++;
        $historico[] = $inc;
    }

    if (($inc['tipo_urgencia'] ?? '') === 'Urgente' && ($inc['estado'] ?? '') !== 'Cancelada') {
        $urgentes++;
    }

    if (($inc['estado'] ?? '') !== 'Cancelada' && !empty($inc['fecha_servicio']) && strtotime($inc['fecha_servicio']) !== false) {
        $color = ($inc['tipo_urgencia'] ?? '') === 'Urgente' ? '#dc2626' : '#15803d';

        $eventosCalendario[] = [
            'id' => (string) ($inc['id'] ?? ''),
            'title' => ($inc['localizador'] ?? 'Aviso') . ' — ' . ($inc['nombre_especialidad'] ?? 'Servicio'),
            'start' => date('Y-m-d\TH:i:s', strtotime($inc['fecha_servicio'])),
            'allDay' => false,
            'display' => 'list-item',
            'backgroundColor' => $color,
            'borderColor' => $color,
            'extendedProps' => [
                'targetId' => 'aviso-' . ($inc['id'] ?? ''),
                'estado' => $inc['estado'] ?? '',
                'direccion' => $inc['direccion'] ?? '',
                'tecnico' => $inc['tecnico_nombre'] ?: 'Pendiente de asignación',
                'localizador' => $inc['localizador'] ?? '',
            ]
        ];
    }
}

usort($seguimiento, function ($a, $b) {
    return strtotime($a['fecha_servicio']) <=> strtotime($b['fecha_servicio']);
});

usort($historico, function ($a, $b) {
    return strtotime($b['fecha_servicio']) <=> strtotime($a['fecha_servicio']);
});

function renderAvisoCliente(array $inc, bool $mostrarAcciones = true): void
{
    $id = (int) ($inc['id'] ?? 0);
    $estado = $inc['estado'] ?? 'Pendiente';
    $urgencia = $inc['tipo_urgencia'] ?? 'Estandar';
    ?>
    <article class="cli-card" id="aviso-<?= htmlspecialchars((string) $id) ?>">
        <div class="cli-card-inner">
            <div class="cli-card-top">
                <div>
                    <div class="cli-card-code"><?= htmlspecialchars($inc['localizador'] ?? 'Aviso') ?></div>
                    <div class="cli-card-date">
                        <strong>Fecha del servicio:</strong> <?= htmlspecialchars(clienteFecha($inc['fecha_servicio'] ?? null)) ?>
                    </div>
                </div>

                <div class="cli-card-badges">
                    <span class="cli-badge <?= clienteUrgenciaClase($urgencia) ?>">
                        <span class="cli-dot <?= $urgencia === 'Urgente' ? 'cli-dot-red' : 'cli-dot-green' ?>"></span>
                        <?= htmlspecialchars($urgencia === 'Urgente' ? 'Urgente' : 'Estándar') ?>
                    </span>

                    <span class="cli-badge <?= clienteEstadoClase($estado) ?>">
                        <span class="cli-dot <?=
                            $estado === 'Asignada'
                                ? 'cli-dot-blue'
                                : ($estado === 'Finalizada'
                                    ? 'cli-dot-green'
                                    : ($estado === 'Cancelada' ? 'cli-dot-red' : 'cli-dot-gold'))
                        ?>"></span>
                        <?= htmlspecialchars($estado) ?>
                    </span>
                </div>
            </div>

            <div class="cli-card-grid">
                <div class="cli-info-box">
                    <span class="cli-info-label">Especialidad</span>
                    <span class="cli-info-value"><?= htmlspecialchars($inc['nombre_especialidad'] ?? 'No disponible') ?></span>
                </div>

                <div class="cli-info-box">
                    <span class="cli-info-label">Técnico asignado</span>
                    <span class="cli-info-value"><?= htmlspecialchars($inc['tecnico_nombre'] ?: 'Pendiente de asignación') ?></span>
                </div>

                <div class="cli-info-box">
                    <span class="cli-info-label">Teléfono de contacto</span>
                    <span class="cli-info-value"><?= htmlspecialchars($inc['telefono_contacto'] ?? 'No disponible') ?></span>
                </div>

                <div class="cli-info-box">
                    <span class="cli-info-label">Dirección</span>
                    <span class="cli-info-value"><?= htmlspecialchars($inc['direccion'] ?? 'No disponible') ?></span>
                </div>
            </div>

            <div class="cli-description-box">
                <h4 class="cli-description-title">Descripción de la incidencia</h4>
                <p class="cli-description-text"><?= nl2br(htmlspecialchars($inc['descripcion'] ?? 'Sin descripción disponible')) ?></p>
            </div>

            <?php if ($mostrarAcciones): ?>
                <div class="cli-actions">
                    <?php if (!empty($inc['puede_cancelar'])): ?>
                        <form action="<?= base_url('mis-avisos/cancelar') ?>" method="POST" onsubmit="return confirm('¿Seguro que quieres cancelar este aviso?');">
                            <input type="hidden" name="id" value="<?= htmlspecialchars((string) $id) ?>">
                            <button type="submit" class="cli-danger-btn">Cancelar aviso</button>
                        </form>
                    <?php else: ?>
                        <div class="cli-disabled-note">
                            <?= htmlspecialchars($inc['motivo_no_cancelable'] ?? 'Este aviso no admite cancelación.') ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="cli-history-note">
                    Aviso archivado dentro del historial de tu cuenta.
                </div>
            <?php endif; ?>
        </div>
    </article>
    <?php
}
?>

<style>
    .cli-wrap {
        display: grid;
        gap: 28px;
    }

    .cli-hero {
        position: relative;
        overflow: hidden;
        border-radius: 30px;
        padding: 36px 30px 40px;
        background: linear-gradient(180deg, #ffffff 0%, #f7faff 100%);
        border: 1px solid #dbe7ff;
        box-shadow: 0 18px 34px rgba(15, 23, 42, 0.06);
        text-align: center;
    }

    .cli-hero::before {
        content: '';
        position: absolute;
        top: -90px;
        right: -90px;
        width: 240px;
        height: 240px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(59,130,246,0.14), transparent 70%);
    }

    .cli-hero::after {
        content: '';
        position: absolute;
        bottom: -100px;
        left: -100px;
        width: 250px;
        height: 250px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(37,99,235,0.10), transparent 70%);
    }

    .cli-hero > * {
        position: relative;
        z-index: 1;
    }

    .cli-chip {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 10px 18px;
        border-radius: 999px;
        background: #e8f1ff;
        border: 1px solid #cfe0ff;
        color: #1d4ed8;
        font-weight: 800;
        font-size: 0.94rem;
        margin-bottom: 18px;
    }

    .cli-title {
        margin: 0;
        font-size: clamp(2.8rem, 4.8vw, 4.5rem);
        line-height: 0.98;
        letter-spacing: -0.03em;
        color: #0f172a;
        font-weight: 900;
    }

    .cli-subtitle {
        margin: 18px auto 0;
        max-width: 1150px;
        color: #475569;
        font-size: 1.08rem;
        line-height: 1.95;
        text-wrap: balance;
    }

    .cli-summary-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 18px;
    }

    .cli-summary-card,
    .cli-panel {
        background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        border: 1px solid #dbe7ff;
        border-radius: 24px;
        padding: 24px;
        box-shadow: 0 14px 28px rgba(15, 23, 42, 0.06);
    }

    .cli-summary-card {
        position: relative;
        overflow: hidden;
    }

    .cli-summary-card::after {
        content: '';
        position: absolute;
        top: -34px;
        right: -34px;
        width: 110px;
        height: 110px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(59,130,246,0.12), transparent 70%);
    }

    .cli-summary-card > * {
        position: relative;
        z-index: 1;
    }

    .cli-summary-label {
        margin: 0 0 10px 0;
        color: #64748b;
        font-weight: 800;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .cli-summary-value {
        margin: 0;
        color: #0f172a;
        font-size: 2.4rem;
        font-weight: 900;
        line-height: 1;
    }

    .cli-summary-help {
        margin: 10px 0 0 0;
        color: #64748b;
        font-size: 0.95rem;
        line-height: 1.75;
    }

    .cli-panel-head {
        display: flex;
        flex-wrap: wrap;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 20px;
    }

    .cli-panel-kicker {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        min-height: 34px;
        padding: 8px 14px;
        border-radius: 999px;
        background: #eef4ff;
        border: 1px solid #dbeafe;
        color: #2563eb;
        font-weight: 800;
        font-size: 0.9rem;
        margin-bottom: 12px;
    }

    .cli-panel-kicker.green {
        background: #eefcf4;
        border-color: #ccefdc;
        color: #15803d;
    }

    .cli-panel-title {
        margin: 0 0 10px 0;
        font-size: clamp(1.9rem, 2.7vw, 2.45rem);
        line-height: 1.08;
        color: #0f172a;
    }

    .cli-panel-text {
        margin: 0;
        color: #64748b;
        font-size: 1rem;
        line-height: 1.85;
        max-width: 1020px;
    }

    .cli-top-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
    }

    .cli-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 50px;
        padding: 12px 20px;
        border-radius: 16px;
        text-decoration: none;
        font-weight: 800;
        font-size: 1rem;
    }

    .cli-btn-primary {
        color: #ffffff;
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        box-shadow: 0 14px 24px rgba(37, 99, 235, 0.18);
    }

    .cli-btn-secondary {
        color: #1d4ed8;
        background: #eaf2ff;
        border: 1px solid #dbeafe;
    }

    .cli-legend {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
    }

    .cli-legend-item,
    .cli-badge {
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

    .cli-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        display: inline-block;
        flex-shrink: 0;
    }

    .cli-dot-blue { background: #2563eb; }
    .cli-dot-green { background: #15803d; }
    .cli-dot-red { background: #dc2626; }
    .cli-dot-gold { background: #d97706; }

    .cli-badge-urgente,
    .cli-legend-item.urgent {
        background: #fee2e2;
        color: #b91c1c;
        border-color: #fecaca;
    }

    .cli-badge-estandar,
    .cli-legend-item.standard {
        background: #dcfce7;
        color: #166534;
        border-color: #bbf7d0;
    }

    .cli-badge-pendiente {
        background: #fef3c7;
        color: #92400e;
        border-color: #fde68a;
    }

    .cli-badge-asignada {
        background: #dbeafe;
        color: #1d4ed8;
        border-color: #bfdbfe;
    }

    .cli-badge-finalizada {
        background: #dcfce7;
        color: #166534;
        border-color: #bbf7d0;
    }

    .cli-badge-cancelada {
        background: #fee2e2;
        color: #b91c1c;
        border-color: #fecaca;
    }

    .cli-calendar-wrap {
        margin-top: 18px;
    }

    #clienteCalendario .fc {
        font-family: var(--rp-font-ui, 'Rajdhani', Arial, sans-serif);
    }

    #clienteCalendario .fc-toolbar-title {
        color: #0f172a;
        font-size: clamp(1.8rem, 2.7vw, 2.5rem);
        font-weight: 900;
        text-transform: lowercase;
    }

    #clienteCalendario .fc-button {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%) !important;
        border: none !important;
        box-shadow: 0 12px 22px rgba(37, 99, 235, 0.18);
        border-radius: 12px !important;
        font-weight: 800;
        text-transform: lowercase;
    }

    #clienteCalendario .fc-theme-standard td,
    #clienteCalendario .fc-theme-standard th,
    #clienteCalendario .fc-theme-standard .fc-scrollgrid {
        border-color: #dbe7ff;
    }

    #clienteCalendario .fc-daygrid-day-number,
    #clienteCalendario .fc-col-header-cell-cushion {
        color: #2563eb;
        font-weight: 800;
        text-decoration: none;
    }

    #clienteCalendario .fc-day-today {
        background: #fffbe8 !important;
    }

    #clienteCalendario .fc-daygrid-event {
        background: transparent !important;
        border: none !important;
        padding: 0 !important;
        margin-top: 4px !important;
    }

    #clienteCalendario .fc-daygrid-event-harness {
        margin-top: 3px !important;
    }

    #clienteCalendario .fc-daygrid-dot-event {
        padding: 2px 0 !important;
    }

    #clienteCalendario .fc-daygrid-event-dot {
        width: 10px !important;
        height: 10px !important;
        border-width: 0 !important;
        border-radius: 50% !important;
        margin-right: 8px !important;
    }

    #clienteCalendario .fc-event-title,
    #clienteCalendario .fc-event-time {
        color: #0f172a !important;
        font-weight: 700;
    }

    .cli-card-list {
        display: grid;
        gap: 18px;
    }

    .cli-card {
        position: relative;
        overflow: hidden;
        border-radius: 24px;
        border: 1px solid #dbe7ff;
        background: linear-gradient(180deg, #ffffff 0%, #fbfdff 100%);
        box-shadow: 0 12px 24px rgba(15, 23, 42, 0.05);
        scroll-margin-top: 24px;
        transition: 0.22s ease;
    }

    .cli-card::after {
        content: '';
        position: absolute;
        top: -40px;
        right: -40px;
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(59,130,246,0.10), transparent 70%);
    }

    .cli-card.history::after {
        background: radial-gradient(circle, rgba(148,163,184,0.12), transparent 70%);
    }

    .cli-card.highlighted {
        border-color: #93c5fd;
        box-shadow: 0 0 0 4px rgba(147, 197, 253, 0.18), 0 18px 30px rgba(37, 99, 235, 0.10);
    }

    .cli-card-inner {
        position: relative;
        z-index: 1;
        padding: 24px;
    }

    .cli-card-top {
        display: flex;
        flex-wrap: wrap;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 18px;
    }

    .cli-card-code {
        margin: 0 0 8px 0;
        font-size: 1.45rem;
        color: #0f172a;
        font-weight: 900;
        letter-spacing: -0.02em;
    }

    .cli-card-date {
        color: #64748b;
        font-size: 1rem;
        line-height: 1.75;
    }

    .cli-card-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .cli-card-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 18px;
    }

    .cli-info-box {
        padding: 16px;
        border-radius: 18px;
        background: #f8fbff;
        border: 1px solid #dbeafe;
    }

    .cli-info-label {
        display: block;
        margin-bottom: 6px;
        color: #64748b;
        font-size: 0.88rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .cli-info-value {
        color: #0f172a;
        font-size: 1rem;
        font-weight: 700;
        line-height: 1.68;
        word-break: break-word;
    }

    .cli-description-box {
        padding: 18px;
        border-radius: 20px;
        background: linear-gradient(180deg, #fbfdff 0%, #f8fbff 100%);
        border: 1px solid #dbeafe;
        margin-bottom: 16px;
    }

    .cli-description-title {
        margin: 0 0 10px 0;
        color: #0f172a;
        font-size: 1.05rem;
        font-weight: 800;
    }

    .cli-description-text {
        margin: 0;
        color: #475569;
        line-height: 1.85;
        font-size: 1rem;
    }

    .cli-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        align-items: center;
    }

    .cli-danger-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 46px;
        padding: 11px 18px;
        border-radius: 14px;
        border: none;
        font-weight: 800;
        font-size: 0.98rem;
        color: #ffffff;
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        box-shadow: 0 12px 20px rgba(220, 38, 38, 0.16);
        cursor: pointer;
    }

    .cli-disabled-note,
    .cli-history-note,
    .cli-empty {
        padding: 16px 18px;
        border-radius: 18px;
        font-weight: 700;
        line-height: 1.75;
    }

    .cli-disabled-note {
        background: #fff7ed;
        border: 1px solid #fed7aa;
        color: #9a3412;
    }

    .cli-history-note {
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        color: #475569;
    }

    .cli-empty {
        background: #f8fbff;
        border: 1px solid #dbeafe;
        color: #64748b;
    }

    @media (max-width: 1100px) {
        .cli-summary-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .cli-card-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 850px) {
        .cli-summary-grid,
        .cli-card-grid {
            grid-template-columns: 1fr;
        }

        .cli-hero,
        .cli-summary-card,
        .cli-panel {
            padding: 20px;
        }

        .cli-title {
            font-size: 2.6rem;
        }

        .cli-subtitle {
            font-size: 1rem;
        }
    }
</style>

<div class="cli-wrap">
    <section class="cli-hero">
        <div class="cli-chip">Área cliente · Seguimiento de servicios</div>
        <h2 class="cli-title">Mis avisos</h2>
        <p class="cli-subtitle">
            Consulta tu calendario de servicios, revisa qué incidencias siguen en seguimiento y accede a un historial claro de todas las solicitudes registradas desde tu cuenta.
        </p>
    </section>

    <?php if (isset($_GET['ok']) && $_GET['ok'] === 'creada'): ?>
        <div class="message-success">
            La nueva solicitud se ha registrado correctamente y ya aparece en tu panel de avisos.
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['ok']) && $_GET['ok'] === 'cancelada'): ?>
        <div class="message-success">
            El aviso ha sido cancelado correctamente.
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="message-error">
            <?php
            $mensajesError = [
                'aviso_invalido' => 'No se ha podido procesar la solicitud de cancelación.',
                'aviso_no_encontrado' => 'El aviso indicado no existe o no pertenece a tu cuenta.',
                'fuera_de_plazo' => 'No se puede cancelar el aviso porque faltan menos de 48 horas para la cita o el estado actual no lo permite.',
                'cancelacion_fallida' => 'No se ha podido completar la cancelación del aviso.'
            ];
            echo htmlspecialchars($mensajesError[$_GET['error']] ?? 'Se ha producido un error al procesar la operación.');
            ?>
        </div>
    <?php endif; ?>

    <section class="cli-summary-grid">
        <article class="cli-summary-card">
            <p class="cli-summary-label">Avisos totales</p>
            <p class="cli-summary-value"><?= $totalAvisos ?></p>
            <p class="cli-summary-help">Total de incidencias registradas desde tu cuenta.</p>
        </article>

        <article class="cli-summary-card">
            <p class="cli-summary-label">En seguimiento</p>
            <p class="cli-summary-value"><?= $activos ?></p>
            <p class="cli-summary-help">Avisos activos pendientes o ya asignados a técnico.</p>
        </article>

        <article class="cli-summary-card">
            <p class="cli-summary-label">Finalizados</p>
            <p class="cli-summary-value"><?= $finalizados ?></p>
            <p class="cli-summary-help">Servicios cerrados correctamente dentro del historial.</p>
        </article>

        <article class="cli-summary-card">
            <p class="cli-summary-label">Urgentes</p>
            <p class="cli-summary-value"><?= $urgentes ?></p>
            <p class="cli-summary-help">Solicitudes con prioridad urgente todavía visibles.</p>
        </article>
    </section>

    <section class="cli-panel">
        <div class="cli-panel-head">
            <div>
                <div class="cli-panel-kicker">
                    <span class="cli-dot cli-dot-blue"></span>
                    Calendario personal del cliente
                </div>
                <h3 class="cli-panel-title">Calendario de tus servicios</h3>
                <p class="cli-panel-text">
                    Aquí se muestran tus avisos no cancelados en formato calendario para que identifiques rápidamente fechas, prioridades y citas previstas. Al pulsar sobre un evento, irás directamente al aviso correspondiente dentro de esta misma página.
                </p>
            </div>

            <div class="cli-top-actions">
                <a href="<?= base_url('nueva-solicitud') ?>" class="cli-btn cli-btn-primary">Nueva solicitud</a>
                <a href="<?= base_url('perfil') ?>" class="cli-btn cli-btn-secondary">Mi perfil</a>
            </div>
        </div>

        <div class="cli-legend">
            <span class="cli-legend-item urgent">
                <span class="cli-dot cli-dot-red"></span>
                Urgente
            </span>
            <span class="cli-legend-item standard">
                <span class="cli-dot cli-dot-green"></span>
                Estándar
            </span>
        </div>

        <div class="cli-calendar-wrap">
            <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet" />
            <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
            <div id="clienteCalendario"></div>
        </div>
    </section>

    <section class="cli-panel">
        <div class="cli-panel-head">
            <div>
                <div class="cli-panel-kicker">
                    <span class="cli-dot cli-dot-blue"></span>
                    Seguimiento activo
                </div>
                <h3 class="cli-panel-title">Avisos actualmente en proceso</h3>
                <p class="cli-panel-text">
                    En este bloque encontrarás las incidencias que todavía siguen vivas dentro del servicio. Aquí se concentran las solicitudes pendientes o ya asignadas y, cuando proceda, la acción de cancelación permitida por la regla de las 48 horas.
                </p>
            </div>
        </div>

        <?php if (empty($seguimiento)): ?>
            <div class="cli-empty">
                No tienes avisos activos en seguimiento en este momento.
            </div>
        <?php else: ?>
            <div class="cli-card-list">
                <?php foreach ($seguimiento as $inc): ?>
                    <?php renderAvisoCliente($inc, true); ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>

    <section class="cli-panel">
        <div class="cli-panel-head">
            <div>
                <div class="cli-panel-kicker green">
                    <span class="cli-dot cli-dot-green"></span>
                    Historial de tu cuenta
                </div>
                <h3 class="cli-panel-title">Avisos cerrados o archivados</h3>
                <p class="cli-panel-text">
                    Este bloque separa claramente las incidencias ya finalizadas o canceladas para que puedas consultar tu historial sin mezclarlo con los avisos que siguen en seguimiento.
                </p>
            </div>
        </div>

        <?php if (empty($historico)): ?>
            <div class="cli-empty">
                Todavía no tienes avisos finalizados o cancelados dentro de tu historial.
            </div>
        <?php else: ?>
            <div class="cli-card-list">
                <?php foreach ($historico as $inc): ?>
                    <div class="cli-card history">
                        <?php renderAvisoCliente($inc, false); ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('clienteCalendario');

    if (!calendarEl) {
        return;
    }

    const eventos = <?= json_encode($eventosCalendario, JSON_UNESCAPED_UNICODE) ?>;

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'es',
        editable: false,
        selectable: false,
        eventDisplay: 'list-item',
        dayMaxEvents: 4,
        displayEventTime: true,
        eventTimeFormat: {
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
        },
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,listWeek'
        },
        buttonText: {
            today: 'today',
            month: 'month',
            list: 'lista'
        },
        events: eventos,
        eventDidMount: function(info) {
            const dot = info.el.querySelector('.fc-daygrid-event-dot');
            if (dot) {
                dot.style.borderColor = info.event.backgroundColor;
                dot.style.backgroundColor = info.event.backgroundColor;
            }
        },
        eventClick: function(info) {
            info.jsEvent.preventDefault();

            const targetId = info.event.extendedProps.targetId;
            const target = document.getElementById(targetId);

            if (target) {
                document.querySelectorAll('.cli-card.highlighted').forEach(function(card) {
                    card.classList.remove('highlighted');
                });

                target.classList.add('highlighted');
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });

                setTimeout(function () {
                    target.classList.remove('highlighted');
                }, 2200);
            }
        }
    });

    calendar.render();

    const hash = window.location.hash;
    if (hash && hash.startsWith('#aviso-')) {
        const target = document.querySelector(hash);
        if (target) {
            setTimeout(function () {
                target.classList.add('highlighted');
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });

                setTimeout(function () {
                    target.classList.remove('highlighted');
                }, 2200);
            }, 250);
        }
    }
});
</script>