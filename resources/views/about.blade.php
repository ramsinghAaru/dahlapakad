@extends('layouts.app')

@section('title', 'About — Dehla Pakad')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <section class="cta-section">
    <div class="container text-center py-5">
        <h2 class="fw-bold mb-3">Ready to Play Dehla Pakad?</h2>
        <p class="lead mb-4">Join thousands of players enjoying this exciting card game online</p>
        <a href="{{ url('/play') }}" class="btn btn-outline-light btn-lg px-5">
            Play Now
        </a>
    </div>
</section>

<!-- Game Overview -->
<section class="py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <h2 class="fw-bold mb-4">What is Dehla Pakad?</h2>
                <p class="lead">Dehla Pakad is a traditional card game that originated in South Asia, known for its simple rules but deep strategic elements. The name translates to "Pick Up the Ten" in English, which is the main objective of the game.</p>
                <p>Played with a standard 52-card deck, Dehla Pakad is a trick-taking game where players aim to collect cards with a value of ten, particularly focusing on the ten of diamonds, which holds special significance.</p>
            </div>
            <div class="col-lg-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4 text-brand">Game Overview</h5>
                        <ul class="list-unstyled">
                            <li class="mb-3 d-flex align-items-center">
                                <div class="feature-icon-sm me-3">
                                    <i class="bi bi-people-fill"></i>
                                </div>
                                <div>
                                    <strong>Players:</strong> 2-4 players
                                </div>
                            </li>
                            <li class="mb-3 d-flex align-items-center">
                                <div class="feature-icon-sm me-3">
                                    <i class="bi bi-clock-fill"></i>
                                </div>
                                <div>
                                    <strong>Duration:</strong> 15-30 minutes
                                </div>
                            </li>
                            <li class="mb-3 d-flex align-items-center">
                                <div class="feature-icon-sm me-3">
                                    <i class="bi bi-card-checklist"></i>
                                </div>
                                <div>
                                    <strong>Deck:</strong> Standard 52-card deck
                                </div>
                            </li>
                            <li class="mb-3 d-flex align-items-center">
                                <div class="feature-icon-sm me-3">
                                    <i class="bi bi-trophy-fill"></i>
                                </div>
                                <div>
                                    <strong>Objective:</strong> Collect the most tens and win tricks
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Rules Section -->
<section id="rules" class="py-5" style="background-color: var(--soft-beige);">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">How to Play</h2>
            <p class="lead text-muted">Learn the rules and start playing in minutes</p>
        </div>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4 text-center">
                        <div class="feature-icon mx-auto">
                            <span class="fw-bold">1</span>
                        </div>
                        <h5 class="fw-bold my-3">Deal the Cards</h5>
                        <p class="mb-0">Shuffle the deck and deal 5 cards to each player. Place the remaining cards face down as a draw pile.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4 text-center">
                        <div class="feature-icon mx-auto">
                            <span class="fw-bold">2</span>
                        </div>
                        <h5 class="fw-bold my-3">Play Tricks</h5>
                        <p class="mb-0">Players take turns playing cards, following suit if possible. The highest card of the leading suit wins the trick.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4 text-center">
                        <div class="feature-icon mx-auto">
                            <span class="fw-bold">3</span>
                        </div>
                        <h5 class="fw-bold my-3">Collect Tens</h5>
                        <p class="mb-0">The main objective is to collect tens. The ten of diamonds is particularly valuable.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 order-lg-2 mb-4 mb-lg-0">
                <h2 class="fw-bold mb-4">Why Play Online?</h2>
                <p class="lead">Playing Dehla Pakad online offers several advantages over traditional card games:</p>
                <ul class="list-unstyled">
                    <li class="mb-3 d-flex">
                        <div class="feature-icon-sm me-3">
                            <i class="bi bi-check-circle-fill text-success"></i>
                        </div>
                        <div>Play anytime, anywhere with friends and family</div>
                    </li>
                    <li class="mb-3 d-flex">
                        <div class="feature-icon-sm me-3">
                            <i class="bi bi-check-circle-fill text-success"></i>
                        </div>
                        <div>No need to shuffle or deal cards manually</div>
                    </li>
                    <li class="mb-3 d-flex">
                        <div class="feature-icon-sm me-3">
                            <i class="bi bi-check-circle-fill text-success"></i>
                        </div>
                        <div>Automatic score tracking and game rules enforcement</div>
                    </li>
                    <li class="mb-3 d-flex">
                        <div class="feature-icon-sm me-3">
                            <i class="bi bi-check-circle-fill text-success"></i>
                        </div>
                        <div>Play with people from around the world</div>
                    </li>
                    <li class="mb-4 d-flex">
                        <div class="feature-icon-sm me-3">
                            <i class="bi bi-check-circle-fill text-success"></i>
                        </div>
                        <div>Learn and improve with practice games against AI</div>
                    </li>
                </ul>
                <div class="mt-4">
                    <a href="{{ url('/play') }}" class="btn btn-primary btn-lg px-4">Start Playing Now</a>
                </div>
            </div>
            <div class="col-lg-6 order-lg-1">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4 text-brand">Game Rules</h5>
                        <div class="accordion" id="rulesAccordion">
                            <div class="accordion-item mb-3 border-0 shadow-sm">
                                <h6 class="accordion-header" id="headingOne">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                        Card Values
                                    </button>
                                </h6>
                                <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#rulesAccordion">
                                    <div class="accordion-body">
                                        <p class="mb-1">Tens are the most valuable cards, especially the ten of diamonds. Other cards follow standard trick-taking values with Aces high.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item mb-3 border-0 shadow-sm">
                                <h6 class="accordion-header" id="headingTwo">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        Winning the Game
                                    </button>
                                </h6>
                                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#rulesAccordion">
                                    <div class="accordion-body">
                                        <p class="mb-1">The game continues until all cards have been played. The player with the most tens at the end wins. In case of a tie, the player with the ten of diamonds wins.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

<!-- Custom Styles -->
<style>
    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.1) !important;
    }
    
    section {
        padding: 5rem 0;
    }
    
    .bg-light {
        background-color: #f8f9fa !important;
    }
</style>
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

<!-- Additional Styles -->
@push('styles')
<style>
    /* Feature icon small variant */
    .feature-icon-sm {
        width: 32px;
        height: 32px;
        background-color: rgba(249, 11, 67, 0.1);
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: var(--brand-red);
    }
    
    /* Accordion styles */
    .accordion-button:not(.collapsed) {
        background-color: rgba(249, 11, 67, 0.05);
        color: var(--brand-red);
    }
    
    .accordion-button:focus {
        border-color: var(--light-gray);
        box-shadow: 0 0 0 0.25rem rgba(249, 11, 67, 0.1);
    }
    
    .accordion-button:not(.collapsed)::after {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23F90B43'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
    }
</style>
@endpush

@endsection
