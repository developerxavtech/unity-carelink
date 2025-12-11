<x-app-layout>
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-1">Team Communication</h2>
                        <p class="text-muted">Internal messaging and collaboration</p>
                    </div>
                    <button class="btn btn-primary" disabled>
                        <i class="bi bi-plus-circle me-1"></i> New Message
                    </button>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3 border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="bi bi-chat-dots text-primary"></i> Conversations
                            </h5>
                            @if($unreadCount > 0)
                                <span class="badge bg-danger">{{ $unreadCount }} unread</span>
                            @endif
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @forelse($conversations as $conversation)
                            <a href="{{ route('agency.team-communication.show', $conversation->id) }}"
                               class="list-group-item list-group-item-action border-0 border-bottom p-3">
                                <div class="d-flex w-100 align-items-start">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                                        <i class="bi bi-person-circle text-primary fs-5"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between mb-1">
                                            <h6 class="mb-0">{{ $conversation->subject ?? 'No Subject' }}</h6>
                                            <small class="text-muted">
                                                {{ $conversation->updated_at->diffForHumans() }}
                                            </small>
                                        </div>
                                        <p class="mb-1 text-muted small">
                                            @if($conversation->messages->isNotEmpty())
                                                {{ Str::limit($conversation->messages->last()->content, 100) }}
                                            @else
                                                No messages yet
                                            @endif
                                        </p>
                                        <div class="d-flex gap-2">
                                            <span class="badge bg-light text-dark">
                                                <i class="bi bi-people"></i>
                                                {{ $conversation->participants_count ?? 2 }} participants
                                            </span>
                                            @if($conversation->messages_count ?? 0 > 0)
                                                <span class="badge bg-light text-dark">
                                                    <i class="bi bi-chat"></i>
                                                    {{ $conversation->messages_count }} messages
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="text-center py-5">
                                <i class="bi bi-inbox text-muted fs-1 mb-3"></i>
                                <p class="text-muted">No conversations yet.</p>
                                <p class="small text-muted">Start a new conversation to communicate with your team.</p>
                                <button class="btn btn-outline-primary btn-sm mt-2" disabled>
                                    <i class="bi bi-plus-circle me-1"></i> Start Conversation
                                </button>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Contacts -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0">
                            <i class="bi bi-people-fill text-info"></i> Quick Contacts
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <button class="btn btn-outline-primary w-100 mb-2" disabled>
                                    <i class="bi bi-building"></i> All Programs
                                </button>
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-outline-secondary w-100 mb-2" disabled>
                                    <i class="bi bi-person-badge"></i> All DSP Staff
                                </button>
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-outline-info w-100 mb-2" disabled>
                                    <i class="bi bi-people"></i> Program Staff
                                </button>
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-outline-success w-100 mb-2" disabled>
                                    <i class="bi bi-star"></i> Admin Team
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
