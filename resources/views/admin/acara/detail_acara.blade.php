@extends('layouts.header')

@section('content')
<style>
    .detail-container {
        background: #fff;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .detail-container .form-group {
        margin-bottom: 1.5rem;
    }

    .detail-container .form-group label {
        font-weight: bold;
        color: #333;
    }

    .detail-container .form-control-static {
        border-radius: 8px;
        border: 1px solid #ddd;
        padding: 10px;
        font-size: 1rem;
        background: #f8f9fa;
    }

    .detail-container .title {
        background: linear-gradient(135deg, #0166ff, #0100cb);
        color: #fff;
        padding: 12px 20px;
        border-radius: 10px;
        font-weight: bold;
        font-size: 1.5rem;
        text-align: center;
        margin-bottom: 30px;
    }

    .qr-container {
        background: white;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
        text-align: center;
    }

    .qr-code {
        display: inline-block;
        padding: 10px;
        background: white;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        margin: 10px 0;
    }

    .qr-code svg {
        width: 100px;
        height: 100px;
    }

    .guest-info {
        margin: 10px 0;
        padding: 10px;
        background: #f8f9fa;
        border-radius: 4px;
    }

    .status-badge {
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 0.875rem;
    }

    .status-hadir {
        background-color: #28a745;
        color: white;
    }

    .status-tidak-hadir {
        background-color: #ffc107;
        color: black;
    }

    @media (max-width: 768px) {
        .detail-container {
            padding: 15px;
        }
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col">
            <div class="p-4">
                <h2 class="mb-4">Detail Acara</h2>
                @if (session('notification'))
                    <div id="notification" class="alert alert-{{ session('notification.type') }}">
                        {{ session('notification.message') }}
                    </div>
                @endif
                <div class="detail-container">
                    <div class="row">
                        <div class="title">
                            Detail Acara
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="kode_acara">Kode Acara</label>
                                <div class="form-control-static" id="kode_acara">{{ $event->kode_acara }}</div>
                            </div>
                            <div class="form-group">
                                <label for="event_date">Tanggal Acara</label>
                                <div class="form-control-static" id="event_date">{{ $event->tanggal }}</div>
                            </div>
                            <div class="form-group">
                                <label for="jam_mulai">Waktu Mulai</label>
                                <div class="form-control-static" id="jam_mulai">{{ $event->jam_mulai }}</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_acara">Nama Acara</label>
                                <div class="form-control-static" id="nama_acara">{{ $event->nama_acara }}</div>
                            </div>
                            <div class="form-group">
                                <label for="tempat">Lokasi Acara</label>
                                <div class="form-control-static" id="tempat">{{ $event->tempat }}</div>
                            </div>
                            <div class="form-group">
                                <label for="jam_selesai">Waktu Selesai</label>
                                <div class="form-control-static" id="jam_selesai">{{ $event->jam_selesai }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Buttons: Edit, Delete, Kembali -->
                    <div>
                        <a href="{{ route('acara.edit', Crypt::encryptString($event->id)) }}" class="btn btn-primary me-2">Edit</a>
                        <button type="button" class="btn btn-danger me-2" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            Hapus
                        </button>
                        <button type="button" class="btn btn-secondary" onclick="window.location.href='{{ route('acara') }}';">Batal</button>
                    </div>
                </div>

                <!-- Delete Confirmation Modal -->
                <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>Apakah Anda yakin ingin menghapus acara ini?</p>
                                <div class="alert alert-warning">
                                    <strong>Acara:</strong> {{ $event->nama_acara }}<br>
                                    <strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($event->tanggal)->format('d/m/Y') }}<br>
                                    <strong>Lokasi:</strong> {{ $event->tempat }}
                                </div>
                                <p class="text-danger"><strong>Perhatian:</strong> Tindakan ini tidak dapat dibatalkan!</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <form action="{{ route('acara.delete', $event->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Data Tamu: {{ $event->nama_acara }}</h2>
                    <div class="d-flex gap-2">
                        <a href="{{ route('acara.download.all.qr', $event->id) }}" class="btn btn-success">
                            <i class="fas fa-download"></i> Download Semua QR
                        </a>
                        <a href="{{ route('acara.showAddGuestForm', Crypt::encryptString($event->id)) }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah Tamu
                        </a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="guestTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Instansi</th>
                                        <th>Jabatan</th>
                                        <th>Status</th>
                                        <th>QR Code</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($tamus->isEmpty())
                                        <tr>
                                            <td colspan="7" class="text-center">
                                                <span>Belum ada tamu terdaftar</span>
                                            </td>
                                        </tr>
                                    @else
                                        @foreach($tamus as $index => $tamuAcara)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $tamuAcara->tamu->nama }}</td>
                                                <td>{{ $tamuAcara->tamu->asal_instansi }}</td>
                                                <td>{{ $tamuAcara->tamu->jabatan }}</td>
                                                <td>
                                                    <span class="status-badge {{ $tamuAcara->status === 'hadir' ? 'status-hadir' : 'status-tidak-hadir' }}">
                                                        {{ ucfirst($tamuAcara->status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="qr-container">
                                                        <div class="qr-code">
                                                            {!! $tamuAcara->qrCode !!}
                                                        </div>
                                                        <div>
                                                            <a href="{{ route('download.qr', [
                                                                'id_tamu' => $tamuAcara->tamu->id_tamu,
                                                                'kode_acara' => $event->kode_acara
                                                            ]) }}" class="btn btn-sm btn-success">
                                                                <i class="fas fa-download"></i> Download QR
                                                            </a>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteGuestModal{{ $tamuAcara->tamu->id_tamu }}">
                                                        Hapus
                                                    </button>
                                                    </form>
                                                </td>
                                            </tr>
                                            <div class="modal fade" id="deleteGuestModal{{ $tamuAcara->tamu->id_tamu }}" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Konfirmasi Hapus</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Apakah Anda yakin ingin menghapus <strong>{{ $tamuAcara->tamu->nama }}</strong>?</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                            <form action="{{ route('acara.remove.guest', ['eventId' => $event->id, 'guestId' => $tamuAcara->tamu->id_tamu]) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger">Hapus</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $('#guestTable').DataTable({
            responsive: true,
            order: [[0, 'asc']],
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
            }
        });

        setTimeout(() => {
            const notification = document.getElementById('notification');
            if (notification) {
                notification.style.transition = 'opacity 0.5s ease-out';
                notification.style.opacity = '0';
                setTimeout(() => notification.remove(), 500);
            }
        }, 5000); // Notifikasi akan hilang setelah 5 detik
    });
</script>
@endpush
@endsection