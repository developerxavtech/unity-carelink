<x-app-layout>
    <x-slot name="header">
        DSP Notes
    </x-slot>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('family.dsp-notes') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Individual</label>
                    <select name="individual_id" class="form-select">
                        <option value="">All</option>
                        @foreach($individualProfiles as $profile)
                            <option value="{{ $profile->id }}"
                                {{ ($filters['individual_id'] ?? '') == $profile->id ? 'selected' : '' }}>
                                {{ $profile->full_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Start Date</label>
                    <input type="date" name="start_date" class="form-control"
                        value="{{ $filters['start_date'] ?? '' }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">End Date</label>
                    <input type="date" name="end_date" class="form-control"
                        value="{{ $filters['end_date'] ?? '' }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Mood</label>
                    <select name="mood" class="form-select">
                        <option value="">All</option>
                        <option value="happy" {{ ($filters['mood'] ?? '') == 'happy' ? 'selected' : '' }}>Happy</option>
                        <option value="neutral" {{ ($filters['mood'] ?? '') == 'neutral' ? 'selected' : '' }}>Neutral</option>
                        <option value="sad" {{ ($filters['mood'] ?? '') == 'sad' ? 'selected' : '' }}>Sad</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" class="form-control"
                        placeholder="Keywords..." value="{{ $filters['search'] ?? '' }}">
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Notes List -->
    <div class="card">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0">Care Notes ({{ $notes->total() }})</h5>
        </div>
        <div class="card-body">
            @forelse($notes as $note)
                <div class="mb-4 pb-4 border-bottom">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h6 class="mb-1">
                                <span class="badge bg-primary">{{ $note->individualProfile->full_name }}</span>
                            </h6>
                            <small class="text-muted">
                                <i class="bi bi-person"></i> {{ $note->dsp->first_name }} {{ $note->dsp->last_name }} •
                                <i class="bi bi-calendar"></i> {{ $note->shift_date->format('M d, Y') }}
                            </small>
                        </div>
                        @if($note->mood)
                            <span class="badge bg-{{ $note->mood == 'happy' ? 'success' : ($note->mood == 'sad' ? 'danger' : 'warning') }}">
                                {{ ucfirst($note->mood) }}
                            </span>
                        @endif
                    </div>

                    <p class="mb-2">{{ $note->notes }}</p>

                    <div class="row small text-muted">
                        @if($note->activities)
                            <div class="col-md-6">
                                <strong>Activities:</strong> {{ $note->activities }}
                            </div>
                        @endif
                        @if($note->meals)
                            <div class="col-md-6">
                                <strong>Meals:</strong> {{ $note->meals }}
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <i class="bi bi-journal fs-1 text-muted mb-3"></i>
                    <p class="text-muted mb-0">No care notes found</p>
                    <small class="text-muted">Try adjusting your filters or check back later</small>
                </div>
            @endforelse

            @if($notes->hasPages())
                <div class="mt-3">
                    {{ $notes->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
