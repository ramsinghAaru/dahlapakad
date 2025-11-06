@extends('layouts.app')

@section('title', 'Room ' . $room->code)

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fa fa-door-open text-primary me-2"></i>
                        Room: {{ $room->code }}
                        @if($room->name)
                            <small class="text-muted">{{ $room->name }}</small>
                        @endif
                    </h1>
                    <nav aria-label="breadcrumb" class="d-none d-md-block mt-2">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('play') }}">Play</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Room {{ $room->code }}</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    @if($isOwner)
                        <button class="btn btn-primary d-none" id="startGameBtn">
                            <i class="fa fa-play me-1"></i> Start Game
                        </button>
                    @endif
                    <button class="btn {{ $isReady ? 'btn-success' : 'btn-outline-primary' }} me-2" id="readyBtn">
                        <i class="fa {{ $isReady ? 'fa-check' : 'fa-thumbs-up' }} me-1"></i>
                        {{ $isReady ? 'Ready!' : 'I\'m Ready' }}
                    </button>
                    <button class="btn btn-outline-danger" id="leaveRoomBtn">
                        <i class="fa fa-sign-out-alt me-1"></i> Leave Room
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Players List -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fa fa-users me-2 text-primary"></i>
                        Players ({{ $playerCount }}/{{ $maxPlayers }})
                    </h5>
                </div>
                <div class="card-body">
                    @if($room->users->isEmpty())
                        <div class="text-center py-5">
                            <i class="fa fa-user-friends fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Waiting for players to join...</p>
                        </div>
                    @else
                        <div class="row g-3" id="players-list">
                            @foreach($room->users as $user)
                                <div class="col-6 col-md-4 col-lg-3" data-player-id="{{ $user->id }}">
                                    <div class="card border-0 shadow-sm h-100">
                                        <div class="card-body text-center">
                                            <div class="position-relative d-inline-block mb-2">
                                                <img src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=random' }}" 
                                                     class="rounded-circle img-thumbnail" 
                                                     alt="{{ $user->name }}"
                                                     style="width: 80px; height: 80px; object-fit: cover;">
                                                @if($user->pivot->is_owner)
                                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary" 
                                                          style="font-size: 0.6rem;">
                                                        <i class="fa fa-crown"></i> Owner
                                                    </span>
                                                @endif
                                            </div>
                                            <h6 class="mb-0">
                                                {{ $user->name }}
                                                <div class="player-status">
                                                    @if($user->pivot->is_ready)
                                                        <span class="badge bg-success ms-1">
                                                            <i class="fa fa-check"></i> Ready
                                                        </span>
                                                    @else
                                                        <span class="badge bg-secondary ms-1">
                                                            <i class="fa fa-clock"></i> Waiting
                                                        </span>
                                                    @endif
                                                </div>
                                            </h6>
                                            <small class="text-muted">
                                                {{ $user->pivot->joined_at ? $user->pivot->joined_at->diffForHumans() : 'Just now' }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Room Info & Chat -->
        <div class="col-lg-4 mt-4 mt-lg-0">
            <!-- Room Info -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fa fa-info-circle me-2 text-primary"></i>
                        Room Info
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span><i class="fa fa-hashtag text-muted me-2"></i>Room Code</span>
                            <div>
                                <span class="badge bg-primary">{{ $room->code }}</span>
                                <button class="btn btn-sm btn-outline-secondary ms-2" onclick="copyToClipboard('{{ $room->code }}')">
                                    <i class="fa fa-copy"></i>
                                </button>
                            </div>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span><i class="fa fa-gamepad text-muted me-2"></i>Game Type</span>
                            <span class="badge bg-info">
                                {{ ucfirst($room->settings['game_type'] ?? 'standard') }}
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span><i class="fa fa-users text-muted me-2"></i>Players</span>
                            <span>{{ $playerCount }} / {{ $maxPlayers }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span><i class="fa fa-flag text-muted me-2"></i>Trump Method</span>
                            <span>
                                @switch($room->trump_method)
                                    @case(1) Random @break
                                    @case(2) First Card @break
                                    @case(3) Winner's Choice @break
                                    @default Random
                                @endswitch
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span><i class="fa fa-globe text-muted me-2"></i>Visibility</span>
                            <span class="badge {{ $room->settings['is_public'] ? 'bg-success' : 'bg-secondary' }}">
                                {{ $room->settings['is_public'] ? 'Public' : 'Private' }}
                            </span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Chat -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fa fa-comments me-2 text-primary"></i>
                        Chat
                    </h5>
                </div>
                <div class="card-body p-0" style="height: 300px;">
                    <div id="chat-messages" class="p-3" style="height: 220px; overflow-y: auto;">
                        <!-- Chat messages will be loaded here -->
                        <div class="text-center text-muted py-5">
                            <i class="fa fa-comment-dots fa-2x mb-2"></i>
                            <p>No messages yet. Say hello!</p>
                        </div>
                    </div>
                    <div class="p-3 border-top">
                        <form id="chat-form" class="d-flex">
                            <input type="text" class="form-control me-2" 
                                   id="message-input" 
                                   placeholder="Type a message..." 
                                   autocomplete="off">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-paper-plane"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Leave Room Confirmation Modal -->
<div class="modal fade" id="leaveRoomModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Leave Room</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to leave this room?
                @if($isOwner)
                    <div class="alert alert-warning mt-2">
                        <i class="fa fa-exclamation-triangle me-2"></i>
                        You are the room owner. Leaving will transfer ownership or delete the room if you're the only one.
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmLeaveBtn">
                    <i class="fa fa-sign-out-alt me-1"></i> Leave Room
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<script>
    // Initialize Pusher
    const pusherKey = '{{ config('broadcasting.connections.pusher.key') }}';
    const pusherCluster = '{{ config('broadcasting.connections.pusher.options.cluster', 'mt1') }}';
    const currentUserId = {{ auth()->id() }};
    
    // Only initialize Pusher if we have the required keys
    if (pusherKey && pusherKey !== '${PUSHER_APP_KEY}') {
        window.pusher = new Pusher(pusherKey, {
            cluster: pusherCluster,
            encrypted: true
        });

        // Subscribe to the room channel
        const channel = window.pusher.subscribe('room.{{ $room->id }}');

        // Listen for player status updates
        channel.bind('player.status.updated', function(data) {
            // Skip if this is the current user's own update
            if (data.user_id === currentUserId) return;
            
            // Find the player element
            const playerElement = document.querySelector(`[data-player-id="${data.user_id}"]`);
            if (playerElement) {
                // Update the ready status
                const statusElement = playerElement.querySelector('.player-status');
                if (statusElement) {
                    if (data.is_ready) {
                        statusElement.innerHTML = '<span class="badge bg-success"><i class="fa fa-check"></i> Ready</span>';
                    } else {
                        statusElement.innerHTML = '<span class="badge bg-secondary"><i class="fa fa-clock"></i> Waiting</span>';
                    }
                }
                
                // Update the ready button if this is the current user
                if (data.user_id === currentUserId) {
                    const readyBtn = document.getElementById('readyBtn');
                    if (readyBtn) {
                        if (data.is_ready) {
                            readyBtn.classList.remove('btn-outline-primary');
                            readyBtn.classList.add('btn-success');
                            readyBtn.innerHTML = '<i class="fa fa-check me-1"></i> Ready!';
                        } else {
                            readyBtn.classList.remove('btn-success');
                            readyBtn.classList.add('btn-outline-primary');
                            readyBtn.innerHTML = '<i class="fa fa-thumbs-up me-1"></i> I\'m Ready';
                        }
                    }
                }
            }
        });

        // Listen for the all.players.ready event
        channel.bind('all.players.ready', function(data) {
            if (data.redirect_url) {
                // Show a countdown before redirecting
                let countdown = 3;
                const countdownInterval = setInterval(() => {
                    if (countdown > 0) {
                        toastr.info(`Game starting in ${countdown}...`, '', {timeOut: 1000});
                        countdown--;
                    } else {
                        clearInterval(countdownInterval);
                        window.location.href = data.redirect_url;
                    }
                }, 1000);
            }
        });
    }

    // Copy room code to clipboard
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            // Show success message
            const btn = event.target.closest('button');
            const originalHTML = btn.innerHTML;
            
            btn.innerHTML = '<i class="fa fa-check"></i>';
            btn.classList.remove('btn-outline-secondary');
            btn.classList.add('btn-success');
            
            setTimeout(() => {
                btn.innerHTML = originalHTML;
                btn.classList.remove('btn-success');
                btn.classList.add('btn-outline-secondary');
            }, 2000);
        }).catch(function(err) {
            console.error('Could not copy text: ', err);
        });
    }

    // Handle leave room
    document.getElementById('leaveRoomBtn').addEventListener('click', function() {
        const modal = new bootstrap.Modal(document.getElementById('leaveRoomModal'));
        modal.show();
    });

    document.getElementById('confirmLeaveBtn').addEventListener('click', function() {
        // Add your leave room logic here
        window.location.href = '{{ route("play") }}';
    });

    // Handle start game (for room owner)
    @if($isOwner)
    document.getElementById('startGameBtn').addEventListener('click', function() {
        const button = this;
        button.disabled = true;
        button.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Starting...';
        
        // Create a form data object to send as form data
        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        
        // Make an API call to start the game
        fetch('{{ route("game.start", $room->code) }}', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { throw err; });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Redirect to the game play page
                window.location.href = data.redirect_url || '{{ route("game.play", ["room" => $room->code]) }}';
            } else {
                throw new Error(data.message || 'Failed to start game');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to start game: ' + (error.message || 'Unknown error occurred'));
            button.disabled = false;
            button.innerHTML = '<i class="fa fa-play me-1"></i> Start Game';
        });
    });
    @endif

    // Handle ready status
    document.getElementById('readyBtn').addEventListener('click', function() {
        const btn = this;
        const roomCode = '{{ $room->code }}';
        const currentUserId = {{ auth()->id() }};
        
        // Show loading state
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fa fa-spinner fa-spin me-1"></i> Updating...';
        
        // Send AJAX request to toggle ready status
        fetch(`/rooms/${roomCode}/toggle-ready`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Toggle button state
                const isReady = data.is_ready;
                if (isReady) {
                    btn.classList.remove('btn-outline-primary');
                    btn.classList.add('btn-success');
                    btn.innerHTML = '<i class="fa fa-check me-1"></i> Ready!';
                    
                    // Update the ready badge for the current user
                    const badge = document.getElementById(`ready-badge-${currentUserId}`);
                    if (badge) {
                        badge.className = 'badge bg-success ms-1';
                        badge.innerHTML = '<i class="fa fa-check"></i> Ready';
                    }
                } else {
                    btn.classList.remove('btn-success');
                    btn.classList.add('btn-outline-primary');
                    btn.innerHTML = '<i class="fa fa-thumbs-up me-1"></i> I\'m Ready';
                    
                    // Update the ready badge for the current user
                    const badge = document.getElementById(`ready-badge-${currentUserId}`);
                    if (badge) {
                        badge.className = 'badge bg-secondary ms-1';
                        badge.innerHTML = '<i class="fa fa-clock"></i> Waiting';
                    }
                }
                
                // Show success message
                if (data.message) {
                    toastr.success(data.message);
                    
                    // If game is starting, the redirect will be handled by the Pusher event
                    if (data.game_started && data.redirect_url && !window.pusher) {
                        // Fallback in case Pusher is not available
                        let countdown = 3;
                        const countdownInterval = setInterval(() => {
                            if (countdown > 0) {
                                toastr.info(`Game starting in ${countdown}...`, '', {timeOut: 1000});
                                countdown--;
                            } else {
                                clearInterval(countdownInterval);
                                window.location.href = data.redirect_url;
                            }
                        }, 1000);
                    }
                }
            } else {
                toastr.error(data.message || 'Failed to update ready status');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            toastr.error('An error occurred while updating ready status');
        })
        .finally(() => {
            // Re-enable button
            btn.disabled = false;
        });
    });
    
    // Auto-focus the ready button for better UX
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('readyBtn').focus();
    });

    // Simple chat functionality
    document.getElementById('chat-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const input = document.getElementById('message-input');
        const message = input.value.trim();
        
        if (message) {
            // In a real app, you would send this to your server via WebSocket
            const chatMessages = document.getElementById('chat-messages');
            
            // Check if this is the first message
            if (chatMessages.querySelector('.text-muted')) {
                chatMessages.innerHTML = '';
            }
            
            // Add message to chat
            const messageElement = document.createElement('div');
            messageElement.className = 'mb-2';
            messageElement.innerHTML = `
                <div class="d-flex">
                    <div class="flex-shrink-0 me-2">
                        <img src="{{ Auth::user()->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=random' }}" 
                             class="rounded-circle" 
                             alt="{{ Auth::user()->name }}"
                             style="width: 32px; height: 32px; object-fit: cover;">
                    </div>
                    <div>
                        <div class="bg-light rounded p-2">
                            <div class="d-flex justify-content-between">
                                <strong class="text-primary">{{ Auth::user()->name }}</strong>
                                <small class="text-muted">just now</small>
                            </div>
                            <div class="mt-1">${message}</div>
                        </div>
                    </div>
                </div>
            `;
            
            chatMessages.appendChild(messageElement);
            input.value = '';
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
    });
</script>
@endpush

<style>
    /* Custom scrollbar for chat */
    #chat-messages::-webkit-scrollbar {
        width: 6px;
    }
    #chat-messages::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    #chat-messages::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
    }
    #chat-messages::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
</style>
@endsection
