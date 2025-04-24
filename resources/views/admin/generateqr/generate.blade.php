@extends('layouts.header')

@section('content')
<style>
    /* Styling Form */
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
                <h2 class="mb-4">Generate QR Code</h2>
                @if (session('notification'))
                    <div id="notification" class="alert alert-{{ session('notification.type') }}">
                        {{ session('notification.message') }}
                    </div>
                @endif
                <div class="form-container">
                    <form id="qr-form">
                        @csrf
                        <div class="title">Form Generate QR Code</div>
                        <div class="row">
                            <!-- Pilihan Dropdown -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="type">Tipe</label>
                                    <select class="form-control" id="type">
                                        <option value="pegawai">Pegawai</option>
                                        <option value="tamu">Tamu</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <!-- Kolom Input -->
                            <div class="col-md-6">
                                <div class="form-group" id="input1-group">
                                    <label for="input1">NIP</label>
                                    <input type="text" class="form-control" id="input1" placeholder="Masukkan NIP" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="kode_acara">Kode Acara</label>
                                    <input type="text" class="form-control" id="kode_acara" placeholder="Masukkan Kode Acara" required>
                                </div>
                            </div>
                        </div>
                        <div style="display: flex; justify-content: center;">
                            <button type="button" id="generate_qr" class="btn btn-primary mt-3">Generate QR Code</button>
                        </div>
                    </form>
                    <div style="display: flex; justify-content: center;">
                        <div id="qr_code_container" class="mt-4" style="display: none; text-align: center;">
                            <h5>QR Code:</h5>
                            <div style="display: flex; justify-content: center; margin-top: 10px;">
                                <img id="qr_code_image" src="" alt="QR Code" style="min-width: 250px; max-width: 400px;">
                            </div>
                            <div style="margin-top: 15px;">
                                <a id="download_link" href="#" class="btn btn-secondary mt-3" download="qr_code.png">Download QR Code</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
<script>
    $(document).ready(function () {
        // Ganti Label dan Placeholder Berdasarkan Pilihan Dropdown
        $('#type').on('change', function () {
            var type = $(this).val();
            if (type === 'pegawai') {
                $('#input1-group label').text('NIP');
                $('#input1').attr('placeholder', 'Masukkan NIP');
            } else if (type === 'tamu') {
                $('#input1-group label').text('ID Tamu');
                $('#input1').attr('placeholder', 'Masukkan ID Tamu');
            }
        });

        // Generate QR Code
        $('#generate_qr').on('click', function () {
            var type = $('#type').val();
            var input1 = $('#input1').val();
            var kodeAcara = $('#kode_acara').val();

            if (input1 && kodeAcara) {
                var data = type === 'pegawai'
                    ? `NIP: ${input1}, Kode Acara: ${kodeAcara}`
                    : `ID Tamu: ${input1}, Kode Acara: ${kodeAcara}`;

                QRCode.toDataURL(data, function (err, url) {
                    if (!err) {
                        $('#qr_code_image').attr('src', url);
                        $('#qr_code_container').show();
                        $('#download_link').attr('href', url);
                    }
                });
            } else {
                alert('Harap masukkan semua data!');
            }
        });
    });
</script>
@endsection
