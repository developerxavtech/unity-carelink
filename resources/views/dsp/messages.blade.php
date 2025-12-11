<x-app-layout>
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">Messages</h2>
                    <p class="text-muted">Communicate with families and team members</p>
                </div>
                <button class="btn btn-primary" disabled>
                    <i class="bi bi-plus-circle me-1"></i> New Message
                </button>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                @forelse($conversations as $conversation)
                    <a href="{{ route('dsp.messages.show', $conversation) }}" class="text-decoration-none text-dark">
                        <div class="d-flex align-items-center p-3 border-bottom hover-bg-light">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                                <i class="bi bi-chat-dots text-primary fs-5"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <h6 class="mb-0">{{ $conversation->name }}</h6>
                                    @if($conversation->unread_count > 0)
                                        <span class="badge bg-primary">{{ $conversation->unread_count }}</span>
                                    @endif
                                </div>
                                @if($conversation->latest_message)
                                    <p class="text-muted small mb-1">{{ Str::limit($conversation->latest_message->content, 80) }}</p>
                                    <small class="text-muted">{{ $conversation->latest_message->created_at->diffForHumans() }}</small>
                                @endif
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="text-center py-5">
                        <i class="bi bi-inbox text-muted" style="font-size: 4rem;"></i>
                        <h5 class="mt-3 mb-2">No Messages</h5>
                        <p class="text-muted mb-3">You don't have any conversations yet.</p>
                        <p class="small text-muted">When families or team members send you messages, they'll appear here.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
