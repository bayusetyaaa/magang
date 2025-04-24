<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Aplikasi Absensi') }}</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.17.0/font/bootstrap-icons.css"> -->

    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css" rel="stylesheet">

    <!-- FullCalendar CSS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css" rel="stylesheet">

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <style>
        /* General Reset and Layout */
        body {
            margin: 0;
            min-height: 100vh;
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }

        #app {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            padding-top: 45px;
        }

        footer {
            margin-top: auto;
            background-color: #ffff;
            color: black;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
            padding: 10px 20px;
            text-align: center;
        }


        /* Navbar Styling */
        .navbar {
            transition: top 0.3s;
        }

        .navbar.sticky {
            position: fixed;
            top: 0;
            width: 100%;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            z-index: 1000;
        }

        /* Sidebar Styling */
        #sidebar {
            height: 100vh;
            background: linear-gradient(to bottom, #0100cb, #0166ff);
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            margin-top: 60px;
        }

        .sidebar a {
            color: #d1c4e9;
            text-decoration: none;
        }

        .sidebar a:hover {
            color: white;
        }

        .nav-link {
            display: flex;
            align-items: center;
        }

        .nav-link i {
            margin-right: 5px;
        }

        .card-link {
            text-decoration: none;
            color: inherit;
        }

        /* Welcome & Logo Styling */
        .welcome-logo {
            width: auto;
            height: 50px;
        }

        .kominfo-logo {
            width: auto;
            height: 200px;
            margin-bottom: 20px;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        /* Card Styles */
        .card-gradient {
            border: none;
            border-radius: 10px;
            background: linear-gradient(45deg, #0100cb, #0166ff);
            color: white;
            margin-bottom: 10px;

        }

        .card-gradient-blue {
            background: linear-gradient(45deg, #8400cb, #7463fb);
            color: white;
            margin-bottom: 10px;
        }

        .card-gradient-green {
            background: linear-gradient(45deg, #00cb1a, #55fbc4);
            color: white;
            margin-bottom: 10px;
        }

        .card-gradient-purple {
            background: linear-gradient(45deg, #8e54e9, #4776e6);
            color: white;
            margin-bottom: 10px;
        }

        .topbar {
            background-color: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 10px 20px;
        }

        .topbar img {
            border-radius: 50%;
        }
        .chart-container {
            position: relative;
            height: 50vh;
            width: auto;

        }
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm fixed-top">
            <div class="container-fluid">
                <div class="d-flex align-items-center">
                    <img src="{{ asset('images/logo-kominfo.png') }}" alt="Logo" class="welcome-logo me-3">
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'Aplikasi Absensi ') }}
                    </a>
                </div>

                <div>
                    <ul class="navbar-nav ms-auto">
                    @guest
                        @if (Route::has('login'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">
                                    <i class="bi bi-box-arrow-in-right"></i> {{ __('Login') }}
                                </a>
                            </li>
                        @endif
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('scanqr') }}">
                                    <i class="material-icons me-2">qr_code</i>{{ __('Scan QR') }}
                                </a>
                            </li>
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="material-icons">account_circle</i> {{ Auth::user()->name }} ({{ Auth::user()->role }})
                                </a>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item d-flex align-items-center" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="bi bi-box-arrow-left me-2"></i> {{ __('Logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        @include('layouts.sidebar')

        <main >
            @yield('content')
        </main>

        <footer class="text-center py-3">
            <div class="container">
                <p>&copy; 2025 Aplikasi Presensi Diskominfo. All rights reserved.</p>
            </div>
        </footer>
    </div>

     <!-- jQuery -->
     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>

<!-- FullCalendar JS -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js"></script>

<!-- QR Code JS -->
<script src="https://cdn.jsdelivr.net/npm/qrcode"></script>
<!-- Add Select2 CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet">

<!-- Add Select2 JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
    $(document).ready(function () {
        // Initialize DataTables
        $('#karyawanTable').DataTable({
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ hingga _END_ dari _TOTAL_ data",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Berikutnya",
                    previous: "Sebelumnya"
                }
            },
            pageLength: 10
        });
        
    });
</script>
