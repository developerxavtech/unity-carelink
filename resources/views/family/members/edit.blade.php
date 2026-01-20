<x-app-layout>
    <x-slot name="header">
        Edit Family Collaborator
    </x-slot>

    <div class="row">
        <div class="col-lg-6 mx-auto">
            <div class="card dashboard-card">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">Edit: {{ $member->full_name }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('family.members.update', $member) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label">First Name *</label>
                                <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                                    id="first_name" name="first_name"
                                    value="{{ old('first_name', $member->first_name) }}" required>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label">Last Name *</label>
                                <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                    id="last_name" name="last_name" value="{{ old('last_name', $member->last_name) }}"
                                    required>
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address *</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                id="email" name="email" value="{{ old('email', $member->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                id="phone" name="phone" value="{{ old('phone', $member->phone) }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status"
                                name="status">
                                <option value="active" {{ $member->status == 'active' ? 'selected' : '' }}>Active
                                </option>
                                <option value="inactive" {{ $member->status == 'inactive' ? 'selected' : '' }}>Inactive
                                </option>
                                <option value="suspended" {{ $member->status == 'suspended' ? 'selected' : '' }}>
                                    Suspended</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Update Collaborator
                            </button>
                            <a href="{{ route('individuals.index') }}" class="btn btn-outline-secondary">
                                Cancel
                            </a>
                        </div>
                    </form>

                    <hr class="my-4">

                    <form action="{{ route('family.members.destroy', $member) }}" method="POST"
                        onsubmit="return confirm('Are you sure you want to remove this family member?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i class="bi bi-trash"></i> Remove From Family
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
