<x-guest-layout>
    <div class="w-full max-w-md mx-auto glass-panel sm:rounded-2xl overflow-hidden p-8 md:p-10 relative">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-white mb-2">Welcome Back</h2>
            <p class="text-slate-400 text-sm">Log in to your account</p>
        </div>

        <x-auth-session-status class="mb-4 text-emerald-400 text-sm font-medium" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div>
                <label for="login_username" class="block font-medium text-sm text-slate-300 mb-2">{{ __('Username') }}</label>
                <input id="login_username" class="block w-full px-4 py-3 bg-slate-800/50 border border-slate-700/50 focus:border-brand focus:ring focus:ring-brand/20 rounded-xl text-white placeholder-slate-500 transition-colors" type="text" name="username" :value="old('username')" required autofocus autocomplete="username" placeholder="developer" />
                <x-input-error :messages="$errors->get('username')" class="mt-2 text-rose-500 text-sm" />
            </div>

            <div class="mt-6">
                <label for="login_password" class="block font-medium text-sm text-slate-300 mb-2">{{ __('Password') }}</label>
                <input id="login_password" class="block w-full px-4 py-3 bg-slate-800/50 border border-slate-700/50 focus:border-brand focus:ring focus:ring-brand/20 rounded-xl text-white placeholder-slate-500 transition-colors" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-rose-500 text-sm" />
            </div>

            <div class="flex items-center justify-between mt-6">
                <label for="remember_me" class="inline-flex items-center cursor-pointer">
                    <input id="remember_me" type="checkbox" class="rounded bg-slate-800 border-slate-700 text-brand focus:ring-brand/50 w-4 h-4 transition-colors" name="remember">
                    <span class="ms-2 text-sm text-slate-400">{{ __('Remember me') }}</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="text-sm font-medium text-brand-light hover:text-white transition-colors" href="{{ route('password.request') }}">
                        {{ __('Forgot password?') }}
                    </a>
                @endif
            </div>

            <div class="mt-8">
                <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-gradient-to-r from-brand-light to-purple-500 hover:shadow-[0_0_20px_rgba(99,102,241,0.4)] transition-all duration-300 cursor-pointer">
                    {{ __('Log in') }}
                </button>
            </div>

            <div class="mt-6 text-center text-sm text-slate-400">
                {{ __("Don't have an account?") }}
                <a class="font-medium text-brand-light hover:text-white transition-colors ml-1" href="{{ route('register') }}">
                    {{ __('Sign up') }}
                </a>
            </div>
        </form>
    </div>
</x-guest-layout>
