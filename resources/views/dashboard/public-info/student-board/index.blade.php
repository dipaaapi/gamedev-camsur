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
        
        .student-board-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .student-board-card:hover {
            transform: translateY(-4px);
            border-color: rgba(99, 102, 241, 0.4);
            box-shadow: 0 10px 30px -10px rgba(99, 102, 241, 0.15);
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
                   class="flex items-center gap-2 px-5 py-3 rounded-xl transition-all cursor-pointer w-full sm:w-auto justify-center border border-transparent text-slate-500 hover:text-slate-300">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    Announcements
                </a>
                <a href="{{ route('public-info', ['tab' => 'student-board']) }}" 
                   class="flex items-center gap-2 px-5 py-3 rounded-xl transition-all cursor-pointer w-full sm:w-auto justify-center bg-indigo-500/10 border border-indigo-500/30 text-indigo-400 font-extrabold shadow-[0_0_12px_rgba(99,102,241,0.15)]">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    Student Boards
                </a>
            </div>

            <!-- Tab Panels Container -->
            <div class="mt-8">
                
                <!-- Student Board Tab -->
                <div class="mb-20">
                    <div class="flex flex-col md:flex-row md:items-end justify-between mb-12">
                        <div class="mb-4 md:mb-0">
                            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-xs text-indigo-400 mb-4 tracking-wide font-semibold">
                                🔥 Student Spotlight
                            </div>
                            <h2 class="text-4xl font-extrabold text-white tracking-tight">Student Board & Happenings</h2>
                            <p class="text-slate-400 text-sm mt-1">Latest achievements, news, and highlights straight from our student creators.</p>
                        </div>
                        
                        <!-- Quick Filter Tabs -->
                        <div class="flex items-center gap-4 self-start md:self-auto">
                            <div class="flex items-center gap-2 bg-slate-950/60 border border-slate-800/80 p-1.5 rounded-xl font-mono text-[10px] font-bold uppercase tracking-wider">
                                <button @click="studentFilter = 'all'" 
                                        class="px-4 py-1.5 rounded-lg transition-colors cursor-pointer"
                                        :class="studentFilter === 'all' ? 'bg-brand text-white' : 'text-slate-500 hover:text-slate-300'">
                                    All
                                </button>
                                <button @click="studentFilter = 'achievement'" 
                                        class="px-4 py-1.5 rounded-lg transition-colors cursor-pointer"
                                        :class="studentFilter === 'achievement' ? 'bg-brand text-white' : 'text-slate-500 hover:text-slate-300'">
                                    Achievements
                                </button>
                                <button @click="studentFilter = 'happening'" 
                                        class="px-4 py-1.5 rounded-lg transition-colors cursor-pointer"
                                        :class="studentFilter === 'happening' ? 'bg-brand text-white' : 'text-slate-500 hover:text-slate-300'">
                                    Happenings
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Talk of the Campus (Featured/Highlighted Spotlight Separately On Top) -->
                    <template x-if="spotlightPost">
                        <div class="mb-10 p-6 rounded-3xl border border-indigo-500/30 bg-slate-900/60 relative overflow-hidden shadow-2xl group flex flex-col md:flex-row gap-8 items-stretch backdrop-blur-md">
                            <!-- HUD style accents -->
                            <div class="absolute top-2 left-2 w-3 h-3 border-t-2 border-l-2 border-indigo-500/30"></div>
                            <div class="absolute top-2 right-2 w-3 h-3 border-t-2 border-r-2 border-indigo-500/30"></div>
                            <div class="absolute bottom-2 left-2 w-3 h-3 border-b-2 border-l-2 border-indigo-500/30"></div>
                            <div class="absolute bottom-2 right-2 w-3 h-3 border-b-2 border-r-2 border-indigo-500/30"></div>
                            
                            <!-- Large Image / Visual decoration / Custom uploaded media -->
                            <div class="w-full md:w-2/5 aspect-video md:aspect-auto min-h-[220px] rounded-2xl bg-slate-950/80 border border-indigo-500/20 relative overflow-hidden flex items-center justify-center shrink-0"
                                 x-data="{ currentImg: 0, getImages() { return spotlightPost.media ? spotlightPost.media.filter(m => m.type === 'image') : []; } }">
                                <template x-if="spotlightPost.media && spotlightPost.media.length > 0">
                                    <div class="absolute inset-0 w-full h-full z-10">
                                        <!-- Image Carousel -->
                                        <template x-if="getImages().length > 0">
                                            <div class="relative w-full h-full">
                                                <img :src="getImages()[currentImg].url" class="w-full h-full object-cover">
                                                
                                                <!-- Carousel dots -->
                                                <template x-if="getImages().length > 1">
                                                    <div class="absolute inset-x-0 bottom-2 flex justify-center gap-1 z-20">
                                                        <template x-for="(img, idx) in getImages()" :key="idx">
                                                            <button @click="currentImg = idx" class="w-1.5 h-1.5 rounded-full transition-all duration-300" :class="currentImg === idx ? 'bg-indigo-500 w-3' : 'bg-slate-500/50'"></button>
                                                        </template>
                                                    </div>
                                                </template>
                                                <!-- Carousel Arrows -->
                                                <template x-if="getImages().length > 1">
                                                    <button @click="currentImg = (currentImg - 1 + getImages().length) % getImages().length" class="absolute left-2 top-1/2 -translate-y-1/2 p-1.5 rounded-full bg-slate-950/85 border border-slate-800 text-slate-300 hover:text-white z-20 cursor-pointer">
                                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                                                        </svg>
                                                    </button>
                                                </template>
                                                <template x-if="getImages().length > 1">
                                                    <button @click="currentImg = (currentImg + 1) % getImages().length" class="absolute right-2 top-1/2 -translate-y-1/2 p-1.5 rounded-full bg-slate-950/85 border border-slate-800 text-slate-300 hover:text-white z-20 cursor-pointer">
                                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                                                        </svg>
                                                    </button>
                                                </template>
                                            </div>
                                        </template>

                                        <!-- Video fallback -->
                                        <template x-if="getImages().length === 0 && spotlightPost.media.some(m => m.type === 'video')">
                                            <iframe class="w-full h-full border-0" :src="getEmbedUrl(spotlightPost.media.find(m => m.type === 'video').url)" allowfullscreen></iframe>
                                        </template>
                                    </div>
                                </template>
                                <div class="absolute inset-0 bg-gradient-to-br from-indigo-950/40 via-slate-950/90 to-pink-950/40 group-hover:scale-105 transition-transform duration-700 z-0"></div>
                                <div class="z-20 flex flex-col items-center gap-2 bg-slate-950/30 p-2 px-4 rounded-xl border border-white/5 backdrop-blur-sm">
                                    <span class="text-2xl">🔥</span>
                                    <span class="font-mono text-xs tracking-widest text-indigo-400 font-bold uppercase">Talk of the Campus</span>
                                </div>
                                <div class="absolute w-32 h-32 rounded-full blur-3xl z-0 bg-indigo-500/20"></div>
                            </div>
                            
                            <div class="flex-grow flex flex-col justify-between w-full">
                                <div>
                                    <div class="flex items-center justify-between mb-4">
                                        <span class="text-[10px] font-bold px-2.5 py-1 rounded bg-indigo-500/10 text-indigo-300 border border-indigo-500/20 uppercase tracking-wider"
                                              x-text="'Featured ' + spotlightPost.category"></span>
                                        <span class="text-[11px] font-mono text-slate-500" x-text="spotlightPost.date"></span>
                                    </div>
                                    
                                    <h3 class="text-xl md:text-2xl font-extrabold text-white mb-3 group-hover:text-indigo-400 transition-colors leading-snug" x-text="spotlightPost.title"></h3>
                                    <p class="text-xs md:text-sm text-slate-300 leading-relaxed text-justify mb-4" x-text="spotlightPost.content"></p>

                                    <!-- Attachments for Spotlight Post -->
                                    <template x-if="spotlightPost.media && spotlightPost.media.some(m => m.type === 'attachment')">
                                        <div class="mt-4 border-t border-slate-800/80 pt-3">
                                            <h5 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2.5 flex items-center gap-1.5">
                                                <svg class="w-4 h-4 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                                Learning Materials / Attachments
                                            </h5>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mb-4">
                                                <template x-for="mediaItem in spotlightPost.media.filter(m => m.type === 'attachment')" :key="mediaItem.url">
                                                    <a :href="mediaItem.url" download class="flex items-center justify-between p-2.5 rounded-xl bg-slate-950/40 border border-slate-800 hover:border-indigo-500/30 text-xs text-slate-300 hover:text-white transition-all">
                                                        <div class="flex items-center gap-2 truncate">
                                                            <span class="text-indigo-400 text-sm" x-text="getFileIcon(mediaItem.extension || '')"></span>
                                                            <span class="truncate font-semibold text-[11px]" x-text="mediaItem.name || 'Attachment'"></span>
                                                        </div>
                                                        <span class="text-[9px] text-slate-500 shrink-0 ml-2" x-text="formatBytes(mediaItem.size || 0)"></span>
                                                    </a>
                                                </template>
                                            </div>
                                        </div>
                                    </template>
                                    
                                    <!-- Poll Component for Featured Post -->
                                    <template x-if="spotlightPost.poll_question">
                                        <div class="mt-4 p-4 rounded-xl border border-indigo-500/20 bg-slate-950/40 text-left">
                                            <h4 class="text-xs font-bold text-indigo-400 uppercase tracking-wider mb-3" x-text="'Poll: ' + spotlightPost.poll_question"></h4>
                                            
                                            <!-- If user is logged in and HAS NOT voted yet -->
                                            <template x-if="userId && !hasVoted(spotlightPost)">
                                                <form :action="`/public-info/posts/${spotlightPost.id}/vote`" method="POST" class="space-y-2">
                                                    @csrf
                                                    <template x-for="(opt, idx) in spotlightPost.poll_options" :key="idx">
                                                        <label class="flex items-center gap-3 p-2.5 rounded-lg border border-slate-800 hover:border-indigo-500/40 bg-slate-900/20 hover:bg-slate-900/60 cursor-pointer transition-all">
                                                            <input type="radio" name="option" :value="idx" required class="rounded-full bg-slate-950 border-slate-800 text-indigo-500 focus:ring-indigo-500 focus:ring-offset-slate-950">
                                                            <span class="text-xs text-slate-300 font-semibold" x-text="opt"></span>
                                                        </label>
                                                    </template>
                                                    <div class="flex justify-end pt-2">
                                                        <button type="submit" class="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-[10px] font-bold uppercase tracking-wider text-white transition-all cursor-pointer">
                                                            Submit Vote
                                                        </button>
                                                    </div>
                                                </form>
                                            </template>

                                            <!-- If user is not logged in OR has already voted -->
                                            <template x-if="!userId || hasVoted(spotlightPost)">
                                                <div class="space-y-3">
                                                    <template x-for="(res, idx) in getPollResults(spotlightPost)" :key="idx">
                                                        <div class="space-y-1">
                                                            <div class="flex items-center justify-between text-xs font-semibold">
                                                                <span :class="hasVoted(spotlightPost) && getVotedOptionIndex(spotlightPost) === idx ? 'text-indigo-400 font-bold' : 'text-slate-300'" x-text="res.option"></span>
                                                                <span class="text-slate-400" x-text="res.percentage + '% (' + res.count + ')'"></span>
                                                            </div>
                                                            <div class="w-full h-2 bg-slate-900 rounded-full overflow-hidden border border-slate-800">
                                                                <div class="h-full bg-indigo-500/80 transition-all duration-500" :style="'width: ' + res.percentage + '%'"></div>
                                                            </div>
                                                        </div>
                                                    </template>
                                                    <div class="flex items-center justify-between text-[10px] font-bold text-slate-500 uppercase tracking-wider pt-2 border-t border-slate-800/60">
                                                        <span x-text="getTotalVotes(spotlightPost) + ' total votes'"></span>
                                                        <template x-if="!userId">
                                                            <a href="{{ route('login') }}" class="text-indigo-400 hover:underline">Log in to vote</a>
                                                        </template>
                                                        <template x-if="userId">
                                                            <span class="text-emerald-500">Vote Casted</span>
                                                        </template>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </template>
                                </div>
                                
                                <div class="flex items-center justify-between border-t border-slate-800/80 pt-4 mt-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-slate-800 border border-slate-700 flex items-center justify-center font-mono text-xs font-bold text-brand-light" x-text="spotlightPost.author_initials"></div>
                                        <span class="text-xs text-slate-400 font-semibold" x-text="spotlightPost.author"></span>
                                    </div>
                                    <span class="text-[10px] font-mono text-indigo-500/60 uppercase" x-text="'ID: #' + spotlightPost.id"></span>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- Empty State -->
                    <template x-if="filteredStudentBoard().length === 0 && !spotlightPost">
                        <div class="flex flex-col items-center justify-center py-16 text-center text-slate-500 bg-slate-900/20 border border-slate-800 rounded-3xl">
                            <svg class="w-12 h-12 mb-3 opacity-40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 4a2 2 0 012 2v6a2 2 0 01-2 2h-2m-4-11V4a2 2 0 114 0v2m-4 5h4" />
                            </svg>
                            <span class="text-sm font-semibold">No spotlight posts found.</span>
                        </div>
                    </template>

                    <!-- Student Board Grid (Remaining items below) -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <template x-for="item in filteredStudentBoard()" :key="item.id">
                            <div class="student-board-card bg-slate-900/30 border border-slate-800/80 rounded-2xl p-5 flex flex-col justify-between backdrop-blur-sm relative overflow-hidden group shadow-xl"
                                 x-data="{ currentImg: 0, getImages() { return item.media ? item.media.filter(m => m.type === 'image') : []; } }">
                                <!-- HUD style accents -->
                                <div class="absolute top-2 left-2 w-2 h-2 border-t border-l border-indigo-500/20"></div>
                                <div class="absolute top-2 right-2 w-2 h-2 border-t border-r border-indigo-500/20"></div>
                                
                                <div>
                                    <!-- Tag & Date -->
                                    <div class="flex items-center justify-between mb-4">
                                        <span class="text-[9px] font-bold px-2 py-0.5 rounded uppercase tracking-wider"
                                              :class="item.category === 'achievement' ? 'bg-indigo-500/10 text-indigo-400 border border-indigo-500/20' : 'bg-pink-500/10 text-pink-400 border border-pink-500/20'"
                                              x-text="item.category"></span>
                                        <span class="text-[10px] font-mono text-slate-500" x-text="item.date"></span>
                                    </div>

                                    <!-- Image/Media Container -->
                                    <div class="relative w-full aspect-video rounded-xl bg-slate-950/60 border border-slate-900 overflow-hidden mb-4 flex items-center justify-center">
                                        <template x-if="item.media && item.media.length > 0">
                                            <div class="absolute inset-0 w-full h-full">
                                                <!-- Image Slider -->
                                                <template x-if="getImages().length > 0">
                                                    <div class="relative w-full h-full">
                                                        <img :src="getImages()[currentImg].url" class="w-full h-full object-cover">
                                                        
                                                        <!-- dots -->
                                                        <template x-if="getImages().length > 1">
                                                            <div class="absolute inset-x-0 bottom-2 flex justify-center gap-1 z-20">
                                                                <template x-for="(img, idx) in getImages()" :key="idx">
                                                                    <button @click="currentImg = idx" class="w-1.5 h-1.5 rounded-full transition-all duration-300" :class="currentImg === idx ? 'bg-indigo-500 w-2.5' : 'bg-slate-500/50'"></button>
                                                                </template>
                                                            </div>
                                                        </template>
                                                        <!-- arrows -->
                                                        <template x-if="getImages().length > 1">
                                                            <button @click="currentImg = (currentImg - 1 + getImages().length) % getImages().length" class="absolute left-1 top-1/2 -translate-y-1/2 p-1 rounded-full bg-slate-950/80 border border-slate-800 text-slate-300 hover:text-white z-20 cursor-pointer">
                                                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                                                                </svg>
                                                            </button>
                                                        </template>
                                                        <template x-if="getImages().length > 1">
                                                            <button @click="currentImg = (currentImg + 1) % getImages().length" class="absolute right-1 top-1/2 -translate-y-1/2 p-1 rounded-full bg-slate-950/80 border border-slate-800 text-slate-300 hover:text-white z-20 cursor-pointer">
                                                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                                                                </svg>
                                                            </button>
                                                        </template>
                                                    </div>
                                                </template>
                                                <!-- video fallback -->
                                                <template x-if="getImages().length === 0 && item.media.some(m => m.type === 'video')">
                                                    <iframe class="w-full h-full border-0" :src="getEmbedUrl(item.media.find(m => m.type === 'video').url)" allowfullscreen></iframe>
                                                </template>
                                            </div>
                                        </template>
                                        <template x-if="!item.media || item.media.length === 0">
                                            <div class="absolute inset-0 flex items-center justify-center">
                                                <div class="absolute inset-0 bg-gradient-to-br from-indigo-950/30 via-slate-950/80 to-pink-950/30 group-hover:opacity-90 transition-opacity"></div>
                                                <span class="font-mono text-[9px] tracking-widest text-slate-600 uppercase z-10" x-text="'STUDENT_MEDIA //' + item.id"></span>
                                                <!-- A subtle decoration visual representation -->
                                                <div class="absolute w-12 h-12 rounded-full blur-xl z-0" 
                                                     :class="item.category === 'achievement' ? 'bg-indigo-500/20' : 'bg-pink-500/20'"></div>
                                            </div>
                                        </template>
                                    </div>

                                    <h3 class="text-base font-bold text-white mb-2 leading-snug group-hover:text-indigo-400 transition-colors" x-text="item.title"></h3>
                                    <p class="text-xs text-slate-400 leading-relaxed text-justify mb-4" x-text="item.content"></p>

                                    <!-- Attachments for student board cards -->
                                    <template x-if="item.media && item.media.some(m => m.type === 'attachment')">
                                        <div class="mt-4 border-t border-slate-800/80 pt-3">
                                            <h5 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                                                <svg class="w-3.5 h-3.5 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                                Learning Materials / Attachments
                                            </h5>
                                            <div class="space-y-1.5 mb-4">
                                                <template x-for="mediaItem in item.media.filter(m => m.type === 'attachment')" :key="mediaItem.url">
                                                    <a :href="mediaItem.url" download class="flex items-center justify-between p-2 rounded-xl bg-slate-950/40 border border-slate-800 hover:border-indigo-500/30 text-xs text-slate-300 hover:text-white transition-colors">
                                                        <div class="flex items-center gap-2 truncate">
                                                            <span class="text-indigo-400 text-sm" x-text="getFileIcon(mediaItem.extension || '')"></span>
                                                            <span class="truncate font-semibold text-[11px]" x-text="mediaItem.name || 'Attachment'"></span>
                                                        </div>
                                                        <span class="text-[9px] text-slate-500 shrink-0 ml-2" x-text="formatBytes(mediaItem.size || 0)"></span>
                                                    </a>
                                                </template>
                                            </div>
                                        </div>
                                    </template>

                                    <!-- Poll / Survey Component -->
                                    <template x-if="item.poll_question">
                                        <div class="mt-4 p-3.5 rounded-xl border border-indigo-500/20 bg-slate-950/40 text-left">
                                            <h4 class="text-[11px] font-bold text-indigo-400 uppercase tracking-wider mb-2.5" x-text="'Poll: ' + item.poll_question"></h4>
                                            
                                            <!-- If user is logged in and HAS NOT voted yet -->
                                            <template x-if="userId && !hasVoted(item)">
                                                <form :action="`/public-info/posts/${item.id}/vote`" method="POST" class="space-y-1.5">
                                                    @csrf
                                                    <template x-for="(opt, idx) in item.poll_options" :key="idx">
                                                        <label class="flex items-center gap-2.5 p-2 rounded-lg border border-slate-800/80 hover:border-indigo-500/40 bg-slate-900/20 hover:bg-slate-900/60 cursor-pointer transition-all">
                                                            <input type="radio" name="option" :value="idx" required class="rounded-full bg-slate-950 border-slate-800 text-indigo-500 focus:ring-indigo-500 focus:ring-offset-slate-950">
                                                            <span class="text-[11px] text-slate-300 font-semibold" x-text="opt"></span>
                                                        </label>
                                                    </template>
                                                    <div class="flex justify-end pt-1.5">
                                                        <button type="submit" class="px-3.5 py-1.5 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-[9px] font-bold uppercase tracking-wider text-white transition-all cursor-pointer">
                                                            Submit Vote
                                                        </button>
                                                    </div>
                                                </form>
                                            </template>

                                            <!-- If user is not logged in OR has already voted -->
                                            <template x-if="!userId || hasVoted(item)">
                                                <div class="space-y-2">
                                                    <template x-for="(res, idx) in getPollResults(item)" :key="idx">
                                                        <div class="space-y-0.5">
                                                            <div class="flex items-center justify-between text-[11px] font-semibold">
                                                                <span :class="hasVoted(item) && getVotedOptionIndex(item) === idx ? 'text-indigo-400 font-bold' : 'text-slate-300'" x-text="res.option"></span>
                                                                <span class="text-slate-400" x-text="res.percentage + '% (' + res.count + ')'"></span>
                                                            </div>
                                                            <div class="w-full h-1.5 bg-slate-900 rounded-full overflow-hidden border border-slate-800/80">
                                                                <div class="h-full bg-indigo-500/80 transition-all duration-500" :style="'width: ' + res.percentage + '%'"></div>
                                                            </div>
                                                        </div>
                                                    </template>
                                                    <div class="flex items-center justify-between text-[9px] font-bold text-slate-500 uppercase tracking-wider pt-1.5 border-t border-slate-800/60">
                                                        <span x-text="getTotalVotes(item) + ' total votes'"></span>
                                                        <template x-if="!userId">
                                                            <a href="{{ route('login') }}" class="text-indigo-400 hover:underline">Log in to vote</a>
                                                        </template>
                                                        <template x-if="userId">
                                                            <span class="text-emerald-500">Vote Casted</span>
                                                        </template>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </template>
                                </div>
                                
                                <div class="flex items-center justify-between border-t border-slate-800/80 pt-3 mt-2">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-slate-800 border border-slate-700 flex items-center justify-center font-mono text-[9px] font-bold text-brand-light" x-text="item.author_initials"></div>
                                        <span class="text-[10px] text-slate-500 font-semibold" x-text="item.author"></span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-[9px] font-mono text-indigo-500/60 uppercase" x-text="'ID: #' + item.id"></span>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <!-- Footer -->
    <x-sitemap />
    <x-footer />
    <x-scroll-to-top />

    <script>
        function publicInfoApp() {
            return {
                userId: @json(Auth::id()),
                
                studentFilter: 'all',
                
                // Data bound from PHP
                studentBoard: @json($studentBoard),

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
                
                get spotlightPost() {
                    return this.studentBoard.find(p => p.is_spotlight === true || p.is_spotlight === 1 || p.is_spotlight === '1');
                },

                filteredStudentBoard() {
                    let posts = this.studentBoard;
                    const spotlight = this.spotlightPost;
                    if (spotlight) {
                        posts = posts.filter(p => p.id !== spotlight.id);
                    }
                    if (this.studentFilter === 'all') return posts;
                    return posts.filter(item => item.category === this.studentFilter);
                },

                getEmbedUrl(url) {
                    if (!url) return '';
                    let regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
                    let match = url.match(regExp);
                    if (match && match[2].length === 11) {
                        return 'https://www.youtube.com/embed/' + match[2];
                    }
                    return url;
                },
                getFileIcon(ext) {
                    if (!ext) return '📄';
                    ext = ext.toLowerCase();
                    if (ext === 'pdf') return '📕';
                    if (['doc', 'docx'].includes(ext)) return '📘';
                    if (['xls', 'xlsx'].includes(ext)) return '📗';
                    if (['ppt', 'pptx'].includes(ext)) return '📙';
                    return '📄';
                },
                formatBytes(bytes, decimals = 2) {
                    if (!bytes) return '0 Bytes';
                    const k = 1024;
                    const dm = decimals < 0 ? 0 : decimals;
                    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
                    const i = Math.floor(Math.log(bytes) / Math.log(k));
                    return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
                }
            }
        }
    </script>
</body>
</html>
