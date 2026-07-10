<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark scroll-smooth">
<head>
    <x-head title="Public Information - GameDev CamSur">
        .gradient-text {
            background: linear-gradient(to right, #f59e0b, #ec4899);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: inline-block;
        }
        
        .announcement-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .announcement-card:hover {
            transform: translateY(-4px);
            border-color: rgba(245, 158, 11, 0.4);
            box-shadow: 0 10px 30px -10px rgba(245, 158, 11, 0.15);
        }
    </x-head>
</head>
@php
    $user = Auth::user();
    $canManageStaff = $user && in_array($user->users_level, [0, 1, 2]); // Developer, Admin, Teacher
    $canPostStudent = $user && in_array($user->users_level, [0, 1, 2, 3]); // Developer, Admin, Teacher, Student
@endphp
<body class="antialiased min-h-screen flex flex-col relative overflow-x-hidden" x-data="publicInfoApp()">
    <x-loading-screen />
    


    <!-- Navigation Bar -->
    <x-guest-nav activePage="public-info" />

    <!-- Main Content -->
    <main class="flex-grow flex flex-col items-center pt-32 pb-20 px-6 relative">
        <div class="max-w-7xl mx-auto w-full">
            
            @if(session('success'))
                <div class="mb-8 p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm flex items-center gap-3 backdrop-blur-sm">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if($errors->any())
                <div class="mb-8 p-4 rounded-2xl bg-rose-500/10 border border-rose-500/20 text-rose-400 text-sm flex flex-col gap-1 backdrop-blur-sm">
                    <div class="flex items-center gap-3 font-semibold mb-1">
                        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Please correct the errors below:</span>
                    </div>
                    <ul class="list-disc list-inside pl-8">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <!-- Page Header -->
            <div class="text-center mb-16">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-slate-800/50 border border-slate-700/50 text-sm text-amber-400 mb-6 backdrop-blur-sm">
                    <span class="relative flex h-2 w-2">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-400"></span>
                    </span>
                    Bulletins & News Hub
                </div>
                <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight mb-4 leading-tight">
                    Public <span class="gradient-text">Information</span>
                </h1>
                <p class="text-slate-400 text-lg max-w-2xl mx-auto text-center">
                    Stay updated with the latest happenings, academic calendars, general announcements, and student achievements at GameDev CamSur.
                </p>
            </div>

            <!-- Tab Navigation -->
            <div class="flex flex-wrap items-center justify-center gap-3 mb-12 bg-slate-950/40 border border-slate-800/80 p-2 rounded-2xl max-w-2xl mx-auto backdrop-blur-sm shadow-xl font-mono text-xs uppercase tracking-wider font-bold">
                <a href="{{ route('public-info', ['tab' => 'calendar']) }}" 
                   class="flex items-center gap-2 px-5 py-3 rounded-xl transition-all cursor-pointer w-full sm:w-auto justify-center border border-transparent text-slate-500 hover:text-slate-300">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Calendar
                </a>
                <a href="{{ route('public-info', ['tab' => 'announcements']) }}" 
                   class="flex items-center gap-2 px-5 py-3 rounded-xl transition-all cursor-pointer w-full sm:w-auto justify-center bg-amber-500/10 border border-amber-500/30 text-amber-400 font-extrabold shadow-[0_0_12px_rgba(245,158,11,0.15)]">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    Announcements
                </a>
                <a href="{{ route('public-info', ['tab' => 'student-board']) }}" 
                   class="flex items-center gap-2 px-5 py-3 rounded-xl transition-all cursor-pointer w-full sm:w-auto justify-center border border-transparent text-slate-500 hover:text-slate-300">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    Student Boards
                </a>
            </div>

            <!-- Tab Panels Container -->
            <div class="mt-8">
                
                <!-- Announcements Tab -->
                <div class="max-w-4xl mx-auto mb-20">
                    <div class="bg-slate-900/40 border border-slate-800/80 rounded-3xl p-6 backdrop-blur-md relative overflow-hidden shadow-2xl flex flex-col gap-6">
                        <div class="absolute top-2 left-2 w-3 h-3 border-t-2 border-l-2 border-amber-500/30"></div>
                        <div class="absolute top-2 right-2 w-3 h-3 border-t-2 border-r-2 border-amber-500/30"></div>
                        <div class="absolute bottom-2 left-2 w-3 h-3 border-b-2 border-l-2 border-amber-500/30"></div>
                        <div class="absolute bottom-2 right-2 w-3 h-3 border-b-2 border-r-2 border-amber-500/30"></div>

                        <div class="flex items-center justify-between pb-3 border-b border-slate-800/80">
                            <div class="flex items-center gap-3">
                                <span class="p-2 bg-amber-500/10 border border-amber-500/20 text-amber-400 rounded-xl">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                    </svg>
                                </span>
                                <h2 class="text-xl font-bold text-white tracking-wide">Public Announcements</h2>
                            </div>
                            
                            <template x-if="announcements.length > 5">
                                <button @click="showMoreAnnouncements = true" 
                                        class="px-4 py-2 rounded-xl bg-slate-950/60 border border-slate-800 hover:border-slate-700 text-xs font-bold uppercase tracking-wider text-slate-400 hover:text-white transition-all cursor-pointer">
                                    Archive List
                                </button>
                            </template>
                        </div>

                        <!-- Featured Announcement (Latest Announcement) -->
                        <template x-if="announcements.length > 0">
                            <div class="announcement-card bg-slate-900/40 border border-slate-800/80 rounded-2xl p-5 backdrop-blur-md relative overflow-hidden flex flex-col gap-3 shadow-xl group/ann">
                                <div class="flex items-center justify-between">
                                    <span class="text-[9px] font-bold px-2 py-0.5 rounded bg-amber-500/15 text-amber-400 border border-amber-500/30 uppercase tracking-widest">
                                        Latest Announcement
                                    </span>
                                    <span class="text-[10px] font-mono text-slate-500" x-text="announcements[0].date"></span>
                                </div>
                                <h3 class="text-lg font-bold text-white leading-tight group-hover:text-amber-400 transition-colors" x-text="announcements[0].title"></h3>
                                <p class="text-xs text-slate-400 leading-relaxed text-justify" x-text="announcements[0].excerpt"></p>
                                
                                <!-- Open Details Button -->
                                <div class="flex justify-between items-center mt-2 pt-3 border-t border-slate-800/80">
                                    <div class="flex items-center gap-3">
                                        <span class="text-[10px] text-slate-500 uppercase tracking-wider font-mono" x-text="'By: ' + announcements[0].author"></span>
                                    </div>
                                    <button @click="openAnnouncementModal(announcements[0])" 
                                            class="text-xs font-bold text-amber-400 hover:text-amber-300 flex items-center gap-1 cursor-pointer transition-colors">
                                        Read Announcement
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </template>

                        <!-- Recent Announcements Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <template x-for="(ann, index) in announcements.slice(1, 5)" :key="ann.id">
                                <div class="announcement-card bg-slate-950/40 border border-slate-900 hover:border-slate-800 rounded-xl p-4 flex flex-col justify-between relative transition-all group/ann">
                                    <div>
                                        <div class="flex justify-between items-center mb-2">
                                            <span class="text-[9px] font-bold px-2 py-0.5 rounded bg-slate-800/80 text-slate-400 border border-slate-800 uppercase tracking-widest">
                                                Recent Bulletin
                                            </span>
                                            <span class="text-[10px] font-mono text-slate-500" x-text="ann.date"></span>
                                        </div>
                                        <h4 class="text-sm font-bold text-white tracking-wide mb-2" x-text="ann.title"></h4>
                                        <p class="text-[11px] text-slate-400 leading-relaxed line-clamp-3 text-justify mb-4" x-text="ann.excerpt"></p>
                                    </div>
                                    
                                    <div class="flex justify-between items-center mt-auto border-t border-slate-800/40 pt-2">
                                        <span class="text-[9px] text-slate-500 font-mono" x-text="'By: ' + ann.author"></span>
                                        <button @click="openAnnouncementModal(ann)"
                                                class="text-[10px] font-bold text-amber-500 hover:text-amber-400 flex items-center gap-0.5 cursor-pointer">
                                            View Details
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <!-- Detailed Announcement Modal -->
    <div x-show="announcementModalOpen" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         style="display: none;">
        
        <div class="bg-slate-900 border border-slate-800 max-w-lg w-full rounded-2xl p-6 shadow-2xl relative" @click.away="announcementModalOpen = false">
            <button @click="announcementModalOpen = false" class="absolute top-4 right-4 text-slate-500 hover:text-white cursor-pointer transition-colors">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            
            <div class="mb-4">
                <span class="text-[9px] font-bold px-2 py-0.5 rounded bg-amber-500/10 text-amber-400 border border-amber-500/20 uppercase tracking-widest" x-text="activeAnnouncement.date"></span>
            </div>
            
            <h3 class="text-xl font-bold text-white mb-3" x-text="activeAnnouncement.title"></h3>
            
            <template x-if="activeAnnouncement.media && activeAnnouncement.media.length > 0">
                <div class="mb-4 w-full aspect-video rounded-xl bg-slate-950/60 overflow-hidden border border-slate-800">
                    <template x-if="activeAnnouncement.media[0].type === 'image'">
                        <img :src="activeAnnouncement.media[0].url" class="w-full h-full object-cover">
                    </template>
                    <template x-if="activeAnnouncement.media[0].type === 'video'">
                        <iframe class="w-full h-full border-0" :src="getEmbedUrl(activeAnnouncement.media[0].url)" allowfullscreen></iframe>
                    </template>
                </div>
            </template>
            
            <p class="text-sm text-slate-300 leading-relaxed text-justify mb-6 whitespace-pre-line" x-text="activeAnnouncement.content"></p>
            
            <!-- Poll / Survey Component -->
            <template x-if="activeAnnouncement.poll_question">
                <div class="mt-6 p-4 rounded-xl border border-amber-500/20 bg-slate-950/60 text-left mb-6">
                    <h4 class="text-xs font-bold text-amber-400 uppercase tracking-wider mb-3" x-text="'Poll: ' + activeAnnouncement.poll_question"></h4>
                    
                    <!-- If user is logged in and HAS NOT voted yet -->
                    <template x-if="userId && !hasVoted(activeAnnouncement)">
                        <form :action="`/public-info/announcements/${activeAnnouncement.id}/vote`" method="POST" class="space-y-2">
                            @csrf
                            <template x-for="(opt, idx) in activeAnnouncement.poll_options" :key="idx">
                                <label class="flex items-center gap-3 p-2.5 rounded-lg border border-slate-800 hover:border-amber-500/40 bg-slate-900/40 hover:bg-slate-900 cursor-pointer transition-all">
                                    <input type="radio" name="option" :value="idx" required class="rounded-full bg-slate-950 border-slate-800 text-amber-500 focus:ring-amber-500 focus:ring-offset-slate-950">
                                    <span class="text-xs text-slate-300 font-semibold" x-text="opt"></span>
                                </label>
                            </template>
                            <div class="flex justify-end pt-2">
                                <button type="submit" class="px-4 py-2 rounded-lg bg-amber-600 hover:bg-amber-500 text-[10px] font-bold uppercase tracking-wider text-white transition-all cursor-pointer">
                                    Submit Vote
                                </button>
                            </div>
                        </form>
                    </template>

                    <!-- If user is not logged in OR has already voted -->
                    <template x-if="!userId || hasVoted(activeAnnouncement)">
                        <div class="space-y-3">
                            <template x-for="(res, idx) in getPollResults(activeAnnouncement)" :key="idx">
                                <div class="space-y-1">
                                    <div class="flex items-center justify-between text-xs font-semibold">
                                        <span :class="hasVoted(activeAnnouncement) && getVotedOptionIndex(activeAnnouncement) === idx ? 'text-amber-400 font-bold' : 'text-slate-300'" x-text="res.option"></span>
                                        <span class="text-slate-400" x-text="res.percentage + '% (' + res.count + ')'"></span>
                                    </div>
                                    <div class="w-full h-2 bg-slate-900 rounded-full overflow-hidden border border-slate-800">
                                        <div class="h-full bg-amber-500/80 transition-all duration-500" :style="'width: ' + res.percentage + '%'"></div>
                                    </div>
                                </div>
                            </template>
                            <div class="flex items-center justify-between text-[10px] font-bold text-slate-500 uppercase tracking-wider pt-2 border-t border-slate-900">
                                <span x-text="getTotalVotes(activeAnnouncement) + ' total votes'"></span>
                                <template x-if="!userId">
                                    <a href="{{ route('login') }}" class="text-amber-500 hover:underline">Log in to vote</a>
                                </template>
                                <template x-if="userId">
                                    <span class="text-emerald-500">Vote Casted</span>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </template>
            
            <div class="flex justify-end">
                <button @click="announcementModalOpen = false" class="px-5 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-xs font-bold uppercase tracking-wider text-white transition-colors cursor-pointer">
                    Dismiss
                </button>
            </div>
        </div>
    </div>

    <!-- More Bulletins Archive List Modal -->
    <div x-show="showMoreAnnouncements" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         style="display: none;">
        
        <div class="bg-slate-900 border border-slate-800 max-w-2xl w-full rounded-2xl p-6 shadow-2xl relative flex flex-col max-h-[85vh]" @click.away="showMoreAnnouncements = false">
            <button @click="showMoreAnnouncements = false" class="absolute top-4 right-4 text-slate-500 hover:text-white cursor-pointer transition-colors">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            
            <h3 class="text-xl font-bold text-white mb-4 pb-2 border-b border-slate-800">Announcements Archive</h3>
            
            <div class="overflow-y-auto space-y-4 pr-2 flex-grow mb-4">
                <template x-for="ann in announcements" :key="ann.id">
                    <div class="p-4 rounded-xl border border-slate-800 bg-slate-950/40 hover:border-slate-700 transition-colors">
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="text-[9px] font-bold px-2 py-0.5 rounded bg-amber-500/10 text-amber-400 border border-amber-500/20 uppercase tracking-widest" x-text="ann.date"></span>
                        </div>
                        <h4 class="text-sm font-bold text-white mb-1" x-text="ann.title"></h4>
                        <p class="text-xs text-slate-400 leading-relaxed text-justify mb-2" x-text="ann.excerpt"></p>
                        <button @click="openAnnouncementModal(ann)" class="text-xs text-amber-400 hover:text-amber-300 font-semibold cursor-pointer">
                            Read Full Details &rarr;
                        </button>
                    </div>
                </template>
            </div>
            
            <div class="flex justify-end pt-2 border-t border-slate-800">
                <button @click="showMoreAnnouncements = false" class="px-5 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-xs font-bold uppercase tracking-wider text-white transition-colors cursor-pointer">
                    Close Archive
                </button>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <x-sitemap />
    <x-footer />
    <x-scroll-to-top />

    <script>
        function publicInfoApp() {
            return {
                userId: @json(Auth::id()),
                
                announcementModalOpen: false,
                activeAnnouncement: {},
                showMoreAnnouncements: false,
                
                // Data bound from PHP
                announcements: @json($announcements),

                getPollResults(item) {
                    const votes = item.poll_votes || {};
                    const options = item.poll_options || [];
                    const totalVotes = Object.keys(votes).length;
                    
                    const counts = options.map(() => 0);
                    Object.values(votes).forEach(optIndex => {
                        if (counts[optIndex] !== undefined) {
                            counts[optIndex]++;
                        }
                    });
                    
                    return options.map((opt, idx) => {
                        const count = counts[idx];
                        const percentage = totalVotes > 0 ? Math.round((count / totalVotes) * 100) : 0;
                        return {
                            option: opt,
                            count: count,
                            percentage: percentage
                        };
                    });
                },
                
                hasVoted(item) {
                    if (!this.userId) return false;
                    const votes = item.poll_votes || {};
                    return votes[this.userId] !== undefined;
                },
                
                getVotedOptionIndex(item) {
                    if (!this.userId) return null;
                    const votes = item.poll_votes || {};
                    return votes[this.userId];
                },
                
                getTotalVotes(item) {
                    return Object.keys(item.poll_votes || {}).length;
                },

                getEmbedUrl(url) {
                    if (!url) return '';
                    let regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
                    let match = url.match(regExp);
                    if (match && match[2].length === 11) {
                        return 'https://www.youtube.com/embed/' + match[2];
                    }
                    return url;
                }
            }
        }
    </script>
</body>
</html>
