<x-app-layout>
    <x-slot name="header">
        Daily Updates
    </x-slot>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <form method="GET" action="{{ route('family.daily-updates') }}" class="row g-3">
                            <div class="col-auto">
                                <label class="form-label visually-hidden">Filter by Individual</label>
                                <select name="individual_id" class="form-select" onchange="this.form.submit()">
                                    <option value="">All Individuals</option>
                                    @foreach ($individualProfiles as $profile)
                                        <option value="{{ $profile->id }}"
                                            {{ $selectedIndividual == $profile->id ? 'selected' : '' }}>
                                            {{ $profile->full_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </form>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#askQuestionModal">
                            <i class="bi bi-chat-dots me-2"></i>Ask a Question
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Mood Checks -->
        <div class="col-md-4 mb-4">
            <div class="card dashboard-card">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">
                        <i class="bi bi-heart-pulse text-danger"></i>
                        Recent CarePulse
                    </h5>
                </div>
                <div class="card-body">
                    @forelse($recentMoodChecks as $check)
                        <div class="mb-3 pb-3 border-bottom">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <strong>{{ $check->individualProfile->full_name }}</strong>
                                    <div class="mt-1">
                                        <span
                                            class="badge bg-{{ $check->mood == 'happy' ? 'success' : ($check->mood == 'sad' ? 'danger' : 'warning') }}">
                                            {{ ucfirst($check->mood) }}
                                        </span>
                                    </div>
                                </div>
                                <small class="text-muted">{{ $check->check_date->format('M d') }}</small>
                            </div>
                            @if ($check->notes)
                                <p class="mb-0 mt-2 small">{{ $check->notes }}</p>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <i class="bi bi-heart fs-1 text-muted mb-2"></i>
                            <p class="text-muted mb-0">No recent CarePulse checks</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Care Notes Feed -->
        <div class="col-md-8 mb-4">
            <div class="card dashboard-card">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">
                        <i class="bi bi-journal-text text-primary"></i>
                        Recent Care Notes
                    </h5>
                </div>
                <div class="card-body">
                    @forelse($recentNotes as $note)
                        <div class="mb-4 pb-4 border-bottom">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">{{ $note->individualProfile->full_name }}</h6>
                                    <small class="text-muted">
                                        by {{ $note->dsp->first_name }} {{ $note->dsp->last_name }} •
                                        {{ $note->shift_date->format('M d, Y') }}
                                    </small>
                                </div>
                                @if ($note->mood)
                                    <span
                                        class="badge bg-{{ $note->mood == 'happy' ? 'success' : ($note->mood == 'sad' ? 'danger' : 'secondary') }}">
                                        {{ ucfirst($note->mood) }}
                                    </span>
                                @endif
                            </div>
                            <p class="mt-2 mb-0">{{ $note->notes }}</p>

                            @if ($note->activities)
                                <div class="mt-2">
                                    <strong class="small">Activities:</strong>
                                    <span class="small">{{ $note->activities }}</span>
                                </div>
                            @endif

                            @if ($note->meals)
                                <div class="mt-1">
                                    <strong class="small">Meals:</strong>
                                    <span class="small">{{ $note->meals }}</span>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="bi bi-journal fs-1 text-muted mb-2"></i>
                            <p class="text-muted mb-0">No recent care notes</p>
                            <small class="text-muted">Care notes from your DSPs will appear here</small>
                        </div>
                    @endforelse

                    @if ($recentNotes->hasPages())
                        <div class="mt-3">
                            {{ $recentNotes->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- Ask Question Modal -->
    <div class="modal fade" id="askQuestionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ask a Question</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('chat.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Select DSP</label>
                            <select name="user_id" class="form-select" required>
                                <option value="" selected disabled>Choose a DSP...</option>
                                @foreach ($availableDsps as $dsp)
                                    <option value="{{ $dsp->id }}">
                                        {{ $dsp->first_name }} {{ $dsp->last_name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text">Select the Direct Support Professional you want to contact.</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Subject</label>
                            <input type="text" name="subject" class="form-control" required
                                placeholder="e.g., Question about medication">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Question</label>
                            <textarea name="message" class="form-control" rows="4" required placeholder="Type your question here..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Send Question</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
