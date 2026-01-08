<x-app-layout>
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex align-items-center">
                    <a href="{{ route('program.messages') }}" class="btn btn-outline-secondary btn-sm me-3">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                    <div>
                        <h4 class="mb-0">{{ $conversation->subject ?? 'Conversation' }}</h4>
                        <small class="text-muted">Started {{ $conversation->created_at->diffForHumans() }}</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Messages Thread -->
        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0">
                            <i class="bi bi-chat-dots text-primary"></i> Messages
                        </h6>
                    </div>
                    <div class="card-body" style="max-height: 500px; overflow-y: auto;">
                        @forelse($messages as $message)
                            <div class="mb-3 {{ $message->user_id === Auth::id() ? 'text-end' : '' }}">
                                <div class="d-inline-block" style="max-width: 70%;">
                                    <div
                                        class="p-3 rounded {{ $message->user_id === Auth::id() ? 'bg-primary text-white' : 'bg-light' }}">
                                        <div class="d-flex align-items-start gap-2 mb-2">
                                            @if ($message->user_id !== Auth::id())
                                                <div class="bg-secondary bg-opacity-25 rounded-circle p-2">
                                                    <i class="bi bi-person-circle"></i>
                                                </div>
                                            @endif
                                            <div class="flex-grow-1">
                                                <strong class="d-block mb-1">
                                                    {{ $message->user->first_name }} {{ $message->user->last_name }}
                                                </strong>
                                                <p class="mb-0">{{ $message->content }}</p>
                                            </div>
                                        </div>
                                        <small class="opacity-75">
                                            {{ $message->created_at->format('M d, Y g:i A') }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <i class="bi bi-chat-left-text text-muted fs-1 mb-3"></i>
                                <p class="text-muted">No messages yet. Start the conversation!</p>
                            </div>
                        @endforelse
                    </div>
                    <div class="card-footer bg-white border-top">
                        <form method="POST" action="#" class="d-flex gap-2" disabled>
                            @csrf
                            <input type="text" class="form-control" placeholder="Type your message..." name="content"
                                disabled>
                            <button type="submit" class="btn btn-primary" disabled>
                                <i class="bi bi-send"></i> Send
                            </button>
                        </form>
                        <small class="text-muted d-block mt-2">
                            <i class="bi bi-info-circle"></i> Message sending functionality coming soon
                        </small>
                    </div>
                </div>
            </div>

            <!-- Conversation Info Sidebar -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0">
                            <i class="bi bi-info-circle text-info"></i> Conversation Info
                        </h6>
                    </div>
                    <div class="card-body">
                        <h6 class="text-muted small mb-2">PARTICIPANTS</h6>
                        <div class="mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-2">
                                    <i class="bi bi-person-circle text-primary"></i>
                                </div>
                                <div>
                                    <strong class="d-block">You</strong>
                                    <small class="text-muted">Program Staff</small>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <h6 class="text-muted small mb-2">DETAILS</h6>
                        <ul class="list-unstyled small">
                            <li class="mb-2">
                                <i class="bi bi-calendar text-muted"></i>
                                <strong>Created:</strong>
                                {{ $conversation->created_at->format('M d, Y') }}
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-clock text-muted"></i>
                                <strong>Last Activity:</strong>
                                {{ $conversation->updated_at->diffForHumans() }}
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-chat text-muted"></i>
                                <strong>Messages:</strong>
                                {{ $messages->count() }}
                            </li>
                        </ul>

                        <hr>

                        <div class="d-grid gap-2">
                            <button class="btn btn-outline-primary btn-sm" disabled>
                                <i class="bi bi-person-plus"></i> Add Participant
                            </button>
                            <button class="btn btn-outline-secondary btn-sm" disabled>
                                <i class="bi bi-archive"></i> Archive
                            </button>
                            <button class="btn btn-outline-danger btn-sm" disabled>
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
