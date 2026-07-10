<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-white leading-tight">
                {{ __('ID Card Generation') }}
            </h2>
            <x-skew-button :href="route('id-cards.create')" class="px-5 py-2.5 text-xs font-bold uppercase tracking-wider">
                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Generate New ID
            </x-skew-button>
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

            <!-- Search and Filter Bar -->
            <div class="mb-8 p-6 bg-slate-900/40 border border-slate-700/30 backdrop-blur-md rounded-2xl">
                <form action="{{ route('id-cards.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Search</label>
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or ID number..." 
                                class="w-full pl-10 pr-4 py-2 bg-slate-800/80 border border-slate-700 rounded-xl text-sm text-white placeholder-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="w-full md:w-48">
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Status</label>
                        <x-custom-select 
                            name="status" 
                            :value="request('status')" 
                            placeholder="All Statuses"
                            :options="[
                                '' => 'All Statuses',
                                'new' => 'New',
                                'renew' => 'Renew',
                                'archived' => 'Archived',
                                'graduated' => 'Graduated',
                                'dismissed' => 'Dismissed',
                                'deceased' => 'Deceased'
                            ]"
                        />
                    </div>

                    <div class="w-full md:w-48">
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Gender</label>
                        <x-custom-select 
                            name="gender" 
                            :value="request('gender')" 
                            placeholder="All Genders"
                            :options="[
                                '' => 'All Genders',
                                'Male' => 'Male',
                                'Female' => 'Female',
                                'Other' => 'Other'
                            ]"
                        />
                    </div>

                    <div class="flex items-end gap-2">
                        <x-skew-button type="submit" class="w-full md:w-auto px-6 py-2 text-sm font-semibold">
                            Filter
                        </x-skew-button>
                        @if(request()->anyFilled(['search', 'status', 'gender']))
                            <a href="{{ route('id-cards.index') }}" class="w-full md:w-auto px-4 py-2 bg-slate-800 hover:bg-slate-700 border border-slate-700 rounded-xl text-sm font-semibold text-slate-300 transition-colors text-center">
                                Clear
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- ID Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($idCards as $card)
                    @php
                        $statusColors = match ($card->status) {
                            'new' => 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20',
                            'renew' => 'bg-indigo-500/10 text-indigo-400 border border-indigo-500/20',
                            'archived' => 'bg-slate-500/10 text-slate-400 border border-slate-500/20',
                            'graduated' => 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/20',
                            'dismissed' => 'bg-rose-500/10 text-rose-400 border border-rose-500/20',
                            'deceased' => 'bg-red-950/40 text-red-500 border border-red-500/20',
                            default => 'bg-slate-500/10 text-slate-400 border border-slate-500/20',
                        };
                    @endphp
                    <div class="relative bg-slate-800/30 border border-slate-700/50 backdrop-blur-sm shadow-xl rounded-2xl overflow-hidden hover:border-slate-600 transition-all group">
                        <div class="p-6">
                            <div class="flex items-start gap-4">
                                <div class="w-16 h-16 rounded-xl overflow-hidden bg-slate-700/50 flex-shrink-0 border border-slate-600">
                                    @if ($card->id_picture)
                                        <img src="{{ asset($card->id_picture) }}" alt="{{ $card->full_name }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-slate-500">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ $statusColors }}">
                                            {{ $card->status }}
                                        </span>
                                    </div>
                                    <h3 class="text-base font-bold text-white truncate">{{ $card->full_name }}</h3>
                                    <p class="text-xs font-mono text-indigo-400">{{ $card->id_number }}</p>
                                </div>
                            </div>

                            <div class="mt-4 pt-4 border-t border-slate-700/40 grid grid-cols-2 gap-2 text-xs text-slate-400">
                                <div>
                                    <span class="block text-[10px] text-slate-500 uppercase">DOB / Age</span>
                                    <span class="text-slate-300 font-semibold">{{ $card->dob->format('M d, Y') }} ({{ $card->age }} yrs)</span>
                                </div>
                                <div>
                                    <span class="block text-[10px] text-slate-500 uppercase">Validity</span>
                                    <span class="text-slate-300 font-semibold">{{ $card->validity_date->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Card Actions Overlay/Footer -->
                        <div class="px-6 py-4 bg-slate-900/50 border-t border-slate-700/30 flex justify-between items-center">
                            <a href="{{ route('id-cards.show', $card) }}" class="text-xs font-bold text-indigo-400 hover:text-indigo-300 transition-colors flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                View ID Card
                            </a>

                            <div class="flex items-center gap-3">
                                <a href="{{ route('id-cards.edit', $card) }}" class="text-xs font-bold text-slate-400 hover:text-white transition-colors flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Edit
                                </a>

                                @if (in_array(auth()->user()->users_level, [0, 1]))
                                    <form action="{{ route('id-cards.destroy', $card) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this ID Card?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs font-bold text-rose-500 hover:text-rose-400 transition-colors flex items-center gap-1 cursor-pointer">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            Delete
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center bg-slate-800/10 border border-slate-700/30 rounded-2xl text-slate-400">
                        <svg class="w-12 h-12 mx-auto mb-3 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        No ID Cards generated yet.
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if ($idCards->hasPages())
                <div class="mt-8">
                    {{ $idCards->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
