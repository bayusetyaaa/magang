@extends('layouts.header')

@section('content')
    <style>
        .table {
            border-radius: 10px;
            overflow: hidden;
        }

        .table th, .table td {
            vertical-align: middle;
            text-align: center;
            padding: 12px;
        }

        .table th {
            background: linear-gradient(180deg, #0100cb, #0166ff);
            color: #fff;
            font-weight: 600;
            font-size: 14px;
        }

        .table td {
            background-color: #fff;
            font-size: 14px;
        }

        .main-content {
            padding: 20px;
        }

        .btn-custom {
            font-weight: 600;
            max-height: 40px;
        }

        .btn-custom:hover {
            opacity: 0.8;
        }

        .dataTables_length, .dataTables_filter {
            margin-top: 10px; 
            margin-bottom: 20px;
        }

        .fade-out {
            opacity: 1;
            transition: opacity 0.5s ease-out;
        }

        .fade-out.hidden {
            opacity: 0;
        }
    </style>

    <div class="container-fluid">
        <div class="row">
            <div class="main-content p-4">
            @if (session('success'))
                <div id="notification" class="alert alert-success fade-out">
                    {{ session('success') }}
                </div>
            @endif


                <div class="d-flex justify-content-between mb-3">
                    <h2 class="mb-4">Data Tamu</h2>
                    <a href="{{ route('tamu.create') }}" class="btn btn-primary btn-custom">Tambah Data</a>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="tamuTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>ID Tamu</th>
                                        <th>Nama</th>
                                        <th>Asal Instansi</th>
                                        <th>Jabatan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tamus as $index => $tamu)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $tamu->id_tamu }}</td>
                                            <td>{{ $tamu->nama }}</td>
                                            <td>{{ $tamu->asal_instansi }}</td>
                                            <td>{{ $tamu->jabatan }}</td>
                                            <td>
                                                <a href="{{ route('tamu.edit', Crypt::encryptString($tamu->id)) }}" class="btn btn-primary btn-sm">Edit</a>
                                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteTamuModal{{ $tamu->id }}">
                                                    Hapus
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Modal Konfirmasi Hapus -->
                                        <div class="modal fade" id="deleteTamuModal{{ $tamu->id }}" tabindex="-1" aria-labelledby="deleteTamuModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteTamuModalLabel">Konfirmasi Hapus</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Apakah Anda yakin ingin menghapus <strong>{{ $tamu->nama }}</strong>?</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <form action="{{ route('tamu.destroy', $tamu->id) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">Hapus</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Modal -->
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- DataTables and Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#tamuTable').DataTable({
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data per halaman",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    paginate: {
                        next: "Berikutnya",
                        previous: "Sebelumnya"
                    }
                }
            });

            setTimeout(() => {
                const notification = document.getElementById('notification');
                if (notification) {
                    notification.classList.add('hidden');
                    setTimeout(() => notification.remove(), 500);
                }
            }, 5000);
        });
    </script>
@endsection
