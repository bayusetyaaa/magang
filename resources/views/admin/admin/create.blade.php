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

    .form-container .title {
        background: linear-gradient(135deg, #0100cb, #0166ff);
        color: #fff;
        padding: 12px 20px;
        border-radius: 8px;
        font-weight: bold;
        font-size: 1.5rem;
        text-align: center;
        margin-bottom: 30px;
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
                <h2 class="mb-4">Tambah Admin</h2>

                <div class="form-container">
                    <form action="{{ route('admin.store') }}" method="POST">
                        @csrf
                        <div class="title">Tambah User Baru</div>
                        
                        <div class="form-group">
                            <label for="nip">Pilih Karyawan</label>
                            <select name="nip" class="form-control" required>
                                <option value="">Pilih Karyawan</option>
                                @foreach ($karyawans as $karyawan)
                                    <option value="{{ $karyawan->nip }}">{{ $karyawan->nama }} ({{ $karyawan->nip }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="role">Role</label>
                            <select class="form-control" id="role" name="role">
                                <option value="" disabled selected>Pilih Role</option>
                                <option value="admin">Admin</option>
                                <option value="karyawan">Karyawan</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation">Konfirmasi Password</label>
                            <input type="password" class="form-control" name="password_confirmation" value="" required>
                        </div>

                        <button type="submit" class="btn btn-primary mt-3 me-2">Tambah</button>
                        <a href="{{ route('admin.datauser') }}" class="btn btn-danger mt-3">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('email').value = '';
    document.getElementById('password').value = '';
    // Script untuk toggle eye icon dan password
    document.getElementById('eye-icon').addEventListener('click', function () {
        const passwordField = document.getElementById('password');
        const icon = this.querySelector('i');

        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            icon.textContent = 'visibility_off';
        } else {
            passwordField.type = 'password';
            icon.textContent = 'visibility';
        }
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
