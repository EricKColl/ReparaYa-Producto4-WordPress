@php
    $calendarTitle = $calendarTitle ?? 'Calendario operativo';
    $calendarSubtitle = $calendarSubtitle ?? null;
    $calendarEvents = collect($calendarEvents ?? []);

    $today = \Carbon\Carbon::today();
    $month = (int) request('calendar_month', now()->month);
    $year = (int) request('calendar_year', now()->year);

    if ($month < 1 || $month > 12) {
        $month = now()->month;
    }

    if ($year < 2020 || $year > 2035) {
        $year = now()->year;
    }

    $currentDate = \Carbon\Carbon::create($year, $month, 1);
    $startOfMonth = $currentDate->copy()->startOfMonth();
    $endOfMonth = $currentDate->copy()->endOfMonth();

    $calendarStart = $startOfMonth->copy()->startOfWeek(\Carbon\Carbon::MONDAY);
    $calendarEnd = $endOfMonth->copy()->endOfWeek(\Carbon\Carbon::SUNDAY);

    $previousMonth = $currentDate->copy()->subMonth();
    $nextMonth = $currentDate->copy()->addMonth();

    $eventsByDate = $calendarEvents->groupBy(function ($event) {
        return \Carbon\Carbon::parse($event['fecha'])->format('Y-m-d');
    });

    $monthEvents = $calendarEvents->filter(function ($event) use ($month, $year) {
        $date = \Carbon\Carbon::parse($event['fecha']);
        return (int) $date->month === (int) $month && (int) $date->year === (int) $year;
    });

    $totalEvents = $monthEvents->count();
    $eventsDone = $monthEvents->where('estado', 'Finalizada')->count();
    $eventsOpen = $monthEvents->filter(fn($event) => in_array($event['estado'], ['Pendiente', 'Asignada']))->count();
    $eventsUrgent = $monthEvents->where('urgencia', 'Urgente')->count();

    $weekDays = ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'];

    $monthNames = [
        1 => 'Enero',
        2 => 'Febrero',
        3 => 'Marzo',
        4 => 'Abril',
        5 => 'Mayo',
        6 => 'Junio',
        7 => 'Julio',
        8 => 'Agosto',
        9 => 'Septiembre',
        10 => 'Octubre',
        11 => 'Noviembre',
        12 => 'Diciembre',
    ];
@endphp

