<x-app-layout>
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <a href="{{ route('dsp.messages') }}" class="btn btn-outline-secondary btn-sm mb-3">
                    <i class="bi bi-arrow-left me-1"></i> Back to Messages
                </a>
                <h2 class="mb-1">{{ $conversation->name }}</h2>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div style="max-height: 500px; overflow-y: auto;" class="mb-4">
                    @forelse($messages as $message)
                        <div class="mb-3 {{ $message->user_id === auth()->id() ? 'text-end' : '' }}">
                            <div class="d-inline-block {{ $message->user_id === auth()->id() ? 'bg-primary text-white' : 'bg-light' }} rounded p-3" style="max-width: 70%;">
                                @if($message->user_id !== auth()->id())
                                    <strong class="d-block mb-1">{{ $message->user->first_name }} {{ $message->user->last_name }}</strong>
                                @endif
                                <p class="mb-1">{{ $message->content }}</p>
                                <small class="{{ $message->user_id === auth()->id() ? 'text-white-50' : 'text-muted' }}">
                                    {{ $message->created_at->format('M j, g:i A') }}
                                </small>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-chat-dots fs-1 mb-3"></i>
                            <p>No messages yet. Start the conversation!</p>
                        </div>
                    @endforelse
                </div>

                <form class="border-top pt-3">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Type your message..." disabled>
                        <button class="btn btn-primary" type="submit" disabled>
                            <i class="bi bi-send"></i> Send
                        </button>
                    </div>
                    <small class="text-muted">Messaging functionality coming soon!</small>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
