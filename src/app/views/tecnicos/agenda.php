<?php
$ahora = new DateTime();
$total = count($incidencias);
$proximas = 0;
$finalizadas = 0;

foreach ($incidencias as $inc) {
    $fechaInc = new DateTime($inc['fecha_servicio']);

    if ($inc['estado'] === 'Finalizada') {
        $finalizadas++;
    }

    if ($fechaInc >= $ahora && $inc['estado'] !== 'Finalizada') {
        $proximas++;
    }
}
?>

<style>
    .tec-agenda-wrap {
        display: grid;
        gap: 26px;
    }

    .tec-hero {
        position: relative;
        overflow: hidden;
        border-radius: 28px;
        padding: 34px 30px 36px;
        background: linear-gradient(180deg, #ffffff 0%, #f7faff 100%);
        border: 1px solid #d9e6ff;
        box-shadow: 0 18px 36px rgba(15, 23, 42, 0.07);
    }

    .tec-hero::before {
        content: '';
        position: absolute;
        top: -70px;
        right: -70px;
        width: 220px;
        height: 220px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(59,130,246,0.14), transparent 70%);
    }

    .tec-hero::after {
        content: '';
        position: absolute;
        bottom: -90px;
        left: -90px;
        width: 240px;
        height: 240px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(37,99,235,0.10), transparent 70%);
    }

    .tec-hero > * {
        position: relative;
        z-index: 1;
    }

    .tec-kicker {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 9px 16px;
        border-radius: 999px;
        background: #e8f1ff;
        border: 1px solid #cfe0ff;
        color: #1d4ed8;
        font-weight: 700;
        font-size: 0.92rem;
        margin-bottom: 16px;
    }

    .tec-hero-center {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .tec-title {
        margin: 0;
        font-size: clamp(2.7rem, 4.8vw, 4.6rem);
        line-height: 0.98;
        letter-spacing: -0.03em;
        color: #0f172a;
        font-weight: 900;
        text-wrap: balance;
    }

    .tec-title-highlight {
        display: block;
        background: linear-gradient(90deg, #0f172a 0%, #1d4ed8 55%, #2563eb 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        color: transparent;
    }

    .tec-subtitle {
        margin: 18px auto 0;
        max-width: 900px;
        color: #475569;
        font-size: 1.12rem;
        line-height: 1.95;
        text-wrap: balance;
    }

    .tec-hero-meta {
        margin-top: 22px;
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 12px;
    }

    .tec-meta-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 14px;
        border-radius: 14px;
        background: #f8fbff;
        border: 1px solid #dbeafe;
        color: #1e293b;
        font-weight: 600;
    }

    .tec-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        display: inline-block;
        flex-shrink: 0;
    }

    .tec-dot-blue {
        background: #2563eb;
    }

    .tec-dot-red {
        background: #dc2626;
    }

    .tec-dot-green {
        background: #15803d;
    }

    .tec-summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
        gap: 18px;
    }

    .tec-summary-card {
        background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        border: 1px solid #dbe7ff;
        border-radius: 22px;
        padding: 22px;
        box-shadow: 0 14px 28px rgba(15, 23, 42, 0.06);
    }

    .tec-summary-label {
        margin: 0 0 10px 0;
        color: #64748b;
        font-weight: 800;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .tec-summary-value {
        margin: 0;
        color: #0f172a;
        font-size: 2.4rem;
        font-weight: 800;
        line-height: 1;
    }

    .tec-summary-help {
        margin: 10px 0 0 0;
        color: #64748b;
        font-size: 0.96rem;
        line-height: 1.6;
    }

    .tec-section {
        background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        border: 1px solid #dbe7ff;
        border-radius: 26px;
        padding: 26px;
        box-shadow: 0 14px 28px rgba(15, 23, 42, 0.06);
    }

    .tec-section-head {
        display: flex;
        flex-wrap: wrap;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 18px;
    }

    .tec-section-kicker {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 13px;
        border-radius: 999px;
        background: #eef4ff;
        border: 1px solid #dbeafe;
        color: #2563eb;
        font-weight: 700;
        font-size: 0.9rem;
        margin-bottom: 10px;
    }

    .tec-section h3 {
        margin: 0 0 10px 0;
        color: #0f172a;
        font-size: clamp(1.9rem, 2.6vw, 2.5rem);
        line-height: 1.08;
    }

    .tec-section p {
        margin: 0;
        color: #64748b;
        line-height: 1.8;
        font-size: 1rem;
    }

    .tec-legend {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 4px;
    }

    .tec-legend-item {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        padding: 10px 14px;
        border-radius: 999px;
        font-weight: 700;
        font-size: 0.95rem;
        border: 1px solid transparent;
    }

    .tec-legend-item.urgent {
        background: #fee2e2;
        color: #b91c1c;
        border-color: #fecaca;
    }

    .tec-legend-item.standard {
        background: #dcfce7;
        color: #166534;
        border-color: #bbf7d0;
    }

    .tec-legend-item .tec-dot {
        width: 11px;
        height: 11px;
    }

    .tec-table-wrap {
        overflow-x: auto;
        margin-top: 18px;
    }

    .tec-table {
        width: 100%;
        min-width: 1080px;
        border-collapse: separate;
        border-spacing: 0;
        overflow: hidden;
        border-radius: 20px;
        border: 1px solid #dbe7ff;
        background: #ffffff;
    }

    .tec-table thead th {
        background: #eaf2ff;
        color: #0f172a;
        padding: 16px 14px;
        text-align: left;
        font-size: 1rem;
        font-weight: 800;
    }

    .tec-table tbody td {
        padding: 16px 14px;
        border-top: 1px solid #e2e8f0;
        vertical-align: top;
        color: #334155;
    }

    .tec-table tbody tr:hover {
        background: #f8fbff;
    }

    .tec-code {
        font-weight: 800;
        color: #0f172a;
        letter-spacing: 0.01em;
    }

    .tec-client-name {
        font-weight: 700;
        color: #0f172a;
        display: block;
        margin-bottom: 4px;
    }

    .tec-client-mail {
        color: #64748b;
        font-size: 0.95rem;
    }

    .tec-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        min-height: 36px;
        padding: 6px 12px;
        border-radius: 999px;
        font-weight: 700;
        font-size: 0.93rem;
        border: 1px solid transparent;
        white-space: nowrap;
    }

    .tec-badge .tec-dot {
        width: 10px;
        height: 10px;
    }

    .tec-badge-urgente {
        background: #fee2e2;
        color: #b91c1c;
        border-color: #fecaca;
    }

    .tec-badge-estandar {
        background: #dcfce7;
        color: #166534;
        border-color: #bbf7d0;
    }

    .tec-badge-pendiente {
        background: #fef3c7;
        color: #92400e;
        border-color: #fde68a;
    }

    .tec-badge-asignada {
        background: #dbeafe;
        color: #1d4ed8;
        border-color: #bfdbfe;
    }

    .tec-badge-finalizada {
        background: #dcfce7;
        color: #166534;
        border-color: #bbf7d0;
    }

    .tec-contact-box {
        display: grid;
        gap: 4px;
    }

    .tec-contact-label {
        color: #64748b;
        font-size: 0.86rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .tec-contact-value {
        color: #0f172a;
        font-weight: 700;
    }

    .tec-address {
        color: #334155;
        line-height: 1.6;
    }

    .tec-empty {
        padding: 18px;
        border-radius: 18px;
        background: #f8fafc;
        border: 1px dashed #cbd5e1;
        color: #64748b;
        line-height: 1.7;
    }

    #calendario-tecnico {
        margin-top: 20px;
    }

    @media (max-width: 900px) {
        .tec-hero {
            padding: 24px 20px 24px;
        }

        .tec-section {
            padding: 20px;
        }

        .tec-title {
            font-size: 2.6rem;
        }

        .tec-subtitle {
            font-size: 1.02rem;
        }
    }
