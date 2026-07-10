<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark scroll-smooth">
<head>
    <x-head title="Advanced Course - GameDev CamSur">
        /* Banner styles for Advanced course headings */
        .course-banner {
            background: linear-gradient(135deg, #7c3aed, #4c1d95);
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
            background: linear-gradient(135deg, #8b5cf6, #5b21b6);
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
    </x-head>
</head>
<body class="antialiased min-h-screen flex flex-col relative overflow-x-hidden">
    <x-loading-screen />
    


    <!-- Navigation Bar -->
    <x-guest-nav />
 
    <!-- Main Content (Advanced Course) -->
    <main class="flex-grow flex flex-col items-center pt-32 pb-20 px-6 relative">
        <div class="max-w-6xl mx-auto w-full">
            <div class="mb-12">
                <a href="{{ route('courses') }}" class="text-brand-light hover:text-white transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                    Back to All Courses
                </a>
            </div>

            <!-- Top Row: Highlight Cards & Video matching layout mockup -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center bg-slate-900/40 border border-slate-800/60 rounded-3xl p-8 md:p-12 backdrop-blur-sm shadow-2xl mb-12">
                <!-- Left Side: Details -->
                <div class="grid-item lg:col-span-6 flex flex-col items-center lg:items-start text-center lg:text-left order-2 lg:order-1">
                    <div class="course-banner mb-8">
                        <span class="text-3xl font-extrabold text-white tracking-widest uppercase">ADVANCED COURSE</span>
                    </div>
                    
                    <ul class="space-y-4 text-slate-200 text-lg font-semibold tracking-wide mb-8">
                        <li class="flex items-center gap-3">
                            <span class="w-2.5 h-2.5 bg-purple-600 inline-block -skew-x-12"></span>
                            <span>Advance Course: C#, Game Engine</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="w-2.5 h-2.5 bg-purple-600 inline-block -skew-x-12"></span>
                            <span>Advance Game Development Course</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="w-2.5 h-2.5 bg-purple-600 inline-block -skew-x-12"></span>
                            <span>6-Months Advance Training Program</span>
                        </li>
                    </ul>

                    <div>
                        <a href="{{ route('login') }}" class="enroll-banner">
                            <span class="text-lg font-bold text-white tracking-widest uppercase">ENROLL NOW</span>
                        </a>
                    </div>
                </div>

                <!-- Right Side: Video Clip Player with purple border frame -->
                <div class="lg:col-span-6 flex justify-center order-1 lg:order-2">
                    <div class="relative w-full max-w-lg bg-[#1a0e35] p-2 rounded-2xl border-[10px] border-[#3e1070] shadow-[0_0_30px_rgba(124,58,237,0.5)] h-fit self-center">
                        <div class="relative rounded-lg">
                            <video src="{{ asset('advance.mp4') }}" autoplay loop muted playsinline class="w-full h-auto object-contain shadow-inner block"></video>
                            <!-- Cover overlay in lower right corner with negative positioning -->
                            <div class="absolute pointer-events-none z-10 w-28 md:w-32" style="bottom: -55px; right: -55px;">
                                <img src="{{ asset('cubes.gif') }}" alt="Overlay cubes" class="w-full h-auto">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Row: Detailed Course Context & Syllabus -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                <!-- Detailed Curriculum -->
                <div class="lg:col-span-7 bg-slate-900/30 border border-slate-800/50 rounded-3xl p-8 backdrop-blur-sm">
                    <h2 class="text-3xl font-bold text-white mb-6">Course Syllabus</h2>
                    <p class="text-slate-400 leading-relaxed mb-8">
                        Dive deep into engine architecture, professional design patterns, custom shader programming, and netcode architectures. Built for serious developers.
                    </p>
                    
                    <div class="space-y-6">
                        <div>
                            <h4 class="text-purple-400 font-bold text-lg mb-2">Phase 1: Advanced C++ & Engine Math</h4>
                            <p class="text-slate-300 text-sm leading-relaxed">
                                Master memory management, pointer safety, custom structures, linear algebra, vector math, and matrices inside custom programming layers.
                            </p>
                        </div>
                        <div>
                            <h4 class="text-purple-400 font-bold text-lg mb-2">Phase 2: Custom Shaders & Graphics Pipelines</h4>
                            <p class="text-slate-300 text-sm leading-relaxed">
                                Write custom vertex and fragment shaders using HLSL/GLSL. Program lighting calculations, reflection, and post-processing screen pipelines.
                            </p>
                        </div>
                        <div>
                            <h4 class="text-purple-400 font-bold text-lg mb-2">Phase 3: Multiplayer Netcode & Databases</h4>
                            <p class="text-slate-300 text-sm leading-relaxed">
                                Build secure client-server architectures. Program state replication, lag compensation, lobby matchmaking, and SQL player progress databases.
                            </p>
                        </div>
                        <div>
                            <h4 class="text-purple-400 font-bold text-lg mb-2">Phase 4: 6-Month AAA Incubation Build</h4>
                            <p class="text-slate-300 text-sm leading-relaxed">
                                Work in a mock studio structure with scrum workflows. Collaborate to construct a commercial-grade 3D multiplayer game ready for Steam.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Career Pathing & Next Steps -->
                <div class="lg:col-span-5 bg-slate-900/30 border border-slate-800/50 rounded-3xl p-8 backdrop-blur-sm">
                    <h2 class="text-2xl font-bold text-white mb-6">Industry Career Paths</h2>
                    <p class="text-slate-400 leading-relaxed mb-6">
                        Graduates of the Advanced Course possess the highly sought-after technical skills needed in AAA and Indie gaming:
                    </p>
                    <ul class="space-y-4 text-slate-300 text-sm">
                        <li class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-purple-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                            <div>
                                <strong>Gameplay / Systems Programmer</strong>
                                <p class="text-xs text-slate-500 mt-1">Design core loop engines, character controls, combat systems, and custom tools.</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-purple-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                            <div>
                                <strong>Graphics & Shaders Developer</strong>
                                <p class="text-xs text-slate-500 mt-1">Write shaders, optimize render matrices, design custom particle nodes, and build visual pipelines.</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-purple-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                            <div>
                                <strong>Network Engine Developer</strong>
                                <p class="text-xs text-slate-500 mt-1">Implement client-server netcode, databases, matchmaking engines, and server APIs.</p>
                            </div>
                        </li>
                    </ul>

                    <div class="mt-8 pt-6 border-t border-slate-800/40">
                        <span class="text-xs text-slate-400 block mb-1">Standard Pathway</span>
                        <a href="{{ route('courses.basic') }}" class="text-purple-400 hover:text-purple-300 transition-colors font-bold flex items-center gap-1">
                            ← Review Basic Game Dev Course
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
