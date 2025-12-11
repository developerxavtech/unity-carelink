<x-app-layout>
    <x-slot name="header">
        Messages
    </x-slot>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Conversations</h5>
                        <button class="btn btn-sm btn-primary" disabled>
                            <i class="bi bi-plus"></i> New Message
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($conversations as $conversation)
                            <a href="{{ route('family.messages.show', $conversation) }}"
                               class="list-group-item list-group-item-action">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">{{ $conversation->name ?? 'Conversation' }}</h6>
                                        <p class="mb-0 small text-muted">
                                            {{ $conversation->messages->first()->content ?? 'No messages yet' }}
                                        </p>
                                    </div>
                                    <div class="text-end">
                                        <small class="text-muted">
                                            {{ $conversation->messages->first()?->created_at?->diffForHumans() }}
                                        </small>
                                        @if($conversation->messages_count > 0)
                                            <span class="badge bg-danger d-block mt-1">
                                                {{ $conversation->messages_count }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="text-center py-5">
                                <i class="bi bi-chat-dots fs-1 text-muted mb-3"></i>
                                <p class="text-muted mb-0">No conversations yet</p>
                                <small class="text-muted">Start a conversation with your care team</small>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
