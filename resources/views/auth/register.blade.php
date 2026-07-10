<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white leading-tight">
            {{ isset($user) ? __('Edit User Account') : __('Create User Account') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-slate-800/30 border border-slate-700/50 backdrop-blur-sm overflow-hidden shadow-[0_0_30px_rgba(0,0,0,0.2)] sm:rounded-2xl p-8">
                <div class="mb-6">
                    <h3 class="text-lg font-bold text-white mb-1">
                        {{ isset($user) ? __('Modify Account Details') : __('Create New Account') }}
                    </h3>
                    <p class="text-slate-400 text-xs">
                        {{ isset($user) ? __('Update the user profile and credentials below.') : __('Fill in user details to create a new credential.') }}
                    </p>
                </div>

                <form method="POST" action="{{ isset($user) ? route('users.update', $user) : route('register') }}" class="space-y-4" x-data="{ role: {{ old('users_level', isset($user) ? $user->users_level : 3) }} }">
                    @csrf
                    @if(isset($user))
                        @method('PATCH')
                    @endif
                    
                    <div>
                        <label for="name" class="block font-medium text-xs text-slate-300 uppercase tracking-wider mb-2">{{ __('Name') }}</label>
                        <input id="name" class="block w-full px-4 py-2.5 bg-slate-950 border border-slate-800 focus:border-indigo-500 rounded-xl text-white placeholder-slate-500 transition-colors focus:outline-none text-sm" type="text" name="name" value="{{ old('name', isset($user) ? $user->name : '') }}" required autofocus autocomplete="name" placeholder="John Doe" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2 text-rose-500 text-xs" />
                    </div>

                    <div>
                        <label for="register_username" class="block font-medium text-xs text-slate-300 uppercase tracking-wider mb-2">{{ __('Username') }}</label>
                        <input id="register_username" class="block w-full px-4 py-2.5 bg-slate-950 border border-slate-800 focus:border-indigo-500 rounded-xl text-white placeholder-slate-500 transition-colors focus:outline-none text-sm" type="text" name="username" value="{{ old('username', isset($user) ? $user->username : '') }}" required autocomplete="username" placeholder="john_doe" />
                        <x-input-error :messages="$errors->get('username')" class="mt-2 text-rose-500 text-xs" />
                    </div>

                    <div>
                        <label for="register_email" class="block font-medium text-xs text-slate-300 uppercase tracking-wider mb-2">{{ __('Email (Optional)') }}</label>
                        <input id="register_email" class="block w-full px-4 py-2.5 bg-slate-950 border border-slate-800 focus:border-indigo-500 rounded-xl text-white placeholder-slate-500 transition-colors focus:outline-none text-sm" type="email" name="email" value="{{ old('email', isset($user) ? $user->email : '') }}" autocomplete="email" placeholder="john@example.com" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-rose-500 text-xs" />
                    </div>

                    <div>
                        <label for="register_mobile_number" class="block font-medium text-xs text-slate-300 uppercase tracking-wider mb-2">{{ __('Mobile Number (Optional)') }}</label>
                        <input id="register_mobile_number" class="block w-full px-4 py-2.5 bg-slate-950 border border-slate-800 focus:border-indigo-500 rounded-xl text-white placeholder-slate-500 transition-colors focus:outline-none text-sm" type="text" name="mobile_number" value="{{ old('mobile_number', isset($user) ? $user->mobile_number : '') }}" autocomplete="tel" placeholder="09123456789" />
                        <x-input-error :messages="$errors->get('mobile_number')" class="mt-2 text-rose-500 text-xs" />
                    </div>

                    <div>
                        <label for="register_password" class="block font-medium text-xs text-slate-300 uppercase tracking-wider mb-2">
                            {{ __('Password') }} {{ isset($user) ? __('(leave blank to keep current)') : '' }}
                        </label>
                        <input id="register_password" class="block w-full px-4 py-2.5 bg-slate-950 border border-slate-800 focus:border-indigo-500 rounded-xl text-white placeholder-slate-500 transition-colors focus:outline-none text-sm" type="password" name="password" {{ isset($user) ? '' : 'required' }} autocomplete="new-password" placeholder="••••••••" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-rose-500 text-xs" />
                    </div>

                    <div>
                        <label for="password_confirmation" class="block font-medium text-xs text-slate-300 uppercase tracking-wider mb-2">
                            {{ __('Confirm Password') }} {{ isset($user) ? __('(leave blank to keep current)') : '' }}
                        </label>
                        <input id="password_confirmation" class="block w-full px-4 py-2.5 bg-slate-950 border border-slate-800 focus:border-indigo-500 rounded-xl text-white placeholder-slate-500 transition-colors focus:outline-none text-sm" type="password" name="password_confirmation" {{ isset($user) ? '' : 'required' }} autocomplete="new-password" placeholder="••••••••" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-rose-500 text-xs" />
                    </div>

                    <div>
                        <label for="users_level" class="block font-medium text-xs text-slate-300 uppercase tracking-wider mb-2">{{ __('Role / User Level') }}</label>
                        <select id="users_level" name="users_level" x-model="role" required class="block w-full px-4 py-2.5 bg-slate-950 border border-slate-800 focus:border-indigo-500 rounded-xl text-white placeholder-slate-500 transition-colors focus:outline-none text-sm">
                            @if(auth()->user()->isDeveloper())
                            <option value="1">School Admin</option>
                            @endif
                            <option value="2">Teacher</option>
                            <option value="3">Student</option>
                            <option value="4">Parent/Guardian</option>
                        </select>
                        <x-input-error :messages="$errors->get('users_level')" class="mt-2 text-rose-500 text-xs" />
                    </div>

                    <div x-show="role == 2" class="border-t border-slate-800/80 pt-4 mt-2" style="display: none;">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="can_delete_users" value="1" {{ old('can_delete_users', isset($user) && $user->can_delete_users) ? 'checked' : '' }} class="rounded bg-slate-950 border-slate-800 text-indigo-500 focus:ring-indigo-500">
                            <span class="text-xs font-bold text-slate-300 uppercase tracking-wider">Grant User Deletion Privileges</span>
                        </label>
                    </div>

                    <div class="flex items-center justify-between pt-4 mt-4 border-t border-slate-800/60">
                        <a class="text-xs text-slate-500 hover:text-white transition-colors" href="{{ route('users.index') }}">
                            &larr; Cancel and go back
                        </a>
                        <x-skew-button type="submit" class="px-5 py-2.5 text-xs font-bold uppercase tracking-wider">
                            {{ isset($user) ? __('Save Changes') : __('Register Account') }}
                        </x-skew-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
