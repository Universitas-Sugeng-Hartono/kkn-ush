<x-guest-layout>
    <style>
        .logo-container {
            margin-bottom: 1.5rem;
        }
        
        .logo-container img {
            max-height: 80px;
            width: auto;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
            transition: transform 0.3s ease;
        }
        
        .logo-container img:hover {
            transform: scale(1.05);
        }
        
        .login-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .login-card .card-body {
            padding: 3rem;
        }
        
        .university-name {
            color: #6c757d;
            font-size: 14px;
            margin-top: 0.5rem;
        }

        /* Mobile Responsive Styles */
        @media (max-width: 768px) {
            .container {
                padding: 0 10px;
            }
            
            .min-vh-100 {
                min-height: 100vh;
                padding: 1rem 0;
            }
            
            .login-card .card-body {
                padding: 2rem;
            }
            
            /* Logo adjustments */
            .logo-container img {
                max-height: 60px;
            }
            
            /* Text adjustments */
            .text-muted {
                font-size: 14px;
            }
            
            /* Form adjustments */
            .form-label {
                font-size: 14px;
            }
            
            .form-control {
                font-size: 14px;
                padding: 8px 12px;
            }
            
            .form-text {
                font-size: 11px !important;
            }
            
            /* Button adjustments */
            .btn {
                font-size: 14px;
                padding: 10px 16px;
            }
            
            /* Checkbox adjustments */
            .form-check-label {
                font-size: 14px;
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
            
            .login-card .card-body {
                padding: 1.5rem;
            }
            
            .logo-container img {
                max-height: 50px;
            }
            
            .text-muted {
                font-size: 13px;
            }
            
            .form-label {
                font-size: 13px;
            }
            
            .form-control {
                font-size: 13px;
                padding: 6px 10px;
            }
            
            .form-text {
                font-size: 10px !important;
            }
            
            .btn {
                font-size: 13px;
                padding: 8px 12px;
            }
            
            .form-check-label {
                font-size: 13px;
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
                <div class="card shadow login-card">
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <div class="logo-container">
                                <img src="{{ asset('images/logo.png') }}" 
                                     alt="Logo Universitas Sugeng Hartono" 
                                     class="img-fluid">
                            </div>
                            <p class="text-muted">Masuk ke Sistem Logbook KKN</p>
                        </div>

                        <!-- Session Status -->
                        <x-auth-session-status class="mb-4" :status="session('status')" />

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                                                          <!-- Email, NIM, atau NIP -->
                              <div class="mb-3">
                                  <label for="login" class="form-label">Email, NIM, atau NIP</label>
                                  <input type="text" class="form-control @error('login') is-invalid @enderror" 
                                         id="login" name="login" value="{{ old('login') }}" 
                                         required autofocus autocomplete="username"
                                         placeholder="Masukkan email, NIM, atau NIP">
                                  <center><div class="form-text" style="font-size: 12px;">Anda bisa login menggunakan email, NIM (mahasiswa), atau NIP (dosen)</div></center>
                                @error('login')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" required autocomplete="current-password">
                                @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Remember Me -->
                            <div class="mb-3">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
                                    <label class="form-check-label" for="remember_me">Ingat Saya</label>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Login</button>
                            </div>

                            <!-- <div class="text-center mt-4">
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-decoration-none">
                                        Lupa Password?
                                    </a>
                                @endif
                            </div> -->
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
