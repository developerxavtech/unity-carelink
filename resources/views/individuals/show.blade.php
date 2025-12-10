<x-app-layout>
    <x-slot name="header">
        {{ $individual->first_name }} {{ $individual->last_name }}
    </x-slot>

    <div class="row">
        <!-- Profile Information -->
        <div class="col-lg-4 mb-4">
            <div class="card dashboard-card">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="bi bi-person-circle fs-1 text-primary"></i>
                    </div>
                    <h4>{{ $individual->first_name }} {{ $individual->last_name }}</h4>
                    <p class="text-muted mb-2">{{ $individual->pronouns }}</p>
                    <span class="badge bg-success mb-3">Active</span>

                    <div class="text-start mt-4">
                        <p class="mb-2"><strong>Age:</strong> {{ $individual->age }} years old</p>
                        <p class="mb-2"><strong>Date of Birth:</strong> {{ \Carbon\Carbon::parse($individual->date_of_birth)->format('M d, Y') }}</p>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <a href="{{ route('individuals.edit', $individual) }}" class="btn btn-primary">
                            <i class="bi bi-pencil"></i> Edit Profile
                        </a>
                        <a href="{{ route('individuals.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
            </div>

            <!-- CarePulse Widget -->
            <div class="card dashboard-card mt-4">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0">
                        <i class="bi bi-heart-pulse text-danger"></i>
                        Recent CarePulse
                    </h6>
                </div>
                <div class="card-body">
                    @if($individual->moodChecks->count() > 0)
                        @foreach($individual->moodChecks->take(3) as $check)
                            <div class="mb-2">
                                <span class="carepulse-indicator carepulse-{{ $check->mood }}">
                                    {{ ucfirst($check->mood) }}
                                </span>
                                <small class="text-muted d-block">{{ $check->created_at->diffForHumans() }}</small>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted mb-0 small">No mood checks yet</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Profile Details & Activity -->
        <div class="col-lg-8">
            <!-- About Section -->
            <div class="card dashboard-card mb-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">About</h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h6 class="text-primary">
                            <i class="bi bi-star"></i> Strengths & Abilities
                        </h6>
                        <p>{{ $individual->strengths_abilities ?? 'Not specified' }}</p>
                    </div>

                    <div class="mb-4">
                        <h6 class="text-primary">
                            <i class="bi bi-heart"></i> Preferences & Interests
                        </h6>
                        <p>{{ $individual->preferences_interests ?? 'Not specified' }}</p>
                    </div>

                    <div class="mb-4">
                        <h6 class="text-primary">
                            <i class="bi bi-chat-dots"></i> Communication Style
                        </h6>
                        <p>{{ $individual->communication_style ?? 'Not specified' }}</p>
                    </div>

                    <div class="mb-0">
                        <h6 class="text-primary">
                            <i class="bi bi-eye"></i> Sensory Profile
                        </h6>
                        <p>{{ $individual->sensory_profile ?? 'Not specified' }}</p>
                    </div>
                </div>
            </div>

            <!-- Support Strategies -->
            <div class="card dashboard-card mb-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">Support Strategies</h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h6 class="text-warning">
                            <i class="bi bi-exclamation-triangle"></i> Triggers
                        </h6>
                        <p>{{ $individual->triggers ?? 'Not specified' }}</p>
                    </div>

                    <div class="mb-4">
                        <h6 class="text-success">
                            <i class="bi bi-emoji-smile"></i> Calming Strategies
                        </h6>
                        <p>{{ $individual->calming_strategies ?? 'Not specified' }}</p>
                    </div>

                    <div class="mb-0">
                        <h6 class="text-danger">
                            <i class="bi bi-shield-check"></i> Safety Notes
                        </h6>
                        <p>{{ $individual->safety_notes ?? 'Not specified' }}</p>
                    </div>
                </div>
            </div>

            <!-- Recent Care Notes -->
            <div class="card dashboard-card">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Recent Care Notes</h5>
                        <button class="btn btn-sm btn-primary" disabled>
                            <i class="bi bi-plus"></i> Add Note
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    @if($individual->careNotes->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($individual->careNotes->take(5) as $note)
                                <div class="list-group-item px-0">
                                    <div class="d-flex justify-content-between">
                                        <strong>{{ $note->user->full_name }}</strong>
                                        <small class="text-muted">{{ $note->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-0 mt-2">{{ Str::limit($note->notes, 150) }}</p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-journal fs-3 mb-2"></i>
                            <p class="mb-0">No care notes yet</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
