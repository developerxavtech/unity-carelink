<style>
    :root {
        --lavender-bg: #f3f0ff;
        --lavender-card: #ffffff;
        --lavender-border: #e5dbff;
        --lavender-primary: #2541e2;
        --lavender-muted: #868e96;
    }

    .section-card {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid var(--lavender-border);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        border-bottom: 1px solid var(--lavender-border);
        padding-bottom: 0.5rem;
    }

    .section-number {
        font-weight: bold;
        color: var(--lavender-primary);
        margin-right: 0.5rem;
    }

    .form-control-custom {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid var(--lavender-border);
        border-radius: 0.5rem;
        background-color: #f8f9fa;
    }

    .role-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .checkbox-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .footer-note {
        font-size: 0.875rem;
        color: var(--lavender-muted);
        margin-top: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
</style>

<div class="mt-6">
    <div class="p-4 sm:p-8" style="border-radius: 1.5rem;">
        <header>
            <h2 class="text-lg font-medium text-gray-900">
                {{ __('DSP Profile Information') }}
            </h2>
            <p class="mt-1 text-sm text-gray-600 mb-4">
                {{ __('This profile helps your care team provide clear, coordinated support.') }}
            </p>
        </header>

        <form action="{{ route('dsp.onboarding.update') }}" method="POST">
            @csrf

            <!-- 1. Basic Information -->
            <div class="section-card">
                <div class="section-header">
                    <div class="h5 mb-0">
                        <span class="section-number">1.</span> {{ __('Basic Information') }}
                    </div>
                </div>

                <p class="small text-muted mb-4">
                    {{ __('This helps people address you correctly and understand your role right away.') }}
                </p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block font-medium text-sm text-gray-700">{{ __('Full Name') }}</label>
                        <input type="text" class="form-control-custom" value="{{ auth()->user()->full_name }}"
                            readonly>
                    </div>

                    <div>
                        <label class="block font-medium text-sm text-gray-700">
                            {{ __('Preferred Name / Nickname') }}
                        </label>
                        <input type="text" name="preferred_name" class="form-control-custom" placeholder="Jess"
                            value="{{ old('preferred_name', $profile->preferred_name ?? '') }}">
                    </div>

                    <div>
                        <label class="block font-medium text-sm text-gray-700">
                            {{ __('Phone Number (for verification)') }}
                        </label>
                        <input type="text" name="phone" class="form-control-custom" placeholder="+1234567890"
                            value="{{ old('phone', auth()->user()->phone) }}">
                    </div>
                </div>

                <!-- Pronouns -->
                <div class="mt-4">
                    <label class="block font-medium text-sm text-gray-700 mb-2">
                        {{ __('Pronouns (optional)') }}
                    </label>

                    <div class="flex flex-wrap gap-4">
                        @foreach (['she/her', 'he/him', 'they/them', 'Prefer not to say'] as $pronoun)
                            <div class="flex items-center">
                                <input type="radio" name="pronouns" id="pronoun_{{ $loop->index }}"
                                    value="{{ $pronoun }}"
                                    class="rounded-full border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                    {{ old('pronouns', $profile->pronouns ?? '') == $pronoun ? 'checked' : '' }}>
                                <label for="pronoun_{{ $loop->index }}" class="ml-2 text-sm text-gray-600">
                                    {{ $pronoun }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Roles -->
                <div class="mt-4">
                    <label class="block font-medium text-sm text-gray-700 mb-2">
                        {{ __('Role on the Care Team') }}
                    </label>

                    <div class="role-grid">
                        @php
                            $roles = [
                                'Direct Support Professional (DSP)',
                                'Caregiver / Family Member',
                                'UCL Rides Driver',
                                'Coordinator / Case Manager',
                            ];
                            $userRoles = old('roles', $profile->roles ?? []);
                        @endphp

                        @foreach ($roles as $role)
                            <div class="checkbox-item">
                                <input type="checkbox" name="roles[]" id="role_{{ $loop->index }}"
                                    value="{{ $role }}"
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                    {{ in_array($role, $userRoles) ? 'checked' : '' }}>
                                <label for="role_{{ $loop->index }}" class="text-sm text-gray-600">
                                    {{ $role }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="footer-note">
                    <i class="bi bi-lock-fill"></i>
                    {{ __('Only visible to people you work with.') }}
                </div>
            </div>

            <!-- 2. Contact & Availability -->
            <div class="section-card">
                <div class="section-header">
                    <div class="h5 mb-0 font-bold">
                        <span class="section-number">2.</span>
                        {{ __('Contact & Availability') }}
                    </div>
                </div>

                <p class="small text-muted mb-4">
                    {{ __('This helps avoid missed messages and unnecessary interruptions.') }}
                </p>

                @php
                    $commPrefs = old(
                        'communication_preferences',
                        $profile->communication_preferences ?? ['In-app messaging'],
                    );
                @endphp

                <div class="flex flex-wrap gap-6 p-4 bg-gray-50 rounded border border-gray-200">
                    <div class="flex items-center">
                        <input type="checkbox" name="communication_preferences[]" value="In-app messaging"
                            id="comm_app"
                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                            {{ in_array('In-app messaging', $commPrefs) ? 'checked' : '' }}>
                        <label for="comm_app" class="ml-2 text-sm text-gray-600">
                            {{ __('In-app messaging') }}
                            <span class="text-indigo-600 text-xs">
                                ({{ __('recommended') }})
                            </span>
                        </label>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="communication_preferences[]" value="Phone" id="comm_phone"
                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                            {{ in_array('Phone', $commPrefs) ? 'checked' : '' }}>
                        <label for="comm_phone" class="ml-2 text-sm text-gray-600">
                            {{ __('Phone') }}
                            <span class="text-gray-400 text-xs">
                                ({{ __('optional') }})
                            </span>
                        </label>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="communication_preferences[]" value="Email" id="comm_email"
                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                            {{ in_array('Email', $commPrefs) ? 'checked' : '' }}>
                        <label for="comm_email" class="ml-2 text-sm text-gray-600">
                            {{ __('Email') }}
                        </label>
                    </div>
                </div>
            </div>

            <!-- 3. Experience & Strengths -->
            <div class="section-card">
                <div class="section-header">
                    <div class="h5 mb-0 font-bold">
                        <span class="section-number">3.</span>
                        {{ __('Experience & Strengths') }}
                        <span class="text-gray-400 text-sm">
                            ({{ __('Optional') }})
                        </span>
                    </div>
                </div>

                <textarea name="experience_strengths" class="form-control-custom" rows="3"
                    placeholder="{{ __('Tell us about your strengths...') }}">
{{ old('experience_strengths', $profile->experience_strengths ?? '') }}
</textarea>
            </div>

            <!-- 5. Boundaries & Expectations -->
            <div class="section-card">
                <div class="section-header">
                    <div class="h5 mb-0 font-bold">
                        <span class="section-number">5.</span>
                        {{ __('Boundaries & Expectations') }}
                        <span class="text-gray-400 text-sm">
                            ({{ __('Optional') }})
                        </span>
                    </div>
                </div>

                <textarea name="boundaries_expectations" class="form-control-custom" rows="3"
                    placeholder="{{ __('What are your expectations?') }}">
                            {{ old('boundaries_expectations', $profile->boundaries_expectations ?? '') }}
                            </textarea>
            </div>

            <!-- 6. Final Notes -->
            <div class="section-card">
                <div class="section-header">
                    <div class="h5 mb-0 font-bold">
                        <span class="section-number">6.</span>
                        {{ __('Final Notes') }}
                        <span class="text-gray-400 text-sm">
                            ({{ __('Optional') }})
                        </span>
                    </div>
                </div>

                <p class="small text-muted mb-2">
                    {{ __('Anything else that doesn\'t fit above but feels important.') }}
                </p>

                <textarea name="final_notes" class="form-control-custom" rows="3" placeholder="{{ __('Any final notes?') }}">
{{ old('final_notes', $profile->final_notes ?? '') }}
</textarea>
            </div>

            <!-- Submit -->
            <div class="flex items-center gap-4 mt-6">
                <button type="submit" class="btn btn-primary" style="background-color: var(--lavender-primary);">
                    {{ __('Update DSP Profile & Send Code') }}
                </button>

                @if (session('status') === 'profile-updated')
                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                        class="text-sm text-gray-600">
                        {{ __('Saved.') }}
                    </p>
                @endif
            </div>
        </form>
    </div>
</div>
