<x-guest-layout>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    
    <form method="POST" action="{{ route('login') }}" style="position: relative; z-index: 2;">
        @csrf

        <div class="mt-4">
            <label for="input_type" class="block font-medium text-sm text-gray-700" style="color: #182d3e; margin-bottom: 7px;">{{ __('اسم المستخدم') }}</label>
            <input id="input_type" type="text" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" name="input_type" value="{{ old('input_type') }}" required autocomplete="input_type" autofocus>
            @php
                $messages = $errors->get('username');
            @endphp
            @if ($messages)
            <ul class="text-sm text-red-600 space-y-1 mt-2">
                @foreach ((array) $messages as $message)
                    <li>{{ $message }}</li>
                @endforeach
            </ul>
            @endif
            @php
                $messages = $errors->get('email');
            @endphp
            @if ($messages)
            <ul class="text-sm text-red-600 space-y-1 mt-2">
                @foreach ((array) $messages as $message)
                    <li>{{ $message }}</li>
                @endforeach
            </ul>
            @endif
        </div>

        <!-- Email Address -->
        {{-- <div>
            <x-input-label for="email" :value="__('الايميل')" style="color: #182d3e; margin-bottom: 7px;"/>
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div> --}}

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('كلمة المرور')" style="color: #182d3e; margin-bottom: 7px;"/>

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2"/>
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <!-- <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600" style="color: #182d3e;">{{ __('تذكرني') }}</span>
            </label> -->
        </div>

        <div class="flex items-center justify-end mt-4 buttons-grouped">
            @if (Route::has('password.request'))
                {{-- <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('هل نسيت كلمة المرور؟') }}
                </a> --}}
            @endif

            <x-primary-button class="ms-3" style="border: none !important; outline: none !important; padding: 10px 20px !important;">
                {{ __('تسجيل الدخول') }}
            </x-primary-button>
                        <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600" style="color: #182d3e;">{{ __('تذكرني') }}</span>
            </label>
        </div>
    </form>
</x-guest-layout>