<style>
    .cal-wrap {
        display: grid;
        gap: 24px;
    }

    .cal-hero {
        position: relative;
        overflow: hidden;
        border-radius: 30px;
        padding: 34px 28px 36px;
        background: linear-gradient(180deg, #ffffff 0%, #f7faff 100%);
        border: 1px solid #dbe7ff;
        box-shadow: 0 18px 34px rgba(15, 23, 42, 0.06);
        text-align: center;
    }

    .cal-hero::before {
        content: '';
        position: absolute;
        top: -90px;
        right: -90px;
        width: 240px;
        height: 240px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(59,130,246,0.14), transparent 70%);
    }

    .cal-hero::after {
        content: '';
        position: absolute;
        bottom: -90px;
        left: -90px;
        width: 220px;
        height: 220px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(37,99,235,0.10), transparent 70%);
    }

    .cal-hero > * {
        position: relative;
        z-index: 1;
    }

    .cal-chip {
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
        margin-bottom: 16px;
    }

    .cal-title {
        margin: 0;
        font-size: clamp(2.8rem, 4.8vw, 4.5rem);
        line-height: 0.98;
        letter-spacing: -0.03em;
        color: #0f172a;
        font-weight: 900;
    }

    .cal-subtitle {
        margin: 18px auto 0;
        max-width: 1100px;
        color: #475569;
        font-size: 1.08rem;
        line-height: 1.9;
        text-wrap: balance;
    }

    .cal-top-actions {
        display: flex;
        justify-content: center;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 24px;
    }

    .cal-btn {
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

    .cal-btn-primary {
        color: #ffffff;
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        box-shadow: 0 14px 24px rgba(37, 99, 235, 0.18);
    }

    .cal-btn-secondary {
        color: #1d4ed8;
        background: #eaf2ff;
        border: 1px solid #dbeafe;
    }

    .cal-panel {
        background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        border: 1px solid #dbe7ff;
        border-radius: 26px;
        padding: 26px;
        box-shadow: 0 14px 28px rgba(15, 23, 42, 0.06);
    }

    .cal-section-head {
        display: flex;
        flex-wrap: wrap;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 20px;
    }

    .cal-section-kicker {
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

    .cal-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        display: inline-block;
        flex-shrink: 0;
    }

    .cal-dot-blue { background: #2563eb; }
    .cal-dot-red { background: #dc2626; }
    .cal-dot-green { background: #15803d; }

    .cal-section-title {
        margin: 0 0 10px 0;
        font-size: clamp(1.9rem, 2.7vw, 2.5rem);
        line-height: 1.08;
        color: #0f172a;
    }

    .cal-section-text {
        margin: 0;
        color: #64748b;
        font-size: 1rem;
        line-height: 1.85;
        max-width: 1050px;
    }

    .cal-legend {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
    }

    .cal-legend-item {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        min-height: 38px;
        padding: 8px 14px;
        border-radius: 999px;
        font-weight: 800;
        font-size: 0.94rem;
        border: 1px solid transparent;
    }

    .cal-legend-item.urgent {
        background: #fee2e2;
        color: #b91c1c;
        border-color: #fecaca;
    }

    .cal-legend-item.standard {
        background: #dcfce7;
        color: #166534;
        border-color: #bbf7d0;
    }

    #calendario {
        margin-top: 18px;
    }

    #calendario .fc {
        font-family: var(--rp-font-ui, 'Rajdhani', Arial, sans-serif);
    }

    #calendario .fc-toolbar-title {
        color: #0f172a;
        font-size: clamp(1.9rem, 2.8vw, 2.6rem);
        font-weight: 900;
        text-transform: lowercase;
    }

    #calendario .fc-button {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%) !important;
        border: none !important;
        box-shadow: 0 12px 22px rgba(37, 99, 235, 0.18);
        border-radius: 12px !important;
        font-weight: 800;
        text-transform: lowercase;
    }

    #calendario .fc-button:disabled {
        opacity: 0.7;
    }

    #calendario .fc-daygrid-day-number,
    #calendario .fc-col-header-cell-cushion {
        color: #2563eb;
        font-weight: 800;
        text-decoration: none;
    }

    #calendario .fc-theme-standard td,
    #calendario .fc-theme-standard th,
    #calendario .fc-theme-standard .fc-scrollgrid {
        border-color: #dbe7ff;
    }

    #calendario .fc-day-today {
        background: #fffbe8 !important;
    }

    #calendario .fc-daygrid-event {
        background: transparent !important;
        border: none !important;
        padding: 0 !important;
        margin-top: 4px !important;
    }

    #calendario .fc-daygrid-event-harness {
        margin-top: 3px !important;
    }

    #calendario .fc-daygrid-dot-event {
        padding: 2px 0 !important;
    }

    #calendario .fc-daygrid-event-dot {
        width: 10px !important;
        height: 10px !important;
        border-width: 0 !important;
        border-radius: 50% !important;
        margin-right: 8px !important;
    }

    #calendario .fc-event-title,
    #calendario .fc-event-time {
        color: #0f172a !important;
        font-weight: 700;
    }

    @media (max-width: 900px) {
        .cal-hero,
        .cal-panel {
            padding: 22px 18px;
        }

        .cal-title {
            font-size: 2.6rem;
        }

        .cal-subtitle {
            font-size: 1rem;
        }
    }
