<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-white leading-tight">
                {{ __('Activity Logs') }}
            </h2>
            <div class="flex gap-4">
                <x-cancel-button :href="route('dashboard')" class="px-5 py-2.5 text-xs font-bold uppercase tracking-wider">
                    <div class="flex gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        {{ __('Return') }}
                    </div>
                </x-cancel-button>
                
                <x-skew-button href="{{ route('activity-logs.print') }}" target="_blank" class="px-5 py-2 text-sm font-semibold">
                    <div class="flex gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        <span>{{ __('Print Logs') }}</span>
                    </div>
                </x-skew-button>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-slate-800/30 border border-slate-700/50 backdrop-blur-sm overflow-hidden shadow-[0_0_30px_rgba(0,0,0,0.2)] sm:rounded-2xl">
                <div class="p-8">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-700/50">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">User</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Action</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Description</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">IP Address</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Timestamp</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-700/30">
                                @forelse ($logs as $log)
                                    <tr class="hover:bg-slate-700/10 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div>
                                                    <div class="text-sm font-semibold text-white">
                                                        {{ $log->user_name ?? 'Deleted User' }}
                                                    </div>
                                                    <div class="text-xs text-slate-400">
                                                        {{ $log->user_email ?? 'N/A' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $badgeClass = match ($log->action) {
                                                    'Registration' => 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20',
                                                    'Login' => 'bg-blue-500/10 text-blue-400 border border-blue-500/20',
                                                    'Logout' => 'bg-slate-500/10 text-slate-400 border border-slate-500/20',
                                                    'Profile Updated' => 'bg-purple-500/10 text-purple-400 border border-purple-500/20',
                                                    'Account Deletion' => 'bg-rose-500/10 text-rose-400 border border-rose-500/20',
                                                    default => 'bg-slate-500/10 text-slate-400 border border-slate-500/20'
                                                };
                                            @endphp
                                            <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-bold rounded-lg {{ $badgeClass }}">
                                                {{ $log->action }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-slate-300">
                                            {{ $log->description }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-400 font-mono">
                                            {{ $log->ip_address ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-400">
                                            {{ $log->created_at->format('M d, Y h:i A') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                            No activity logs found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($logs->hasPages())
                        <div class="mt-6">
                            {{ $logs->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
