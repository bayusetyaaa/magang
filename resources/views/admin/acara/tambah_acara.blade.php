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
                <h2 class="mb-4">Tambah Acara</h2>
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
                    <form action="{{ route('acara.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="title">
                                Tambahkan Acara Baru
                            </div>
                            <!-- Kolom Kiri -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="kode_acara">Kode Acara</label>
                                    <input type="text" class="form-control" id="kode_acara" name="kode_acara" required readonly>
                                </div>
                                <div class="form-group">
                                    <label for="event_date">Tanggal Acara</label>
                                    <input type="date" class="form-control" id="event_date" name="event_date" value="{{ $date }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="jam_mulai">Waktu Mulai</label>
                                    <input type="time" class="form-control" id="jam_mulai" name="jam_mulai" required>
                                </div>
                            </div>

                            <!-- Kolom Kanan -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nama_acara">Nama Acara</label>
                                    <input type="text" class="form-control" id="nama_acara" name="nama_acara" required>
                                </div>
                                <div class="form-group">
                                    <label for="tempat">Lokasi Acara</label>
                                    <input class="form-control" id="tempat" name="tempat" required></input>
                                </div>
                                <div class="form-group">
                                    <label for="jam_selesai">Waktu Selesai</label>
                                    <input type="time" class="form-control" id="jam_selesai" name="jam_selesai" required>
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Simpan dan Batal -->
                         <div>
                             <button type="submit" class="btn btn-primary">Simpan</button>
                             <a href="{{ route('acara') }}" class="btn btn-danger">Batal</a>
                         </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const kodeAcaraInput = document.getElementById('kode_acara');
        const eventDateInput = document.getElementById('event_date');

        if (kodeAcaraInput && eventDateInput) {
            // Fungsi untuk mendapatkan kode acara terakhir berdasarkan tanggal yang dipilih
            const fetchLastKodeAcara = async (selectedDate) => {
                try {
                    const response = await fetch(`/get-last-kode-acara?date=${selectedDate}`);
                    const data = await response.json();

                    if (data.success && data.lastKodeAcara) {
                        const lastKode = data.lastKodeAcara;

                        // Ekstrak tanggal dan nomor urut dari kode terakhir
                        const match = lastKode.match(/^(\d{8})-(\d{3})$/);
                        
                        let nextNumber = 1;
                        
                        if (match) {
                            const lastDate = match[1];
                            const lastSequence = parseInt(match[2], 10);

                            if (lastDate === selectedDate) {
                                nextNumber = lastSequence + 1;
                            }
                        }

                        // Format kode acara baru berdasarkan tanggal dan nomor urut
                        const newKodeAcara = selectedDate + '-' + nextNumber.toString().padStart(3, '0');
                        kodeAcaraInput.value = newKodeAcara;
                    } else {
                        // Jika tidak ada kode acara terakhir, mulai dengan tanggal dan urutan 001
                        const newKodeAcara = selectedDate + '-001';
                        kodeAcaraInput.value = newKodeAcara;
                    }
                } catch (error) {
                    console.error('Gagal mendapatkan kode acara terakhir:', error);
                    kodeAcaraInput.value = selectedDate + '-001'; // Default jika ada masalah
                }
            };

            // Panggil fungsi untuk mengambil kode acara berdasarkan tanggal yang dipilih
            eventDateInput.addEventListener('change', (e) => {
                const selectedDate = e.target.value.replace(/-/g, ''); // Format YYYYMMDD
                fetchLastKodeAcara(selectedDate);
            });

            // Panggil fungsi pertama kali jika ada tanggal yang sudah dipilih sebelumnya
            const initialDate = eventDateInput.value.replace(/-/g, '');
            if (initialDate) {
                fetchLastKodeAcara(initialDate);
            }
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

