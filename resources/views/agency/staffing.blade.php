<x-app-layout>
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-1">Staffing Management</h2>
                        <p class="text-muted">Manage staff across all programs</p>
                    </div>
                    <button class="btn btn-primary" disabled>
                        <i class="bi bi-person-plus me-1"></i> Add Staff Member
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
                            <div class="col-md-3">
                                <label class="form-label small text-muted">Search Staff</label>
                                <input type="text" name="search" class="form-control"
                                       placeholder="Name or email..."
                                       value="{{ $filters['search'] ?? '' }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small text-muted">Role</label>
                                <select name="role" class="form-select">
                                    <option value="">All Roles</option>
                                    <option value="dsp" {{ ($filters['role'] ?? '') === 'dsp' ? 'selected' : '' }}>DSP</option>
                                    <option value="program_staff" {{ ($filters['role'] ?? '') === 'program_staff' ? 'selected' : '' }}>Program Staff</option>
                                    <option value="family_admin" {{ ($filters['role'] ?? '') === 'family_admin' ? 'selected' : '' }}>Family Admin</option>
                                    <option value="agency_admin" {{ ($filters['role'] ?? '') === 'agency_admin' ? 'selected' : '' }}>Agency Admin</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small text-muted">Program</label>
                                <select name="organization_id" class="form-select">
                                    <option value="">All Programs</option>
                                    @foreach($organizations as $org)
                                        <option value="{{ $org->id }}" {{ ($filters['organization_id'] ?? '') == $org->id ? 'selected' : '' }}>
                                            {{ $org->name }}
                                        </option>
                                    @endforeach
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

        <!-- Staff List -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0">
                            <i class="bi bi-people text-primary"></i> Staff Members ({{ $staff->total() }})
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role(s)</th>
                                        <th>Program(s)</th>
                                        <th>Status</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($staff as $member)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-2">
                                                        <i class="bi bi-person text-primary"></i>
                                                    </div>
                                                    <div>
                                                        <strong>{{ $member->first_name }} {{ $member->last_name }}</strong>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $member->email }}</td>
                                            <td>
                                                @foreach($member->roleAssignments->unique('role_type') as $assignment)
                                                    <span class="badge bg-info me-1">
                                                        {{ ucwords(str_replace('_', ' ', $assignment->role_type)) }}
                                                    </span>
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach($member->roleAssignments->unique('organization_id') as $assignment)
                                                    @if($assignment->organization)
                                                        <span class="badge bg-light text-dark me-1">
                                                            {{ $assignment->organization->name }}
                                                        </span>
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                <span class="badge bg-success">Active</span>
                                            </td>
                                            <td class="text-end">
                                                <div class="btn-group btn-group-sm">
                                                    <button class="btn btn-outline-secondary" disabled>
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                    <button class="btn btn-outline-primary" disabled>
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5">
                                                <i class="bi bi-people text-muted fs-1 mb-3"></i>
                                                <p class="text-muted">No staff members found.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if($staff->hasPages())
                            <div class="p-3">
                                {{ $staff->withQueryString()->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
