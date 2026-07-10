<section class="text-slate-300">
    <header class="mb-6">
        <h2 class="text-xl font-bold text-white flex items-center gap-2">
            <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
            </svg>
            {{ __('Update Password') }}
        </h2>
        <p class="mt-1 text-sm text-slate-400">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2" for="update_password_current_password">{{ __('Current Password') }}</label>
            <input id="update_password_current_password" name="current_password" type="password" class="w-full bg-slate-950/60 border border-slate-800 focus:border-indigo-500 rounded-xl px-4 py-3 text-white text-sm focus:ring-1 focus:ring-indigo-500 outline-none transition-all" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2 text-xs text-rose-400" />
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2" for="update_password_password">{{ __('New Password') }}</label>
            <input id="update_password_password" name="password" type="password" class="w-full bg-slate-950/60 border border-slate-800 focus:border-indigo-500 rounded-xl px-4 py-3 text-white text-sm focus:ring-1 focus:ring-indigo-500 outline-none transition-all" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2 text-xs text-rose-400" />
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2" for="update_password_password_confirmation">{{ __('Confirm Password') }}</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="w-full bg-slate-950/60 border border-slate-800 focus:border-indigo-500 rounded-xl px-4 py-3 text-white text-sm focus:ring-1 focus:ring-indigo-500 outline-none transition-all" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2 text-xs text-rose-400" />
        </div>

        <div class="flex items-center gap-4 pt-4 border-t border-slate-850">
            <x-skew-button type="submit" class="px-6 py-2.5 text-xs font-bold tracking-wider uppercase transition-all select-none">
                {{ __('Update Password') }}
            </x-skew-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-xs font-semibold text-emerald-400"
                >{{ __('Saved successfully.') }}</p>
            @endif
        </div>
    </form>
</section>
