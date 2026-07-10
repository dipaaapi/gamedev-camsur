<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-white leading-tight">
                {{ __('User Management') }}
            </h2>
            @if(in_array(auth()->user()->users_level, [0, 1]))
                <a href="{{ route('register') }}">
                    <x-skew-button class="px-5 py-2.5 text-xs font-bold uppercase tracking-wider cursor-pointer">
                        + Create User
                    </x-skew-button>
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success/Error Messages -->
            @if (session('success'))
                <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-xl text-sm font-semibold">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 p-4 bg-rose-500/10 border border-rose-500/20 text-rose-400 rounded-xl text-sm font-semibold">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-slate-800/30 border border-slate-700/50 backdrop-blur-sm overflow-hidden shadow-[0_0_30px_rgba(0,0,0,0.2)] sm:rounded-2xl">
                <div class="p-8">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-700/50">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">User Details</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Current Role</th>
                                    <th class="px-6 py-3 text-right text-xs font-semibold text-slate-400 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-700/30">
                                @forelse ($users as $user)
                                    <tr class="hover:bg-slate-700/10 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div>
                                                    <div class="text-sm font-semibold text-white">
                                                        {{ $user->name }}
                                                    </div>
                                                    <div class="text-xs text-slate-400">
                                                        {{ $user->email ?? 'No email' }} | @ {{ $user->username }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $badgeClass = match ($user->users_level) {
                                                    0 => 'bg-indigo-500/10 text-indigo-400 border border-indigo-500/20',
                                                    1 => 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/20',
                                                    2 => 'bg-purple-500/10 text-purple-400 border border-purple-500/20',
                                                    3 => 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20',
                                                    4 => 'bg-amber-500/10 text-amber-400 border border-amber-500/20',
                                                    default => 'bg-slate-500/10 text-slate-400 border border-slate-500/20'
                                                };
                                            @endphp
                                            <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-bold rounded-lg {{ $badgeClass }}">
                                                {{ $user->role_name }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                            @php
                                                $canEdit = false;
                                                $currLevel = auth()->user()->users_level;
                                                
                                                if (auth()->user()->isDeveloper()) {
                                                    if (in_array($user->users_level, [1, 2, 3, 4]) && $user->id !== auth()->id()) {
                                                        $canEdit = true;
                                                    }
                                                } elseif (auth()->user()->isSchoolAdmin()) {
                                                    if (in_array($user->users_level, [2, 3, 4]) && $user->id !== auth()->id()) {
                                                        $canEdit = true;
                                                    }
                                                } elseif (auth()->user()->isTeacher()) {
                                                    if (in_array($user->users_level, [3, 4]) && $user->id !== auth()->id()) {
                                                        $canEdit = true;
                                                    }
                                                }

                                                $canDelete = false;
                                                if ($user->id !== auth()->id() && auth()->user()->canDeleteOtherUsers()) {
                                                    if ($currLevel === 0 && $user->users_level !== 0) {
                                                        $canDelete = true;
                                                    } elseif ($currLevel === 1 && !in_array($user->users_level, [0, 1])) {
                                                        $canDelete = true;
                                                    } elseif ($currLevel === 2 && in_array($user->users_level, [3, 4])) {
                                                        $canDelete = true;
                                                    }
                                                }
                                            @endphp

                                            <div class="flex items-center justify-end gap-3">
                                                @if ($canEdit)
                                                    <a href="{{ route('users.edit', $user) }}" class="text-indigo-400 hover:text-indigo-300 cursor-pointer transition-colors p-1.5 rounded-lg hover:bg-indigo-500/10 inline-flex items-center text-xs font-bold uppercase tracking-wider">
                                                        <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                        Edit
                                                    </a>
                                                @endif

                                                @if($canDelete)
                                                    <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline m-0" onsubmit="return confirm('Are you sure you want to permanently delete this user?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-rose-400 hover:text-rose-300 cursor-pointer transition-colors p-1.5 rounded-lg hover:bg-rose-500/10">
                                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                @endif

                                                @if(!$canEdit && !$canDelete)
                                                    <span class="text-xs text-slate-500 italic">-</span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-12 text-center text-slate-400">
                                            No users found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($users->hasPages())
                        <div class="mt-6">
                            {{ $users->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
