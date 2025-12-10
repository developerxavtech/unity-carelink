<x-app-layout>
    <x-slot name="header">
        My Loved Ones
    </x-slot>

    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h4>Individual Profiles</h4>
                <a href="{{ route('individuals.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Add Individual
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        @forelse($individuals as $individual)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card dashboard-card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h5 class="mb-1">{{ $individual->first_name }} {{ $individual->last_name }}</h5>
                                <small class="text-muted">{{ $individual->pronouns }}</small>
                            </div>
                            <span class="badge bg-success">Active</span>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted d-block mb-1">Age</small>
                            <p class="mb-2">{{ $individual->age }} years old</p>

                            <small class="text-muted d-block mb-1">Strengths</small>
                            <p class="mb-0">{{ Str::limit($individual->strengths_abilities ?? 'Not specified', 80) }}</p>
                        </div>

                        <div class="d-flex gap-2">
                            <a href="{{ route('individuals.show', $individual) }}" class="btn btn-sm btn-primary flex-fill">
                                <i class="bi bi-eye"></i> View
                            </a>
                            <a href="{{ route('individuals.edit', $individual) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card dashboard-card">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-person-plus fs-1 text-muted mb-3"></i>
                        <h5 class="text-muted">No Individual Profiles Yet</h5>
                        <p class="text-muted mb-4">
                            Get started by creating a profile for your loved one.
                        </p>
                        <a href="{{ route('individuals.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Create First Profile
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
</x-app-layout>
