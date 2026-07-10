<nav x-data="{ open: false }" class="glass-nav relative z-50 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-6 h-20 flex items-stretch justify-between">
        
        <!-- Left Side: Logo -->
        <div class="flex items-center cursor-pointer shrink-0">
            <a href="{{ url('/') }}" class="flex items-center">
                <x-brand-logo />
            </a>
        </div>
        
        <!-- Right Side Container (Menu + dividing line + PH Clock) -->
        <div class="flex items-stretch gap-6 shrink-0">
            <!-- User Profile Link -->
            <a href="{{ route('profile.edit') }}" class="hidden sm:flex items-center gap-3 hover:opacity-85 transition-opacity duration-200">
                <div class="relative">
                    @if(Auth::user()->avatar)
                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="{{ Auth::user()->username }}" class="w-9 h-9 rounded-full object-cover border border-slate-700/50">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&color=7F9CF5&background=EBF4FF" alt="{{ Auth::user()->username }}" class="w-9 h-9 rounded-full border border-slate-700/50">
                    @endif
                    @php
                        $unreadMsgs = \App\Models\DirectMessage::where('receiver_id', Auth::user()->id)->where('is_read', false)->count();
                        $hasInvites = Auth::user()->pendingIncomingRequests()->count() > 0;
                    @endphp
                    @if($hasInvites || $unreadMsgs > 0)
                        <span class="absolute -top-1 -right-1 flex h-3 w-3">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-rose-500"></span>
                        </span>
                    @endif
                </div>
                <div class="flex flex-col text-left">
                    <span class="text-white text-sm font-bold tracking-normal leading-tight">{{ Auth::user()->username ?? Auth::user()->name }}</span>
                    <span class="text-[9px] text-slate-400 font-medium tracking-normal leading-none">{{ Auth::user()->role_name }}</span>
                </div>
            </a>

            <!-- Menu Dropdown -->
            <div class="hidden sm:flex items-stretch text-sm font-bold tracking-wider text-slate-300 uppercase">
                <div x-data="{ menuOpen: false }" @click.outside="menuOpen = false" class="flex items-center px-4">
                    <x-skew-button @click="menuOpen = !menuOpen" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold tracking-wider uppercase transition-all select-none">
                        <span class="flex items-center gap-2">
                            <span>Menu</span>
                            <svg :class="menuOpen ? 'rotate-180' : ''" class="w-4 h-4 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                            </svg>
                        </span>
                    </x-skew-button>
                    
                    <!-- Full-Width Mega Dropdown Menu -->
                    <div :class="menuOpen ? 'opacity-100 translate-y-0 pointer-events-auto' : 'opacity-0 translate-y-2 pointer-events-none'" class="mega-dropdown-panel absolute top-20 left-0 right-0 w-full bg-slate-950/98 backdrop-blur-md border-b border-slate-800/80 shadow-2xl transition-all duration-300 z-50">
                        <div class="max-w-7xl mx-auto px-12 py-8 grid grid-cols-1 md:grid-cols-4 gap-8 text-left normal-case tracking-normal">
                            
                            <!-- Column 1: Explore -->
                            <div>
                                <h4 class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-4 border-b border-slate-800/80 pb-2">Explore</h4>
                                <div class="flex flex-col gap-3">
                                    <a href="{{ route('courses') }}" class="text-slate-300 hover:text-white transition-colors flex items-center gap-2.5 text-base font-semibold">
                                        <span class="w-2 h-2 bg-brand rounded-full shadow-[0_0_8px_var(--color-brand)]"></span>
                                        Our Courses
                                    </a>
                                    <a href="{{ url('/') }}#careers" class="text-slate-300 hover:text-white transition-colors flex items-center gap-2.5 text-base font-semibold">
                                        <span class="w-2 h-2 bg-purple-500 rounded-full shadow-[0_0_8px_#a855f7]"></span>
                                        Careers
                                    </a>
                                    <a href="{{ url('/') }}#contact" class="text-slate-300 hover:text-white transition-colors flex items-center gap-2.5 text-base font-semibold">
                                        <span class="w-2 h-2 bg-pink-500 rounded-full shadow-[0_0_8px_#ec4899]"></span>
                                        Contact Us
                                    </a>
                                    <a href="{{ route('about') }}" class="text-slate-300 hover:text-white transition-colors flex items-center gap-2.5 text-base font-semibold">
                                        <span class="w-2 h-2 bg-blue-500 rounded-full shadow-[0_0_8px_#3b82f6]"></span>
                                        About Us
                                    </a>
                                    <!-- Public Info with Sub-menu -->
                                    <div x-data="{ subOpen: false }" class="flex flex-col gap-2">
                                        <button @click="subOpen = !subOpen" class="text-slate-300 hover:text-white transition-colors flex items-center justify-between normal-case tracking-normal text-base font-semibold w-full text-left cursor-pointer">
                                            <div class="flex items-center gap-2.5">
                                                <span class="w-2 h-2 bg-amber-500 rounded-full shadow-[0_0_8px_#f59e0b]"></span>
                                                <span>Public Info</span>
                                            </div>
                                            <svg :class="subOpen ? 'rotate-180' : ''" class="w-4 h-4 transition-transform text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </button>
                                        <div x-show="subOpen" x-transition class="pl-4 flex flex-col gap-2 border-l border-slate-800 ml-1 mt-1">
                                            <a href="{{ route('public-info', ['tab' => 'calendar']) }}" class="text-slate-400 hover:text-white transition-colors text-sm font-medium normal-case tracking-normal flex items-center gap-2 py-1">
                                                <span class="w-1.5 h-1.5 bg-amber-500/60 rounded-full"></span>
                                                Calendar
                                            </a>
                                            <a href="{{ route('public-info', ['tab' => 'announcements']) }}" class="text-slate-400 hover:text-white transition-colors text-sm font-medium normal-case tracking-normal flex items-center gap-2 py-1">
                                                <span class="w-1.5 h-1.5 bg-amber-500/60 rounded-full"></span>
                                                Announcements
                                            </a>
                                            <a href="{{ route('public-info', ['tab' => 'student-board']) }}" class="text-slate-400 hover:text-white transition-colors text-sm font-medium normal-case tracking-normal flex items-center gap-2 py-1">
                                                <span class="w-1.5 h-1.5 bg-indigo-500/60 rounded-full"></span>
                                                Student Boards
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Column 2: Administration & Developer Tools -->
                            <div>
                                <h4 class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-4 border-b border-slate-800/80 pb-2">Management</h4>
                                <div class="flex flex-col gap-3">
                                    @if(in_array(auth()->user()->users_level, [0, 1, 2]))
                                        <a href="{{ route('users.index') }}" class="text-slate-300 hover:text-white transition-colors flex items-center gap-2.5 text-base font-semibold {{ request()->routeIs('users.*') ? 'text-white font-extrabold' : '' }}">
                                            <span class="w-2 h-2 bg-purple-500 rounded-full shadow-[0_0_8px_#a855f7]"></span>
                                            User Management
                                        </a>
                                        <a href="{{ route('id-cards.index') }}" class="text-slate-300 hover:text-white transition-colors flex items-center gap-2.5 text-base font-semibold {{ request()->routeIs('id-cards.*') ? 'text-white font-extrabold' : '' }}">
                                            <span class="w-2 h-2 bg-pink-500 rounded-full shadow-[0_0_8px_#ec4899]"></span>
                                            ID Generation
                                        </a>
                                        <a href="{{ route('contact-messages.index') }}" class="text-slate-300 hover:text-white transition-colors flex items-center gap-2.5 text-base font-semibold {{ request()->routeIs('contact-messages.*') ? 'text-white font-extrabold' : '' }}">
                                            <span class="w-2 h-2 bg-amber-500 rounded-full shadow-[0_0_8px_#f59e0b]"></span>
                                            Contact Inquiries
                                        </a>
                                        @if(in_array(auth()->user()->users_level, [0, 1]) || (auth()->user()->users_level === 2 && auth()->user()->can_manage_admissions))
                                            <a href="{{ route('admission.admin.index') }}" class="text-slate-300 hover:text-white transition-colors flex items-center gap-2.5 text-base font-semibold {{ request()->routeIs('admission.admin.*') ? 'text-white font-extrabold' : '' }}">
                                                <span class="w-2 h-2 bg-emerald-500 rounded-full shadow-[0_0_8px_#10b981]"></span>
                                                Admissions
                                            </a>
                                        @endif
                                    @endif

                                    @if(auth()->user()->isDeveloper() || auth()->user()->isStudent() || auth()->user()->isParentGuardian())
                                        <a href="{{ route('activity-logs.index') }}" class="text-slate-300 hover:text-white transition-colors flex items-center gap-2.5 text-base font-semibold {{ request()->routeIs('activity-logs.*') ? 'text-white font-extrabold' : '' }}">
                                            <span class="w-2 h-2 bg-brand rounded-full shadow-[0_0_8px_var(--color-brand)]"></span>
                                            Activity Logs
                                        </a>
                                    @endif

                                    <!-- Public Info Management with Sub-menu -->
                                    <div x-data="{ subOpen2: {{ request()->routeIs('public-info-management') ? 'true' : 'false' }} }" class="flex flex-col gap-2">
                                        <button @click="subOpen2 = !subOpen2" class="text-slate-300 hover:text-white transition-colors flex items-center justify-between normal-case tracking-normal text-base font-semibold w-full text-left cursor-pointer">
                                            <div class="flex items-center gap-2.5">
                                                <span class="w-2 h-2 bg-indigo-500 rounded-full shadow-[0_0_8px_#6366f1]"></span>
                                                <span>Public Info Management</span>
                                            </div>
                                            <svg :class="subOpen2 ? 'rotate-180' : ''" class="w-4 h-4 transition-transform text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </button>
                                        <div x-show="subOpen2" x-transition class="pl-4 flex flex-col gap-2 border-l border-slate-800 ml-1 mt-1">
                                            @if(in_array(auth()->user()->users_level, [0, 1, 2]))
                                            <a href="{{ route('public-info-management', ['tab' => 'calendar']) }}" class="text-slate-400 hover:text-white transition-colors text-sm font-medium normal-case tracking-normal flex items-center gap-2 py-1">
                                                <span class="w-1.5 h-1.5 bg-indigo-500/60 rounded-full"></span>
                                                Calendar Events
                                            </a>
                                            <a href="{{ route('public-info-management', ['tab' => 'announcements']) }}" class="text-slate-400 hover:text-white transition-colors text-sm font-medium normal-case tracking-normal flex items-center gap-2 py-1">
                                                <span class="w-1.5 h-1.5 bg-indigo-500/60 rounded-full"></span>
                                                Announcements
                                            </a>
                                            @endif
                                            @if(in_array(auth()->user()->users_level, [0, 1, 2, 3]))
                                            <a href="{{ route('public-info-management', ['tab' => 'student-board']) }}" class="text-slate-400 hover:text-white transition-colors text-sm font-medium normal-case tracking-normal flex items-center gap-2 py-1">
                                                <span class="w-1.5 h-1.5 bg-pink-500/60 rounded-full"></span>
                                                Student Board
                                            </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Column 3: Account -->
                            <div>
                                <h4 class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-4 border-b border-slate-800/80 pb-2">Account</h4>
                                <div class="flex flex-col gap-3">
                                    <a href="{{ route('dashboard') }}" class="text-slate-300 hover:text-white transition-colors flex items-center gap-2.5 text-base font-semibold {{ request()->routeIs('dashboard') ? 'text-white font-extrabold' : '' }}">
                                        <span class="w-2 h-2 bg-emerald-500 rounded-full shadow-[0_0_8px_#10b981]"></span>
                                        Dashboard
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                                        @csrf
                                        <button type="submit" class="text-slate-300 hover:text-rose-400 transition-colors flex items-center gap-2.5 text-base font-semibold cursor-pointer w-full text-left">
                                            <span class="w-2 h-2 bg-rose-500 rounded-full shadow-[0_0_8px_#f43f5e]"></span>
                                            Log Out
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <!-- Column 4: Accessibility Options -->
                            <div>
                                <h4 class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-4 border-b border-slate-800/80 pb-2">Accessibility Options</h4>
                                <div class="grid grid-cols-2 gap-3">
                                    <button onclick="toggleA11yClass('a11y-light-mode')" class="a11y-btn flex items-center gap-2.5 px-4 py-2.5 rounded-xl bg-slate-900/60 border border-slate-800 hover:bg-slate-800/50 hover:border-slate-700 text-sm text-slate-300 hover:text-white transition-all text-left cursor-pointer">
                                        <svg class="w-4 h-4 text-brand-light shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                                        Light Mode
                                    </button>
                                    <button onclick="toggleA11yClass('a11y-high-contrast')" class="a11y-btn flex items-center gap-2.5 px-4 py-2.5 rounded-xl bg-slate-900/60 border border-slate-800 hover:bg-slate-800/50 hover:border-slate-700 text-sm text-slate-300 hover:text-white transition-all text-left cursor-pointer">
                                        <svg class="w-4 h-4 text-brand-light shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" /></svg>
                                        High Contrast
                                    </button>
                                    <button onclick="toggleA11yClass('a11y-large-text')" class="a11y-btn flex items-center gap-2.5 px-4 py-2.5 rounded-xl bg-slate-900/60 border border-slate-800 hover:bg-slate-800/50 hover:border-slate-700 text-sm text-slate-300 hover:text-white transition-all text-left cursor-pointer">
                                        <svg class="w-4 h-4 text-brand-light shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" /></svg>
                                        Enlarge Text
                                    </button>
                                    <button onclick="toggleA11yClass('a11y-dyslexic')" class="a11y-btn flex items-center gap-2.5 px-4 py-2.5 rounded-xl bg-slate-900/60 border border-slate-800 hover:bg-slate-800/50 hover:border-slate-700 text-sm text-slate-300 hover:text-white transition-all text-left cursor-pointer">
                                        <svg class="w-4 h-4 text-brand-light shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        Dyslexia Font
                                    </button>
                                    <button onclick="resetA11y()" class="a11y-reset col-span-2 flex items-center justify-center gap-2.5 px-4 py-2.5 rounded-xl bg-rose-950/20 border border-rose-900/30 hover:bg-rose-900/50 text-sm text-rose-400 font-bold transition-all cursor-pointer">
                                        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                                        Reset Settings
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dividing Line and Philippine Flag + Live Time Clock -->
            <div class="flex items-center gap-3.5 border-l border-slate-800/80 pl-5 h-full">
                <!-- Philippine Flag SVG -->
                <svg class="w-9 h-auto shadow-[0_0_12px_rgba(0,0,0,0.4)] rounded border border-slate-800/50 shrink-0" viewBox="0 0 180 90" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect width="180" height="90" fill="#0038A8"/>
                    <rect y="45" width="180" height="45" fill="#CE1126"/>
                    <path d="M0 0V90L77.9423 45L0 0Z" fill="white"/>
                    <circle cx="26" cy="45" r="10" fill="#FCD116"/>
                    <path d="M26 31.5L27 34.5H30L27.5 36.5L28.5 39.5L26 37.5L23.5 39.5L24.5 36.5L22 34.5H25L26 31.5Z" fill="#FCD116"/>
                    <path d="M12 59.5L13 62.5H16L13.5 64.5L14.5 67.5L12 65.5L9.5 67.5L10.5 64.5L8 62.5H11L12 59.5Z" fill="#FCD116"/>
                    <path d="M46 45L45 40L40 40L44 37.5L42 32.5L46 35.5L50 32.5L48 37.5L52 40L47 40L46 45Z" fill="#FCD116"/>
                </svg>
                
                <!-- Live Clock Container -->
                <div class="flex flex-col text-right font-mono select-none">
                    <span id="nav-live-time" class="text-white text-[13px] font-bold tracking-wider leading-none mb-1">--:--:-- --</span>
                    <span id="nav-live-date" class="text-[9px] text-slate-500 font-bold uppercase tracking-widest leading-none">--- --, ----</span>
                </div>
            </div>
            
            <!-- Hamburger (Mobile menu trigger) -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-900/50 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-slate-950/98 border-b border-slate-800">
        <div class="pt-2 pb-3 space-y-1 px-4">
            <a href="{{ url('/') }}" class="block px-3 py-2 text-slate-300 hover:text-white font-medium text-sm">Home</a>
            <a href="{{ route('courses') }}" class="block px-3 py-2 text-slate-300 hover:text-white font-medium text-sm">Courses</a>
            <a href="{{ route('about') }}" class="block px-3 py-2 text-slate-300 hover:text-white font-medium text-sm">About</a>
            <!-- Mobile Public Info with Sub-menu -->
            <div x-data="{ subOpen: false }" class="block px-3 py-1">
                <button @click="subOpen = !subOpen" class="w-full text-left flex items-center justify-between text-slate-300 hover:text-white font-medium text-sm py-1 cursor-pointer">
                    <span>Public Info</span>
                    <svg :class="subOpen ? 'rotate-180' : ''" class="w-4 h-4 transition-transform text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="subOpen" x-transition class="pl-4 flex flex-col gap-1.5 border-l border-slate-800 ml-1 mt-1">
                    <a href="{{ route('public-info', ['tab' => 'calendar']) }}" class="block py-1 text-xs text-slate-400 hover:text-white">Calendar</a>
                    <a href="{{ route('public-info', ['tab' => 'announcements']) }}" class="block py-1 text-xs text-slate-400 hover:text-white">Announcements</a>
                    <a href="{{ route('public-info', ['tab' => 'student-board']) }}" class="block py-1 text-xs text-slate-400 hover:text-white">Student Boards</a>
                </div>
            </div>
        </div>

        <div class="pt-4 pb-4 border-t border-slate-800 px-4">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-slate-500">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="space-y-1">
                <a href="{{ route('dashboard') }}" class="block px-3 py-2 text-slate-300 hover:text-white font-medium text-sm">Dashboard</a>
                <a href="{{ route('profile.edit') }}" class="block px-3 py-2 text-slate-300 hover:text-white font-medium text-sm flex items-center justify-between">
                    <span>Profile</span>
                    @php
                        $unreadMsgs = \App\Models\DirectMessage::where('receiver_id', Auth::user()->id)->where('is_read', false)->count();
                        $hasInvites = Auth::user()->pendingIncomingRequests()->count() > 0;
                    @endphp
                    @if($hasInvites || $unreadMsgs > 0)
                        <span class="px-2 py-0.5 text-[9px] font-bold bg-rose-500 text-white rounded-full animate-pulse">
                            New Activity
                        </span>
                    @endif
                </a>
                
                @if(in_array(auth()->user()->users_level, [0, 1, 2]))
                    <a href="{{ route('users.index') }}" class="block px-3 py-2 text-slate-300 hover:text-white font-medium text-sm">User Management</a>
                    <a href="{{ route('id-cards.index') }}" class="block px-3 py-2 text-slate-300 hover:text-white font-medium text-sm">ID Generation</a>
                    <a href="{{ route('contact-messages.index') }}" class="block px-3 py-2 text-slate-300 hover:text-white font-medium text-sm">Contact Inquiries</a>
                @endif
                
                @if(auth()->user()->isDeveloper() || auth()->user()->isStudent() || auth()->user()->isParentGuardian())
                    <a href="{{ route('activity-logs.index') }}" class="block px-3 py-2 text-slate-300 hover:text-white font-medium text-sm">Activity Logs</a>
                @endif
                
                @if(in_array(auth()->user()->users_level, [0, 1, 2, 3]))
                    <!-- Mobile Public Info Management with Sub-menu -->
                    <div x-data="{ subOpen: false }" class="block px-3 py-1">
                        <button @click="subOpen = !subOpen" class="w-full text-left flex items-center justify-between text-slate-300 hover:text-white font-medium text-sm py-1 cursor-pointer">
                            <span>Public Info Management</span>
                            <svg :class="subOpen ? 'rotate-180' : ''" class="w-4 h-4 transition-transform text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="subOpen" x-transition class="pl-4 flex flex-col gap-1.5 border-l border-slate-800 ml-1 mt-1">
                            @if(in_array(auth()->user()->users_level, [0, 1, 2]))
                                <a href="{{ route('public-info-management', ['tab' => 'calendar']) }}" class="block py-1 text-xs text-slate-400 hover:text-white">Calendar Events</a>
                                <a href="{{ route('public-info-management', ['tab' => 'announcements']) }}" class="block py-1 text-xs text-slate-400 hover:text-white">Announcements</a>
                            @endif
                            <a href="{{ route('public-info-management', ['tab' => 'student-board']) }}" class="block py-1 text-xs text-slate-400 hover:text-white">Student Board</a>
                        </div>
                    </div>
                @endif
                
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="block w-full text-left px-3 py-2 text-rose-400 hover:text-rose-300 font-medium text-sm cursor-pointer">
                        Log Out
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>

