@extends('layouts.app')

@section('title', 'Dehla Pakad — Collect the Tens')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container py-5">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <h1 class="display-4 fw-bold mb-4">Dehla Pakad</h1>
                <p class="lead mb-4">
                    Team up, follow suit, and 
                    <span class="fw-bold">collect the tens</span> in this exciting card game.
                </p>
                <div class="d-flex flex-wrap gap-3 mb-4">
                    <a href="{{ url('/play') }}" class="btn btn-primary btn-lg px-4">
                        Play Now
                    </a>
                    <a href="{{ url('/about') }}" class="btn btn-outline-light btn-lg px-4 ms-2">
                        How to Play
                    </a>
                </div>
                
                <div class="d-flex flex-wrap gap-4 text-light-blue">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-people-fill fs-5 me-2"></i>
                        <span>2-4 Players</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="bi bi-clock-fill fs-5 me-2"></i>
                        <span>15-30 mins</span>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card shadow-lg bg-soft-beige border-0">
                    <div class="card-body p-4">
                        <h5 class="card-title fw-bold mb-4 text-brand">🎮 Quick Start</h5>
                        <ol class="list-group list-group-numbered">
                            <li class="list-group-item border-0 ps-0 bg-transparent">
                                <span class="fw-medium">Create a room</span> and invite friends
                            </li>
                            <li class="list-group-item border-0 ps-0 bg-transparent">
                                <span class="fw-medium">Deal 5 cards</span> to each player
                            </li>
                            <li class="list-group-item border-0 ps-0 bg-transparent">
                                <span class="fw-medium">Play anticlockwise</span>, following suit when possible
                            </li>
                            <li class="list-group-item border-0 ps-0 bg-transparent">
                                <span class="fw-medium">Win two tricks in a row</span> to take the center pile
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features-section">
    <div class="container py-4">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Why Play Dehla Pakad?</h2>
            <p class="lead text-muted">A game of strategy, luck, and quick thinking</p>
        </div>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Social & Fun</h5>
                        <p class="text-muted mb-0">Perfect for game nights and family gatherings. Easy to learn, fun to master!</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon">
                            <i class="bi bi-lightbulb-fill"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Strategic Depth</h5>
                        <p class="text-muted mb-0">Plan your moves carefully to outsmart opponents and collect the most tens.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon">
                            <i class="bi bi-phone-fill"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Play Anywhere</h5>
                        <p class="text-muted mb-0">Fully responsive design works on desktop, tablet, and mobile devices.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <div class="container text-center py-4">
        <h2 class="fw-bold mb-3">Ready to Play?</h2>
        <p class="lead mb-4">Join thousands of players enjoying Dehla Pakad online</p>
        <a href="{{ url('/play') }}" class="btn btn-outline-light btn-lg px-5">
            Start Playing Now
        </a>
    </div>
</section>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

<!-- Additional Styles -->
@push('styles')
<style>
    /* Additional page-specific styles can go here */
    .bg-soft-beige {
        background-color: var(--soft-beige);
    }
    
    .text-brand {
        color: var(--brand-red);
    }
    
    .text-light-blue {
        color: var(--light-gray-blue);
    }
</style>
@endpush
@endsection
