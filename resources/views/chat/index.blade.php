<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Conversations') }}
            </h2>
            <a href="{{ route('chat.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg"></i> New Chat
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($conversations->count() > 0)
                        <div class="list-group">
                            @foreach($conversations as $conversation)
                                <a href="{{ route('chat.show', $conversation) }}"
                                    class="list-group-item list-group-item-action border-0 border-bottom">
                                    <div class="d-flex w-100 justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1 fw-bold">
                                                {{ $conversation->receiver ?? 'Direct Message' }}
                                            </h6>
                                            <small class="text-muted">
                                                By:
                                                @foreach($conversation->participants as $participant)
                                                    {{ $participant->first_name }}{{ !$loop->last ? ',' : '' }}
                                                @endforeach
                                            </small>
                                        </div>
                                        <div class="text-end">
                                            @if($conversation->messages->count() > 0)
                                                <small
                                                    class="text-muted d-block">{{ $conversation->messages->first()->created_at->diffForHumans() }}</small>
                                            @endif
                                            @if($conversation->unread_count > 0)
                                                <span class="badge bg-primary rounded-pill">{{ $conversation->unread_count }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    @if($conversation->messages->count() > 0)
                                        <p class="mb-1 text-truncate small text-muted mt-2">
                                            {{ Str::limit($conversation->messages->first()->content, 100) }}
                                        </p>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                        <div class="mt-4">
                            {{ $conversations->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-chat-dots fs-1 text-muted"></i>
                            <p class="mt-3 text-muted">No conversations yet. Start one today!</p>
                            <a href="{{ route('chat.create') }}" class="btn btn-outline-primary mt-2">Start a
                                conversation</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>