<x-guest-layout>
    <style>
        /* Mobile Responsive Styles */
        @media (max-width: 768px) {
            .mb-4 {
                margin-bottom: 1rem;
            }
            
            .mt-4 {
                margin-top: 1rem;
            }
            
            .mt-2 {
                margin-top: 0.5rem;
            }
            
            .block {
                width: 100%;
            }
            
            /* Text adjustments */
            .text-sm {
                font-size: 14px;
            }
            
            /* Stack elements vertically */
            .flex.justify-end {
                flex-direction: column;
                gap: 1rem;
                align-items: stretch;
            }
            
            .flex.justify-end > * {
                width: 100%;
                text-align: center;
            }
        }
        
        /* Extra small devices */
        @media (max-width: 576px) {
            .mb-4 {
                margin-bottom: 0.75rem;
            }
            
            .mt-4 {
                margin-top: 0.75rem;
            }
            
            .mt-2 {
                margin-top: 0.375rem;
            }
            
            .text-sm {
                font-size: 13px;
            }
            
            .flex.justify-end {
                gap: 0.75rem;
            }
        }
    </style>

    <div class="mb-4 text-sm text-gray-600">
        {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex justify-end mt-4">
            <x-primary-button>
                {{ __('Confirm') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
