<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark scroll-smooth">
<head>
    <x-head title="Basic Course - GameDev CamSur">
        /* Banner styles for Basic course headings */
        .course-banner {
            background: linear-gradient(135deg, #1e3a8a, #312e81);
            transform: skewX(-15deg);
            display: inline-block;
            padding: 0.5rem 3rem;
            margin-bottom: 2rem;
            box-shadow: 4px 4px 0px rgba(0,0,0,0.3);
        }
        
        .course-banner span {
            transform: skewX(15deg);
            display: inline-block;
        }
        
        .enroll-banner {
            background: linear-gradient(135deg, #4f46e5, #3730a3);
            transform: skewX(-15deg);
            display: inline-block;
            padding: 0.5rem 2rem;
            margin-top: 1.5rem;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 4px 4px 0px rgba(0,0,0,0.3);
        }
        
        .enroll-banner:hover {
            transform: skewX(-15deg) translateY(-2px);
            box-shadow: 6px 6px 0px rgba(0,0,0,0.4);
        }
        
        .enroll-banner span {
            transform: skewX(15deg);
            display: inline-block;
        }
        
        .course-list li {
            position: relative;
            padding-left: 1.5rem;
            margin-bottom: 0.8rem;
        }
        
        .course-list li::before {
            content: "";
            position: absolute;
            left: 0;
            top: 0.6rem;
            width: 0.5rem;
            height: 0.5rem;
            background-color: #3b82f6;
            transform: skewX(-12deg);
        }
    </x-head>
</head>
<body class="antialiased min-h-screen flex flex-col relative overflow-x-hidden">
    <x-loading-screen />
    


    <!-- Navigation Bar -->
    <x-guest-nav />
 
    <!-- Main Content (Basic Course) -->
    <main class="flex-grow flex flex-col items-center pt-32 pb-20 px-6 relative">
        <div class="max-w-6xl mx-auto w-full">
            <div class="mb-12">
                <a href="{{ route('courses') }}" class="text-brand-light hover:text-white transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                    Back to All Courses
                </a>
            </div>

            <!-- Top Row: Video & Highlight Cards matching layout mockup -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center bg-slate-900/40 border border-slate-800/60 rounded-3xl p-8 md:p-12 backdrop-blur-sm shadow-2xl mb-12">
                <!-- Left Side: Video Clip Player with blue border frame -->
                <div class="lg:col-span-6 flex justify-center">
                    <div class="relative w-full max-w-lg bg-[#0e1b35] p-2 rounded-2xl border-[10px] border-[#103070] shadow-[0_0_30px_rgba(30,58,138,0.5)] h-fit self-center">
                        <div class="relative rounded-lg">
                            <video src="{{ asset('basic.mp4') }}" autoplay loop muted playsinline class="w-full h-auto object-contain shadow-inner block"></video>
                            <!-- Cover overlay in lower right corner with negative positioning -->
                            <div class="absolute pointer-events-none z-10 w-28 md:w-32" style="bottom: -55px; right: -55px;">
                                <img src="{{ asset('cubes.gif') }}" alt="Overlay cubes" class="w-full h-auto">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Side: Highlights & CTA -->
                <div class="lg:col-span-6 flex flex-col items-center lg:items-start text-center lg:text-left">
                    <div class="course-banner mb-8">
                        <span class="text-3xl font-extrabold text-white tracking-widest uppercase">BASIC COURSE</span>
                    </div>
                    
                    <ul class="space-y-4 text-slate-200 text-lg font-semibold tracking-wide mb-8">
                        <li class="flex items-center gap-3">
                            <span class="w-2.5 h-2.5 bg-blue-600 inline-block -skew-x-12"></span>
                            <span>Beginner Course: C#</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="w-2.5 h-2.5 bg-blue-600 inline-block -skew-x-12"></span>
                            <span>Basic Game Development Course</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="w-2.5 h-2.5 bg-blue-600 inline-block -skew-x-12"></span>
                            <span>3-Months Basic Training Program</span>
                        </li>
                    </ul>

                    <div>
                        <a href="{{ route('admission.selection') }}" class="enroll-banner">
                            <span class="text-lg font-bold text-white tracking-widest uppercase">ENROLL NOW</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Bottom Row: Detailed Course Context & Syllabus -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                <!-- Detailed Curriculum -->
                <div class="lg:col-span-7 bg-slate-900/30 border border-slate-800/50 rounded-3xl p-8 backdrop-blur-sm">
                    <h2 class="text-3xl font-bold text-white mb-6">Course Syllabus</h2>
                    <p class="text-slate-400 leading-relaxed mb-8">
                        Our curriculum is meticulously structured to transform aspiring creators into confident developers. Go from basic scripts to fully interactive games.
                    </p>
                    
                    <div class="space-y-6">
                        <div>
                            <h4 class="text-indigo-400 font-bold text-lg mb-2">Phase 1: Game Engine Fundamentals</h4>
                            <p class="text-slate-300 text-sm leading-relaxed">
                                Learn the Unity Editor layout, handle 2D sprites, arrange UI Canvas scenes, manage asset directories, and configure basic physics settings.
                            </p>
                        </div>
                        <div>
                            <h4 class="text-indigo-400 font-bold text-lg mb-2">Phase 2: C# Programming Basics</h4>
                            <p class="text-slate-300 text-sm leading-relaxed">
                                Master fundamental coding syntax. Work with variables, conditions, nested loops, custom classes, array lists, and clean script architecture.
                            </p>
                        </div>
                        <div>
                            <h4 class="text-indigo-400 font-bold text-lg mb-2">Phase 3: Inputs, Physics & Controls</h4>
                            <p class="text-slate-300 text-sm leading-relaxed">
                                Implement keyboard/mouse listeners, apply rigidbodies, handle collision layers, code triggers, and deploy basic animation transitions.
                            </p>
                        </div>
                        <div>
                            <h4 class="text-indigo-400 font-bold text-lg mb-2">Phase 4: Game Polish & Output Builds</h4>
                            <p class="text-slate-300 text-sm leading-relaxed">
                                Configure sound effects, design main menus, save progress states, and export standalone Windows / WebGL executable builds.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Career Pathing & Next Steps -->
                <div class="lg:col-span-5 bg-slate-900/30 border border-slate-800/50 rounded-3xl p-8 backdrop-blur-sm">
                    <h2 class="text-2xl font-bold text-white mb-6">What You Can Do Next</h2>
                    <p class="text-slate-400 leading-relaxed mb-6">
                        Completing this course equips you with solid foundation blocks to transition to creative fields:
                    </p>
                    <ul class="space-y-4 text-slate-300 text-sm">
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-green-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <span><strong>Indie Game Developer:</strong> Publish 2D games, platformers, or puzzle games directly on platforms like Itch.io.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-green-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <span><strong>Game Jams:</strong> Collaborate with artists and audio designers in high-speed developer challenges.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-green-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <span><strong>AAA Junior Dev:</strong> Prepare for entry-level positions or pursue advanced learning in 3D systems.</span>
                        </li>
                    </ul>

                    <div class="mt-8 pt-6 border-t border-slate-800/40">
                        <span class="text-xs text-slate-400 block mb-1">Advanced Pathway</span>
                        <a href="{{ route('courses.advanced') }}" class="text-indigo-400 hover:text-indigo-300 transition-colors font-bold flex items-center gap-1">
                            Explore Advanced Game Dev Course →
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <x-sitemap />
    <x-footer />
    <x-scroll-to-top />
</body>
</html>
