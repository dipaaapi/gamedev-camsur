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
        
        .calendar-day {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
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
                   class="flex items-center gap-2 px-5 py-3 rounded-xl transition-all cursor-pointer w-full sm:w-auto justify-center bg-amber-500/10 border border-amber-500/30 text-amber-400 font-extrabold shadow-[0_0_12px_rgba(245,158,11,0.15)]">
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
                   class="flex items-center gap-2 px-5 py-3 rounded-xl transition-all cursor-pointer w-full sm:w-auto justify-center border border-transparent text-slate-500 hover:text-slate-300">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    Student Boards
                </a>
            </div>

            <!-- Tab Panels Container -->
            <div class="mt-8">
                
                <!-- Calendar Tab -->
                <div class="max-w-4xl mx-auto mb-20">
                    <div class="bg-slate-900/40 border border-slate-800/80 rounded-3xl p-6 backdrop-blur-md relative overflow-hidden shadow-2xl">
                        <div class="absolute top-2 left-2 w-3 h-3 border-t-2 border-l-2 border-amber-500/30"></div>
                        <div class="absolute top-2 right-2 w-3 h-3 border-t-2 border-r-2 border-amber-500/30"></div>
                        <div class="absolute bottom-2 left-2 w-3 h-3 border-b-2 border-l-2 border-amber-500/30"></div>
                        <div class="absolute bottom-2 right-2 w-3 h-3 border-b-2 border-r-2 border-amber-500/30"></div>

                        <!-- Calendar Header -->
                        <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-800/80">
                            <div class="flex items-center gap-3">
                                <span class="p-2.5 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-400">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </span>
                                <div>
                                    <h2 class="text-xl font-bold text-white tracking-wide">Interactive Calendar</h2>
                                    <p class="text-xs text-slate-500">Click highlighted dates to view event details</p>
                                </div>
                            </div>
                            
                            <!-- Month Navigation -->
                            <div class="flex items-center gap-2 bg-slate-950/40 border border-slate-800 rounded-xl p-1">
                                <button @click="prevMonth()" class="p-2 hover:bg-slate-800 hover:text-white rounded-lg text-slate-400 transition-colors cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                                    </svg>
                                </button>
                                <span class="text-xs font-bold text-slate-300 px-3 min-w-[100px] text-center uppercase tracking-wider" x-text="monthNames[currentMonth] + ' ' + currentYear"></span>
                                <button @click="nextMonth()" class="p-2 hover:bg-slate-800 hover:text-white rounded-lg text-slate-400 transition-colors cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                            <!-- Calendar Grid -->
                            <div class="md:col-span-12">
                                <!-- Days of Week Headers -->
                                <div class="grid grid-cols-7 gap-1 text-center mb-2">
                                    <template x-for="day in ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa']">
                                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest py-1" x-text="day"></span>
                                    </template>
                                </div>

                                <!-- Calendar Days -->
                                <div class="grid grid-cols-7 gap-1">
                                    <!-- Blank days for start offset -->
                                    <template x-for="blank in blankDays">
                                        <div class="aspect-square bg-transparent"></div>
                                    </template>

                                    <!-- Calendar Day Blocks -->
                                    <template x-for="day in daysInMonthList" :key="day">
                                        <div @click="selectDate(day)"
                                             class="aspect-square rounded-lg flex flex-col items-center justify-between p-1.5 calendar-day cursor-pointer border relative"
                                             :class="{
                                                 'bg-amber-500/10 border-amber-500/30 hover:bg-amber-500/20': hasEvents(day) && !isSelected(day),
                                                 'bg-brand-dark/30 border-brand/50 text-white font-bold scale-[1.03] shadow-[0_0_12px_rgba(99,102,241,0.25)]': isSelected(day),
                                                 'bg-slate-950/20 border-slate-800 hover:bg-slate-800/40 text-slate-400': !hasEvents(day) && !isSelected(day),
                                                 'border-amber-400/80': isToday(day) && !isSelected(day)
                                             }">
                                            
                                            <!-- Today indicator -->
                                            <span class="text-xs" x-text="day"></span>
                                            
                                            <!-- Dot indicators for events -->
                                            <div class="flex gap-1 justify-center mt-1">
                                                <template x-for="event in getEventsForDate(day)">
                                                    <span class="w-1.5 h-1.5 rounded-full" 
                                                          :class="event.type === 'announcement' ? 'bg-amber-400 shadow-[0_0_4px_#f59e0b]' : 'bg-indigo-400 shadow-[0_0_4px_#818cf8]'"></span>
                                                </template>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- Sidebar: Event details on the selected day -->
                        <div class="bg-slate-950/60 border border-slate-800/60 rounded-2xl mt-4 p-4 flex flex-col justify-between min-h-[250px]">
                            <div>
                                <div class="flex items-center justify-between border-b border-slate-800/80 pb-2 mb-3">
                                    <h3 class="text-xs font-bold text-slate-500 uppercase tracking-widest">
                                        Schedule Info
                                    </h3>
                                </div>
                                
                                <div class="text-sm font-semibold text-slate-300 mb-2" x-text="formatSelectedDate()"></div>
                                
                                <div class="space-y-4 max-h-[220px] overflow-y-auto pr-1">
                                    <template x-for="event in selectedDateEvents" :key="event.id">
                                        <div class="p-3 rounded-xl border bg-slate-900/60 relative group/event" 
                                             :class="event.type === 'announcement' ? 'border-amber-500/20 hover:border-amber-500/30' : 'border-indigo-500/20 hover:border-indigo-500/30'">
                                            <div class="flex items-center justify-between mb-1.5">
                                                <span class="text-[9px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider"
                                                      :class="event.type === 'announcement' ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20' : 'bg-indigo-500/10 text-indigo-400 border border-indigo-500/20'"
                                                      x-text="event.type"></span>
                                                <div class="flex items-center gap-1.5">
                                                    <span class="text-[9px] font-mono text-slate-500" x-text="event.time"></span>
                                                </div>
                                            </div>
                                            <h4 class="text-xs font-bold text-white mb-1" x-text="event.title"></h4>
                                            <p class="text-[10.5px] text-slate-400 leading-relaxed text-left" x-text="event.description"></p>
                                        </div>
                                    </template>
                                    
                                    <template x-if="selectedDateEvents.length === 0">
                                        <div class="flex flex-col items-center justify-center py-8 text-center text-slate-500">
                                            <svg class="w-8 h-8 mb-2 opacity-40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span class="text-xs font-semibold">No schedules recorded.</span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                            
                            <!-- Legend -->
                            <div class="border-t border-slate-800/80 pt-3 mt-3 flex justify-between items-center text-[9px] font-bold tracking-wider uppercase text-slate-500">
                                <div class="flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                                    <span>Academic/Events</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                    <span>Bulletins</span>
                                </div>
                            </div>
                        </div>
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
                
                currentYear: new Date().getFullYear(),
                currentMonth: new Date().getMonth(),
                monthNames: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
                
                selectedDay: new Date().getDate(),
                selectedDateEvents: [],
                
                // Data bound from PHP
                schedules: @json($schedules),

                get blankDays() {
                    let firstDayOfMonth = new Date(this.currentYear, this.currentMonth, 1).getDay();
                    let blanks = [];
                    for (let i = 0; i < firstDayOfMonth; i++) {
                        blanks.push(i);
                    }
                    return blanks;
                },
                
                get daysInMonthList() {
                    let numDays = new Date(this.currentYear, this.currentMonth + 1, 0).getDate();
                    let days = [];
                    for (let i = 1; i <= numDays; i++) {
                        days.push(i);
                    }
                    return days;
                },
                
                init() {
                    this.updateSelectedDateEvents();
                },

                isToday(day) {
                    const today = new Date();
                    return today.getDate() === day && 
                           today.getMonth() === this.currentMonth && 
                           today.getFullYear() === this.currentYear;
                },

                isSelected(day) {
                    return this.selectedDay === day;
                },

                selectDate(day) {
                    this.selectedDay = day;
                    this.updateSelectedDateEvents();
                },

                formatSelectedDate() {
                    return `${this.monthNames[this.currentMonth]} ${this.selectedDay}, ${this.currentYear}`;
                },

                getEventsForDate(day) {
                    const monthStr = String(this.currentMonth + 1).padStart(2, '0');
                    const dayStr = String(day).padStart(2, '0');
                    const targetDateStr = `${this.currentYear}-${monthStr}-${dayStr}`;
                    return this.schedules.filter(s => s.date === targetDateStr);
                },

                hasEvents(day) {
                    return this.getEventsForDate(day).length > 0;
                },

                updateSelectedDateEvents() {
                    this.selectedDateEvents = this.getEventsForDate(this.selectedDay);
                },

                prevMonth() {
                    if (this.currentMonth === 0) {
                        this.currentMonth = 11;
                        this.currentYear--;
                    } else {
                        this.currentMonth--;
                    }
                    this.selectedDay = 1;
                    this.updateSelectedDateEvents();
                },

                nextMonth() {
                    if (this.currentMonth === 11) {
                        this.currentMonth = 0;
                        this.currentYear++;
                    } else {
                        this.currentMonth++;
                    }
                    this.selectedDay = 1;
                    this.updateSelectedDateEvents();
                }
            }
        }
    </script>
</body>
</html>