<style>
    /* Accessibility Classes */
    
    /* Light Mode Override */
    html.a11y-light-mode body {
        background-color: #f8fafc !important;
        color: #0f172a !important;
    }
    html.a11y-light-mode .bg-slate-900\/30,
    html.a11y-light-mode .bg-slate-900\/40,
    html.a11y-light-mode .bg-slate-900\/50,
    html.a11y-light-mode .bg-slate-950\/98,
    html.a11y-light-mode .mega-dropdown-panel,
    html.a11y-light-mode .glass-nav,
    html.a11y-light-mode section,
    html.a11y-light-mode footer {
        background: #ffffff !important;
        border-color: #cbd5e1 !important;
        backdrop-filter: none !important;
        -webkit-backdrop-filter: none !important;
    }
    html.a11y-light-mode .bg-slate-800\/30,
    html.a11y-light-mode .bg-slate-800\/50,
    html.a11y-light-mode .bg-slate-800\/80,
    html.a11y-light-mode .border-slate-800\/50,
    html.a11y-light-mode .border-slate-800\/60,
    html.a11y-light-mode .border-slate-700\/50,
    html.a11y-light-mode .hover\:bg-slate-700:hover,
    html.a11y-light-mode .bg-slate-800\/95,
    html.a11y-light-mode .bg-slate-900\/60,
    html.a11y-light-mode .a11y-btn {
        background: #f1f5f9 !important;
        border-color: #cbd5e1 !important;
    }
    html.a11y-light-mode .text-slate-400,
    html.a11y-light-mode .text-slate-300,
    html.a11y-light-mode .text-slate-200,
    html.a11y-light-mode .text-slate-100,
    html.a11y-light-mode .text-slate-50,
    html.a11y-light-mode .text-white {
        color: #334155 !important;
    }
    html.a11y-light-mode h1, html.a11y-light-mode h2, html.a11y-light-mode h3 {
        color: #0f172a !important;
    }
    html.a11y-light-mode .logo-invert-in-light {
        filter: invert(1) brightness(0.2) opacity(0.8) !important;
    }
    
    /* High Contrast Override */
    html.a11y-high-contrast body {
        background-color: #000 !important;
        color: #ffff00 !important; 
    }
    html.a11y-high-contrast * {
        border-color: #ffff00 !important;
    }
    html.a11y-high-contrast .bg-slate-800\/30,
    html.a11y-high-contrast .bg-slate-800\/50,
    html.a11y-high-contrast .bg-slate-800\/80,
    html.a11y-high-contrast .hover\:bg-slate-700:hover,
    html.a11y-high-contrast .bg-slate-800\/95,
    html.a11y-high-contrast .bg-slate-900\/50,
    html.a11y-high-contrast .bg-slate-950\/98,
    html.a11y-high-contrast .mega-dropdown-panel,
    html.a11y-high-contrast .glass-nav,
    html.a11y-high-contrast section,
    html.a11y-high-contrast .bg-slate-900\/60,
    html.a11y-high-contrast .a11y-btn,
    html.a11y-high-contrast footer {
        background: #000 !important;
        border: 2px solid #ffff00 !important;
    }
    html.a11y-high-contrast .text-slate-400,
    html.a11y-high-contrast .text-slate-300,
    html.a11y-high-contrast .text-brand-light,
    html.a11y-high-contrast .text-white {
        color: #ffff00 !important;
    }
    html.a11y-high-contrast svg {
        color: #ffff00 !important;
    }
    
    html.a11y-large-text body {
        font-size: 115% !important;
    }
    html.a11y-large-text p, 
    html.a11y-large-text a, 
    html.a11y-large-text span, 
    html.a11y-large-text button { 
        font-size: 110% !important; 
        line-height: 1.6 !important;
    }

    html.a11y-dyslexic body, 
    html.a11y-dyslexic * {
        font-family: 'Comic Sans MS', 'OpenDyslexic', sans-serif !important;
        letter-spacing: 0.05em !important;
        word-spacing: 0.1em !important;
    }
