<x-app-layout>
    <x-slot name="header">
        Add Family Member
    </x-slot>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card dashboard-card">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">New Family Member Profile</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('individuals.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label">First Name *</label>
                                <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                                    id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label">Last Name *</label>
                                <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                    id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="date_of_birth" class="form-label">Date of Birth *</label>
                                <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror"
                                    id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}" required>
                                @error('date_of_birth')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="pronouns" class="form-label">Pronouns</label>
                                <input type="text" class="form-control @error('pronouns') is-invalid @enderror"
                                    id="pronouns" name="pronouns" value="{{ old('pronouns') }}"
                                    placeholder="e.g., He/Him, She/Her, They/Them">
                                @error('pronouns')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">
                        <h6 class="text-muted mb-3">About This Family Member</h6>

                        <div class="mb-3">
                            <label for="strengths_abilities" class="form-label">Strengths & Abilities</label>
                            <textarea class="form-control @error('strengths_abilities') is-invalid @enderror" id="strengths_abilities"
                                name="strengths_abilities" rows="3">{{ old('strengths_abilities') }}</textarea>
                            @error('strengths_abilities')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">What are they good at? What do they enjoy?</small>
                        </div>

                        <div class="mb-3">
                            <label for="preferences_interests" class="form-label">Preferences & Interests</label>
                            <textarea class="form-control @error('preferences_interests') is-invalid @enderror" id="preferences_interests"
                                name="preferences_interests" rows="3">{{ old('preferences_interests') }}</textarea>
                            @error('preferences_interests')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="communication_style" class="form-label">Communication Style</label>
                            <textarea class="form-control @error('communication_style') is-invalid @enderror" id="communication_style"
                                name="communication_style" rows="2">{{ old('communication_style') }}</textarea>
                            @error('communication_style')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">How do they communicate? Any special needs?</small>
                        </div>

                        <div class="mb-3">
                            <label for="sensory_profile" class="form-label">Sensory Profile</label>
                            <textarea class="form-control @error('sensory_profile') is-invalid @enderror" id="sensory_profile"
                                name="sensory_profile" rows="2">{{ old('sensory_profile') }}</textarea>
                            @error('sensory_profile')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Sensory sensitivities or preferences</small>
                        </div>

                        <hr class="my-4">
                        <h6 class="text-muted mb-3">Support Strategies</h6>

                        <div class="mb-3">
                            <label for="triggers" class="form-label">Triggers</label>
                            <textarea class="form-control @error('triggers') is-invalid @enderror" id="triggers" name="triggers" rows="2">{{ old('triggers') }}</textarea>
                            @error('triggers')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">What situations might be challenging?</small>
                        </div>

                        <div class="mb-3">
                            <label for="calming_strategies" class="form-label">Calming Strategies</label>
                            <textarea class="form-control @error('calming_strategies') is-invalid @enderror" id="calming_strategies"
                                name="calming_strategies" rows="2">{{ old('calming_strategies') }}</textarea>
                            @error('calming_strategies')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">What helps them feel calm and comfortable?</small>
                        </div>

                        <div class="mb-3">
                            <label for="safety_notes" class="form-label">Safety Notes</label>
                            <textarea class="form-control @error('safety_notes') is-invalid @enderror" id="safety_notes" name="safety_notes"
                                rows="2">{{ old('safety_notes') }}</textarea>
                            @error('safety_notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Important safety information, allergies,
                                medications</small>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Save Profile
                            </button>
                            <a href="{{ route('individuals.index') }}" class="btn btn-outline-secondary">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
