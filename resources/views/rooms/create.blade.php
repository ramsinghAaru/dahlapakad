@extends('layouts.app')

@section('title', 'Create Game Room')

@push('styles')
<style>
    .option-card {
        transition: all 0.3s ease;
        cursor: pointer;
        border: 2px solid transparent;
    }
    .option-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    .option-card.active {
        border-color: #0d6efd;
        background-color: #f8f9fa;
    }
    .option-icon {
        font-size: 2rem;
        margin-bottom: 1rem;
        color: #0d6efd;
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom-0">
                    <div class="d-flex align-items-center">
                        <a href="{{ route('play') }}" class="btn btn-sm btn-outline-secondary me-2">
                            <i class="fa fa-arrow-left"></i>
                        </a>
                        <h5 class="mb-0">
                            <i class="fa fa-plus-circle me-2 text-primary"></i>Create New Room
                        </h5>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('rooms.store') }}" method="POST" id="roomForm">
                        @csrf
                        
                        <!-- Room Name -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="fa fa-tag me-2 text-primary"></i>Room Name (Optional)
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-hashtag"></i></span>
                                <input type="text" class="form-control form-control-lg @error('room_name') is-invalid @enderror" 
                                       name="room_name" value="{{ old('room_name') }}" 
                                       placeholder="e.g., Friday Night Game">
                                @error('room_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Game Type -->
                        <div class="mb-4">
                            <label class="form-label fw-bold d-block mb-3">
                                <i class="fa fa-gamepad me-2 text-primary"></i>Game Type
                            </label>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="option-card card h-100 text-center p-3 {{ old('game_type', 'standard') == 'standard' ? 'active' : '' }}"
                                         onclick="selectOption('game_type', 'standard', this)">
                                        <div class="option-icon">
                                            <i class="fa fa-star"></i>
                                        </div>
                                        <h6>Standard</h6>
                                        <small class="text-muted">Classic game mode</small>
                                        <input type="radio" name="game_type" value="standard" 
                                               {{ old('game_type', 'standard') == 'standard' ? 'checked' : '' }} hidden>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="option-card card h-100 text-center p-3 {{ old('game_type') == 'tournament' ? 'active' : '' }}"
                                         onclick="selectOption('game_type', 'tournament', this)">
                                        <div class="option-icon">
                                            <i class="fa fa-trophy"></i>
                                        </div>
                                        <h6>Tournament</h6>
                                        <small class="text-muted">Competitive play</small>
                                        <input type="radio" name="game_type" value="tournament"
                                               {{ old('game_type') == 'tournament' ? 'checked' : '' }} hidden>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="option-card card h-100 text-center p-3 {{ old('game_type') == 'practice' ? 'active' : '' }}"
                                         onclick="selectOption('game_type', 'practice', this)">
                                        <div class="option-icon">
                                            <i class="fa fa-graduation-cap"></i>
                                        </div>
                                        <h6>Practice</h6>
                                        <small class="text-muted">Learn and practice</small>
                                        <input type="radio" name="game_type" value="practice"
                                               {{ old('game_type') == 'practice' ? 'checked' : '' }} hidden>
                                    </div>
                                </div>
                            </div>
                            @error('game_type')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Team-Based Players -->
                        <div class="mb-4">
                            <label class="form-label fw-bold d-block mb-3">
                                <i class="fa fa-users me-2 text-primary"></i>Game Mode
                            </label>
                            <div class="row g-3">
                                <!-- 4 Players (2v2) -->
                                <div class="col-md-6">
                                    <div class="option-card card h-100 text-center p-3 {{ old('max_players', 4) == 4 ? 'active' : '' }}"
                                         onclick="selectOption('max_players', '4', this, 'radio')">
                                        <div class="option-icon">
                                            <i class="fa fa-users"></i>
                                        </div>
                                        <h6>2 vs 2 Teams</h6>
                                        <div class="d-flex justify-content-center gap-2 mb-2">
                                            <span class="badge bg-danger">2</span>
                                            <span class="align-self-center">vs</span>
                                            <span class="badge bg-primary">2</span>
                                        </div>
                                        <small class="text-muted">Classic team play</small>
                                        <input type="radio" name="max_players" value="4" 
                                               {{ old('max_players', 4) == 4 ? 'checked' : '' }} hidden>
                                    </div>
                                </div>
                                
                                <!-- 6 Players (3v3) -->
                                <div class="col-md-6">
                                    <div class="option-card card h-100 text-center p-3 {{ old('max_players') == 6 ? 'active' : '' }}"
                                         onclick="selectOption('max_players', '6', this, 'radio')">
                                        <div class="option-icon">
                                            <i class="fa fa-users"></i>
                                        </div>
                                        <h6>3 vs 3 Teams</h6>
                                        <div class="d-flex justify-content-center gap-2 mb-2">
                                            <span class="badge bg-danger">3</span>
                                            <span class="align-self-center">vs</span>
                                            <span class="badge bg-primary">3</span>
                                        </div>
                                        <small class="text-muted">Big team battle</small>
                                        <input type="radio" name="max_players" value="6"
                                               {{ old('max_players') == 6 ? 'checked' : '' }} hidden>
                                    </div>
                                </div>
                            </div>
                            @error('max_players')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                            
                            <!-- Team Info Alert -->
                            <div class="alert alert-info mt-3 mb-0">
                                <i class="fa fa-info-circle me-2"></i>
                                Players will be automatically assigned to teams when joining the room.
                            </div>
                        </div>

                        <!-- Room Privacy -->
                        <div class="mb-4">
                            <label class="form-label fw-bold d-block mb-3">
                                <i class="fa fa-lock me-2 text-primary"></i>Room Privacy
                            </label>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="option-card card h-100 text-center p-3 {{ !old('is_public') ? 'active' : '' }}"
                                         onclick="selectOption('is_public', '0', this, 'radio')">
                                        <div class="option-icon">
                                            <i class="fas fa-user-lock"></i>
                                        </div>
                                        <h6>Private</h6>
                                        <small class="text-muted">Invite only with link</small>
                                        <input type="radio" name="is_public" value="0" 
                                               {{ !old('is_public') ? 'checked' : '' }} hidden>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="option-card card h-100 text-center p-3 {{ old('is_public') ? 'active' : '' }}"
                                         onclick="selectOption('is_public', '1', this, 'radio')">
                                        <div class="option-icon">
                                            <i class="fas fa-globe-americas"></i>
                                        </div>
                                        <h6>Public</h6>
                                        <small class="text-muted">Visible to everyone</small>
                                        <input type="radio" name="is_public" value="1"
                                               {{ old('is_public') ? 'checked' : '' }} hidden>
                                    </div>
                                </div>
                            </div>
                            @error('is_public')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Hidden Trump Method (Default: Random) -->
                        <input type="hidden" name="trump_method" value="1">

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fa fa-play-circle me-2"></i> Start Game
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function selectOption(name, value, element, type = 'radio') {
        // For radio buttons
        if (type === 'radio') {
            // Remove active class from all siblings
            const parent = element.closest('.row') || element.closest('.btn-group');
            if (parent) {
                parent.querySelectorAll('.option-card').forEach(card => {
                    card.classList.remove('active');
                });
            }
            
            // Add active class to clicked element
            element.classList.add('active');
            
            // Update the hidden input
            const input = element.querySelector('input[type="' + type + '"]');
            if (input) {
                input.checked = true;
            }
        }
    }

    // Initialize active states on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Set initial active states for radio options
        document.querySelectorAll('input[type="radio"]:checked').forEach(radio => {
            const card = radio.closest('.option-card');
            if (card) {
                card.classList.add('active');
            }
        });
    });
</script>
@endpush
