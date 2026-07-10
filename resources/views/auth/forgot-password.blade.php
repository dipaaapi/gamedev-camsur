<x-guest-layout>
    <div class="w-full max-w-md mx-auto glass-panel sm:rounded-2xl overflow-hidden p-8 md:p-10 relative">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-white mb-2">Account Recovery</h2>
            @if($verified)
                <p class="text-emerald-400 text-sm font-semibold">Identity Verified! Update your details below.</p>
            @else
                <p class="text-slate-400 text-sm">Verify your details to update your credentials directly.</p>
            @endif
        </div>

        <x-auth-session-status class="mb-4 text-emerald-400 text-sm font-medium" :status="session('status')" />

        @if($errors->has('identity'))
            <div class="mb-4 p-3 bg-rose-950/30 border border-rose-900/50 rounded-xl text-rose-400 text-sm text-center">
                {{ $errors->first('identity') }}
            </div>
        @endif

        @if(!$verified)
            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                
                <div>
                    <label for="name" class="block font-medium text-sm text-slate-300 mb-2">{{ __('Full Name') }}</label>
                    <input id="name" class="block w-full px-4 py-3 bg-slate-800/50 border border-slate-700/50 focus:border-brand focus:ring focus:ring-brand/20 rounded-xl text-white placeholder-slate-500 transition-colors" type="text" name="name" :value="old('name')" required autofocus placeholder="John Doe" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2 text-rose-500 text-sm" />
                </div>

                <div class="mt-6">
                    <label for="email_or_mobile" class="block font-medium text-sm text-slate-300 mb-2">{{ __('Email Address or Mobile Number') }}</label>
                    <input id="email_or_mobile" class="block w-full px-4 py-3 bg-slate-800/50 border border-slate-700/50 focus:border-brand focus:ring focus:ring-brand/20 rounded-xl text-white placeholder-slate-500 transition-colors" type="text" name="email_or_mobile" :value="old('email_or_mobile')" required placeholder="developer@example.com or 09123456789" />
                    <x-input-error :messages="$errors->get('email_or_mobile')" class="mt-2 text-rose-500 text-sm" />
                </div>

                <div class="mt-8 flex flex-col gap-4">
                    <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-gradient-to-r from-brand-light to-purple-500 hover:shadow-[0_0_20px_rgba(99,102,241,0.4)] transition-all duration-300 cursor-pointer">
                        {{ __('Verify Identity') }}
                    </button>
                    <a href="{{ route('login') }}" class="text-sm font-medium text-slate-400 hover:text-white transition-colors text-center">
                        Back to Login
                    </a>
                </div>
            </form>
        @else
            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <input type="hidden" name="update_credentials" value="1">

                <div>
                    <label for="username" class="block font-medium text-sm text-slate-300 mb-2">{{ __('Username') }}</label>
                    <input id="username" class="block w-full px-4 py-3 bg-slate-800/50 border border-slate-700/50 focus:border-brand focus:ring focus:ring-brand/20 rounded-xl text-white placeholder-slate-500 transition-colors" type="text" name="username" value="{{ old('username', $user->username) }}" required autocomplete="username" />
                    <x-input-error :messages="$errors->get('username')" class="mt-2 text-rose-500 text-sm" />
                </div>

                <div class="mt-6">
                    <label for="email" class="block font-medium text-sm text-slate-300 mb-2">{{ __('Email Address (Optional)') }}</label>
                    <input id="email" class="block w-full px-4 py-3 bg-slate-800/50 border border-slate-700/50 focus:border-brand focus:ring focus:ring-brand/20 rounded-xl text-white placeholder-slate-500 transition-colors" type="email" name="email" value="{{ old('email', $user->email) }}" autocomplete="email" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-rose-500 text-sm" />
                </div>

                <div class="mt-6">
                    <label for="mobile_number" class="block font-medium text-sm text-slate-300 mb-2">{{ __('Mobile Number (Optional)') }}</label>
                    <input id="mobile_number" class="block w-full px-4 py-3 bg-slate-800/50 border border-slate-700/50 focus:border-brand focus:ring focus:ring-brand/20 rounded-xl text-white placeholder-slate-500 transition-colors" type="text" name="mobile_number" value="{{ old('mobile_number', $user->mobile_number) }}" autocomplete="tel" />
                    <x-input-error :messages="$errors->get('mobile_number')" class="mt-2 text-rose-500 text-sm" />
                </div>

                <div class="mt-6">
                    <label for="password" class="block font-medium text-sm text-slate-300 mb-2">{{ __('New Password (Leave blank to keep current)') }}</label>
                    <input id="password" class="block w-full px-4 py-3 bg-slate-800/50 border border-slate-700/50 focus:border-brand focus:ring focus:ring-brand/20 rounded-xl text-white placeholder-slate-500 transition-colors" type="password" name="password" autocomplete="new-password" placeholder="••••••••" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-rose-500 text-sm" />
                </div>

                <div class="mt-6">
                    <label for="password_confirmation" class="block font-medium text-sm text-slate-300 mb-2">{{ __('Confirm New Password') }}</label>
                    <input id="password_confirmation" class="block w-full px-4 py-3 bg-slate-800/50 border border-slate-700/50 focus:border-brand focus:ring focus:ring-brand/20 rounded-xl text-white placeholder-slate-500 transition-colors" type="password" name="password_confirmation" autocomplete="new-password" placeholder="••••••••" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-rose-500 text-sm" />
                </div>

                <div class="mt-8 flex flex-col gap-4">
                    <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-gradient-to-r from-brand-light to-purple-500 hover:shadow-[0_0_20px_rgba(99,102,241,0.4)] transition-all duration-300 cursor-pointer">
                        {{ __('Update Credentials') }}
                    </button>
                    
                    <button type="submit" name="reset_flow" value="1" formnovalidate class="text-sm font-medium text-rose-400 hover:text-rose-300 transition-colors text-center cursor-pointer bg-transparent border-none">
                        Cancel & Start Over
                    </button>
                </div>
            </form>
        @endif
    </div>
</x-guest-layout>
