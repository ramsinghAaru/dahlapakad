<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ showAuthModal: false, activeTab: 'login' }" x-cloak>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Dehla Pakad'))</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <!-- Custom CSS -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    
    <!-- Additional Page-Specific Styles -->
    @stack('styles')
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    
    <!-- Page-Specific Meta Tags -->
    @stack('meta')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ url('/') }}">
                <i class="fa fa-gamepad me-2"></i>Dehla Pakad
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">
                            <i class="fa fa-home d-lg-none me-2"></i>Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('about') ? 'active' : '' }}" href="{{ url('/about') }}">
                            <i class="fa fa-info-circle d-lg-none me-2"></i>About
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('play*') ? 'active' : '' }}" href="{{ url('/play') }}">
                            <i class="fa fa-play d-lg-none me-2"></i>Play
                        </a>
                    </li>
                </ul>
                <div class="d-flex flex-column flex-lg-row gap-3 my-2 my-lg-0">
                    @auth
                        <div class="d-flex align-items-center me-0 me-lg-3 mb-2 mb-lg-0">
                            <img src="{{ Auth::user()->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=random' }}" 
                                 alt="{{ Auth::user()->name }}" 
                                 class="rounded-circle me-2" 
                                 style="width: 32px; height: 32px; object-fit: cover;">
                            <span class="text-white d-none d-lg-inline">{{ Auth::user()->name }}</span>
                        </div>
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-light me-lg-2 mb-2 mb-lg-0" 
                           data-bs-toggle="tooltip" data-bs-placement="bottom" title="Dashboard">
                            <i class="fa fa-tachometer-alt"></i>
                            <span class="d-none d-lg-inline ms-1">Dashboard</span>
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-light w-100" 
                                    data-bs-toggle="tooltip" data-bs-placement="bottom" title="Logout">
                                <i class="fa fa-sign-out-alt"></i>
                                <span class="d-none d-lg-inline ms-1">Logout</span>
                            </button>
                        </form>
                    @else
                        <div class="d-flex flex-column flex-lg-row gap-2 w-100">
                            <a href="{{ route('login') }}" 
                               class="btn btn-outline-light text-nowrap d-flex align-items-center justify-content-center"
                               data-bs-toggle="tooltip" data-bs-placement="bottom" title="Sign in to your account">
                                <i class="fa fa-sign-in me-1"></i>
                                <span>Login</span>
                            </a>
                            <a href="{{ route('register') }}" 
                               class="btn btn-primary text-nowrap d-flex align-items-center justify-content-center"
                               data-bs-toggle="tooltip" data-bs-placement="bottom" title="Create a new account">
                                <i class="fa fa-user-plus me-1"></i>
                                <span>Register</span>
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <main class="flex-grow-1 py-3 py-md-4">
        <div class="container px-3">
            @if($errors->any())
                @foreach($errors->all() as $error)
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            toastr.error('{{ $error }}');
                        });
                    </script>
                @endforeach
            @endif
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-4">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12 col-md-6 text-center text-md-start mb-3 mb-md-0">
                    <p class="mb-0">
                        <i class="fa fa-copyright"></i> {{ date('Y') }} Dehla Pakad. All rights reserved.
                    </p>
                </div>
                <div class="col-12 col-md-6 text-center text-md-end">
                    <div class="d-flex justify-content-center justify-content-md-end gap-3">
                        <a href="{{ url('/about') }}" class="text-white text-decoration-none">
                            <i class="fa fa-info-circle me-1"></i> About
                        </a>
                        <a href="{{ url('/terms') }}" class="text-white text-decoration-none">
                            <i class="fa fa-file-text me-1"></i> Terms
                        </a>
                        <a href="{{ url('/privacy') }}" class="text-white text-decoration-none">
                            <i class="fa fa-shield me-1"></i> Privacy
                        </a>
                        <a href="#" class="text-white text-decoration-none">
                            <i class="fa fa-question-circle me-1"></i> Help
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.10/dist/cdn.min.js" defer></script>
    <!-- jQuery (required for toastr) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <!-- Custom JS -->
    <script src="{{ asset('js/app.js' . (config('app.env') === 'production' ? '?v=' . filemtime(public_path('js/app.js')) : '')) }}" defer></script>
    
    <!-- Page-Specific Scripts -->
    @stack('scripts')
    
    <script>
        // Initialize tooltips
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            
            // Initialize toastr with default options
            toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };
        });
    </script>
    <style>
        /* Custom styles for auth buttons */
        .navbar .btn-outline-light:hover {
            background-color: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.3);
        }
        .navbar .btn-primary {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .navbar .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }
        .navbar .nav-link {
            transition: all 0.2s ease-in-out;
        }
        .navbar .nav-link:hover {
            color: rgba(255, 255, 255, 0.9) !important;
        }
    </style>
</body>
</html>
