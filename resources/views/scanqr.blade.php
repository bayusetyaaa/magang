@extends('layouts.app')

@section('content')
    <style>
        body {
            font-family: 'Figtree', sans-serif;
            background: #f8f9fa;
        }

        .welcome-container {
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            gap: 20px;
            padding: 20px;
        }

        .card-container {
            max-width: 500px;
            width: 100%;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: #ffffff;
        }

        .welcome-logo1 {
            width: auto;
            height: 200px;
            margin-bottom: 20px;
        }

        #reader {
            width: 100%;
            height: 265px;
            border: 1px solid #ddd;
            display: none;
            border-radius: 8px;
            overflow: hidden;
        }

        .loading-spinner {
            display: inline-block;
            width: 1rem;
            height: 1rem;
            border: 0.2em solid currentColor;
            border-right-color: transparent;
            border-radius: 50%;
            animation: spinner-rotation 0.75s linear infinite;
            margin-right: 0.5rem;
        }

        @keyframes spinner-rotation {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .alert {
            display: none;
            margin-top: 1rem;
        }
    </style>
    <div class="welcome-container">
        <div class="card-container">
            <div class="text-center">
                <img src="{{ asset('images/logo-kominfo.png') }}" alt="Logo" class="welcome-logo1">
            </div>
            <h4 class="text-center mb-4">SELAMAT DATANG PADA ABSENSI<br>KOMINFO BLORA</h4>

            <!-- Alert Messages -->
            <div id="successAlert" class="alert alert-success" role="alert"></div>
            <div id="errorAlert" class="alert alert-danger" role="alert"></div>

            <!-- QR Code Scanner -->
            <div>
                <label for="reader" class="form-label">Scan QR Code</label>
                <div id="reader"></div>
                <button id="openCameraBtn" class="btn btn-success w-100">Buka Kamera</button>
                <button id="closeCameraBtn" class="btn btn-danger w-100 mt-2" style="display: none;">Tutup Kamera</button>
            </div>

            <div class="mt-4">
                <div class="mt-4">
                    @if (Route::has('login'))
                        @auth
                            @if (Auth::user()->role == 'admin')
                                <a href="{{ url('/home') }}" class="btn btn-primary w-100">Login Dashboard</a>
                            @else
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-danger w-100">Log Out</button>
                                </form>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn btn-danger w-100">Log in</a>
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- html5-qrcode -->
    <script src="https://cdn.jsdelivr.net/npm/html5-qrcode/minified/html5-qrcode.min.js"></script>
    
    <script>
        class AttendanceScanner {
            constructor() {
                this.html5QrCode = new Html5Qrcode("reader");
                this.openCameraBtn = document.getElementById("openCameraBtn");
                this.closeCameraBtn = document.getElementById("closeCameraBtn");
                this.reader = document.getElementById("reader");
                this.successAlert = document.getElementById("successAlert");
                this.errorAlert = document.getElementById("errorAlert");
                this.isScanning = false;

                this.initializeEventListeners();
            }

            initializeEventListeners() {
                this.openCameraBtn.addEventListener("click", () => this.startScanning());
                this.closeCameraBtn.addEventListener("click", () => this.stopScanning());
                window.addEventListener('beforeunload', () => this.cleanup());
            }

            showLoading(button) {
                button.disabled = true;
                button.innerHTML = '<span class="loading-spinner"></span>Memproses...';
            }

            hideLoading(button, text) {
                button.disabled = false;
                button.innerHTML = text;
            }

            showAlert(type, message) {
                const alert = type === 'success' ? this.successAlert : this.errorAlert;
                alert.textContent = message;
                alert.style.display = 'block';
                setTimeout(() => {
                    alert.style.display = 'none';
                }, 5000);
            }

            async startScanning() {
                try {
                    this.showLoading(this.openCameraBtn);
                    this.reader.style.display = "block";
                    this.closeCameraBtn.style.display = "block";
                    this.openCameraBtn.style.display = "none";

                    await this.html5QrCode.start(
                        { facingMode: "environment" },
                        {
                            fps: 10,
                            qrbox: { width: 250, height: 250 },
                            rememberLastUsedCamera: true
                        },
                        this.onQRCodeSuccess.bind(this),
                        this.onQRCodeError.bind(this)
                    );

                    this.isScanning = true;
                } catch (err) {
                    if (err.name === 'NotAllowedError') {
                        this.showAlert('error', 'Mohon izinkan akses kamera untuk menggunakan fitur ini');
                    } else {
                        this.showAlert('error', 'Gagal mengakses kamera. Silakan coba lagi.');
                    }
                    console.error("Camera error:", err);
                    this.cleanup();
                }
            }

            async onQRCodeSuccess(qrCodeMessage) {
                try {
                    const response = await fetch(`/api/check-qr-code/${qrCodeMessage}`, {
                        method: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest' 
                        },
                        credentials: 'same-origin' 
                    });

                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    

                    const data = await response.json();
                    
                    if (data.status === 'karyawan') {
                        this.showAlert('success', `Selamat datang, ${data.nama}!`);
                    } else if (data.status === 'tamu') {
                        this.showAlert('success', `Selamat berkunjung, Bpk/Ibu ${data.nama}!`);
                    } else {
                        this.showAlert('error', "Data tidak ditemukan. Silakan hubungi admin.");
                    }

                    await this.stopScanning();
                } catch (err) {
                    console.error("Error checking QR Code:", err);
                    this.showAlert('error', "QR Code yang Anda Berikan Tidak Valid, Silahkan Coba Lagi Atau Periksa QR Code Anda");
                    await this.stopScanning();
                }
            }

            onQRCodeError(errorMessage) {
                // Only log QR code reading errors, don't show to user unless critical
                console.log("QR Code reading error:", errorMessage);
            }

            async stopScanning() {
                if (this.isScanning) {
                    try {
                        await this.html5QrCode.stop();
                        this.cleanup();
                    } catch (err) {
                        console.error("Failed to stop scanner:", err);
                        this.showAlert('error', "Gagal menghentikan scanner. Silakan refresh halaman.");
                    }
                }
            }

            cleanup() {
                this.reader.style.display = "none";
                this.closeCameraBtn.style.display = "none";
                this.openCameraBtn.style.display = "block";
                this.hideLoading(this.openCameraBtn, 'Buka Kamera');
                this.isScanning = false;
            }
        }

        // Initialize scanner when document is ready
        document.addEventListener('DOMContentLoaded', () => {
            new AttendanceScanner();
        });
    </script>
</body>
</html>
@endsection
