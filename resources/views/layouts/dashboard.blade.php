<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'DahlaPakad') }} - Dashboard</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100 font-sans leading-none">
    <div id="app">
        <!-- Sidebar -->
        <div class="flex h-screen bg-gray-200">
            <div class="flex flex-col w-64 bg-gray-800 text-white">
                <!-- Logo -->
                <div class="flex items-center justify-center h-16 bg-gray-900">
                    <span class="text-xl font-semibold">DahlaPakad</span>
                </div>
                
                <!-- Navigation -->
                <nav class="flex-1 px-2 py-4 space-y-1">
                    <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded-md {{ request()->routeIs('dashboard') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                        <i class="fas fa-home mr-3"></i>
                        Dashboard
                    </a>
                    <a href="{{ route('profile') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded-md {{ request()->routeIs('profile*') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                        <i class="fas fa-user mr-3"></i>
                        Profile
                    </a>
                    <a href="{{ route('wallet') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded-md {{ request()->routeIs('wallet*') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                        <i class="fas fa-wallet mr-3"></i>
                        Wallet
                    </a>
                    <a href="{{ route('rooms.index') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded-md {{ request()->routeIs('rooms*') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                        <i class="fas fa-gamepad mr-3"></i>
                        Game Rooms
                    </a>
                </nav>
                
                <!-- User Profile -->
                <div class="p-4 mt-auto border-t border-gray-700">
                    <div class="flex items-center">
                        <img class="w-10 h-10 rounded-full" src="{{ Auth::user()->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=random' }}" alt="{{ Auth::user()->name }}">
                        <div class="ml-3">
                            <p class="text-sm font-medium text-white">{{ Auth::user()->name }}</p>
                            <p class="text-xs font-medium text-gray-400">{{ Auth::user()->rank ?? 'Player' }}</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-gray-300 rounded-md hover:bg-gray-700">
                                <i class="fas fa-sign-out-alt mr-3"></i>
                                Sign out
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="flex flex-col flex-1 overflow-hidden">
                <!-- Top Navigation -->
                <header class="bg-white shadow">
                    <div class="flex items-center justify-between px-6 py-3">
                        <h1 class="text-xl font-semibold text-gray-800">@yield('title', 'Dashboard')</h1>
                        <div class="flex items-center space-x-4">
                            <div class="relative">
                                <button class="p-2 text-gray-600 rounded-full hover:bg-gray-100">
                                    <i class="fas fa-bell"></i>
                                    <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
                                </button>
                            </div>
                            <div class="relative">
                                <button class="flex items-center text-sm font-medium text-gray-700 rounded-full focus:outline-none">
                                    <span class="mr-2">Balance: ₹{{ number_format(Auth::user()->wallet_balance ?? 0, 2) }}</span>
                                    <i class="fas fa-plus-circle text-green-500"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
                    <div class="container px-6 py-6 mx-auto">
                        @yield('content')
                    </div>
                </main>
            </div>
        </div>
    </div>

    <!-- Login/Signup Modal -->
    @include('auth.modal')

    @stack('scripts')
</body>
</html>
