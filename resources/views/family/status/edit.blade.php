<x-app-layout>
    <x-slot name="header">
        Update My Status
    </x-slot>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card dashboard-card">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">
                        <i class="bi bi-person-badge text-primary"></i>
                        What are you up to?
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('family.status.update') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label">Quick Activity Status</label>
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                @php
                                    $suggestions = [
                                        ['label' => 'Busy at the mall', 'emoji' => '🛍️'],
                                        ['label' => 'Swimming', 'emoji' => '🏊'],
                                        ['label' => 'At the gym', 'emoji' => '🏋️'],
                                        ['label' => 'Driving', 'emoji' => '🚗'],
                                        ['label' => 'Sleeping', 'emoji' => '😴'],
                                        ['label' => 'In a meeting', 'emoji' => '🤝'],
                                        ['label' => 'Out for lunch', 'emoji' => '🍴'],
                                    ];
                                @endphp
                                @foreach($suggestions as $suggest)
                                    <button type="button" class="btn btn-outline-secondary btn-sm suggestion-btn"
                                        onclick="setStatus('{{ $suggest['label'] }}', '{{ $suggest['emoji'] }}')">
                                        {{ $suggest['emoji'] }} {{ $suggest['label'] }}
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label for="status_emoji" class="form-label">Emoji</label>
                                <input type="text" name="status_emoji" id="status_emoji"
                                    class="form-control text-center fs-4"
                                    value="{{ old('status_emoji', $user->status_emoji) }}" placeholder="📍">
                            </div>
                            <div class="col-md-9">
                                <label for="activity_status" class="form-label">Activity</label>
                                <input type="text" name="activity_status" id="activity_status" class="form-control"
                                    value="{{ old('activity_status', $user->activity_status) }}"
                                    placeholder="e.g. Busy at the mall">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="status_message" class="form-label">Short Note (Optional)</label>
                            <textarea name="status_message" id="status_message" class="form-control"
                                rows="2">{{ old('status_message', $user->status_message) }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label for="status_busy_until" class="form-label">Busy Until (Optional)</label>
                            <input type="datetime-local" name="status_busy_until" id="status_busy_until"
                                class="form-control"
                                value="{{ $user->status_busy_until ? $user->status_busy_until->format('Y-m-d\TH:i') : '' }}">
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('family.home') }}" class="btn btn-light border">Cancel</a>
                            <div>
                                @if($user->activity_status)
                                    <button type="button" onclick="document.getElementById('clear-form').submit()"
                                        class="btn btn-outline-danger me-2">
                                        Clear Status
                                    </button>
                                @endif
                                <button type="submit" class="btn btn-primary">Update Status</button>
                            </div>
                        </div>
                    </form>

                    <form id="clear-form" action="{{ route('family.status.clear') }}" method="POST"
                        style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function setStatus(label, emoji) {
            document.getElementById('activity_status').value = label;
            document.getElementById('status_emoji').value = emoji;
        }
    </script>
</x-app-layout>