<style>
    .ry-calendar-shell {
        display: grid;
        gap: 22px;
    }

    .ry-calendar-panel {
        position: relative;
        overflow: hidden;
        border-radius: 30px;
        padding: 30px;
        background:
            radial-gradient(circle at 10% 12%, rgba(86,199,255,0.12), transparent 26%),
            radial-gradient(circle at 92% 8%, rgba(214,184,109,0.08), transparent 24%),
            linear-gradient(180deg, rgba(246,250,255,0.98) 0%, rgba(255,255,255,0.99) 100%);
        border: 1px solid rgba(214, 227, 242, 0.9);
        box-shadow: 0 16px 38px rgba(15, 23, 42, 0.08);
    }

    .ry-calendar-panel::before {
        content: "";
        position: absolute;
        inset: 0;
        pointer-events: none;
        background-image:
            linear-gradient(rgba(15,111,255,0.026) 1px, transparent 1px),
            linear-gradient(90deg, rgba(15,111,255,0.026) 1px, transparent 1px);
        background-size: 34px 34px;
        mask-image: radial-gradient(circle at center, black 0%, transparent 92%);
    }

    .ry-calendar-content {
        position: relative;
        z-index: 2;
    }

    .ry-calendar-head {
        display: grid;
        grid-template-columns: 1fr auto;
        align-items: center;
        gap: 18px;
        margin-bottom: 22px;
    }

    .ry-calendar-title-zone {
        text-align: center;
        justify-self: center;
    }

    .ry-calendar-title {
        margin: 0;
        color: #0f172a;
        font-size: clamp(30px, 3vw, 42px);
        line-height: 1.04;
        letter-spacing: -1.2px;
        font-weight: 900;
    }

    .ry-calendar-subtitle {
        margin: 10px auto 0;
        max-width: 760px;
        color: #64748b;
        font-size: 15.5px;
        line-height: 1.6;
        font-weight: 700;
    }

    .ry-calendar-nav {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
        flex-wrap: wrap;
    }

    .ry-calendar-nav a,
    .ry-calendar-nav span {
        min-height: 42px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0 14px;
        border-radius: 999px;
        font-size: 14px;
        font-weight: 900;
        text-decoration: none;
    }

    .ry-calendar-nav a {
        color: #0f172a;
        background: #eaf2fb;
        border: 1px solid rgba(15,111,255,0.10);
    }

    .ry-calendar-nav span {
        color: white;
        background: linear-gradient(135deg, #0f6fff, #56c7ff);
        box-shadow: 0 10px 20px rgba(15, 111, 255, 0.16);
    }

    .ry-calendar-kpis {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px;
        margin-bottom: 22px;
    }

    .ry-calendar-kpi {
        padding: 18px;
        border-radius: 22px;
        background: white;
        border: 1px solid #e4edf7;
        text-align: center;
        box-shadow: 0 10px 22px rgba(15, 23, 42, 0.04);
    }

    .ry-calendar-kpi span {
        display: block;
        color: #64748b;
        font-size: 12px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.45px;
        margin-bottom: 8px;
    }

    .ry-calendar-kpi strong {
        display: block;
        color: #0f172a;
        font-size: 30px;
        line-height: 1;
        letter-spacing: -0.9px;
        font-weight: 900;
    }

    .ry-calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, minmax(0, 1fr));
        gap: 10px;
    }

    .ry-calendar-weekday {
        text-align: center;
        padding: 12px 8px;
        border-radius: 16px;
        background: #edf4fb;
        color: #0f172a;
        font-size: 13px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.45px;
    }

    .ry-calendar-day {
        min-height: 150px;
        padding: 12px;
        border-radius: 20px;
        background: white;
        border: 1px solid #e4edf7;
        box-shadow: 0 10px 22px rgba(15, 23, 42, 0.04);
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .ry-calendar-day.outside {
        opacity: 0.45;
        background: #f8fafc;
    }

    .ry-calendar-day.today {
        border-color: rgba(15,111,255,0.55);
        box-shadow: 0 0 0 4px rgba(15,111,255,0.08), 0 10px 22px rgba(15, 23, 42, 0.04);
    }

    .ry-calendar-day-number {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        color: #0f172a;
        font-size: 15px;
        font-weight: 900;
    }

    .ry-calendar-count {
        min-width: 26px;
        height: 26px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 999px;
        background: #dfeafb;
        color: #1554c8;
        font-size: 12px;
        font-weight: 900;
    }

    .ry-calendar-events {
        display: grid;
        gap: 7px;
    }

    .ry-event {
        padding: 9px 10px;
        border-radius: 14px;
        border: 1px solid rgba(15,23,42,0.06);
        background: #f8fbff;
        display: grid;
        gap: 4px;
    }

    .ry-event.finalizada {
        background: #effaf4;
        border-color: rgba(25, 135, 84, 0.16);
    }

    .ry-event.cancelada {
        background: #f3f4f6;
        border-color: rgba(100, 116, 139, 0.16);
    }

    .ry-event.urgente {
        background: #fff0f3;
        border-color: rgba(220, 53, 69, 0.16);
    }

    .ry-event-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 8px;
        color: #0f172a;
        font-size: 12.5px;
        font-weight: 900;
    }

    .ry-event-time {
        color: #0f6fff;
        white-space: nowrap;
    }

    .ry-event-title {
        color: #0f172a;
        font-size: 12.5px;
        line-height: 1.35;
        font-weight: 800;
    }

    .ry-event-meta {
        color: #64748b;
        font-size: 11.5px;
        line-height: 1.35;
        font-weight: 700;
    }

    .ry-more-events {
        color: #1554c8;
        font-size: 12px;
        font-weight: 900;
        text-align: center;
        padding: 6px;
        border-radius: 12px;
        background: #eaf2ff;
    }

    .ry-calendar-empty {
        margin-top: 14px;
        padding: 18px;
        border-radius: 20px;
        background: #fff7dd;
        color: #715400;
        border: 1px solid #f3e1a4;
        font-weight: 900;
        text-align: center;
    }

    @media (max-width: 1350px) {
        .ry-calendar-kpis {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .ry-calendar-day {
            min-height: 130px;
        }
    }

    @media (max-width: 900px) {
        .ry-calendar-head {
            grid-template-columns: 1fr;
            justify-items: center;
        }

        .ry-calendar-nav {
            justify-content: center;
        }

        .ry-calendar-grid {
            min-width: 980px;
        }

        .ry-calendar-scroll {
            overflow-x: auto;
            padding-bottom: 4px;
        }
    }

    @media (max-width: 760px) {
        .ry-calendar-panel {
            padding: 20px 14px;
            border-radius: 24px;
        }

        .ry-calendar-kpis {
            grid-template-columns: 1fr;
        }
    }
</style>

<section class="ry-calendar-panel">
    <div class="ry-calendar-content">

        <div class="ry-calendar-head">
            <div class="ry-calendar-title-zone">
                <h2 class="ry-calendar-title">{{ $calendarTitle }}</h2>

                @if($calendarSubtitle)
                    <p class="ry-calendar-subtitle">{{ $calendarSubtitle }}</p>
                @endif
            </div>

            <div class="ry-calendar-nav">
                <a href="{{ request()->fullUrlWithQuery(['calendar_month' => $previousMonth->month, 'calendar_year' => $previousMonth->year]) }}">
                    ← Mes anterior
                </a>

                <span>
                    {{ $monthNames[$month] }} {{ $year }}
                </span>

                <a href="{{ request()->fullUrlWithQuery(['calendar_month' => $nextMonth->month, 'calendar_year' => $nextMonth->year]) }}">
                    Mes siguiente →
                </a>
            </div>
        </div>

        <div class="ry-calendar-kpis">
            <div class="ry-calendar-kpi">
                <span>Eventos del mes</span>
                <strong>{{ $totalEvents }}</strong>
            </div>

            <div class="ry-calendar-kpi">
                <span>Abiertos</span>
                <strong>{{ $eventsOpen }}</strong>
            </div>

            <div class="ry-calendar-kpi">
                <span>Finalizados</span>
                <strong>{{ $eventsDone }}</strong>
            </div>

            <div class="ry-calendar-kpi">
                <span>Urgentes</span>
                <strong>{{ $eventsUrgent }}</strong>
            </div>
        </div>

        <div class="ry-calendar-scroll">
            <div class="ry-calendar-grid">
                @foreach($weekDays as $dayName)
                    <div class="ry-calendar-weekday">{{ $dayName }}</div>
                @endforeach

                @php
                    $cursor = $calendarStart->copy();
                @endphp

                @while($cursor->lte($calendarEnd))
                    @php
                        $dateKey = $cursor->format('Y-m-d');
                        $dayEvents = collect($eventsByDate->get($dateKey, []))->sortBy('fecha');
                        $visibleEvents = $dayEvents->take(3);
                    @endphp

                    <div class="ry-calendar-day {{ $cursor->month !== $month ? 'outside' : '' }} {{ $cursor->isSameDay($today) ? 'today' : '' }}">
                        <div class="ry-calendar-day-number">
                            <span>{{ $cursor->day }}</span>

                            @if($dayEvents->count() > 0)
                                <span class="ry-calendar-count">{{ $dayEvents->count() }}</span>
                            @endif
                        </div>

                        <div class="ry-calendar-events">
                            @foreach($visibleEvents as $event)
                                @php
                                    $eventDate = \Carbon\Carbon::parse($event['fecha']);
                                    $eventClass = '';

                                    if (($event['estado'] ?? '') === 'Finalizada') {
                                        $eventClass = 'finalizada';
                                    } elseif (($event['estado'] ?? '') === 'Cancelada') {
                                        $eventClass = 'cancelada';
                                    } elseif (($event['urgencia'] ?? '') === 'Urgente') {
                                        $eventClass = 'urgente';
                                    }
                                @endphp

                                <div class="ry-event {{ $eventClass }}">
                                    <div class="ry-event-top">
                                        <span>{{ $event['codigo'] ?? 'Aviso' }}</span>
                                        <span class="ry-event-time">{{ $eventDate->format('H:i') }}</span>
                                    </div>

                                    <div class="ry-event-title">
                                        {{ \Illuminate\Support\Str::limit($event['titulo'] ?? 'Servicio programado', 42) }}
                                    </div>

                                    <div class="ry-event-meta">
                                        {{ $event['estado'] ?? 'Pendiente' }}
                                        @if(!empty($event['urgencia']))
                                            · {{ $event['urgencia'] === 'Estandar' ? 'Estándar' : $event['urgencia'] }}
                                        @endif
                                    </div>
                                </div>
                            @endforeach

                            @if($dayEvents->count() > 3)
                                <div class="ry-more-events">
                                    +{{ $dayEvents->count() - 3 }} más
                                </div>
                            @endif
                        </div>
                    </div>

                    @php
                        $cursor->addDay();
                    @endphp
                @endwhile
            </div>
        </div>

        @if($monthEvents->isEmpty())
            <div class="ry-calendar-empty">
                No hay eventos programados en este mes.
            </div>
        @endif

    </div>
</section>