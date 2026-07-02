<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $conversation->subject ?? 'Conversation' }}
            </h2>
            <a href="{{ route('chat.index') }}" class="btn btn-light btn-sm">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="row">
                <!-- Chat Messages -->
                <div class="col-md-8">
                    <div class="card shadow-sm sm:rounded-lg overflow-hidden"
                        style="height: 600px; display: flex; flex-direction: column;">
                        <div class="card-body overflow-auto flex-grow-1 p-4" id="message-container"
                            data-conversation-id="{{ $conversation->id }}" data-current-user-id="{{ Auth::id() }}">
                            @foreach ($messages as $message)
                                <div data-message-id="{{ $message->id }}"
                                    class="mb-4 d-flex {{ $message->user_id === Auth::id() ? 'justify-content-end' : 'justify-content-start' }}">
                                    <div class="max-w-75">
                                        <div
                                            class="d-flex align-items-center mb-1 {{ $message->user_id === Auth::id() ? 'flex-row-reverse' : '' }}">
                                            <span class="fw-bold small px-2">{{ $message->user->first_name }}</span>
                                            <small
                                                class="text-muted">{{ $message->created_at->format('g:i A') }}</small>
                                        </div>
                                        <div
                                            class="p-3 rounded-pill {{ $message->user_id === Auth::id() ? 'bg-primary text-white shadow-sm' : 'bg-light border' }}">
                                            {{ $message->content }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="card-footer bg-white p-3 border-top">
                            <form id="chat-form" action="{{ route('chat.messages.send', $conversation) }}"
                                method="POST">
                                @csrf
                                <div class="input-group">
                                    <input type="text" name="content" id="chat-message-input" class="form-control"
                                        placeholder="Type your message..." required autocomplete="off">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-send"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Chat Participants -->
                <div class="col-md-4">
                    <div class="card shadow-sm sm:rounded-lg mb-4">
                        <div class="card-header bg-white border-bottom p-3">
                            <h6 class="mb-0 fw-bold">Participants</h6>
                        </div>
                        <div class="card-body p-0">
                            <ul class="list-group list-group-flush">
                                @foreach ($participants as $participant)
                                    <li
                                        class="list-group-item d-flex justify-content-between align-items-center border-0 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-3"
                                                style="width: 40px; height: 40px;">
                                                <i class="bi bi-person text-secondary"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $participant->first_name }}
                                                    {{ $participant->last_name }}
                                                </div>
                                                <small
                                                    class="text-muted d-block">{{ $participant->getRoleNames()->first() }}</small>
                                                @if ($participant->activity_status)
                                                    <small class="text-primary d-block mt-1 boarder-top pt-1">
                                                        {{ $participant->status_emoji }}
                                                        {{ $participant->activity_status }}
                                                        @if ($participant->isBusy() && $participant->status_busy_until)
                                                            <span class="text-muted d-block"
                                                                style="font-size: 0.75rem;">
                                                                (Busy until
                                                                {{ $participant->status_busy_until->format('g:i A') }})
                                                            </span>
                                                        @endif
                                                    </small>
                                                @endif
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    @if (Auth::user()->hasAnyRole(['family_admin', 'dsp', 'program_staff']))
                        <div class="card shadow-sm sm:rounded-lg">
                            <div class="card-header bg-white border-bottom p-3">
                                <h6 class="mb-0 fw-bold">Add Participant</h6>
                            </div>
                            <div class="card-body p-3">
                                <form action="{{ route('chat.participants.add', $conversation) }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <select name="user_id" class="form-select" required>
                                            <option value="" selected disabled>Select user...</option>
                                            @foreach ($potentialParticipants as $pUser)
                                                <option value="{{ $pUser->id }}">
                                                    {{ $pUser->first_name }} {{ $pUser->last_name }}
                                                    ({{ $pUser->getRoleNames()->first() }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-outline-primary w-100">Add to
                                        Conversation</button>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var container = document.getElementById('message-container');
            var conversationId = container.dataset.conversationId;
            var currentUserId = Number(container.dataset.currentUserId);
            var form = document.getElementById('chat-form');
            var input = document.getElementById('chat-message-input');

            container.scrollTop = container.scrollHeight;

            function escapeHtml(value) {
                var div = document.createElement('div');
                div.textContent = value ?? '';
                return div.innerHTML;
            }

            function formatTime(isoString) {
                return new Date(isoString).toLocaleTimeString([], {
                    hour: 'numeric',
                    minute: '2-digit'
                });
            }

            function appendMessage(message) {
                if (container.querySelector('[data-message-id="' + message.id + '"]')) {
                    return;
                }

                var isMine = Number(message.user.id) === currentUserId;
                var firstName = (message.user.name || '').split(' ')[0];

                var wrapper = document.createElement('div');
                wrapper.dataset.messageId = message.id;
                wrapper.className = 'mb-4 d-flex ' + (isMine ? 'justify-content-end' : 'justify-content-start');
                wrapper.innerHTML =
                    '<div class="max-w-75">' +
                    '<div class="d-flex align-items-center mb-1 ' + (isMine ? 'flex-row-reverse' : '') + '">' +
                    '<span class="fw-bold small px-2">' + escapeHtml(firstName) + '</span>' +
                    '<small class="text-muted">' + formatTime(message.created_at) + '</small>' +
                    '</div>' +
                    '<div class="p-3 rounded-pill ' + (isMine ? 'bg-primary text-white shadow-sm' : 'bg-light border') + '">' +
                    escapeHtml(message.content) +
                    '</div>' +
                    '</div>';

                container.appendChild(wrapper);
                container.scrollTop = container.scrollHeight;
            }

            if (window.Echo) {
                window.Echo.private('conversation.' + conversationId)
                    .listen('.message.sent', function(message) {
                        appendMessage(message);
                    });
            }

            if (form) {
                form.addEventListener('submit', function(event) {
                    event.preventDefault();

                    var content = input.value.trim();
                    if (!content) {
                        return;
                    }

                    var submitButton = form.querySelector('button[type="submit"]');
                    submitButton.disabled = true;

                    window.axios.post(form.action, {
                            content: content
                        }, {
                            headers: {
                                Accept: 'application/json'
                            }
                        })
                        .then(function() {
                            input.value = '';
                        })
                        .catch(function() {
                            alert('Could not send message. Please try again.');
                        })
                        .finally(function() {
                            submitButton.disabled = false;
                            input.focus();
                        });
                });
            }
        });
    </script>
</x-app-layout>
