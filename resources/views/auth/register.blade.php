<x-guest-layout>
    <div class="mb-4 text-center">
        <h2 class="fw-bold text-dark tracking-tight">
            {{ __('Create an Account') }}
        </h2>
        <p class="text-muted">
            {{ __('Join Unity Carelink and start managing care today.') }}
        </p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <div class="row g-3 mb-3">
            <!-- First Name -->
            <div class="col-md-6">
                <x-input-label for="first_name" :value="__('First Name')" />
                <x-text-input id="first_name" type="text" name="first_name" :value="old('first_name')" required autofocus
                    autocomplete="given-name" />
                <x-input-error :messages="$errors->get('first_name')" />
            </div>

            <!-- Last Name -->
            <div class="col-md-6">
                <x-input-label for="last_name" :value="__('Last Name')" />
                <x-text-input id="last_name" type="text" name="last_name" :value="old('last_name')" required
                    autocomplete="family-name" />
                <x-input-error :messages="$errors->get('last_name')" />
            </div>
        </div>

        <!-- Email Address -->
        <div class="mb-3">
            <x-input-label for="email" :value="__('Email Address')" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required
                autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <!-- Role Selection -->
        <div class="mb-3">
            <x-input-label for="role" :value="__('Register As')" />
            <select id="role" name="role" class="form-select shadow-sm" required>
                <option value="" disabled selected>{{ __('Select your role') }}</option>
                <option value="family_admin" {{ old('role') == 'family_admin' ? 'selected' : '' }}>
                    {{ __('Family Admin') }}</option>
                <option value="family_member" {{ old('role') == 'family_member' ? 'selected' : '' }}>
                    {{ __('Family Member') }}</option>
                <option value="dsp" {{ old('role') == 'dsp' ? 'selected' : '' }}>
                    {{ __('Direct Support Professional (DSP)') }}</option>
                <option value="agency_admin" {{ old('role') == 'agency_admin' ? 'selected' : '' }}>
                    {{ __('Agency Admin') }}</option>
                <option value="program_staff" {{ old('role') == 'program_staff' ? 'selected' : '' }}>
                    {{ __('Program Staff') }}</option>
                <option value="super_admin" {{ old('role') == 'super_admin' ? 'selected' : '' }}>
                    {{ __('Super Admin') }}</option>
            </select>
            <x-input-error :messages="$errors->get('role')" />
        </div>

        <div class="row g-3 mb-4">
            <!-- Password -->
            <div class="col-md-6">
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" type="password" name="password" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" />
            </div>

            <!-- Confirm Password -->
            <div class="col-md-6">
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                <x-text-input id="password_confirmation" type="password" name="password_confirmation" required
                    autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" />
            </div>
        </div>

        <div class="d-grid gap-3">
            <x-primary-button class="py-2 fs-5 fw-semibold shadow-sm transition-all">
                {{ __('Create Account') }}
            </x-primary-button>

            <div class="text-center">
                <a class="text-decoration-none text-muted small hover-primary" href="{{ route('login') }}">
                    {{ __('Already have an account? Sign in') }}
                </a>
            </div>
        </div>
    </form>

    <style>
        .hover-primary:hover {
            color: var(--bs-primary) !important;
        }

        .transition-all {
            transition: all 0.2s ease-in-out;
        }

        .transition-all:hover {
            transform: translateY(-1px);
        }
    </style>
</x-guest-layout>
