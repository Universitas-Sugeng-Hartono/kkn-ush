<x-guest-layout>
    <style>
        /* Mobile Responsive Styles */
        @media (max-width: 768px) {
            .block {
                width: 100%;
            }
            
            /* Form adjustments */
            .mt-4 {
                margin-top: 1rem;
            }
            
            .mt-2 {
                margin-top: 0.5rem;
            }
            
            /* Stack elements vertically */
            .flex.items-center.justify-end {
                flex-direction: column;
                gap: 1rem;
                align-items: stretch;
            }
            
            .flex.items-center.justify-end > * {
                width: 100%;
                text-align: center;
            }
        }
        
        /* Extra small devices */
        @media (max-width: 576px) {
            .mt-4 {
                margin-top: 0.75rem;
            }
            
            .mt-2 {
                margin-top: 0.375rem;
            }
            
            .flex.items-center.justify-end {
                gap: 0.75rem;
            }
        }
    </style>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Reset Password') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
