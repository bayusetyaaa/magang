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
    .dataTables_length {
        margin-top: 10px; /* Jarak atas */
        margin-bottom: 20px; /* Jarak bawah */
    }
</style>

<div class="container-fluid">
    <div class="row">
        <!-- Main Content -->
        <div class="main-content p-4">
            @if (session('notification'))
                <div id="notification" class="alert alert-{{ session('notification.type') }}">
                    {{ session('notification.message') }}
                </div>
            @endif

            <div class="d-flex justify-content-between mb-3">
                <h2 class="mb-4">Data Karyawan</h2>
                <div>
                    <a href="{{ route('admin.datauser') }}" class="btn btn-success btn-custom me-2">Kelola User</a>
                    <a href="{{ route('karyawan.create') }}" class="btn btn-primary btn-custom me-2">Tambah Data</a>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="karyawanTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NIP</th>
                                    <th>Nama</th>
                                    <th>Divisi</th>
                                    <th>Jabatan</th>
                                    <th>Alamat</th>
                                    <th>No. HP</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($karyawans as $index => $karyawan)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $karyawan->nip }}</td>
                                    <td>{{ $karyawan->nama }}</td>
                                    <td>{{ $karyawan->divisi }}</td>
                                    <td>{{ $karyawan->jabatan }}</td>
                                    <td>{{ $karyawan->alamat }}</td>
                                    <td>{{ $karyawan->no_hp }}</td>
                                    <td>
                                        <a href="{{ route('karyawan.edit', Crypt::encryptString($karyawan->id)) }}" class="btn btn-primary btn-sm">Edit</a>
                                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="{{ $karyawan->id }}">Hapus</button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus <strong>{{$karyawan->nama}} </strong> dari karyawan?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" style="display:inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    var deleteModal = document.getElementById('deleteModal');
    deleteModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget; // Tombol yang memicu modal
        var karyawanId = button.getAttribute('data-id'); // Mendapatkan ID Karyawan dari tombol
        var form = document.getElementById('deleteForm');
        form.action = '/karyawan/' + karyawanId; // Menentukan URL action untuk form
    });

    setTimeout(() => {
        const notification = document.getElementById('notification');
        if (notification) {
            notification.style.opacity = '0';
            setTimeout(() => notification.remove(), 500);
        }
    }, 5000);
</script>
@endsection