</style>

<div class="tec-agenda-wrap">
    <section class="tec-hero">
        <div class="tec-hero-center">
            <div class="tec-kicker">Panel técnico · Consulta operativa</div>

            <?php if ($sinVinculo): ?>
                <h2 class="tec-title">
                    <span class="tec-title-highlight">Mi espacio de trabajo</span>
                </h2>

                <p class="message-error" style="margin-top:18px;">
                    Tu cuenta tiene rol técnico, pero todavía no está vinculada a una ficha del maestro de técnicos. El administrador debe completar esa vinculación para que puedas consultar tu planificación de servicios.
                </p>
            <?php else: ?>
                <h2 class="tec-title">
                    <span class="tec-title-highlight">Mi espacio de trabajo</span>
                </h2>

                <p class="tec-subtitle">
                    Aquí podrás revisar de forma sencilla la planificación de tus intervenciones asignadas, tanto las previstas para hoy como las próximas.
                </p>

                <div class="tec-hero-meta">
                    <div class="tec-meta-pill">
                        <span class="tec-dot tec-dot-blue"></span>
                        <strong>Técnico:</strong> <?= htmlspecialchars($tecnico['nombre_completo']) ?>
                    </div>

                    <div class="tec-meta-pill">
                        <span class="tec-dot tec-dot-green"></span>
                        <strong>Especialidad:</strong> <?= htmlspecialchars($tecnico['nombre_especialidad'] ?? 'Sin especialidad') ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <?php if (!$sinVinculo): ?>
        <section class="tec-summary-grid">
            <article class="tec-summary-card">
                <p class="tec-summary-label">Servicios asignados</p>
                <p class="tec-summary-value"><?= $total ?></p>
                <p class="tec-summary-help">Total de intervenciones actualmente vinculadas a tu ficha técnica.</p>
            </article>

            <article class="tec-summary-card">
                <p class="tec-summary-label">Próximos servicios</p>
                <p class="tec-summary-value"><?= $proximas ?></p>
                <p class="tec-summary-help">Avisos todavía pendientes de realizar a partir del momento actual.</p>
            </article>

            <article class="tec-summary-card">
                <p class="tec-summary-label">Servicios finalizados</p>
                <p class="tec-summary-value"><?= $finalizadas ?></p>
                <p class="tec-summary-help">Intervenciones cerradas que permanecen visibles como histórico operativo.</p>
            </article>
        </section>

        <section class="tec-section">
            <div class="tec-section-head">
                <div>
                    <div class="tec-section-kicker">
                        <span class="tec-dot tec-dot-blue"></span>
                        Vista detallada
                    </div>
                    <h3>Listado de intervenciones asignadas</h3>
                    <p>
                        Relación completa de tus avisos, ordenados por fecha de servicio, con información útil para preparar cada desplazamiento y atención al cliente.
                    </p>
                </div>

                <div class="tec-legend">
                    <span class="tec-legend-item urgent">
                        <span class="tec-dot" style="background:#dc2626;"></span>
                        Urgente
                    </span>

                    <span class="tec-legend-item standard">
                        <span class="tec-dot" style="background:#15803d;"></span>
                        Estándar
                    </span>
                </div>
            </div>

            <?php if (empty($incidencias)): ?>
                <div class="tec-empty">
                    No tienes incidencias asignadas en este momento. Cuando el administrador te asigne nuevos avisos, aparecerán aquí automáticamente.
                </div>
            <?php else: ?>
                <div class="tec-table-wrap">
                    <table class="tec-table">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Fecha de servicio</th>
                                <th>Cliente</th>
                                <th>Especialidad</th>
                                <th>Prioridad</th>
                                <th>Estado</th>
                                <th>Contacto</th>
                                <th>Dirección</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($incidencias as $inc): ?>
                                <tr>
                                    <td>
                                        <span class="tec-code"><?= htmlspecialchars($inc['localizador']) ?></span>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars(date('d/m/Y H:i', strtotime($inc['fecha_servicio']))) ?>
                                    </td>

                                    <td>
                                        <span class="tec-client-name"><?= htmlspecialchars($inc['cliente_nombre']) ?></span>
                                        <span class="tec-client-mail"><?= htmlspecialchars($inc['cliente_email']) ?></span>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($inc['nombre_especialidad']) ?>
                                    </td>

                                    <td>
                                        <?php if ($inc['tipo_urgencia'] === 'Urgente'): ?>
                                            <span class="tec-badge tec-badge-urgente">
                                                <span class="tec-dot" style="background:#dc2626;"></span>
                                                Urgente
                                            </span>
                                        <?php else: ?>
                                            <span class="tec-badge tec-badge-estandar">
                                                <span class="tec-dot" style="background:#15803d;"></span>
                                                Estándar
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <?php if ($inc['estado'] === 'Asignada'): ?>
                                            <span class="tec-badge tec-badge-asignada">
                                                <span class="tec-dot" style="background:#2563eb;"></span>
                                                Asignada
                                            </span>
                                        <?php elseif ($inc['estado'] === 'Finalizada'): ?>
                                            <span class="tec-badge tec-badge-finalizada">
                                                <span class="tec-dot" style="background:#15803d;"></span>
                                                Finalizada
                                            </span>
                                        <?php else: ?>
                                            <span class="tec-badge tec-badge-pendiente">
                                                <span class="tec-dot" style="background:#d97706;"></span>
                                                <?= htmlspecialchars($inc['estado']) ?>
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <div class="tec-contact-box">
                                            <span class="tec-contact-label">Teléfono</span>
                                            <span class="tec-contact-value"><?= htmlspecialchars($inc['telefono_contacto']) ?></span>
                                        </div>
                                    </td>

                                    <td>
                                        <span class="tec-address"><?= htmlspecialchars($inc['direccion']) ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </section>

        <section class="tec-section">
            <div class="tec-section-head">
                <div>
                    <div class="tec-section-kicker">
                        <span class="tec-dot tec-dot-blue"></span>
                        Visión temporal
                    </div>
                    <h3>Calendario personal de servicios</h3>
                    <p>
                        Representación visual de tu agenda para localizar rápidamente tus avisos programados y consultar su detalle operativo con un clic.
                    </p>
                </div>

                <div class="tec-legend">
                    <span class="tec-legend-item urgent">
                        <span class="tec-dot" style="background:#dc2626;"></span>
                        Urgente
                    </span>

                    <span class="tec-legend-item standard">
                        <span class="tec-dot" style="background:#15803d;"></span>
                        Estándar
                    </span>
                </div>
            </div>

            <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css' rel='stylesheet' />
            <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>

            <div id="calendario-tecnico"></div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    var calendarEl = document.getElementById('calendario-tecnico');

                    var eventos = <?php
                        $eventos = [];
                        foreach ($incidencias as $inc) {
                            $eventos[] = [
                                'title' => $inc['localizador'] . ' — ' . $inc['nombre_especialidad'],
                                'start' => $inc['fecha_servicio'],
                                'color' => $inc['tipo_urgencia'] === 'Urgente' ? '#dc2626' : '#15803d',
                                'extendedProps' => [
                                    'cliente' => $inc['cliente_nombre'],
                                    'email' => $inc['cliente_email'],
                                    'direccion' => $inc['direccion'],
                                    'telefono' => $inc['telefono_contacto'],
                                    'estado' => $inc['estado'],
                                    'descripcion' => $inc['descripcion']
                                ]
                            ];
                        }
                        echo json_encode($eventos, JSON_UNESCAPED_UNICODE);
                    ?>;

                    var calendar = new FullCalendar.Calendar(calendarEl, {
                        initialView: 'dayGridMonth',
                        locale: 'es',
                        editable: false,
                        selectable: false,
                        headerToolbar: {
                            left: 'prev,next today',
                            center: 'title',
                            right: 'dayGridMonth,timeGridWeek,timeGridDay'
                        },
                        events: eventos,
                        eventClick: function(info) {
                            var p = info.event.extendedProps;
                            alert(
                                '📋 ' + info.event.title +
                                '\n\nCliente: ' + p.cliente +
                                '\nEmail: ' + p.email +
                                '\nDirección: ' + p.direccion +
                                '\nTeléfono: ' + p.telefono +
                                '\nEstado: ' + p.estado +
                                '\nDescripción: ' + p.descripcion
                            );
                        }
                    });

                    calendar.render();
                });
            </script>
        </section>
    <?php endif; ?>
</div>