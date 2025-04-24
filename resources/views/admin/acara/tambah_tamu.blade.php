@extends('layouts.header')

@section('content')
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />

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
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .form-container button:hover {
        background: linear-gradient(135deg, #0166ff, #0100cb);
    }

    .form-container .title {
        background: linear-gradient(135deg, #0100cb, #0166ff);
        color: #fff;
        padding: 12px 20px;
        border-radius: 8px;
        font-weight: bold;
        text-align: center;
        margin-bottom: 30px;
    }

    .error-text {
        color: red;
        font-size: 0.9em;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col">
            <div class="p-4">
                <h2 class="mb-4">Tambah Data Tamu</h2>
                @if (session('notification'))
                    <div id="notification" class="alert alert-{{ session('notification.type') }}">
                        {{ session('notification.message') }}
                    </div>
                @endif

                <div class="form-container">
                    <form id="tambah-tamu-form" action="{{ route('acara.addGuestToEvent', ['id' => $event->id]) }}" method="POST">
                        @csrf
                        <div class="title" id="form-title">Tambah Tamu Baru</div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="kode_acara">Kode Acara</label>
                                    <input type="text" class="form-control" id="kode_acara" name="kode_acara" value="{{ $event->kode_acara }}" required readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nama_acara">Nama Acara</label>
                                    <input type="text" class="form-control" id="nama_acara" name="nama_acara" value="{{ $event->nama_acara }}" required readonly>
                                </div>
                            </div>
                        </div>

                        <div style="display: flex; align-items: center;">
                            <div class="form-group" style="flex-grow: 1;">
                                <label for="tamu_select">Pilih Tamu</label>
                                <select class="form-control select2" id="tamu_select" name="id_tamu">
                                    <option value="">Pilih Tamu</option>
                                    @foreach ($availableGuests as $guest)
                                        <option value="{{ $guest->id_tamu }}" 
                                            data-nama="{{ $guest->nama }}" 
                                            data-asal-instansi="{{ $guest->asal_instansi }}" 
                                            data-jabatan="{{ $guest->jabatan }}">
                                            {{ $guest->nama }} ({{ $guest->id_tamu }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div id="form-tamu-baru">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="id_tamu_baru">ID Tamu</label>
                                        <input type="text" class="form-control" id="id_tamu_baru" name="id_tamu" required>
                                        <small class="error-text" id="id_tamu_error"></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nama_baru">Nama Tamu</label>
                                        <input type="text" class="form-control" id="nama_baru" name="nama" required>
                                        <small class="error-text" id="nama_error"></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="asal_instansi_baru">Asal Instansi</label>
                                        <input type="text" class="form-control" id="asal_instansi_baru" name="asal_instansi">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="jabatan_baru">Jabatan</label>
                                        <input type="text" class="form-control" id="jabatan_baru" name="jabatan">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" id="submit-button">Tambah Tamu</button>
                            <a href="{{ route('acara.detail', ['id' => $event->id]) }}" class="btn btn-danger">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- jQuery (Required for Select2) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

<script>
    setTimeout(() => {
        const notification = document.getElementById('notification');
        if (notification) {
            notification.style.opacity = '0';
            setTimeout(() => notification.remove(), 500);
        }
    }, 5000);
    
$(document).ready(function() {
    // Inisialisasi Select2
    $('#tamu_select').select2({
        placeholder: "Pilih Tamu",
        allowClear: true,
        minimumInputLength: 1,
        language: {
            noResults: function() {
                return 'Tamu belum terdaftar, silahkan daftar dengan mengisi manual form dibawah';
            }
        },
        escapeMarkup: function(markup) {
            return markup;
        }
    });

    // Event listener untuk dropdown
    $('#tamu_select').on('change', function() {
        const selectedOption = $(this).find('option:selected');
        const idTamu = selectedOption.val();
        const nama = selectedOption.data('nama');
        const asalInstansi = selectedOption.data('asal-instansi');
        const jabatan = selectedOption.data('jabatan');

        if (idTamu) {
            $('#id_tamu_baru').val(idTamu).prop('readonly', true);
            $('#nama_baru').val(nama).prop('readonly', true);
            $('#asal_instansi_baru').val(asalInstansi).prop('readonly', true);
            $('#jabatan_baru').val(jabatan).prop('readonly', true);
        } else {
            $('#id_tamu_baru').val('').prop('readonly', false);
            $('#nama_baru').val('').prop('readonly', false);
            $('#asal_instansi_baru').val('').prop('readonly', false);
            $('#jabatan_baru').val('').prop('readonly', false);
        }
    });
});
</script>
@endsection
