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
            
            /* Text adjustments */
            .text-sm {
                font-size: 14px;
            }
            
            /* Button adjustments */
            .ms-4 {
                margin-left: 1rem;
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
            
            .text-sm {
                font-size: 13px;
            }
            
            .ms-4 {
                margin-left: 0.75rem;
            }
            
            .flex.items-center.justify-end {
                gap: 0.75rem;
            }
        }
    </style>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

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
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
