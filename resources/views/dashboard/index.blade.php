<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Friend Requests Notification Banner -->
            @php
                $pendingCount = Auth::user()->pendingIncomingRequests()->count();
            @endphp
            @if($pendingCount > 0)
                <div class="bg-indigo-950/40 border border-indigo-500/30 backdrop-blur-sm overflow-hidden shadow-[0_0_30px_rgba(99,102,241,0.15)] sm:rounded-2xl mb-8 flex items-center justify-between p-6">
                    <div class="flex items-center gap-4">
                        <!-- Pulse Effect Dot Container -->
                        <span class="relative flex h-4 w-4 shrink-0">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-4 w-4 bg-rose-500"></span>
                        </span>
                        <div>
                            <h4 class="text-white font-bold text-base">You have {{ $pendingCount }} pending friend {{ Str::plural('request', $pendingCount) }}!</h4>
                            <p class="text-slate-400 text-sm mt-0.5">Head over to your profile social hub to accept or decline them.</p>
                        </div>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl text-xs font-bold transition-colors shadow-lg">
                        Manage Requests
                    </a>
                </div>
            @endif

            <!-- Unread Messages Notification Banner -->
            @php
                $unreadMsgs = \App\Models\DirectMessage::where('receiver_id', Auth::user()->id)->where('is_read', false)->count();
            @endphp
            @if($unreadMsgs > 0)
                <div class="bg-indigo-950/40 border border-indigo-500/30 backdrop-blur-sm overflow-hidden shadow-[0_0_30px_rgba(99,102,241,0.15)] sm:rounded-2xl mb-8 flex items-center justify-between p-6">
                    <div class="flex items-center gap-4">
                        <!-- Pulse Effect Dot Container -->
                        <span class="relative flex h-4 w-4 shrink-0">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-4 w-4 bg-rose-500"></span>
                        </span>
                        <div>
                            <h4 class="text-white font-bold text-base">You have {{ $unreadMsgs }} unread direct {{ Str::plural('message', $unreadMsgs) }}!</h4>
                            <p class="text-slate-400 text-sm mt-0.5">Head over to your profile social hub to read and reply to them.</p>
                        </div>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl text-xs font-bold transition-colors shadow-lg">
                        View Chat Box
                    </a>
                </div>
            @endif

            <!-- Welcome banner (Once per login session) -->
            @if(!session()->has('welcome_shown'))
                <div class="bg-slate-800/30 border border-slate-700/50 backdrop-blur-sm overflow-hidden shadow-[0_0_30px_rgba(0,0,0,0.2)] sm:rounded-2xl mb-8">
                    <div class="p-8">
                        <h3 class="text-3xl font-extrabold tracking-tight mb-4 text-white">Welcome back, <span class="gradient-text">{{ Auth::user()->name }}</span>!</h3>
                        <p class="text-slate-400 text-lg leading-relaxed">
                            {{ __("You're successfully logged in. This is your personal dashboard where you can manage your projects and view community events.") }}
                        </p>
                    </div>
                </div>
                @php
                    session()->put('welcome_shown', true);
                @endphp
            @endif

            <!-- Database Statistics Section -->
            <div class="mt-8">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-slate-300 flex items-center gap-2 m-0">
                        <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.58 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.58 4 8 4s8-1.79 8-4M4 7c0-2.21 3.58-4 8-4s8 1.79 8 4m0 5c0 2.21-3.58 4-8 4s-8-1.79-8-4"></path>
                        </svg>
                        Database Statistics & Metrics
                    </h3>
                    
                    <!-- Carousel Controls -->
                    <div class="flex items-center gap-2 select-none">
                        <button id="carousel-prev" class="w-8 h-8 rounded-lg bg-slate-900 border border-slate-800 hover:bg-slate-800 hover:border-slate-700 text-slate-400 hover:text-white flex items-center justify-center transition-all cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" /></svg>
                        </button>
                        <button id="carousel-next" class="w-8 h-8 rounded-lg bg-slate-900 border border-slate-800 hover:bg-slate-800 hover:border-slate-700 text-slate-400 hover:text-white flex items-center justify-center transition-all cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" /></svg>
                        </button>
                    </div>
                </div>

                @php
                    $dashboardUser = Auth::user();
                    $isStudentOrParent = $dashboardUser && ($dashboardUser->isStudent() || $dashboardUser->isParentGuardian());
                @endphp

                <div id="stats-carousel-container" class="overflow-hidden w-full relative">
                    <div id="stats-carousel-track" class="flex gap-6 transition-transform duration-500 ease-out" style="transform: translateX(0px);">
                        @if(!$isStudentOrParent)
                            <!-- Users Card -->
                            <a href="{{ route('users.index') }}" class="w-full sm:w-[calc(50%-0.75rem)] lg:w-[calc(25%-1.125rem)] shrink-0 block bg-slate-800/30 border border-slate-700/50 backdrop-blur-sm rounded-2xl p-6 hover:scale-[1.03] transition-all duration-300 shadow-lg group hover:border-indigo-500/50 hover:shadow-indigo-500/10">
                                <div class="flex items-center justify-between mb-4">
                                    <span class="text-slate-400 text-sm font-semibold tracking-wide uppercase">Registered Users</span>
                                    <div class="p-3 bg-indigo-500/10 rounded-xl text-indigo-400 group-hover:bg-indigo-500 group-hover:text-white transition-colors duration-300">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex items-baseline gap-2">
                                    <span class="text-4xl font-extrabold text-white tracking-tight">{{ number_format($stats['users']['total']) }}</span>
                                    <span class="text-indigo-400 text-xs font-semibold">Total</span>
                                </div>
                                <div class="flex gap-4 mt-3 text-xs font-medium border-t border-slate-700/30 pt-2 text-slate-400">
                                    <div>Active: <span class="text-emerald-400 font-bold">{{ number_format($stats['users']['active']) }}</span></div>
                                    <div>Archived: <span class="text-rose-400 font-bold">{{ number_format($stats['users']['archived']) }}</span></div>
                                </div>
                            </a>

                            <!-- ID Cards Card -->
                            <a href="{{ route('id-cards.index') }}" class="w-full sm:w-[calc(50%-0.75rem)] lg:w-[calc(25%-1.125rem)] shrink-0 block bg-slate-800/30 border border-slate-700/50 backdrop-blur-sm rounded-2xl p-6 hover:scale-[1.03] transition-all duration-300 shadow-lg group hover:border-pink-500/50 hover:shadow-pink-500/10">
                                <div class="flex items-center justify-between mb-4">
                                    <span class="text-slate-400 text-sm font-semibold tracking-wide uppercase">Generated ID Cards</span>
                                    <div class="p-3 bg-pink-500/10 rounded-xl text-pink-400 group-hover:bg-pink-500 group-hover:text-white transition-colors duration-300">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex items-baseline gap-2">
                                    <span class="text-4xl font-extrabold text-white tracking-tight">{{ number_format($stats['id_cards']['total']) }}</span>
                                    <span class="text-pink-400 text-xs font-semibold">Total</span>
                                </div>
                                <div class="flex gap-4 mt-3 text-xs font-medium border-t border-slate-700/30 pt-2 text-slate-400">
                                    <div>Active: <span class="text-emerald-400 font-bold">{{ number_format($stats['id_cards']['active']) }}</span></div>
                                    <div>Archived: <span class="text-rose-400 font-bold">{{ number_format($stats['id_cards']['archived']) }}</span></div>
                                </div>
                            </a>

                            <!-- Activity Logs Card -->
                            <a href="{{ route('activity-logs.index') }}" class="w-full sm:w-[calc(50%-0.75rem)] lg:w-[calc(25%-1.125rem)] shrink-0 block bg-slate-800/30 border border-slate-700/50 backdrop-blur-sm rounded-2xl p-6 hover:scale-[1.03] transition-all duration-300 shadow-lg group hover:border-rose-500/50 hover:shadow-rose-500/10">
                                <div class="flex items-center justify-between mb-4">
                                    <span class="text-slate-400 text-sm font-semibold tracking-wide uppercase">Activity Logs</span>
                                    <div class="p-3 bg-rose-500/10 rounded-xl text-rose-400 group-hover:bg-rose-500 group-hover:text-white transition-colors duration-300">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex items-baseline gap-2">
                                    <span class="text-4xl font-extrabold text-white tracking-tight">{{ number_format($stats['activity_logs']['total']) }}</span>
                                    <span class="text-rose-400 text-xs font-semibold">Total</span>
                                </div>
                                <div class="flex gap-4 mt-3 text-xs font-medium border-t border-slate-700/30 pt-2 text-slate-400">
                                    <div>Active: <span class="text-emerald-400 font-bold">{{ number_format($stats['activity_logs']['active']) }}</span></div>
                                    <div>Archived: <span class="text-rose-400 font-bold">{{ number_format($stats['activity_logs']['archived']) }}</span></div>
                                </div>
                            </a>

                            <!-- Careers Card -->
                            <a href="{{ route('careers.all') }}" class="w-full sm:w-[calc(50%-0.75rem)] lg:w-[calc(25%-1.125rem)] shrink-0 block bg-slate-800/30 border border-slate-700/50 backdrop-blur-sm rounded-2xl p-6 hover:scale-[1.03] transition-all duration-300 shadow-lg group hover:border-blue-500/50 hover:shadow-blue-500/10">
                                <div class="flex items-center justify-between mb-4">
                                    <span class="text-slate-400 text-sm font-semibold tracking-wide uppercase">Careers Listed</span>
                                    <div class="p-3 bg-blue-500/10 rounded-xl text-blue-400 group-hover:bg-blue-500 group-hover:text-white transition-colors duration-300">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex items-baseline gap-2">
                                    <span class="text-4xl font-extrabold text-white tracking-tight">{{ number_format($stats['careers']['total']) }}</span>
                                    <span class="text-blue-400 text-xs font-semibold">Total</span>
                                </div>
                                <div class="flex gap-4 mt-3 text-xs font-medium border-t border-slate-700/30 pt-2 text-slate-400">
                                    <div>Active: <span class="text-emerald-400 font-bold">{{ number_format($stats['careers']['active']) }}</span></div>
                                    <div>Archived: <span class="text-rose-400 font-bold">{{ number_format($stats['careers']['archived']) }}</span></div>
                                </div>
                            </a>

                            <!-- Contact Messages Card -->
                            <a href="{{ route('contact-messages.index') }}" class="w-full sm:w-[calc(50%-0.75rem)] lg:w-[calc(25%-1.125rem)] shrink-0 block bg-slate-800/30 border border-slate-700/50 backdrop-blur-sm rounded-2xl p-6 hover:scale-[1.03] transition-all duration-300 shadow-lg group hover:border-amber-500/50 hover:shadow-amber-500/10">
                                <div class="flex items-center justify-between mb-4">
                                    <span class="text-slate-400 text-sm font-semibold tracking-wide uppercase">Contact Inquiries</span>
                                    <div class="p-3 bg-amber-500/10 rounded-xl text-amber-400 group-hover:bg-amber-500 group-hover:text-white transition-colors duration-300">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex items-baseline gap-2">
                                    <span class="text-4xl font-extrabold text-white tracking-tight">{{ number_format($stats['contact_messages']['total']) }}</span>
                                    <span class="text-amber-400 text-xs font-semibold">Total</span>
                                </div>
                                <div class="flex gap-2 sm:gap-3 flex-wrap mt-3 text-xs font-medium border-t border-slate-700/30 pt-2 text-slate-400">
                                    <div>Pending: <span class="text-amber-400 font-bold">{{ number_format($stats['contact_messages']['pending']) }}</span></div>
                                    <div>Resolved: <span class="text-emerald-400 font-bold">{{ number_format($stats['contact_messages']['resolved']) }}</span></div>
                                    <div>Archived: <span class="text-rose-400 font-bold">{{ number_format($stats['contact_messages']['archived']) }}</span></div>
                                </div>
                            </a>
                        @endif

                        @if(!$isStudentOrParent || $dashboardUser->can_post_announcement)
                            <!-- Announcements Card -->
                            <a href="{{ route('public-info-management') }}" class="w-full sm:w-[calc(50%-0.75rem)] lg:w-[calc(25%-1.125rem)] shrink-0 block bg-slate-800/30 border border-slate-700/50 backdrop-blur-sm rounded-2xl p-6 hover:scale-[1.03] transition-all duration-300 shadow-lg group hover:border-emerald-500/50 hover:shadow-emerald-500/10">
                                <div class="flex items-center justify-between mb-4">
                                    <span class="text-slate-400 text-sm font-semibold tracking-wide uppercase">Announcements</span>
                                    <div class="p-3 bg-emerald-500/10 rounded-xl text-emerald-400 group-hover:bg-emerald-500 group-hover:text-white transition-colors duration-300">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex items-baseline gap-2">
                                    <span class="text-4xl font-extrabold text-white tracking-tight">{{ number_format($stats['announcements']['total']) }}</span>
                                    <span class="text-emerald-400 text-xs font-semibold">Total</span>
                                </div>
                                <div class="flex gap-4 mt-3 text-xs font-medium border-t border-slate-700/30 pt-2 text-slate-400">
                                    <div>Active: <span class="text-emerald-400 font-bold">{{ number_format($stats['announcements']['active']) }}</span></div>
                                    <div>Archived: <span class="text-rose-400 font-bold">{{ number_format($stats['announcements']['archived']) }}</span></div>
                                </div>
                            </a>
                        @endif

                        @if(!$isStudentOrParent || $dashboardUser->can_post_calendar)
                            <!-- Calendar Events Card -->
                            <a href="{{ route('public-info-management') }}" class="w-full sm:w-[calc(50%-0.75rem)] lg:w-[calc(25%-1.125rem)] shrink-0 block bg-slate-800/30 border border-slate-700/50 backdrop-blur-sm rounded-2xl p-6 hover:scale-[1.03] transition-all duration-300 shadow-lg group hover:border-cyan-500/50 hover:shadow-cyan-500/10">
                                <div class="flex items-center justify-between mb-4">
                                    <span class="text-slate-400 text-sm font-semibold tracking-wide uppercase">Calendar Events</span>
                                    <div class="p-3 bg-cyan-500/10 rounded-xl text-cyan-400 group-hover:bg-cyan-500 group-hover:text-white transition-colors duration-300">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex items-baseline gap-2">
                                    <span class="text-4xl font-extrabold text-white tracking-tight">{{ number_format($stats['calendar_events']['total']) }}</span>
                                    <span class="text-cyan-400 text-xs font-semibold">Total</span>
                                </div>
                                <div class="flex gap-4 mt-3 text-xs font-medium border-t border-slate-700/30 pt-2 text-slate-400">
                                    <div>Active: <span class="text-emerald-400 font-bold">{{ number_format($stats['calendar_events']['active']) }}</span></div>
                                    <div>Archived: <span class="text-rose-400 font-bold">{{ number_format($stats['calendar_events']['archived']) }}</span></div>
                                </div>
                            </a>
                        @endif

                        @if(!$isStudentOrParent || $dashboardUser->can_post_student_board)
                            <!-- Student Board Card -->
                            <a href="{{ route('public-info-management') }}" class="w-full sm:w-[calc(50%-0.75rem)] lg:w-[calc(25%-1.125rem)] shrink-0 block bg-slate-800/30 border border-slate-700/50 backdrop-blur-sm rounded-2xl p-6 hover:scale-[1.03] transition-all duration-300 shadow-lg group hover:border-violet-500/50 hover:shadow-violet-500/10">
                                <div class="flex items-center justify-between mb-4">
                                    <span class="text-slate-400 text-sm font-semibold tracking-wide uppercase">Student Board Posts</span>
                                    <div class="p-3 bg-violet-500/10 rounded-xl text-violet-400 group-hover:bg-violet-500 group-hover:text-white transition-colors duration-300">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1M19 20a2 2 0 002-2V8a2 2 0 00-2-2h-5a2 2 0 00-2 2v10a2 2 0 002 2zM19 20a1.5 1.5 0 11-3 0M20 4a2 2 0 00-2 2v3m-6 3h2m-2 4h2"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex items-baseline gap-2">
                                    <span class="text-4xl font-extrabold text-white tracking-tight">{{ number_format($stats['student_board_posts']['total']) }}</span>
                                    <span class="text-violet-400 text-xs font-semibold">Total</span>
                                </div>
                                <div class="flex gap-4 mt-3 text-xs font-medium border-t border-slate-700/30 pt-2 text-slate-400">
                                    <div>Active: <span class="text-emerald-400 font-bold">{{ number_format($stats['student_board_posts']['active']) }}</span></div>
                                    <div>Archived: <span class="text-rose-400 font-bold">{{ number_format($stats['student_board_posts']['archived']) }}</span></div>
                                </div>
                            </a>
                        @endif

                        @if($isStudentOrParent)
                            <!-- Activity Logs Card -->
                            <a href="{{ route('activity-logs.index') }}" class="w-full sm:w-[calc(50%-0.75rem)] lg:w-[calc(25%-1.125rem)] shrink-0 block bg-slate-800/30 border border-slate-700/50 backdrop-blur-sm rounded-2xl p-6 hover:scale-[1.03] transition-all duration-300 shadow-lg group hover:border-rose-500/50 hover:shadow-rose-500/10">
                                <div class="flex items-center justify-between mb-4">
                                    <span class="text-slate-400 text-sm font-semibold tracking-wide uppercase">Activity Logs</span>
                                    <div class="p-3 bg-rose-500/10 rounded-xl text-rose-400 group-hover:bg-rose-500 group-hover:text-white transition-colors duration-300">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex items-baseline gap-2">
                                    <span class="text-4xl font-extrabold text-white tracking-tight">{{ number_format($stats['activity_logs']['total']) }}</span>
                                    <span class="text-rose-400 text-xs font-semibold">Total</span>
                                </div>
                                <div class="flex gap-4 mt-3 text-xs font-medium border-t border-slate-700/30 pt-2 text-slate-400">
                                    <div>Active: <span class="text-emerald-400 font-bold">{{ number_format($stats['activity_logs']['active']) }}</span></div>
                                    <div>Archived: <span class="text-rose-400 font-bold">{{ number_format($stats['activity_logs']['archived']) }}</span></div>
                                </div>
                            </a>
                        @endif

                        @if(Auth::user()->users_level === 0 || Auth::user()->users_level === 1 || (Auth::user()->users_level === 2 && Auth::user()->can_manage_admissions))
                            <!-- Admissions Card -->
                            <a href="{{ route('admission.admin.index') }}" class="w-full sm:w-[calc(50%-0.75rem)] lg:w-[calc(25%-1.125rem)] shrink-0 block bg-slate-800/30 border border-slate-700/50 backdrop-blur-sm rounded-2xl p-6 hover:scale-[1.03] transition-all duration-300 shadow-lg group hover:border-emerald-500/50 hover:shadow-emerald-500/10">
                                <div class="flex items-center justify-between mb-4">
                                    <span class="text-slate-400 text-sm font-semibold tracking-wide uppercase">Course Admissions</span>
                                    <div class="p-3 bg-emerald-500/10 rounded-xl text-emerald-400 group-hover:bg-emerald-500 group-hover:text-white transition-colors duration-300">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex items-baseline gap-2">
                                    <span class="text-4xl font-extrabold text-white tracking-tight">{{ number_format($stats['admissions']['total']) }}</span>
                                    <span class="text-emerald-400 text-xs font-semibold">Total</span>
                                </div>
                                <div class="flex gap-2 sm:gap-3 flex-wrap mt-3 text-xs font-medium border-t border-slate-700/30 pt-2 text-slate-400">
                                    <div>Pending: <span class="text-amber-400 font-bold">{{ number_format($stats['admissions']['pending']) }}</span></div>
                                    <div>Approved: <span class="text-emerald-400 font-bold">{{ number_format($stats['admissions']['approved']) }}</span></div>
                                    <div>Rejected: <span class="text-rose-400 font-bold">{{ number_format($stats['admissions']['rejected']) }}</span></div>
                                </div>
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Two-Column Feed Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mt-12">
                <!-- Column 1 & 2: Announcements & Schedules -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Event Calendar / Schedule -->
                    <div class="bg-slate-800/30 border border-slate-700/50 backdrop-blur-sm rounded-2xl p-6 shadow-xl">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                                <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Upcoming Schedules & Events
                            </h3>
                            <span class="text-xs text-slate-400 font-semibold uppercase tracking-wider">{{ $schedules->count() }} Scheduled</span>
                        </div>

                        @if($schedules->isEmpty())
                            <div class="text-center py-8">
                                <p class="text-slate-500 text-sm">No scheduled events at this time.</p>
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach($schedules->take(4) as $event)
                                    <div class="bg-slate-900/40 border border-slate-800/80 rounded-xl p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:border-slate-700 transition-colors duration-200">
                                        <div class="flex items-start gap-4">
                                            <div class="px-3 py-2 bg-cyan-500/10 text-cyan-400 rounded-lg text-center flex flex-col justify-center min-w-[70px]">
                                                <span class="text-xs font-bold uppercase">{{ \Carbon\Carbon::parse($event->date)->format('M') }}</span>
                                                <span class="text-lg font-extrabold leading-none">{{ \Carbon\Carbon::parse($event->date)->format('d') }}</span>
                                            </div>
                                            <div>
                                                <div class="flex items-center gap-2">
                                                    <span class="px-2 py-0.5 bg-slate-800 text-[10px] font-bold rounded text-slate-300 uppercase tracking-wide">
                                                        {{ $event->type }}
                                                    </span>
                                                    <span class="text-xs text-slate-500 font-medium">
                                                        {{ $event->time }}
                                                    </span>
                                                </div>
                                                <h4 class="text-white font-bold text-sm mt-1">{{ $event->title }}</h4>
                                                <p class="text-slate-400 text-xs mt-0.5 line-clamp-1">{{ $event->description }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Announcements Board -->
                    <div class="bg-slate-800/30 border border-slate-700/50 backdrop-blur-sm rounded-2xl p-6 shadow-xl">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                                </svg>
                                Announcements Board
                            </h3>
                            <span class="text-xs text-slate-400 font-semibold uppercase tracking-wider">{{ $announcements->count() }} Total</span>
                        </div>

                        @if($announcements->isEmpty())
                            <div class="text-center py-8">
                                <p class="text-slate-500 text-sm">No announcements published yet.</p>
                            </div>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($announcements->take(4) as $announcement)
                                    <div class="bg-slate-900/40 border border-slate-800/80 rounded-xl p-5 flex flex-col justify-between hover:border-slate-700 transition-colors duration-200">
                                        <div>
                                            <div class="flex items-center justify-between text-xs text-slate-500 mb-2">
                                                <span>{{ $announcement->date }}</span>
                                                <span class="font-bold text-indigo-400">By {{ $announcement->author }}</span>
                                            </div>
                                            <h4 class="text-white font-bold text-sm mb-1.5 line-clamp-1">{{ $announcement->title }}</h4>
                                            <p class="text-slate-400 text-xs line-clamp-3 leading-relaxed">{{ $announcement->excerpt ?: $announcement->content }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Column 3: Student Board Stories -->
                <div class="space-y-8">
                    <div class="bg-slate-800/30 border border-slate-700/50 backdrop-blur-sm rounded-2xl p-6 shadow-xl h-full flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                                    <svg class="w-5 h-5 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1M19 20a2 2 0 002-2V8a2 2 0 00-2-2h-5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    Student Board Stories
                                </h3>
                                <span class="text-xs text-slate-400 font-semibold uppercase tracking-wider">{{ $studentBoard->count() }} Posts</span>
                            </div>

                            @if($studentBoard->isEmpty())
                                <div class="text-center py-8">
                                    <p class="text-slate-500 text-sm">No community posts yet.</p>
                                </div>
                            @else
                                <div class="space-y-4 max-h-[580px] overflow-y-auto pr-1">
                                    @foreach($studentBoard as $post)
                                        <div class="bg-slate-900/40 border border-slate-800/80 rounded-xl p-4 hover:border-slate-700 transition-colors duration-200">
                                            <div class="flex items-center gap-3 mb-2.5">
                                                <div class="w-8 h-8 rounded-full bg-violet-600/20 border border-violet-500/30 flex items-center justify-center text-xs font-bold text-violet-300">
                                                    {{ $post->author_initials }}
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <h5 class="text-white font-bold text-xs truncate">{{ $post->author }}</h5>
                                                    <span class="text-[10px] text-slate-500">{{ $post->date }}</span>
                                                </div>
                                                <span class="ms-auto flex-shrink-0 px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider {{ $post->category === 'achievement' ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20' : 'bg-indigo-500/10 text-indigo-400 border border-indigo-500/20' }}">
                                                    {{ $post->category }}
                                                </span>
                                            </div>
                                            <h4 class="text-slate-200 font-bold text-xs mb-1 line-clamp-1">{{ $post->title }}</h4>
                                            <p class="text-slate-400 text-xs leading-relaxed line-clamp-3">{{ $post->content }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const track = document.getElementById('stats-carousel-track');
            const prevBtn = document.getElementById('carousel-prev');
            const nextBtn = document.getElementById('carousel-next');
            const container = document.getElementById('stats-carousel-container');
            
            if (!track || !prevBtn || !nextBtn || !container) return;

            let activeItems = Array.from(track.children).filter(item => {
                return window.getComputedStyle(item).display !== 'none';
            });

            let currentIndex = 0;
            let autoplayTimer = null;
            const delay = 3000; // Autoplay delay

            function getVisibleCount() {
                if (window.innerWidth >= 1024) return 4;
                if (window.innerWidth >= 640) return 2;
                return 1;
            }

            function getMaxIndex() {
                const visibleCount = getVisibleCount();
                return Math.max(0, activeItems.length - visibleCount);
            }

            function updateCarousel() {
                const visibleCount = getVisibleCount();
                const maxIndex = getMaxIndex();
                
                // Constrain current index
                if (currentIndex > maxIndex) {
                    currentIndex = 0; // Loop back to start
                } else if (currentIndex < 0) {
                    currentIndex = maxIndex; // Loop to end
                }

                // Calculate offset percentage (including gap)
                const itemWidth = activeItems[0] ? activeItems[0].getBoundingClientRect().width : 0;
                const gap = 24; // 1.5rem gap
                const offset = currentIndex * (itemWidth + gap);

                track.style.transform = `translateX(-${offset}px)`;
            }

            function nextSlide() {
                const maxIndex = getMaxIndex();
                if (currentIndex >= maxIndex) {
                    currentIndex = 0;
                } else {
                    currentIndex++;
                }
                updateCarousel();
            }

            function prevSlide() {
                const maxIndex = getMaxIndex();
                if (currentIndex <= 0) {
                    currentIndex = maxIndex;
                } else {
                    currentIndex--;
                }
                updateCarousel();
            }

            function startAutoplay() {
                if (autoplayTimer) return;
                autoplayTimer = setInterval(nextSlide, delay);
            }

            function stopAutoplay() {
                if (autoplayTimer) {
                    clearInterval(autoplayTimer);
                    autoplayTimer = null;
                }
            }

            // Click handlers
            nextBtn.addEventListener('click', () => {
                nextSlide();
                stopAutoplay(); // pause/stop autoplay on click
            });

            prevBtn.addEventListener('click', () => {
                prevSlide();
                stopAutoplay(); // pause/stop autoplay on click
            });

            // Hover handlers to pause auto-loop
            container.addEventListener('mouseenter', stopAutoplay);
            container.addEventListener('mouseleave', startAutoplay);
            prevBtn.addEventListener('mouseenter', stopAutoplay);
            nextBtn.addEventListener('mouseenter', stopAutoplay);

            // Responsive handling
            window.addEventListener('resize', () => {
                updateCarousel();
            });

            // Initial setup
            updateCarousel();
            startAutoplay();
        });
    </script>
</x-app-layout>
