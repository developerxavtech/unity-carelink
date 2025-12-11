<x-app-layout>
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="mb-1">My Participants</h2>
                <p class="text-muted">Individuals I support ({{ $individuals->count() }})</p>
            </div>
        </div>

        <div class="row g-4">
            @forelse($individuals as $individual)
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                                    <i class="bi bi-person text-primary fs-3"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0">{{ $individual->first_name }} {{ $individual->last_name }}</h5>
                                    <small class="text-muted">
                                        Age {{ $individual->date_of_birth ? \Carbon\Carbon::parse($individual->date_of_birth)->age : 'N/A' }}
                                    </small>
                                </div>
                            </div>

                            @if($individual->communication_style)
                                <div class="mb-3">
                                    <h6 class="small text-muted mb-1">Communication Style</h6>
                                    <p class="small mb-0">{{ Str::limit($individual->communication_style, 100) }}</p>
                                </div>
                            @endif

                            @if($individual->strengths)
                                <div class="mb-3">
                                    <h6 class="small text-muted mb-1">Strengths</h6>
                                    <p class="small mb-0">{{ Str::limit($individual->strengths, 100) }}</p>
                                </div>
                            @endif

                            @if($individual->preferences)
                                <div class="mb-3">
                                    <h6 class="small text-muted mb-1">Preferences</h6>
                                    <p class="small mb-0">{{ Str::limit($individual->preferences, 100) }}</p>
                                </div>
                            @endif

                            <div class="d-flex gap-2 mt-3">
                                <a href="{{ route('individuals.show', $individual) }}" class="btn btn-sm btn-primary flex-grow-1">
                                    <i class="bi bi-eye me-1"></i> View Profile
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center py-5">
                            <i class="bi bi-people text-muted" style="font-size: 4rem;"></i>
                            <h5 class="mt-3 mb-2">No Participants Assigned</h5>
                            <p class="text-muted mb-0">You haven't been assigned to any participants yet. Contact your supervisor for assignments.</p>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