</style>

<script>
    (function() {
        function updateClock() {
            const timeEl = document.getElementById('nav-live-time');
            const dateEl = document.getElementById('nav-live-date');
            if (!timeEl || !dateEl) return;

            const optionsTime = {
                timeZone: 'Asia/Manila',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: true
            };
            const optionsDate = {
                timeZone: 'Asia/Manila',
                month: 'short',
                day: '2-digit',
                year: 'numeric'
            };

            const now = new Date();
            timeEl.textContent = now.toLocaleTimeString('en-US', optionsTime);
            dateEl.textContent = now.toLocaleDateString('en-US', optionsDate) + ' PHT';
        }
        
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => {
                updateClock();
                setInterval(updateClock, 1000);
            });
        } else {
            updateClock();
            setInterval(updateClock, 1000);
        }
    })();

    // Accessibility Script
    document.addEventListener('DOMContentLoaded', () => {
        const settings = JSON.parse(localStorage.getItem('a11y-settings')) || {};
        Object.keys(settings).forEach(key => {
            if (settings[key]) {
                document.documentElement.classList.add(key);
            }
        });
        if (document.documentElement.classList.contains('a11y-light-mode')) {
            document.documentElement.classList.remove('dark');
        }
    });

    function toggleA11yClass(className) {
        if (className === 'a11y-light-mode') {
            document.documentElement.classList.remove('a11y-high-contrast');
            const isCurrentlyLight = document.documentElement.classList.contains('a11y-light-mode');
            if (isCurrentlyLight) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        } else if (className === 'a11y-high-contrast') {
            document.documentElement.classList.remove('a11y-light-mode');
            document.documentElement.classList.add('dark');
        }
        document.documentElement.classList.toggle(className);
        saveA11ySettings();
    }

    function resetA11y() {
        document.documentElement.classList.remove('a11y-light-mode', 'a11y-high-contrast', 'a11y-large-text', 'a11y-dyslexic');
        document.documentElement.classList.add('dark');
        saveA11ySettings();
    }

    function saveA11ySettings() {
        const settings = {
            'a11y-light-mode': document.documentElement.classList.contains('a11y-light-mode'),
            'a11y-high-contrast': document.documentElement.classList.contains('a11y-high-contrast'),
            'a11y-large-text': document.documentElement.classList.contains('a11y-large-text'),
            'a11y-dyslexic': document.documentElement.classList.contains('a11y-dyslexic')
        };
        localStorage.setItem('a11y-settings', JSON.stringify(settings));
    }
</script>
