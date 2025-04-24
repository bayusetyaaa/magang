@extends('layouts.header')

@section('content')
<style>
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

    .form-container .title {
        background: linear-gradient(135deg, #0166ff, #0100cb);
        color: #fff;
        padding: 12px 20px;
        border-radius: 10px;
        font-weight: bold;
        font-size: 1.5rem;
        text-align: center;
        margin-bottom: 30px;
    }

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
        <div class="col">
            <div class="p-4">
                <h2 class="mb-4">Edit Acara</h2>
                
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('notification'))
                    <div id="notification" class="alert alert-{{ session('notification.type') }}">
                        {{ session('notification.message') }}
                    </div>
                @endif

                <div class="form-container">
                    <form action="{{ route('acara.update', $event->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="title">
                                Edit Acara
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="kode_acara">Kode Acara</label>
                                    <input type="text" class="form-control" id="kode_acara" value="{{ $event->kode_acara }}" disabled>
                                </div>
                                <div class="form-group">
                                    <label for="event_date">Tanggal Acara</label>
                                    <input type="date" class="form-control @error('event_date') is-invalid @enderror" 
                                           id="event_date" name="event_date" 
                                           value="{{ old('event_date', $event->tanggal) }}" required>
                                    @error('event_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="jam_mulai">Waktu Mulai</label>
                                    <input type="time" class="form-control @error('jam_mulai') is-invalid @enderror" 
                                           id="jam_mulai" name="jam_mulai" 
                                           value="{{ old('jam_mulai', $event->jam_mulai) }}" required>
                                    @error('jam_mulai')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nama_acara">Nama Acara</label>
                                    <input type="text" class="form-control @error('nama_acara') is-invalid @enderror" 
                                           id="nama_acara" name="nama_acara" 
                                           value="{{ old('nama_acara', $event->nama_acara) }}" required>
                                    @error('nama_acara')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="tempat">Lokasi Acara</label>
                                    <input type="text" class="form-control @error('tempat') is-invalid @enderror" 
                                           id="tempat" name="tempat" 
                                           value="{{ old('tempat', $event->tempat) }}" required>
                                    @error('tempat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="jam_selesai">Waktu Selesai</label>
                                    <input type="time" class="form-control @error('jam_selesai') is-invalid @enderror" 
                                           id="jam_selesai" name="jam_selesai" 
                                           value="{{ old('jam_selesai', $event->jam_selesai) }}" required>
                                    @error('jam_selesai')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary me-2">Update Acara</button>
                        <a type="button" class="btn btn-secondary" onclick="window.history.back()">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    setTimeout(() => {
        const notification = document.getElementById('notification');
        if (notification) {
            notification.style.opacity = '0';
            setTimeout(() => notification.remove(), 500);
        }
    }, 5000);
</script>
@endsection