@extends('layouts.header')

@section('content')
    <style>
        /* Styling tambahan untuk tampilan */
        .table-dark-blue {
            background: linear-gradient(45deg, #0100cb, #0166ff);
            color: white !important;
        }

        .table th, .table td {
            vertical-align: middle;
            text-align: center;
            padding: 10px 15px;
        }

        .table-hover tbody tr:hover {
            background-color: #f0f8ff;
            transition: background-color 0.3s ease;
        }

        .btn-primary {
            background-color: #0061b3;
            border-color: #0056b3;
            padding: 8px 15px;
            font-size: 14px;
        }

        .btn-primary:hover {
            opacity: 0.8;
        }

        .table-bordered th, .table-bordered td {
            border: 1px solid #ddd;
        }

        .table th {
            background-color: #003366;
            color: white;
            font-weight: bold;
        }

        .table td {
            background-color: #f9f9f9;
            color: #333;
        }

        .content-wrapper {
            margin-left: 0;
            padding-top: 50px;
        }

        .section-header {
            margin-bottom: 25px;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background: linear-gradient(to bottom, #0100cb, #0166ff);
            color: white;
            font-size: 1.2rem;
            padding: 12px 20px;
        }

        .card-body {
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            @include('layouts.sidebar')

            <!-- Main Content -->
            <div class="content-wrapper">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <!-- Header Section -->
                            <div class="section-header d-flex justify-content-between align-items-center mb-4">
                                <h1 class="mb-0">Presensi dan Kunjungan Acara</h1>
                            </div>

                            <!-- Dropdown for Acara -->
                            <div class="mb-4">
                                <label for="kode_acara" class="form-label">Pilih Acara:</label>
                                <select name="kode_acara" id="kode_acara" class="form-select input">
                                    <option value="">-- Pilih Acara --</option>
                                    @foreach($event as $eventItem)
                                        <option value="{{ $eventItem->kode_acara }}">{{ $eventItem->nama_acara }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Card for Presensi Table -->
                            <!-- <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">Tabel Presensi Acara</h5>
                                </div> -->
                                <!-- <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-bordered table-striped">
                                            <thead class="table-dark-blue">
                                                <tr>
                                                    <th>No</th>
                                                    <th>Kode Acara</th>
                                                    <th>NIP</th>
                                                    <th>Nama</th>
                                                    <th>Tanggal</th>
                                                    <th>Jam Masuk</th>
                                                    <th>Jabatan</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody id="presensiTable">
                                                 Tabel Presensi akan diisi secara dinamis -->
                                            <!-- </tbody>
                                        </table>
                                    </div>
                                </div> --> 
                            </div>

                            <!-- Card for Kunjungan Table -->
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="card-title">Data Kunjungan</h5>
                                    <a href="#" id="downloadPdfButton" class="btn btn-primary" disabled>
                                        Download Laporan
                                    </a>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-bordered table-striped">
                                            <thead class="table-dark-blue">
                                                <tr>
                                                    <th>No</th>
                                                    <th>Kode Acara</th>
                                                    <th>ID Tamu</th>
                                                    <th>Nama Tamu</th>
                                                    <th>Tanggal</th>
                                                    <th>Jam Masuk</th>
                                                    <th>Asal Instansi</th>
                                                    <th>Jabatan</th>
                                                </tr>
                                            </thead>
                                            <tbody id="kunjunganTable">
                                                <!-- Tabel Kunjungan akan diisi secara dinamis -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- External Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#kode_acara').on('change', function () {
                var kode_acara = $(this).val();
                if (kode_acara) {
                    // Ubah URL tombol Download PDF
                    var downloadUrl = '/kunjungan/download-pdf?kode_acara=' + encodeURIComponent(kode_acara);
                    $('#downloadPdfButton').attr('href', downloadUrl).prop('disabled', false).text('Download Laporan untuk ' + $("#kode_acara option:selected").text());

                    // Panggil AJAX untuk mengisi tabel
                    $.ajax({
                        url: '/event/' + kode_acara,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            // Kosongkan tabel sebelumnya
                            $('#presensiTable').empty();
                            $('#kunjunganTable').empty();

                            // Isi tabel Presensi
                            var presensiCounter = 1;
                            data.presensi.forEach(function(presensiItem) {
                                $('#presensiTable').append(
                                    '<tr>' +
                                    '<td>' + presensiCounter++ + '</td>' +
                                    '<td>' + presensiItem.kode_acara + '</td>' +
                                    '<td>' + presensiItem.nip + '</td>' +
                                    '<td>' + presensiItem.nama + '</td>' +
                                    '<td>' + presensiItem.tanggal + '</td>' +
                                    '<td>' + presensiItem.jam_masuk + '</td>' +
                                    '<td>' + presensiItem.jabatan + '</td>' +
                                    '<td>' + presensiItem.status + '</td>' +
                                    '</tr>'
                                );
                            });

                            // Isi tabel Kunjungan
                            var kunjunganCounter = 1;
                            data.kunjungan.forEach(function(kunjunganItem) {
                                $('#kunjunganTable').append(
                                    '<tr>' +
                                    '<td>' + kunjunganCounter++ + '</td>' +
                                    '<td>' + kunjunganItem.kode_acara + '</td>' +
                                    '<td>' + kunjunganItem.id_tamu + '</td>' +
                                    '<td>' + kunjunganItem.nama_tamu + '</td>' +
                                    '<td>' + kunjunganItem.tanggal + '</td>' +
                                    '<td>' + kunjunganItem.jam_masuk + '</td>' +
                                    '<td>' + kunjunganItem.asal_instansi + '</td>' +
                                    '<td>' + kunjunganItem.jabatan + '</td>' +
                                    '</tr>'
                                );
                            });
                        },
                        error: function() {
                            alert('Gagal mengambil data.');
                        }
                    });
                } else {
                    $('#downloadPdfButton').attr('href', '#').prop('disabled', true).text('Download PDF');
                    $('#presensiTable').empty();
                    $('#kunjunganTable').empty();
                }
            });
        });
    </script>
</body>
@endsection