</style>

<div class="cal-wrap">
    <section class="cal-hero">
        <div class="cal-chip">Planificación operativa · Calendario de servicios</div>
        <h2 class="cal-title">Calendario central del servicio</h2>
        <p class="cal-subtitle">
            Consulta la programación completa de incidencias activas, detecta rápidamente prioridades y obtén una visión clara del volumen de trabajo previsto por fecha.
        </p>

        <div class="cal-top-actions">
            <a href="<?= base_url('admin') ?>" class="cal-btn cal-btn-secondary">Volver al panel</a>
            <a href="<?= base_url('admin/create') ?>" class="cal-btn cal-btn-primary">Nueva incidencia</a>
        </div>
    </section>

    <section class="cal-panel">
        <div class="cal-section-head">
            <div>
                <div class="cal-section-kicker">
                    <span class="cal-dot cal-dot-blue"></span>
                    Visión mensual, semanal y diaria
                </div>
                <h3 class="cal-section-title">Agenda operativa de incidencias</h3>
                <p class="cal-section-text">
                    En este calendario solo se muestran incidencias activas. Las canceladas no aparecen. Cada aviso mantiene el mismo formato visual para facilitar una lectura homogénea y profesional en cualquier fecha.
                </p>
            </div>

            <div class="cal-legend">
                <span class="cal-legend-item urgent">
                    <span class="cal-dot cal-dot-red"></span>
                    Urgente
                </span>
                <span class="cal-legend-item standard">
                    <span class="cal-dot cal-dot-green"></span>
                    Estándar
                </span>
            </div>
        </div>

        <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

        <div id="calendario"></div>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendario');

    const eventos = <?php
        $eventos = [];
        foreach ($incidencias as $inc) {
            $esUrgente = ($inc['tipo_urgencia'] ?? '') === 'Urgente';
            $color = $esUrgente ? '#dc2626' : '#15803d';

            $eventos[] = [
                'title' => $inc['localizador'] . ' — ' . $inc['nombre_especialidad'],
                'start' => date('Y-m-d\TH:i:s', strtotime($inc['fecha_servicio'])),
                'allDay' => false,
                'display' => 'list-item',
                'backgroundColor' => $color,
                'borderColor' => $color,
                'extendedProps' => [
                    'localizador' => $inc['localizador'],
                    'cliente'     => $inc['cliente_nombre'],
                    'tecnico'     => $inc['tecnico_nombre'] ?? 'Sin asignar',
                    'estado'      => $inc['estado'],
                    'direccion'   => $inc['direccion'],
                    'descripcion' => $inc['descripcion'],
                ]
            ];
        }
        echo json_encode($eventos, JSON_UNESCAPED_UNICODE);
    ?>;

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
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        buttonText: {
            today: 'today',
            month: 'month',
            week: 'week',
            day: 'day'
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
            const p = info.event.extendedProps;
            alert(
                '📋 ' + info.event.title +
                '\n\nCliente: ' + p.cliente +
                '\nTécnico: ' + p.tecnico +
                '\nEstado: ' + p.estado +
                '\nDirección: ' + p.direccion +
                '\nDescripción: ' + p.descripcion
            );
        }
    });

    calendar.render();
});
</script>