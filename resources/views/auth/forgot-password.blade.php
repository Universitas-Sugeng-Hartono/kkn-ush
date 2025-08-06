<x-guest-layout>
    <style>
        /* Mobile Responsive Styles */
        @media (max-width: 768px) {
            .container {
                padding: 0 10px;
            }
            
            .min-vh-100 {
                min-height: 100vh;
                padding: 1rem 0;
            }
            
            .card-body {
                padding: 2rem !important;
            }
            
            /* Text adjustments */
            h4.fw-bold {
                font-size: 1.3rem;
            }
            
            .form-label {
                font-size: 14px;
            }
            
            .form-control {
                font-size: 14px;
                padding: 8px 12px;
            }
            
            /* Button adjustments */
            .btn {
                font-size: 14px;
                padding: 10px 16px;
            }
        }
        
        /* Extra small devices */
        @media (max-width: 576px) {
            .container {
                padding: 0 5px;
            }
            
            .min-vh-100 {
                padding: 0.5rem 0;
            }
            
            .card-body {
                padding: 1.5rem !important;
            }
            
            h4.fw-bold {
                font-size: 1.1rem;
            }
            
            .form-label {
                font-size: 13px;
            }
            
            .form-control {
                font-size: 13px;
                padding: 6px 10px;
            }
            
            .btn {
                font-size: 13px;
                padding: 8px 12px;
            }
            
            /* Stack elements vertically */
            .row {
                margin: 0;
            }
            
            .col-md-6.col-lg-5 {
                padding: 0;
            }
        }
    </style>

    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <h4 class="fw-bold">Lupa Password</h4>
                        </div>

                        <!-- Session Status -->
                        <x-auth-session-status class="mb-4" :status="session('status')" />

                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf

                            <!-- Email Address -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">{{ __('Email Password Reset Link') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>

