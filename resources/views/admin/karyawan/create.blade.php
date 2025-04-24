@extends('layouts.header')
@section('content')
<style>
    /* Form Styling */
    .form-container {
        background: #fff;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .form-container .form-group {
        margin-bottom: 1.5rem;
    }

    .form-container .form-group label {
        font-weight: bold;
        color: #333;
    }

    .form-container .form-control {
        border-radius: 8px;
        border: 1px solid #ddd;
        padding: 10px;
        font-size: 1rem;
    }

    .form-container .form-control:focus {
        border-color: #0100cb;
        box-shadow: 0 0 0 0.2rem rgba(1, 0, 203, 0.25);
    }

    .form-container button {
        background: linear-gradient(135deg, #0100cb, #0166ff);
        color: #fff;
        border: none;
        padding: 10px 20px;
        font-size: 1rem;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .form-container button:hover {
        background: linear-gradient(135deg, #0166ff, #0100cb);
        transform: translateY(-2px);
    }

    .form-container button:active {
        transform: translateY(2px);
    }

    #notification {
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        transition: opacity 0.5s ease;
    }

    #notification.alert-success {
        background-color: #28a745;
        color: white;
    }

    #notification.alert-error {
        background-color: #dc3545;
        color: white;
    }

    #notification.alert-info {
        background-color: #17a2b8;
        color: white;
    }

    .form-container .title {
        background: linear-gradient(135deg, #0100cb, #0166ff);
        color: #fff;
        padding: 12px 20px;
        border-radius: 8px;
        font-weight: bold;
        font-size: 1.5rem;
        text-align: center;
        margin-bottom: 30px; /* Memastikan hanya satu margin */
    }

    /* Responsive Styling */
    @media (max-width: 768px) {
        .form-container {
            padding: 15px;
        }

        .form-container button {
            font-size: 0.875rem;
            padding: 8px 16px;
        }
    }
</style>
<div class="container-fluid">
    <div class="row">
        <!-- Main Content -->
        <div class="col">
            <div class="p-4">
                <h2 class="mb-4">Tambah Karyawan</h2>
                @if (session('notification'))
                    <div id="notification" class="alert alert-{{ session('notification.type') }}">
                        {{ session('notification.message') }}
                    </div>
                @endif
                <div class="form-container">
                    @php
                        // Ambil tanggal dari URL (query string)
                        $date = request()->query('date');
                    @endphp
                    <form action="{{ route('karyawan.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="title">
                                Tambahkan Karyawan Baru
                            </div>
                            <!-- Kolom Kiri -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nama">NIP</label>
                                    <input type="text" class="form-control" id="nama" name="nip" required>
                                </div>
                                <div class="form-group">
                                    <label for="nama">Nama</label>
                                    <input type="text" class="form-control" id="nama" name="nama" required>
                                </div>
                                <div class="form-group">
                                    <label for="divisi">Divisi</label>
                                    <input type="text" class="form-control" id="divisi" name="divisi" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jabatan">Jabatan</label>
                                    <input type="text" class="form-control" id="jabatan" name="jabatan" required>
                                </div>
                                <div class="form-group">
                                    <label for="alamat">Alamat</label>
                                    <input type="text" class="form-control" id="alamat" name="alamat" required>
                                </div>
                                <div class="form-group">
                                    <label for="no_hp">No. HP</label>
                                    <input type="text" class="form-control" id="no_hp" name="no_hp" required>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-custom mt-3 me-2">Tambah</button>
                        <a href="{{ route('karyawan.datakar') }}" class="btn btn-danger mt-3 me-2">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<script>
    setTimeout(() => {
        const notification = document.getElementById('notification');
        if (notification) {
            notification.style.opacity = '0';
            setTimeout(() => notification.remove(), 500);
        }
    }, 5000);
</script>