@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid px-3 py-3">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fa fa-tachometer-alt text-primary me-2"></i>Dashboard
                    </h1>
                    <nav aria-label="breadcrumb" class="d-none d-md-block mt-2">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item active" aria-current="page">Home</li>
                        </ol>
                    </nav>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('play') }}" class="btn btn-primary">
                        <i class="fa fa-play me-1"></i> Play Now
                    </a>
                    <button class="btn btn-outline-secondary d-md-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
                        <i class="fa fa-bars"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Games Played</h6>
                            <h3 class="mb-0">{{ $stats['games_played'] ?? 0 }}</h3>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded">
                            <i class="fa fa-gamepad text-primary fs-4"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <span class="badge bg-success bg-opacity-10 text-success">
                            <i class="fa fa-arrow-up"></i> 12%
                        </span>
                        <span class="text-muted small ms-1">vs last week</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Wins</h6>
                            <h3 class="mb-0">{{ $stats['wins'] ?? 0 }}</h3>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded">
                            <i class="fa fa-trophy text-success fs-4"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <span class="badge bg-success bg-opacity-10 text-success">
                            <i class="fa fa-arrow-up"></i> 8%
                        </span>
                        <span class="text-muted small ms-1">vs last week</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total Points</h6>
                            <h3 class="mb-0">{{ number_format($stats['points'] ?? 0) }}</h3>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-3 rounded">
                            <i class="fa fa-star text-warning fs-4"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <span class="badge bg-success bg-opacity-10 text-success">
                            <i class="fa fa-arrow-up"></i> 15%
                        </span>
                        <span class="text-muted small ms-1">vs last week</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Rank</h6>
                            <h3 class="mb-0">#{{ $stats['rank'] ?? 'N/A' }}</h3>
                        </div>
                        <div class="bg-info bg-opacity-10 p-3 rounded">
                            <i class="fa fa-award text-info fs-4"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <span class="badge bg-success bg-opacity-10 text-success">
                            <i class="fa fa-arrow-up"></i> 2
                        </span>
                        <span class="text-muted small ms-1">positions up</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row g-3">
        <!-- Welcome Card -->
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fa fa-user me-2 text-primary"></i>Welcome Back, {{ Auth::user()->name }}
                    </h5>
                    <span class="badge bg-{{ Auth::user()->rank_color ?? 'primary' }}-light text-{{ Auth::user()->rank_color ?? 'primary' }}">
                        {{ Auth::user()->rank ?? 'Player' }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-4 text-center mb-3 mb-md-0">
                            <img src="{{ Auth::user()->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=random' }}" 
                                 alt="{{ Auth::user()->name }}" 
                                 class="rounded-circle img-thumbnail" 
                                 style="width: 120px; height: 120px; object-fit: cover;">
                        </div>
                        <div class="col-md-8">
                            <div class="d-flex flex-column h-100">
                                <div class="mb-3">
                                    <h4 class="mb-2">Welcome to Your Dashboard</h4>
                                    <p class="text-muted mb-0">
                                        Stay updated with your game statistics, recent activities, and more.
                                    </p>
                                </div>
                                <div class="mt-auto">
                                    <div class="progress mb-2" style="height: 8px;">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $stats['level_progress'] ?? 25 }}%" 
                                             aria-valuenow="{{ $stats['level_progress'] ?? 25 }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <div class="d-flex justify-content-between small text-muted">
                                        <span>Level {{ $stats['level'] ?? 1 }}</span>
                                        <span>{{ $stats['xp'] ?? 0 }}/{{ $stats['xp_to_next_level'] ?? 1000 }} XP</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Games -->
        <div class="col-12 col-lg-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-header bg-white d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                    <h5 class="mb-2 mb-md-0">
                        <i class="fa fa-gamepad me-2 text-primary"></i> Active Games
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($activeRoom)
                        <div class="overflow-hidden bg-white border border-gray-200 rounded-lg">
                            <div class="px-4 py-5 sm:p-6">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="text-lg font-medium text-gray-900">Room #{{ $activeRoom->code }}</h4>
                                        <p class="mt-1 text-sm text-gray-500">
                                            {{ $activeRoom->status === 'in_progress' ? 'Game in progress' : 'Waiting for players' }}
                                        </p>
                                    </div>
                                    <span class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-full {{ $activeRoom->status === 'in_progress' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                        {{ $activeRoom->status === 'in_progress' ? 'Playing' : 'Waiting' }}
                                    </span>
                                </div>
                                
                                <div class="mt-4">
                                    <h5 class="text-sm font-medium text-gray-500">Players ({{ $activeRoom->players->count() }}/4)</h5>
                                    <div class="grid grid-cols-2 gap-4 mt-2 sm:grid-cols-4">
                                        @foreach($activeRoom->players as $player)
                                            <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                                <img class="w-10 h-10 rounded-full" src="{{ $player->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($player->name) }}" alt="{{ $player->name }}">
                                                <div class="ml-3">
                                                    <p class="text-sm font-medium text-gray-900">{{ $player->name }}</p>
                                                    <p class="text-xs text-gray-500">{{ $player->seat }} @if($player->user_id === Auth::id())(You)@endif</p>
                                                </div>
                                            </div>
                                        @endforeach
                                        @for($i = $activeRoom->players->count(); $i < 4; $i++)
                                            <div class="flex items-center justify-center p-3 bg-gray-50 rounded-lg">
                                                <div class="w-10 h-10 bg-gray-200 rounded-full"></div>
                                                <div class="ml-3">
                                                    <div class="w-20 h-4 bg-gray-200 rounded"></div>
                                                    <div class="w-12 h-3 mt-1 bg-gray-200 rounded"></div>
                                                </div>
                                            </div>
                                        @endfor
                                    </div>
                                </div>

                                <div class="flex justify-end mt-6 space-x-3">
                                    @if($activeRoom->status === 'waiting' && $activeRoom->players->count() < 4)
                                        <button type="button" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <i class="mr-2 fas fa-share"></i>
                                            Invite Friends
                                        </button>
                                    @endif
                                    
                                    <a href="{{ route('game.show', $activeRoom->code) }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        <i class="mr-2 fas fa-play"></i>
                                        {{ $activeRoom->status === 'in_progress' ? 'Continue Game' : 'Start Game' }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center">
                            <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No active games</h3>
                            <p class="mt-1 text-sm text-gray-500">Join or create a game room to start playing.</p>
                            <div class="mt-6">
                                <a href="{{ route('rooms.create') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <i class="mr-2 fas fa-plus"></i>
                                    Create Room
                                </a>
                                <a href="{{ route('rooms.index') }}" class="inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <i class="mr-2 fas fa-search"></i>
                                    Find Room
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row g-3">
        <div class="col-12 col-lg-8">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-header bg-white d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                    <h5 class="mb-2 mb-md-0">
                        <i class="fa fa-history me-2 text-primary"></i> Recent Activity
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="flow-root">
                        <ul class="-mb-8">
                            <li>
                                <div class="relative pb-8">
                                    <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span class="flex items-center justify-center w-8 h-8 bg-blue-500 rounded-full">
                                                <i class="text-white fas fa-trophy"></i>
                                            </span>
                                        </div>
                                        <div class="flex-1 min-w-0 pt-1.5 flex justify-between space-x-4">
                                            <div>
                                                <p class="text-sm text-gray-500">You won a game in <span class="font-medium text-gray-900">Room #{{ strtoupper(Str::random(6)) }}</span></p>
                                            </div>
                                            <div class="text-sm text-right text-gray-500 whitespace-nowrap">
                                                <time datetime="2023-04-15">2h ago</time>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="relative pb-8">
                                    <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span class="flex items-center justify-center w-8 h-8 bg-green-500 rounded-full">
                                                <i class="text-white fas fa-coins"></i>
                                            </span>
                                        </div>
                                        <div class="flex-1 min-w-0 pt-1.5 flex justify-between space-x-4">
                                            <div>
                                                <p class="text-sm text-gray-500">You received <span class="font-medium text-gray-900">₹100.00</span> in your wallet</p>
                                            </div>
                                            <div class="text-sm text-right text-gray-500 whitespace-nowrap">
                                                <time datetime="2023-04-14">1d ago</time>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="relative pb-8">
                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span class="flex items-center justify-center w-8 h-8 bg-purple-500 rounded-full">
                                                <i class="text-white fas fa-level-up-alt"></i>
                                            </span>
                                        </div>
                                        <div class="flex-1 min-w-0 pt-1.5 flex justify-between space-x-4">
                                            <div>
                                                <p class="text-sm text-gray-500">You've been promoted to <span class="font-medium text-gray-900">Advanced Player</span></p>
                                            </div>
                                            <div class="text-sm text-right text-gray-500 whitespace-nowrap">
                                                <time datetime="2023-04-12">3d ago</time>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="mt-6">
                        <a href="#" class="flex items-center text-sm font-medium text-blue-600 hover:text-blue-500">
                            View all activity
                            <i class="ml-1 text-xs fas fa-chevron-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-12 col-lg-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fa fa-bolt me-2 text-warning"></i> Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('play') }}" class="btn btn-primary btn-lg mb-3 py-3">
                            <i class="fa fa-play me-2"></i> Play Now
                        </a>
                        <a href="#" class="btn btn-outline-primary btn-lg mb-3 py-3">
                            <i class="fa fa-plus-circle me-2"></i> Create Room
                        </a>
                        <a href="#" class="btn btn-outline-secondary btn-lg mb-3 py-3">
                            <i class="fa fa-trophy me-2"></i> Leaderboard
                        </a>
                        <a href="#" class="btn btn-outline-secondary btn-lg py-3">
                            <i class="fa fa-cog me-2"></i> Settings
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="row g-3 mb-4">
        <!-- Stats Cards -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card stats-card h-100 border-0 bg-primary bg-gradient text-white">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase text-white-50 mb-1">Total Games</h6>
                            <h2 class="mb-0">{{ auth()->user()->games_played ?? 0 }}</h2>
                            <span class="badge bg-white bg-opacity-25 text-white mt-2">
                                <i class="fa fa-arrow-up me-1"></i> 12%
                            </span>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="fa fa-gamepad fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card stats-card h-100 border-0 bg-success bg-gradient text-white">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase text-white-50 mb-1">Games Won</h6>
                            <h2 class="mb-0">{{ auth()->user()->games_won ?? 0 }}</h2>
                            <span class="badge bg-white bg-opacity-25 text-white mt-2">
                                <i class="fa fa-arrow-up me-1"></i> 8%
                            </span>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="fa fa-trophy fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card stats-card h-100 border-0 bg-info bg-gradient text-white">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase text-white-50 mb-1">Win Rate</h6>
                            <h2 class="mb-0">
                                {{ auth()->user()->games_played > 0 ? round((auth()->user()->games_won / auth()->user()->games_played) * 100, 1) : 0 }}%
                            </h2>
                            <span class="badge bg-white bg-opacity-25 text-white mt-2">
                                <i class="fa fa-arrow-down me-1"></i> 2%
                            </span>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="fa fa-percent fa-2x"></i>
                        </div>
                    </div>
                </div>
            <div class="flex-shrink-0 p-3 bg-purple-500 rounded-md">
                <i class="text-white fas fa-coins"></i>
            </div>
            <div class="ml-5 w-0 flex-1">
                <dl>
                    <dt class="text-sm font-medium text-gray-500 truncate">Wallet Balance</dt>
                    <dd class="flex items-baseline">
                        <div class="text-2xl font-semibold text-gray-900">₹{{ number_format(Auth::user()->wallet_balance ?? 0, 2) }}</div>
                    </dd>
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('dashboard', () => ({
            showAuthModal: false,
            activeTab: 'login',
            
            init() {
                // Check if we need to show auth modal
                @if(!auth()->check())
                    this.showAuthModal = true;
                @endif
                
                // Listen for auth events
                window.addEventListener('show-auth-modal', (e) => {
                    this.activeTab = e.detail.tab || 'login';
                    this.showAuthModal = true;
                });
            }
        }));
    });
</script>
@endpush
