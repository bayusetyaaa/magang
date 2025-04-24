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
        margin-top: 10px; 
        margin-bottom: 20px;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <!-- Main Content -->
        <div class="main-content p-4">
            <!-- Success Notification -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="d-flex justify-content-between mb-3">
                <h2 class="mb-4">Daftar User</h2>
                <a href="{{ route('admin.create') }}" class="btn btn-primary btn-custom">Tambah User</a>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>NIP</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $index => $user)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $user->karyawan ? $user->karyawan->nama : '-' }}</td>
                                        <td>{{ $user->nip }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->role }}</td>
                                        <td>
                                            <form action="{{ route('admin.destroy', $user->id) }}" method="POST" style="display: inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');">Hapus</button>
                                            </form>
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