<x-app-layout>
    <x-slot name="header">
        Family Members
    </x-slot>

    {{-- <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h4>My Family Members</h4>
                <a href="{{ route('individuals.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Add Family Member
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
                            <p class="mb-0">{{ Str::limit($individual->strengths_abilities ?? 'Not specified', 80) }}
                            </p>
                        </div>

                        <div class="d-flex gap-2">
                            <a href="{{ route('individuals.show', $individual) }}"
                                class="btn btn-sm btn-primary flex-fill">
                                <i class="bi bi-eye"></i> View Profile
                            </a>
                            <a href="{{ route('individuals.edit', $individual) }}"
                                class="btn btn-sm btn-outline-primary">
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
                        <h5 class="text-muted">No Family Members (Care Profiles) Yet</h5>
                        <p class="text-muted mb-4">
                            Get started by creating a profile for a family member who needs care.
                        </p>
                        <a href="{{ route('individuals.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Create First Profile
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div> --}}

    @if ($familyUsers && $familyUsers->count() > 0)
        <hr class="my-5">

        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4>Care Team & Collaborators</h4>
                        <p class="text-muted mb-0">Family members who collaborate in care coordination.</p>
                    </div>
                    @if (auth()->user()->hasRole('family_admin'))
                        <a href="{{ route('family.members.create') }}" class="btn btn-outline-primary">
                            <i class="bi bi-person-plus"></i> Add Collaborator
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="row">
            @foreach ($familyUsers as $fUser)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card dashboard-card h-100 border-start border-4 border-info">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar me-3">
                                    <i class="bi bi-person-circle fs-2 text-info"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <h5 class="mb-0">{{ $fUser->first_name }} {{ $fUser->last_name }}</h5>
                                        @if (auth()->user()->hasRole('family_admin'))
                                            <a href="{{ route('family.members.edit', $fUser) }}" class="text-muted">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                        @endif
                                    </div>
                                    <small
                                        class="badge bg-info text-white">{{ ucfirst(str_replace('_', ' ', $fUser->getRoleNames()->first() ?? 'Member')) }}</small>
                                </div>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block"><i class="bi bi-envelope me-1"></i> Email</small>
                                <p class="mb-1">{{ $fUser->email }}</p>
                                @if ($fUser->phone)
                                    <small class="text-muted d-block"><i class="bi bi-telephone me-1"></i> Phone</small>
                                    <p class="mb-0">{{ $fUser->phone }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        @if (auth()->user()->hasRole('family_admin'))
            <hr class="my-5">
            <div class="card dashboard-card border-dashed border-2 text-center py-5">
                <div class="card-body">
                    <i class="bi bi-people fs-1 text-muted mb-3"></i>
                    <h5>No Collaborators Yet</h5>
                    <p class="text-muted mb-4">Add family members to help you coordinate care for your loved ones.</p>
                    <a href="{{ route('family.members.create') }}" class="btn btn-primary">
                        <i class="bi bi-person-plus"></i> Add First Collaborator
                    </a>
                </div>
            </div>
        @endif
    @endif
</x-app-layout>
