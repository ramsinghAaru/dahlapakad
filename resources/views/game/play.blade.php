@extends('layouts.app')

@section('title', 'Game - ' . $room->code)

@push('styles')
<style>
    :root {
        --card-width: 80px;
        --card-height: 120px;
        --card-margin: -15px;
    }
    .game-container {
        padding: 1rem;
        background: linear-gradient(135deg, #1a3a4a 0%, #1e4b6b 100%);
        min-height: 100vh;
        color: #fff;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    .game-header {
        background: rgba(0, 0, 0, 0.2);
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 1.5rem;
        backdrop-filter: blur(5px);
    }
    
    .game-board {
        position: relative;
        height: 70vh;
        max-width: 1200px;
        margin: 0 auto;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 20px;
        padding: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        overflow: hidden;
    }
    
    .player-seat {
        position: absolute;
        padding: 10px;
        border-radius: 10px;
        background: rgba(0, 0, 0, 0.3);
        backdrop-filter: blur(5px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        transition: all 0.3s ease;
        min-width: 120px;
        text-align: center;
    }
    
    .player-seat:hover {
        background: rgba(255, 255, 255, 0.15);
        transform: translateY(-3px);
    }
    
    .player-north {
        top: 20px;
        left: 50%;
        transform: translateX(-50%);
    }
    
    .player-south {
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
    }
    
    .player-east {
        right: 20px;
        top: 50%;
        transform: translateY(-50%);
    }
    
    .player-west {
        left: 20px;
        top: 50%;
        transform: translateY(-50%);
    }
    
    .player-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: 2px solid #fff;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        object-fit: cover;
        margin: 0 auto;
    }
    
    .player-name {
        margin: 5px 0 0;
        font-size: 0.8rem;
        font-weight: 600;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .player-cards {
        margin-top: 8px;
        display: flex;
        justify-content: center;
        gap: 5px;
    }
    
    .player-card {
        width: 20px;
        height: 30px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 3px;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .game-center {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 180px;
        height: 120px;
        background: rgba(0, 0, 0, 0.3);
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px dashed rgba(255, 255, 255, 0.1);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        z-index: 5;
    }
    
    .card-pile {
        width: 80px;
        height: 120px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: rgba(255, 255, 255, 0.5);
        font-size: 0.8rem;
    }
    
    .player-hand {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 15px 0;
        display: flex;
        justify-content: center;
        background: rgba(0, 0, 0, 0.7);
        backdrop-filter: blur(5px);
        z-index: 1000;
        box-shadow: 0 -5px 20px rgba(0, 0, 0, 0.3);
        gap: 8px;
        flex-wrap: wrap;
    }
    
    .card {
        width: 80px;
        height: 120px;
        background: white;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
    }
    
    .card:hover {
        transform: translateY(-10px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    }
    
    .card.selected {
        transform: translateY(-20px);
        box-shadow: 0 0 15px rgba(255, 215, 0, 0.8);
    }
    
    .game-controls {
        margin-top: 2rem;
        background: rgba(0, 0, 0, 0.2);
        border-radius: 10px;
        padding: 1.5rem;
    }
    
    @media (max-width: 768px) {
        .player-avatar {
            width: 40px;
            height: 40px;
        }
        
        .player-name {
            font-size: 0.8rem;
        }
        
        .card {
            width: 50px;
            height: 80px;
            font-size: 1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid game-container">
    <div class="game-header">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
            <div class="text-center text-md-start mb-3 mb-md-0">
                <h1 class="h4 mb-1">
                    <i class="fa fa-gamepad text-warning me-2"></i>
                    {{ $room->name ?? 'Room ' . $room->code }}
                </h1>
                <div class="text-muted small">
                    <span class="badge bg-primary me-2">
                        <i class="fa fa-users me-1"></i>
                        {{ $room->users->count() }}/{{ $room->settings['max_players'] ?? 4 }} Players
                    </span>
                    <span class="d-inline-block mt-1 mt-md-0">
                        <button class="btn btn-sm btn-outline-light" onclick="copyToClipboard('{{ route('rooms.show', $room->code) }}')">
                            <i class="fa fa-link me-1"></i> Copy Room Link
                        </button>
                    </span>
                </div>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-light">
                    <i class="fa fa-cog"></i>
                </button>
                <button class="btn btn-outline-light">
                    <i class="fa fa-question-circle"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="game-board">
        <!-- North Player -->
        <div class="player-seat player-north">
            @if(isset($playerSeats['N']))
                <div class="player-info">
                    @php
                        $playerN = $playerSeats['N'];
                        $isObject = is_object($playerN);
                        $avatar = $isObject ? 
                            ($playerN->user->avatar_url ?? asset('images/avatar.png')) : 
                            ($playerN['avatar'] ?? asset('images/avatar.png'));
                        $name = $isObject ? $playerN->name : $playerN['name'];
                        $isOwner = $isObject ? ($playerN->is_owner ?? false) : ($playerN['is_owner'] ?? false);
                        $handCount = $isObject ? ($playerN->hand_count ?? 0) : ($playerN['hand_count'] ?? 0);
                        $isReady = $isObject ? ($playerN->is_ready ?? false) : ($playerN['is_ready'] ?? false);
                    @endphp
                    <img src="{{ $avatar }}" 
                         class="player-avatar" 
                         alt="{{ $name }}">
                    <div class="player-name">
                        {{ $name }}
                        @if($isOwner)
                            <i class="fas fa-crown text-warning ms-1"></i>
                        @endif
                    </div>
                    @if($isReady)
                        <span class="badge bg-success">Ready</span>
                    @endif
                    <div class="player-cards">
                        @for($i = 0; $i < $handCount; $i++)
                            <div class="player-card"></div>
                        @endfor
                    </div>
                </div>
            @else
                <div class="text-center text-muted">
                    <i class="fa fa-user-slash"></i> Empty
                </div>
            @endif
        </div>

        <!-- West Player -->
        <div class="player-seat player-west">
            @if(isset($playerSeats['W']))
                <div class="player-info text-center">
                    @php
                        $playerW = $playerSeats['W'];
                        $isObject = is_object($playerW);
                        $avatar = $isObject ? 
                            ($playerW->user->avatar_url ?? asset('images/avatar.png')) : 
                            ($playerW['avatar'] ?? asset('images/avatar.png'));
                        $name = $isObject ? $playerW->name : $playerW['name'];
                        $isOwner = $isObject ? ($playerW->is_owner ?? false) : ($playerW['is_owner'] ?? false);
                        $handCount = $isObject ? ($playerW->hand_count ?? 0) : ($playerW['hand_count'] ?? 0);
                    @endphp
                    <img src="{{ $avatar }}" 
                         class="player-avatar" 
                         alt="{{ $name }}">
                    <div class="player-name">
                        {{ $name }}
                        @if($isOwner)
                            <i class="fas fa-crown text-warning ms-1"></i>
                        @endif
                    </div>
                    <div class="player-cards">
                        @for($i = 0; $i < $handCount; $i++)
                            <div class="player-card"></div>
                        @endfor
                    </div>
                </div>
            @else
                <div class="text-center text-muted">
                    <i class="fa fa-user-slash"></i> Empty
                </div>
            @endif
        </div>

        <!-- Center Area -->
        <div class="game-center">
            <div class="text-center">
                @if($game->trump_suit)
                    <div class="h5 mb-1">Trump</div>
                    <div class="h4">{!! $game->getSuitIcon() !!}</div>
                    <small>{{ $game->trump_suit }}</small>
                @else
                    <div class="h5 mb-1">Deck</div>
                    <div class="h4">🃏</div>
                    <small>{{ $game->deck_count }} cards</small>
                @endif
            </div>
        </div>

        <!-- Player's Hand -->
        <div class="player-hand" id="playerHandContainer">
            @php
                $playerHand = $player->hand ?? [];
            @endphp
            @if(is_array($playerHand) && count($playerHand) > 0)
                @foreach($playerHand as $index => $card)
                    <div class="card-wrapper" onclick="selectCard({{ $index }})">
                        <img src="{{ asset('images/cards/' . strtolower($card) . '.png') }}" 
                             alt="{{ $card }}" 
                             class="playing-card {{ $game->isCardPlayable($card, $playerHand) ? 'playable' : 'disabled' }}">
                    </div>
                @endforeach
            @else
                <div class="text-center text-muted py-4 w-100">
                    <i class="fa fa-spinner fa-spin fa-2x mb-2 d-block"></i>
                    <span>Loading your cards...</span>
                </div>
            @endif
        </div>

        <!-- East Player -->
        <div class="player-seat player-east">
            @if(isset($playerSeats['E']))
                <div class="player-info text-center">
                    @php
                        $playerE = $playerSeats['E'];
                        $isObject = is_object($playerE);
                        $avatar = $isObject ? 
                            ($playerE->user->avatar_url ?? asset('images/avatar.png')) : 
                            ($playerE['avatar'] ?? asset('images/avatar.png'));
                        $name = $isObject ? $playerE->name : $playerE['name'];
                        $isOwner = $isObject ? ($playerE->is_owner ?? false) : ($playerE['is_owner'] ?? false);
                        $handCount = $isObject ? ($playerE->hand_count ?? 0) : ($playerE['hand_count'] ?? 0);
                        $isReady = $isObject ? ($playerE->is_ready ?? false) : ($playerE['is_ready'] ?? false);
                    @endphp
                    <img src="{{ $avatar }}" 
                         class="player-avatar" 
                         alt="{{ $name }}">
                    <div class="player-name">
                        {{ $name }}
                        @if($isOwner)
                            <i class="fas fa-crown text-warning ms-1"></i>
                        @endif
                    </div>
                    @if($isReady)
                        <span class="badge bg-success">Ready</span>
                    @endif
                    <div class="player-cards">
                        @for($i = 0; $i < $handCount; $i++)
                            <div class="player-card"></div>
                        @endfor
                    </div>
            @else
                <div class="text-center text-muted">
                    <i class="fa fa-user-slash"></i> Empty
                </div>
            @endif
        </div>

        <!-- South Player (Current User) -->
        <div class="player-seat player-south">
            @if(isset($playerSeats['S']))
                <div class="player-info text-center">
                    @php
                        $playerS = $playerSeats['S'];
                        $isObject = is_object($playerS);
                        $avatar = $isObject ? 
                            ($playerS->user->avatar_url ?? asset('images/avatar.png')) : 
                            ($playerS['avatar'] ?? asset('images/avatar.png'));
                        $name = $isObject ? $playerS->name : $playerS['name'];
                        $isOwner = $isObject ? ($playerS->is_owner ?? false) : ($playerS['is_owner'] ?? false);
                        $handCount = $isObject ? ($playerS->hand_count ?? 0) : ($playerS['hand_count'] ?? 0);
                        $isReady = $isObject ? ($playerS->is_ready ?? false) : ($playerS['is_ready'] ?? false);
                    @endphp
                    <img src="{{ $avatar }}" 
                         class="player-avatar" 
                         alt="{{ $name }}">
                    <div class="player-name">
                        {{ $name }} (You)
                        @if($isOwner)
                            <i class="fas fa-crown text-warning ms-1"></i>
                        @endif
                    </div>
                    @if($isReady)
                        <span class="badge bg-success">Ready</span>
                    @endif
                    <div class="player-cards">
                        @for($i = 0; $i < $handCount; $i++)
                            <div class="player-card"></div>
                        @endfor
                    </div>
            @endif
            
            <!-- Player's hand will be displayed here -->
            <div class="player-hand" id="playerHand">
                <div class="text-center text-muted py-4 w-100">
                    <i class="fa fa-spinner fa-spin fa-2x mb-2 d-block"></i>
                    <span>Loading your cards...</span>
                    <p>Loading your cards...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Game controls -->
    <div class="game-controls">
        <div class="row g-3">
            <div class="col-md-4">
                <div class="card bg-dark text-white h-100">
                    <div class="card-header bg-primary">
                        <i class="fa fa-info-circle me-2"></i> Game Info
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Round:</span>
                            <strong id="roundNumber">1</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Turn:</span>
                            <strong id="currentTurn">-</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Trump:</span>
                            <strong id="trumpSuit">-</strong>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-dark text-white h-100">
                    <div class="card-header bg-success">
                        <i class="fa fa-trophy me-2"></i> Score
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Team 1 (N/S):</span>
                            <strong id="team1Score">0</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Team 2 (E/W):</span>
                            <strong id="team2Score">0</strong>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-dark text-white h-100">
                    <div class="card-header bg-warning text-dark">
                        <i class="fa fa-cog me-2"></i> Actions
                    </div>
                    <div class="card-body d-flex flex-column">
                        <button id="playCardBtn" class="btn btn-primary btn-lg mb-2" disabled>
                            <i class="fa fa-play me-2"></i> Play Card
                        </button>
                        <button class="btn btn-outline-light btn-sm">
                            <i class="fa fa-undo me-1"></i> Undo
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Game Modals -->
    <div class="modal fade" id="selectTrumpModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header">
                    <h5 class="modal-title">Select Trump Suit</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <p class="mb-4">Choose the trump suit for this round:</p>
                    <div class="d-flex justify-content-center gap-3">
                        <button class="btn btn-outline-danger btn-lg" onclick="selectTrump('hearts')">
                            <i class="fa fa-heart fa-2x"></i>
                        </button>
                        <button class="btn btn-outline-primary btn-lg" onclick="selectTrump('diamonds')">
                            <i class="fa fa-diamond fa-2x"></i>
                        </button>
                        <button class="btn btn-outline-success btn-lg" onclick="selectTrump('clubs')">
                            <i class="fa fa-club fa-2x"></i>
                        </button>
                        <button class="btn btn-outline-warning btn-lg" onclick="selectTrump('spades')">
                            <i class="fa fa-spade fa-2x"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Game state
        const gameState = {
            playerSeat: '{{ $player->seat }}',
            isPlayersTurn: {{ $game->turn_seat === $player->seat ? 'true' : 'false' }},
            selectedCard: null,
            playerHand: [],
            
            init: function() {
                this.setupEventListeners();
                this.loadGameState();
                this.updateUI();
                
                // If it's player's turn, enable card selection
                if (this.isPlayersTurn) {
                    this.enableCardSelection();
                }
            },
            
            setupEventListeners: function() {
                // Card click handler
                document.addEventListener('click', (e) => {
                    const cardElement = e.target.closest('.card');
                    if (cardElement && cardElement.dataset.index !== undefined) {
                        this.selectCard(parseInt(cardElement.dataset.index));
                    }
                });
                
                // Play card button
                document.getElementById('playCardBtn').addEventListener('click', () => {
                    if (this.selectedCard !== null) {
                        this.playSelectedCard();
                    }
                });
                
                // Listen for game updates
                window.Echo.private(`game.${'{{ $game->id }}'}`)
                    .listen('.game.updated', (data) => {
                        this.handleGameUpdate(data);
                    });
            },
            
            loadGameState: function() {
                // Load initial game state from server
                fetch(`/api/game/${'{{ $game->id }}'}`)
                    .then(response => response.json())
                    .then(data => {
                        this.updateGameState(data);
                    })
                    .catch(error => {
                        console.error('Error loading game state:', error);
                        toastr.error('Failed to load game state');
                    });
            },
            
            selectCard: function(cardIndex) {
                // Toggle card selection
                if (this.selectedCard === cardIndex) {
                    this.selectedCard = null;
                } else {
                    this.selectedCard = cardIndex;
                }
                this.updateUI();
            },
            
            playSelectedCard: function() {
                if (this.selectedCard === null) return;
                
                const card = this.playerHand[this.selectedCard];
                
                // Disable UI while processing
                this.disableCardSelection();
                
                // Send card to server
                fetch(`/game/${'{{ $game->id }}'}/play`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        card_index: this.selectedCard,
                        card: card
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Card played successfully, wait for server update
                        toastr.success('Card played!');
                    } else {
                        toastr.error(data.message || 'Failed to play card');
                        this.enableCardSelection();
                    }
                })
                .catch(error => {
                    console.error('Error playing card:', error);
                    toastr.error('Failed to play card');
                    this.enableCardSelection();
                });
            },
            
            handleGameUpdate: function(data) {
                this.updateGameState(data.game);
                
                // Show notification if it's player's turn
                if (this.isPlayersTurn) {
                    toastr.info('Your turn to play!');
                    this.enableCardSelection();
                } else {
                    this.disableCardSelection();
                }
                
                // Update UI
                this.updateUI();
            },
            
            updateGameState: function(gameData) {
                // Update game state with data from server
                this.playerHand = gameData.player_hand || [];
                this.isPlayersTurn = gameData.current_turn === this.playerSeat;
                
                // Update scores
                document.getElementById('team1Score').textContent = gameData.scores?.team1 || 0;
                document.getElementById('team2Score').textContent = gameData.scores?.team2 || 0;
                document.getElementById('roundNumber').textContent = gameData.round_number || 1;
                document.getElementById('currentTurn').textContent = gameData.current_turn || '-';
                document.getElementById('trumpSuit').textContent = gameData.trump_suit || '-';
                
                // Render player hand
                this.renderPlayerHand();
            },
            
            renderPlayerHand: function() {
                const handContainer = document.getElementById('playerHand');
                if (!handContainer) return;
                
                if (this.playerHand.length === 0) {
                    handContainer.innerHTML = `
                        <div class="text-center text-muted py-4 w-100">
                            <i class="fa fa-info-circle fa-2x mb-2 d-block"></i>
                            <span>No cards in hand</span>
                        </div>
                    `;
                    return;
                }
                
                handContainer.innerHTML = this.playerHand.map((card, index) => {
                    const isSelected = this.selectedCard === index;
                    const cardClass = `card ${isSelected ? 'selected' : ''}`;
                    
                    return `
                        <div class="${cardClass}" data-index="${index}">
                            ${this.getCardSymbol(card)}
                        </div>
                    `;
                }).join('');
            },
            
            getCardSymbol: function(card) {
                const suits = {
                    'hearts': '♥',
                    'diamonds': '♦',
                    'clubs': '♣',
                    'spades': '♠'
                };
                
                const values = {
                    '1': 'A',
                    '11': 'J',
                    '12': 'Q',
                    '13': 'K'
                };
                
                const value = values[card.value] || card.value;
                const suit = suits[card.suit] || card.suit[0].toUpperCase();
                
                return `${value}${suit}`;
            },
            
            enableCardSelection: function() {
                document.getElementById('playCardBtn').disabled = this.selectedCard === null;
                // Add any additional UI updates when enabling card selection
            },
            
            disableCardSelection: function() {
                document.getElementById('playCardBtn').disabled = true;
                // Add any additional UI updates when disabling card selection
            },
            
            updateUI: function() {
                // Update button states
                document.getElementById('playCardBtn').disabled = !this.isPlayersTurn || this.selectedCard === null;
                
                // Update card selection
                document.querySelectorAll('.card').forEach((card, index) => {
                    if (index === this.selectedCard) {
                        card.classList.add('selected');
                    } else {
                        card.classList.remove('selected');
                    }
                });
            }
        };
        
        // Initialize game when document is ready
        document.addEventListener('DOMContentLoaded', () => {
            gameState.init();
            
            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            
            // Initialize toastr
            toastr.options = {
                closeButton: true,
                progressBar: true,
                positionClass: 'toast-bottom-right',
                timeOut: 3000
            };
        });
        
        // Helper function to select trump suit
        function selectTrump(suit) {
            fetch(`/game/${'{{ $game->id }}'}/trump`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ suit: suit })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    $('#selectTrumpModal').modal('hide');
                    toastr.success(`Trump set to ${suit}`);
                } else {
                    toastr.error(data.message || 'Failed to set trump');
                }
            })
            .catch(error => {
                console.error('Error setting trump:', error);
                toastr.error('Failed to set trump');
            });
        }
        
        // Helper function to copy text to clipboard
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                toastr.success('Link copied to clipboard!');
            }).catch(err => {
                console.error('Failed to copy text: ', err);
                toastr.error('Failed to copy link');
            });
        }
    </script>
    @endpush
                <div class="card h-100">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fa fa-info-circle me-2"></i>Game Info</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Trump Suit:</span>
                            <strong id="trumpSuit">{{ $game->trump_suit ?? 'Not selected' }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Current Turn:</span>
                            <strong id="currentTurn">{{ $game->turn_seat ?? 'N/A' }}</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Phase:</span>
                            <strong id="gamePhase">{{ $game->phase ?? 'Not started' }}</strong>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fa fa-trophy me-2"></i>Scores</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Team NS:</span>
                            <strong id="teamNsScore">0</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Team EW:</span>
                            <strong id="teamEwScore">0</strong>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fa fa-cog me-2"></i>Actions</h6>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <button class="btn btn-primary mb-2" id="playCardBtn" disabled>
                            <i class="fa fa-play me-1"></i> Play Selected Card
                        </button>
                        <button class="btn btn-outline-secondary" id="leaveGameBtn">
                            <i class="fa fa-sign-out-alt me-1"></i> Leave Game
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Game Modals -->
<div class="modal fade" id="selectTrumpModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Select Trump Suit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <p>Choose the trump suit for this round:</p>
                <div class="d-flex justify-content-around my-4">
                    <button class="btn btn-outline-danger btn-lg suit-btn" data-suit="♥">
                        <i class="fa fa-heart fa-2x"></i>
                    </button>
                    <button class="btn btn-outline-primary btn-lg suit-btn" data-suit="♠">
                        <i class="fa fa-spade fa-2x"></i>
                    </button>
                    <button class="btn btn-outline-success btn-lg suit-btn" data-suit="♣">
                        <i class="fa fa-club fa-2x"></i>
                    </button>
                    <button class="btn btn-outline-warning btn-lg suit-btn" data-suit="♦">
                        <i class="fa fa-diamond fa-2x"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Game Over Modal -->
<div class="modal fade" id="gameOverModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Game Over!</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <h4 id="winnerTeam" class="mb-4"></h4>
                <div class="row">
                    <div class="col-6">
                        <h5>Team NS</h5>
                        <div class="display-4" id="finalNsScore">0</div>
                    </div>
                    <div class="col-6">
                        <h5>Team EW</h5>
                        <div class="display-4" id="finalEwScore">0</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="playAgainBtn">Play Again</button>
            </div>
        </div>
    </div>
</div>

<!-- Leave Game Confirmation Modal -->
<div class="modal fade" id="leaveGameModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Leave Game</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to leave the game? Your progress will be lost.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmLeaveGameBtn">
                    <i class="fa fa-sign-out-alt me-1"></i> Leave Game
                </button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .game-container {
        padding: 1rem;
        background-color: #f8f9fa;
        min-height: 100vh;
    }
    
    .game-board {
        position: relative;
        width: 100%;
        height: 60vh;
        background-color: #28a745;
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
        overflow: hidden;
    }
    
    .player-seat {
        position: absolute;
        padding: 1rem;
        text-align: center;
        background-color: rgba(255, 255, 255, 0.9);
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
    
    .player-north {
        top: 20px;
        left: 50%;
        transform: translateX(-50%);
    }
    
    .player-south {
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        width: 80%;
    }
    
    .player-east {
        top: 50%;
        right: 20px;
        transform: translateY(-50%);
    }
    
    .player-west {
        top: 50%;
        left: 20px;
        transform: translateY(-50%);
    }
    
    .player-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
        margin-bottom: 0.5rem;
    }
    
    .player-name {
        font-weight: 600;
        margin-bottom: 0.25rem;
    }
    
    .game-center {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
    }
    
    .game-table {
        width: 300px;
        height: 200px;
        background-color: #1e7e34;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: inset 0 0 20px rgba(0, 0, 0, 0.3);
    }
    
    .trick-area {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 10px;
        width: 100%;
        height: 100%;
    }
    
    .player-hand {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 5px;
        margin-top: 1rem;
        min-height: 120px;
    }
    
    .card {
        width: 80px;
        height: 120px;
        background-color: white;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        cursor: pointer;
        transition: transform 0.2s, box-shadow 0.2s;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }
    
    .card:hover {
        transform: translateY(-10px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    }
    
    .card.selected {
        transform: translateY(-20px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
    }
    
    .suit-btn {
        width: 80px;
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
    }
    
    .game-controls {
        margin-top: 2rem;
    }
    
    @media (max-width: 768px) {
        .game-board {
            height: 50vh;
        }
        
        .player-seat {
            padding: 0.5rem;
            font-size: 0.9rem;
        }
        
        .player-avatar {
            width: 40px;
            height: 40px;
        }
        
        .game-table {
            width: 200px;
            height: 150px;
        }
        
        .card {
            width: 60px;
            height: 90px;
            font-size: 1.2rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Game state
    const gameState = {
        playerSeat: '{{ $player->seat }}',
        isPlayersTurn: {{ $game->turn_seat === $player->seat ? 'true' : 'false' }},
        selectedCard: null,
        gamePhase: '{{ $game->phase }}',
        playerHand: {!! json_encode($game->hands[$player->seat] ?? []) !!},
        currentTurn: '{{ $game->turn_seat }}',
        trumpSuit: '{{ $game->trump_suit ?? '' }}'
    };

    // Initialize the game
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Game initialized', gameState);
        
        // If it's the player's turn and we're in the five card phase, show the trump selection modal
        if (gameState.isPlayersTurn && gameState.gamePhase === 'five_card_phase' && !gameState.trumpSuit) {
            const modal = new bootstrap.Modal(document.getElementById('selectTrumpModal'));
            modal.show();
        }
        
        // Render the player's hand
        renderPlayerHand();
        
        // Set up event listeners
        setupEventListeners();
    });
    
    // Render the player's hand
    function renderPlayerHand() {
        const handContainer = document.getElementById('playerHand');
        
        if (!handContainer) return;
        
        if (gameState.playerHand.length === 0) {
            handContainer.innerHTML = `
                <div class="text-center text-muted py-4">
                    <i class="fa fa-cards fa-2x mb-2"></i>
                    <p>No cards in hand</p>
                </div>
            `;
            return;
        }
        
        handContainer.innerHTML = '';
        
        gameState.playerHand.forEach((card, index) => {
            const cardElement = document.createElement('div');
            cardElement.className = 'card' + (gameState.selectedCard === index ? ' selected' : '');
            cardElement.textContent = card;
            cardElement.dataset.index = index;
            
            // Color code the cards based on suit
            const suit = card[1];
            if (suit === '♥' || suit === '♦') {
                cardElement.style.color = 'red';
            } else {
                cardElement.style.color = 'black';
            }
            
            // Highlight if it's the player's turn and we're in the playing phase
            if (gameState.isPlayersTurn && gameState.gamePhase === 'playing') {
                cardElement.style.cursor = 'pointer';
                cardElement.addEventListener('click', () => selectCard(index));
            } else {
                cardElement.style.opacity = gameState.isPlayersTurn ? '1' : '0.6';
            }
            
            handContainer.appendChild(cardElement);
        });
        
        // Update the play card button state
        updatePlayButton();
    }
    
    // Select a card
    function selectCard(index) {
        if (gameState.selectedCard === index) {
            // Deselect if the same card is clicked again
            gameState.selectedCard = null;
        } else {
            gameState.selectedCard = index;
        }
        
        // Re-render the hand to show the selected card
        renderPlayerHand();
    }
    
    // Update the play button state based on whether a card is selected
    function updatePlayButton() {
        const playButton = document.getElementById('playCardBtn');
        if (playButton) {
            playButton.disabled = gameState.selectedCard === null;
        }
    }
    
    // Set up event listeners
    function setupEventListeners() {
        // Play card button
        const playButton = document.getElementById('playCardBtn');
        if (playButton) {
            playButton.addEventListener('click', playSelectedCard);
        }
        
        // Leave game button
        const leaveGameBtn = document.getElementById('leaveGameBtn');
        if (leaveGameBtn) {
            leaveGameBtn.addEventListener('click', () => {
                const modal = new bootstrap.Modal(document.getElementById('leaveGameModal'));
                modal.show();
            });
        }
        
        // Confirm leave game button
        const confirmLeaveBtn = document.getElementById('confirmLeaveGameBtn');
        if (confirmLeaveBtn) {
            confirmLeaveBtn.addEventListener('click', () => {
                window.location.href = '{{ route("play") }}';
            });
        }
        
        // Trump suit selection
        const suitButtons = document.querySelectorAll('.suit-btn');
        suitButtons.forEach(button => {
            button.addEventListener('click', () => {
                const suit = button.dataset.suit;
                selectTrumpSuit(suit);
            });
        });
    }
    
    // Play the selected card
    function playSelectedCard() {
        if (gameState.selectedCard === null || !gameState.isPlayersTurn) return;
        
        const card = gameState.playerHand[gameState.selectedCard];
        console.log('Playing card:', card);
        
        // Here you would typically make an API call to play the card
        // For now, we'll just simulate it
        simulatePlayCard(card);
    }
    
    // Simulate playing a card (replace with actual API call)
    function simulatePlayCard(card) {
        // Show loading state
        const playButton = document.getElementById('playCardBtn');
        if (playButton) {
            const originalText = playButton.innerHTML;
            playButton.disabled = true;
            playButton.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Playing...';
            
            // Simulate API delay
            setTimeout(() => {
                // Update UI to show the card was played
                const trickArea = document.getElementById('trickArea');
                if (trickArea) {
                    const cardElement = document.createElement('div');
                    cardElement.className = 'card';
                    cardElement.textContent = card;
                    
                    // Color code the card
                    const suit = card[1];
                    if (suit === '♥' || suit === '♦') {
                        cardElement.style.color = 'red';
                    } else {
                        cardElement.style.color = 'black';
                    }
                    
                    trickArea.appendChild(cardElement);
                }
                
                // Remove the card from the player's hand
                const cardIndex = gameState.playerHand.indexOf(card);
                if (cardIndex > -1) {
                    gameState.playerHand.splice(cardIndex, 1);
                }
                
                // Reset selection
                gameState.selectedCard = null;
                
                // Update the UI
                renderPlayerHand();
                
                // Reset the button
                playButton.innerHTML = originalText;
                
                // Simulate next player's turn (for demo purposes)
                setTimeout(() => {
                    // In a real game, this would be handled by the server via WebSockets
                    alert('Card played! Waiting for other players...');
                }, 500);
                
            }, 500);
        }
    }
    
    // Select trump suit
    function selectTrumpSuit(suit) {
        console.log('Selected trump suit:', suit);
        
        // Here you would typically make an API call to set the trump suit
        // For now, we'll just update the UI
        document.getElementById('trumpSuit').textContent = suit;
        gameState.trumpSuit = suit;
        gameState.gamePhase = 'playing';
        
        // Close the modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('selectTrumpModal'));
        if (modal) {
            modal.hide();
        }
        
        // Show a message
        alert(`Trump suit set to ${suit}. Game is starting!`);
    }
    
    // Copy room link to clipboard
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            const tooltip = new bootstrap.Tooltip(document.querySelector('[data-bs-toggle="tooltip"]'), {
                title: 'Copied!',
                trigger: 'manual'
            });
            
            tooltip.show();
            
            setTimeout(() => {
                tooltip.hide();
            }, 2000);
        }).catch(err => {
            console.error('Failed to copy text: ', err);
        });
    }
    
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush

@endsection
