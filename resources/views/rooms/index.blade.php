@extends('layouts.app')

@section('title', 'Game Rooms')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="fa fa-gamepad me-2 text-primary"></i>Available Game Rooms
                </h1>
                <a href="{{ route('rooms.create') }}" class="btn btn-primary">
                    <i class="fa fa-plus me-1"></i> Create Room
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card border-0 shadow-sm">
                @if($rooms->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Room Code</th>
                                    <th>Players</th>
                                    <th>Game Type</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rooms as $room)
                                    <tr>
                                        <td class="align-middle">
                                            <strong>{{ $room->code }}</strong>
                                            @if(isset($room->settings['is_public']) && $room->settings['is_public'])
                                                <span class="badge bg-success ms-2">Public</span>
                                            @else
                                                <span class="badge bg-secondary ms-2">Private</span>
                                            @endif
                                        </td>
                                        <td class="align-middle">
                                            {{ $room->users_count }} / {{ $room->settings['max_players'] ?? 4 }}
                                        </td>
                                        <td class="align-middle">
                                            {{ ucfirst($room->settings['game_type'] ?? 'standard') }}
                                        </td>
                                        <td class="align-middle">
                                            @if($room->status === 'waiting')
                                                <span class="badge bg-warning text-dark">Waiting</span>
                                            @elseif($room->status === 'in_progress')
                                                <span class="badge bg-primary">In Progress</span>
                                            @else
                                                <span class="badge bg-secondary">{{ ucfirst($room->status) }}</span>
                                            @endif
                                        </td>
                                        <td class="align-middle">
                                            {{ $room->created_at->diffForHumans() }}
                                        </td>
                                        <td class="align-middle">
                                            @if($room->status === 'waiting' && $room->users_count < ($room->settings['max_players'] ?? 4))
                                                <a href="{{ route('rooms.join', $room->code) }}" class="btn btn-sm btn-success">
                                                    <i class="fa fa-sign-in-alt me-1"></i> Join
                                                </a>
                                            @else
                                                <button class="btn btn-sm btn-outline-secondary" disabled>
                                                    <i class="fa fa-eye me-1"></i> View
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="card-footer bg-white border-top-0">
                        {{ $rooms->links() }}
                    </div>
                @else
                    <div class="card-body text-center py-5">
                        <div class="mb-3">
                            <i class="fa fa-door-open fa-4x text-muted mb-3"></i>
                        </div>
                        <h5 class="mb-2">No game rooms available</h5>
                        <p class="text-muted mb-3">Be the first to create a room and start playing!</p>
                        <a href="{{ route('rooms.create') }}" class="btn btn-primary">
                            <i class="fa fa-plus me-1"></i> Create Room
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
