@extends('layouts.header')

@section('content')
<style>
    /* Calendar Base Styling */
    #calendar {
        max-width: 100%;
        margin: 0 auto;
        background: #fff;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 6px 6px rgba(0, 0, 0, 0.3);
        overflow: hidden;
        height: auto;
    }

    .fc-view-harness {
        height: auto !important;
    }

    .fc-timegrid-slot {
        height: 3em !important;
    }
    .fc .fc-daygrid {
        height: 100% !important;
    }

    /* Month View Day Cell Styling */
    .fc-daygrid-day {
        position: relative;
        max-height: calc(100vh / 6); 
    }
    .fc-daygrid-month-view {
        height: calc(100vh - 150px);
    }

    /* Event Styling di Bulan */
    .fc-daygrid-event {
        position: relative;
        top: 0;
        left: 0;
        width: 100%;
        font-size: 0.8rem;
        color: white;
        padding: 4px 8px;
        background-color: #0100cb;
        border-radius: 4px;
        white-space: normal !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
        transition: transform 0.2s ease, background-color 0.3s ease;
    }

    .fc-daygrid-event:hover {
        transform: scale(1.02);
        cursor: pointer;
        background-color: rgb(91, 0, 203, 0.7);
    }

    /* Styling Hover di Sel */
    .fc-daygrid-day:hover .add-event-hover {
        display: flex;
        background: rgba(172, 217, 255, 0.5);
    }

    .fc-timegrid-event .fc-event-time {
        display: none !important;
    }

    .fc-event-time {
        display: none !important;
    }

    /* Add Event Hover Element */
    .add-event-hover {
        display: none;
        position: absolute;
        background: rgba(1, 0, 203, 0.1);
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        cursor: pointer;
        align-items: center;
        justify-content: center;
        z-index: 1;
    }

    .add-event-hover i {
        font-size: 1.2rem;
        margin-right: 5px;
        color: #0100cb;
    }

    .add-event-hover span {
        color: #0100cb;
        font-weight: 500;
    }

    /* Toolbar Styling */
    .fc-header-toolbar {
        margin-bottom: 1.5em !important;
        padding: 1rem;
        border-radius: 8px;
        background: linear-gradient(135deg, #0100cb, #0166ff);
    }

    .fc-toolbar-title {
        color: #fff !important;
        font-size: 1.5rem !important;
        font-weight: 600;
    }

    /* Button Styling */
    .fc-button-primary {
        background: rgba(255, 255, 255, 0.2) !important;
        border: 1px solid rgba(255, 255, 255, 0.3) !important;
        padding: 8px 16px !important;
        transition: all 0.3s ease;
    }

    .fc-button-primary:hover {
        background: rgba(255, 255, 255, 0.3) !important;
        transform: translateY(-1px);
    }

    .fc-button-primary:not(:disabled):active {
        background: rgba(255, 255, 255, 0.4) !important;
        transform: translateY(1px);
    }

    /* Calendar Grid Styling */
    .fc-theme-standard td,
    .fc-theme-standard th {
        border-color: rgb(207, 205, 205);
    }

    .fc-day-today {
        background: rgba(1, 0, 203, 0.1) !important;
        position: relative;
    }

    .fc-day-today .today-label {
        position: absolute;
        top: 5px;
        right: 20px;
        background: #00cb1a;
        color: #fff;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: bold;
    }

    /* Event Styling */
    .fc-event {
        background-color: rgb(91, 0, 203);
        border: none;
        border-radius: 4px;
        padding: 4px 8px;
        font-size: 0.8rem;
        transition: transform 0.2s ease;
    }

    .fc-event:hover {
        transform: scale(1.02);
        cursor: pointer;
    }

    .fc-daygrid-day-number {
        text-decoration: none;
    }

    /* event */
    .upcoming-events-wrapper {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 6px 6px rgba(0, 0, 0, 0.3);
        padding: 20px;
    }

    .upcoming-events-title {
        background: linear-gradient(135deg, #0166ff, #0100cb);
        color: #fff;
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: bold;
        font-size: 1.5rem;
        text-align: center;
    }

    .upcoming-events-list {
        margin-top: 15px;
    }

    .upcoming-events-list li {
        margin-bottom: 10px;
        font-size: 1rem;
    }

    .upcoming-events-list li strong {
        color: #0100cb;
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .fc-toolbar-title {
            font-size: 1.5rem !important;
        }

        .fc-button {
            padding: 6px 12px !important;
            font-size: 0.875rem !important;
        }

        #calendar {
            padding: 10px;
        }
        .fc-daygrid-event .fc-event-title {
            display: none !important;
        } 
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col">
            <div class="p-4">
                <h2 class="mb-4">Jadwal Acara</h2>
                
                @if (session('notification'))
                    <div id="notification" class="alert alert-{{ session('notification.type') }}">
                        {{ session('notification.message') }}
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-8">
                        <div id="calendar"></div>
                    </div>

                    <div class="col-md-4">
                        <div class="upcoming-events-wrapper">
                            <div class="upcoming-events-title">
                                Acara Mendatang
                            </div>
                            <ul class="upcoming-events-list">
                                @foreach ($upcomingEvents as $event)
                                    <li>
                                        <strong>{{ $event->nama_acara }}</strong><br>
                                        <span>{{ $event->tanggal }} | {{ $event->jam_mulai }} - {{ $event->jam_selesai }}</span><br>
                                        <span>{{ $event->tempat }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            locale: 'id',
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth'
            },
            events: '/api/schedules',
            editable: false,
            height: 'auto',
            eventDidMount: function(info) {
                // Format waktu dan judul event
                const startTime = info.event.start.toLocaleTimeString('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false
                });
                const endTime = info.event.end.toLocaleTimeString('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false
                });

                // Update tampilan event
                info.el.querySelector('.fc-event-title').textContent = 
                    `${startTime}-${endTime} ${info.event.title}`;
            },
            dayCellDidMount: function(arg) {
                const cell = arg.el;
                const cellDate = new Date(arg.date);
                const today = new Date();

                today.setHours(0, 0, 0, 0);
                cellDate.setHours(0, 0, 0, 0);

                if (cellDate.getTime() === today.getTime()) {
                    const label = document.createElement('div');
                    label.className = 'today-label';
                    label.textContent = 'Hari Ini';
                    cell.appendChild(label);
                }

                if (cellDate >= today && !cell.querySelector('.add-event-hover')) {
                    const hoverElement = document.createElement('div');
                    hoverElement.className = 'add-event-hover';
                    hoverElement.innerHTML = '<i class="material-icons">add</i><span>Acara baru</span>';
                    cell.appendChild(hoverElement);

                    hoverElement.addEventListener('click', function() {
                        const formattedDate = cellDate.toLocaleDateString('en-CA');
                        window.location.href = `/tambah_acara?date=${formattedDate}`;
                    });
                }
            },
            eventClick: function(info) {
                const eventId = info.event.id;
                window.location.href = `/detail_acara/${eventId}`;
            },
            dayMaxEventRows: 4
        });

        calendar.render();
    });
    setTimeout(() => {
        const notification = document.getElementById('notification');
        if (notification) {
            notification.style.transition = 'opacity 0.5s ease';
            notification.style.opacity = '0'; 
            setTimeout(() => notification.remove(), 500);
        }
    }, 5000);
</script>
@endsection
