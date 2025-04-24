@extends('layouts.header')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Main Content -->
            <div>
                <!-- Content -->
                <div class="p-4">
                    <h2 class="mb-4">Dashboard</h2>
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card card-gradient shadow">
                                <div class="card-body">
                                    <h5>Total Acara ({{ now()->format('F Y') }})</h5>
                                    <h2>{{ $totalAcara }}</h2>
                                    <p>Acara</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card card-gradient shadow">
                                <div class="card-body">
                                    <h5>Total Keseluruhan Hadir</h5>
                                    <h2>{{ $totalHadir }}</h2>
                                    <p>Pengunjung</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card card-gradient-purple shadow">
                                <div class="card-body">
                                    <h5>Detail Acara Terakhir</h5>
                                    <h2>{{ $lastEvent->nama_acara }}</h2>
                                    <p>{{ $lastEvent->tanggal }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card card-gradient-purple shadow">
                                <div class="card-body">
                                    <h5>Tamu Hadir</h5>
                                    <h2>{{ $tamuHadir }}</h2>
                                    <p>Tamu Menghadiri Acara {{ $lastEvent->nama_acara }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="card shadow">
                                <div class="card-body">
                                    <h5>Statistik Kehadiran</h5>
                                    <div class="chart-container">
                                        <canvas id="attendanceChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="card shadow">
                                <div class="card-body">
                                    <h5>Acara Mendatang</h5>
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

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Statistik Kehadiran (10 Acara Sebelumnya)
        const attendanceCtx = document.getElementById('attendanceChart').getContext('2d');
        new Chart(attendanceCtx, {
            type: 'bar',
            data: {
                labels: @json($last10Events->pluck('nama_acara')), // Nama acara dari 10 acara terakhir
                datasets: [
                    {
                        label: 'Tamu Hadir',
                        data: @json($last10Events->pluck('tamu_hadir')), // Data tamu hadir
                        backgroundColor: 'rgba(0, 7, 205, 0.7)'
                    },
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, // Menggunakan tinggi dari CSS
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Total Hadir : {{ $totalHadir }}'
                        }   
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Jumlah Kehadiran'
                        }
                    }
                }
            }
        });
    </script>
@endsection
