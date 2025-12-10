<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- Login Header -->
    <div class="text-center mb-4">
        <h4 class="fw-bold mb-2 text-primary">
            Welcome Back
        </h4>
        <p class="text-muted small">Sign in to continue to Unity CareLink</p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label fw-semibold text-dark">Email Address</label>
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0">
                    <i class="bi bi-envelope text-primary"></i>
                </span>
                <input id="email" type="email" class="form-control border-start-0 @error('email') is-invalid @enderror"
                    name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                    placeholder="Enter your email">
            </div>
            @error('email')
                <div class="invalid-feedback d-block">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label fw-semibold text-dark">Password</label>
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0">
                    <i class="bi bi-lock text-primary"></i>
                </span>
                <input id="password" type="password"
                    class="form-control border-start-0 @error('password') is-invalid @enderror" name="password" required
                    autocomplete="current-password" placeholder="Enter your password">
            </div>
            @error('password')
                <div class="invalid-feedback d-block">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="form-check">
                <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                <label class="form-check-label text-muted small" for="remember_me">
                    Remember me
                </label>
            </div>

            @if (Route::has('password.request'))
                <a class="text-decoration-none small text-primary" href="{{ route('password.request') }}">
                    Forgot password?
                </a>
            @endif
        </div>

        <!-- Login Button -->
        <div class="d-grid mb-3">
            <button type="submit" class="btn btn-primary btn-lg fw-semibold shadow-sm"
                style="transition: all 0.3s ease;"
                onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(13, 110, 253, 0.3)';"
                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(13, 110, 253, 0.15)';">
                <i class="bi bi-box-arrow-in-right me-2"></i>
                Sign In
            </button>
        </div>

        <!-- Register Link -->
        @if (Route::has('register'))
            <div class="text-center">
                <p class="text-muted small mb-0">
                    Don't have an account?
                    <a href="{{ route('register') }}" class="text-decoration-none fw-semibold text-primary">
                        Create Account
                    </a>
                </p>
            </div>
        @endif
    </form>

    <style>
        /* Enhanced input focus states with blue theme */
        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
        }

        .input-group-text {
            transition: all 0.3s ease;
        }

        .form-control:focus+.input-group-text,
        .input-group:focus-within .input-group-text {
            border-color: #0d6efd;
            background-color: rgba(13, 110, 253, 0.05);
        }

        .form-check-input:checked {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        .form-check-input:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
        }

        /* Clean input styling */
        .input-group-text {
            background-color: white;
            border-color: #dee2e6;
        }

        .form-control {
            border-color: #dee2e6;
        }

        .form-control::placeholder {
            color: #adb5bd;
        }
    </style>
</x-guest-layout>