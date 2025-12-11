<x-app-layout>
    <x-slot name="header">
        {{ $conversation->name ?? 'Conversation' }}
    </x-slot>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body" style="min-height: 500px; max-height: 600px; overflow-y: auto;">
                    @foreach($messages as $message)
                        <div class="mb-3 {{ $message->user_id == auth()->id() ? 'text-end' : '' }}">
                            <div class="d-inline-block text-start" style="max-width: 70%;">
                                <div class="card {{ $message->user_id == auth()->id() ? 'bg-primary text-white' : 'bg-light' }}">
                                    <div class="card-body py-2 px-3">
                                        <div class="small mb-1">
                                            <strong>{{ $message->user->first_name }} {{ $message->user->last_name }}</strong>
                                        </div>
                                        <p class="mb-0">{{ $message->content }}</p>
                                        <div class="small mt-1 {{ $message->user_id == auth()->id() ? 'text-white-50' : 'text-muted' }}">
                                            {{ $message->created_at->format('M d, g:i A') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="card-footer">
                    <form class="d-flex gap-2">
                        <input type="text" class="form-control" placeholder="Type a message..." disabled>
                        <button class="btn btn-primary" disabled>Send</button>
                    </form>
                    <small class="text-muted">Message composition coming soon</small>
                </div>
            </div>

            <a href="{{ route('family.messages') }}" class="btn btn-outline-secondary mt-3">
                <i class="bi bi-arrow-left"></i> Back to Messages
            </a>
        </div>
    </div>
</x-app-layout>
