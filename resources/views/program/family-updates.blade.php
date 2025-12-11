<x-app-layout>
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-1">Family Updates</h2>
                        <p class="text-muted">Share updates and communicate with families</p>
                    </div>
                    <button class="btn btn-primary" disabled>
                        <i class="bi bi-plus-circle me-1"></i> Create Update
                    </button>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <form method="GET" class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label small text-muted">Filter by Participant</label>
                                <select name="participant_id" class="form-select">
                                    <option value="">All Participants</option>
                                    @foreach($participants as $participant)
                                        <option value="{{ $participant->id }}">
                                            {{ $participant->first_name }} {{ $participant->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small text-muted">Date Range</label>
                                <select name="date_range" class="form-select">
                                    <option value="today">Today</option>
                                    <option value="week" selected>This Week</option>
                                    <option value="month">This Month</option>
                                    <option value="all">All Time</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small text-muted">Type</label>
                                <select name="update_type" class="form-select">
                                    <option value="">All Types</option>
                                    <option value="care_note">Care Notes</option>
                                    <option value="announcement">Announcements</option>
                                    <option value="achievement">Achievements</option>
                                </select>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-outline-primary w-100">
                                    <i class="bi bi-funnel"></i> Filter
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Updates List -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0">
                            <i class="bi bi-megaphone text-primary"></i> Recent Updates
                        </h5>
                    </div>
                    <div class="card-body">
                        @forelse($updates as $update)
                            <div class="border-start border-3 border-primary ps-3 mb-4 pb-4 border-bottom">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h6 class="mb-1">
                                            {{ $update->individualProfile->first_name }}
                                            {{ $update->individualProfile->last_name }}
                                        </h6>
                                        <small class="text-muted">
                                            <i class="bi bi-person-badge"></i>
                                            {{ $update->dsp ? $update->dsp->first_name . ' ' . $update->dsp->last_name : 'Staff' }}
                                        </small>
                                    </div>
                                    <div class="text-end">
                                        <small class="text-muted d-block">
                                            {{ $update->shift_date ? $update->shift_date->format('M d, Y') : $update->created_at->format('M d, Y') }}
                                        </small>
                                        @if($update->mood)
                                            <span class="badge bg-{{ $update->mood === 'great' ? 'success' : ($update->mood === 'good' ? 'info' : ($update->mood === 'okay' ? 'warning' : 'danger')) }} mt-1">
                                                {{ ucfirst($update->mood) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <p class="mb-2">{{ $update->notes }}</p>

                                <div class="d-flex gap-2 mt-2">
                                    <button class="btn btn-sm btn-outline-primary" disabled>
                                        <i class="bi bi-share"></i> Share with Family
                                    </button>
                                    <button class="btn btn-sm btn-outline-secondary" disabled>
                                        <i class="bi bi-eye"></i> View Details
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <i class="bi bi-inbox text-muted fs-1 mb-3"></i>
                                <p class="text-muted">No updates available.</p>
                                <p class="small text-muted">Updates will appear here as care notes and activities are logged.</p>
                            </div>
                        @endforelse

                        @if($updates->hasPages())
                            <div class="mt-4">
                                {{ $updates->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
