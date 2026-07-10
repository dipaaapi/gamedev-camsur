<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white leading-tight">
            {{ __('Contact Inquiries & Message Center') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <!-- Messages / Errors -->
            @if (session('success'))
                <div class="p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-xl text-sm font-semibold">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="p-4 bg-rose-500/10 border border-rose-500/20 text-rose-400 rounded-xl text-sm font-semibold">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Teacher Deployment Management (Only for Developer / Admin) -->
            @if(auth()->user()->isDeveloper() || auth()->user()->isSchoolAdmin())
                <div class="bg-slate-800/30 border border-slate-700/50 backdrop-blur-sm shadow-xl rounded-2xl p-6">
                    <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        Deploy Teachers to Inquiry Purposes
                    </h3>
                    <p class="text-slate-400 text-sm mb-6">
                        Deploy qualified teachers to reply to incoming customer/trainee contact inquiries, complaints, suggestions, purchases, and priority issues.
                    </p>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($validPurposes as $purpose)
                            <div class="bg-slate-900/40 border border-slate-800/80 rounded-xl p-4 flex flex-col justify-between">
                                <div>
                                    <div class="flex items-center justify-between mb-3">
                                        <span class="text-sm font-bold text-slate-200">{{ $purpose }}</span>
                                        <span class="text-xs bg-slate-800 text-slate-400 px-2 py-0.5 rounded border border-slate-700/50">Category</span>
                                    </div>

                                    <!-- Deployed Teachers -->
                                    <div class="space-y-2 mb-4">
                                        <span class="text-xs font-semibold text-slate-500 block uppercase tracking-wider">Deployed Staff:</span>
                                        @forelse($deploymentsByPurpose[$purpose] ?? [] as $deployment)
                                            <div class="flex items-center justify-between bg-slate-850/50 border border-slate-800 px-2.5 py-1.5 rounded-lg text-xs">
                                                <div class="text-slate-350">
                                                    <span class="font-medium text-white">{{ $deployment->user->name }}</span>
                                                    <span class="text-[10px] text-slate-500 block">{{ $deployment->user->email }}</span>
                                                </div>
                                                <form action="{{ route('contact-messages.revoke', $deployment) }}" method="POST" class="m-0">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-rose-400 hover:text-rose-350 transition-colors focus:outline-none" title="Revoke deployment">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        @empty
                                            <span class="text-xs text-slate-500 italic block">No teachers deployed</span>
                                        @endforelse
                                    </div>
                                </div>

                                <!-- Deployment Form -->
                                <form action="{{ route('contact-messages.deploy') }}" method="POST" class="pt-3 border-t border-slate-850 flex gap-2">
                                    @csrf
                                    <input type="hidden" name="purpose" value="{{ $purpose }}">
                                    <div class="flex-grow">
                                        <select name="user_id" required class="w-full text-xs bg-slate-900 border border-slate-700/50 rounded-lg text-white focus:outline-none focus:border-brand transition-colors p-1.5">
                                            <option value="">Select Teacher...</option>
                                            @foreach($teachers as $t)
                                                <option value="{{ $t->id }}">{{ $t->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <x-skew-button type="submit" class="px-3 text-xs font-bold shrink-0">
                                        Deploy
                                    </x-skew-button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Inquiries List -->
            <div class="bg-slate-800/30 border border-slate-700/50 backdrop-blur-sm shadow-xl rounded-2xl p-8">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                    <div>
                        <h3 class="text-xl font-bold text-white">Inbox Inquiries</h3>
                        <p class="text-slate-400 text-sm mt-1">Manage user messages, feedback, and replies.</p>
                    </div>

                    <!-- Filters -->
                    <form action="{{ route('contact-messages.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
                        <div class="w-40">
                            <select name="purpose" onchange="this.form.submit()" class="w-full text-sm bg-slate-900 border border-slate-700/50 rounded-xl text-white focus:outline-none focus:border-brand transition-colors py-2 px-3">
                                <option value="">All Purposes</option>
                                @foreach($validPurposes as $p)
                                    <option value="{{ $p }}" {{ request('purpose') == $p ? 'selected' : '' }}>{{ $p }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-40">
                            <select name="status" onchange="this.form.submit()" class="w-full text-sm bg-slate-900 border border-slate-700/50 rounded-xl text-white focus:outline-none focus:border-brand transition-colors py-2 px-3">
                                <option value="">All Statuses</option>
                                <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="Replied" {{ request('status') == 'Replied' ? 'selected' : '' }}>Replied</option>
                                <option value="Resolved" {{ request('status') == 'Resolved' ? 'selected' : '' }}>Resolved</option>
                                <option value="Archived" {{ request('status') == 'Archived' ? 'selected' : '' }}>Archived</option>
                            </select>
                        </div>
                        @if(request()->filled('purpose') || request()->filled('status'))
                            <a href="{{ route('contact-messages.index') }}" class="text-xs text-rose-400 hover:underline">Clear Filters</a>
                        @endif
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-700/50">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Inquirer Details</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Purpose</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Received</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-slate-400 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-700/30">
                            @forelse($inquiries as $inquiry)
                                <tr class="hover:bg-slate-700/10 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-white">{{ $inquiry->name }}</div>
                                        <div class="text-xs text-slate-400">{{ $inquiry->email ?? 'No email' }} | {{ $inquiry->mobile_number }}</div>
                                        @if($inquiry->pronouns)
                                            <div class="text-[10px] text-indigo-400 font-semibold">{{ $inquiry->pronouns }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $purposeColor = match(strtok($inquiry->purpose, ':')) {
                                                'Inquiry' => 'bg-indigo-500/10 text-indigo-400 border border-indigo-500/20',
                                                'Complaint' => 'bg-rose-500/10 text-rose-400 border border-rose-500/20',
                                                'Suggestion' => 'bg-amber-500/10 text-amber-400 border border-amber-500/20',
                                                'Purchase' => 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20',
                                                'Priority' => 'bg-fuchsia-500/10 text-fuchsia-400 border border-fuchsia-500/20',
                                                default => 'bg-slate-500/10 text-slate-400 border border-slate-500/20'
                                            };
                                        @endphp
                                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-bold rounded-lg {{ $purposeColor }}">
                                            {{ $inquiry->purpose }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusColor = match($inquiry->status) {
                                                'Pending' => 'bg-amber-500/10 text-amber-400 border border-amber-500/20',
                                                'Replied' => 'bg-blue-500/10 text-blue-400 border border-blue-500/20',
                                                'Resolved' => 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20',
                                                'Archived' => 'bg-purple-500/10 text-purple-400 border border-purple-500/20',
                                                default => 'bg-slate-500/10 text-slate-400 border border-slate-500/20'
                                            };
                                        @endphp
                                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-bold rounded-lg {{ $statusColor }}">
                                            {{ $inquiry->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-400">
                                        {{ $inquiry->created_at->diffForHumans() }}
                                        <span class="block text-[10px] text-slate-500">{{ $inquiry->created_at->format('M d, Y h:i A') }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('contact-messages.show', $inquiry) }}" class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-bold bg-slate-700/50 hover:bg-slate-700 text-white rounded-lg border border-slate-600/50 transition-colors">
                                                View
                                            </a>

                                            @if($inquiry->status === 'Archived')
                                                <form action="{{ route('contact-messages.restore', $inquiry) }}" method="POST" class="inline m-0">
                                                    @csrf
                                                    <x-skew-button type="submit" class="px-3 py-1.5 text-xs font-bold bg-indigo-600/30 hover:bg-indigo-600 border-indigo-500/50 text-indigo-400">
                                                        Restore
                                                    </x-skew-button>
                                                </form>
                                            @else
                                                <form action="{{ route('contact-messages.archive', $inquiry) }}" method="POST" class="inline m-0">
                                                    @csrf
                                                    <x-skew-button type="submit" class="px-3 py-1.5 text-xs font-bold bg-amber-600/20 hover:bg-amber-600/50 border-amber-500/30 text-amber-400">
                                                        Archive
                                                    </x-skew-button>
                                                </form>
                                            @endif

                                            <form action="{{ route('contact-messages.destroy', $inquiry) }}" method="POST" class="inline m-0" onsubmit="return confirm('Are you sure you want to permanently delete this inquiry?');">
                                                @csrf
                                                @method('DELETE')
                                                <x-skew-button type="submit" class="px-3 py-1.5 text-xs font-bold bg-rose-600/20 hover:bg-rose-650 border-rose-500/30 text-rose-450">
                                                    Delete
                                                </x-skew-button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-slate-500 italic">
                                        No contact inquiries found matching criteria.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($inquiries->hasPages())
                    <div class="mt-6">
                        {{ $inquiries->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
