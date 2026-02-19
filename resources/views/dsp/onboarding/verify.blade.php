<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold mb-0">
            {{ __('Verify Your Profile') }}
        </h2>
    </x-slot>

    <style>
        .verify-container {
            max-width: 500px;
            margin: 4rem auto;
            background-color: #f3f0ff;
            padding: 3rem;
            border-radius: 1.5rem;
            text-align: center;
        }

        .code-input {
            letter-spacing: 0.5rem;
            font-size: 2rem;
            text-align: center;
            border-radius: 1rem;
            border: 2px solid #e5dbff;
            margin-bottom: 2rem;
        }
    </style>

    <div class="verify-container shadow-sm">
        <div class="mb-4">
            <i class="bi bi-shield-check text-primary" style="font-size: 4rem;"></i>
        </div>
        <h4>Security Verification</h4>
        <p class="text-muted mb-4">We've sent a 6-digit verification code to your phone. Please enter it below to verify
            your DSP profile.</p>

        <form action="{{ route('dsp.onboarding.verify.check') }}" method="POST">
            @csrf
            <div class="mb-3">
                <input type="text" name="code" class="form-control code-input" maxlength="6" placeholder="000000"
                    required autofocus>
            </div>

            <button type="submit" class="btn btn-primary btn-lg w-100 mb-3"
                style="background-color: #7048e8; border: none; border-radius: 2rem;">
                Verify & Complete Setup
            </button>
        </form>

        <form action="{{ route('dsp.onboarding.update') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-link text-muted small">Didn't receive a code? Resend</button>
        </form>
    </div>
</x-app-layout>
