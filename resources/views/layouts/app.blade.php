    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Aplikasi Absensi') }}</title>

        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.17.0/font/bootstrap-icons.css">

        @vite(['resources/sass/app.scss', 'resources/js/app.js'])
        <style>


            body {
                margin: 0;
                min-height: 100vh;
            }
            #app {
                display: flex;
                flex-direction: column;
                min-height: 100vh;
            }

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
            #sidebar {
                min-width: 225px;
                max-width: 225px;
                min-height: 100vh;
            }

            .nav-link i {
                margin-right: 5px;
            }

            .nav-link {
                display: flex;
                align-items: center;
            }

            .card-link {
            text-decoration: none;
            color: inherit;
            }
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
            main {
                flex-grow: 1;
                display: flex;
                justify-content: center;
                align-items: center;
            }

            footer {
                margin-top: auto;
                background-color: #343a40;
                color: white;
                padding: 10px 0;
                text-align: center;
            }
        </style>
    </head>
    <body>
        <div id="app">
            <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm fixed-top">
                <div class="container">
                    <img src="{{ asset('images/logo-kominfo.png') }}" alt="Logo" class="welcome-logo">
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'Aplikasi Absensi ') }}
                    </a>

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
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="material-icons">account_circle</i> {{ Auth::user()->name }} ({{ Auth::user()->role }})
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            {{ __('Logout') }}
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

            <main class="py-4">
                @yield('content')
            </main>

            <footer class="bg-dark text-white text-center py-3">
                <div class="container">
                    <p>&copy; 2025 Aplikasi Presensi Diskominfo. All rights reserved.</p>
                </div>
            </footer>
        </div>

        <!-- External Scripts -->
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
        <script>
            $('.alert').alert();
            $(document).ready(function () {
                $('.table').DataTable();
            });
        </script>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
        <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
    </body>
    </html>