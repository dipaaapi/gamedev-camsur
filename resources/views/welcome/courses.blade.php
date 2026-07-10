<!-- Courses Section -->
<section id="courses" class="py-20 px-6 relative border-t border-slate-800/60">
    <div class="max-w-6xl mx-auto w-full">
        <div class="text-center mb-16">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-slate-800/50 border border-slate-700/50 text-sm text-brand-light mb-8 backdrop-blur-sm">
                <span class="relative flex h-2.5 w-2.5">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-brand-light opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-brand-light"></span>
                </span>
                Our Programs
            </div>
            <h2 class="text-4xl md:text-5xl font-extrabold tracking-tight mb-4 leading-tight text-white text-center">
                Our Courses <span class="gradient-text">Offered</span>
            </h2>
            <p class="text-slate-400 text-lg max-w-2xl mx-auto font-medium">
                Choose from our carefully structured programs designed to take you from coding basics to building professional-grade games.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-stretch">
            <!-- Basic Course Section -->
            <a href="{{ route('courses.basic') }}" class="flex flex-col justify-between bg-slate-800/30 border border-slate-700/50 rounded-3xl p-8 md:p-10 backdrop-blur-sm text-center hover:bg-slate-800/50 transition-all duration-300 group">
                <div>
                    <div class="course-banner group-hover:scale-105 transition-transform">
                        <span class="text-2xl md:text-3xl font-extrabold text-white tracking-widest uppercase">BASIC COURSE</span>
                    </div>
                    
                    <ul class="course-list text-left inline-block text-slate-300 text-lg md:text-xl font-bold tracking-wide mt-4 mx-auto w-full max-w-lg">
                        <li>Beginner Course: C#</li>
                        <li>Basic Game Development Course</li>
                        <li>3-Months Basic Training Program</li>
                    </ul>
                </div>
                
                <div class="mt-8">
                    <div class="enroll-banner">
                        <span class="text-lg font-bold text-white tracking-widest uppercase">VIEW DETAILS</span>
                    </div>
                </div>
            </a>

            <!-- Advanced Course Section -->
            <a href="{{ route('courses.advanced') }}" class="flex flex-col justify-between bg-slate-800/30 border border-slate-700/50 rounded-3xl p-8 md:p-10 backdrop-blur-sm text-center hover:bg-slate-800/50 transition-all duration-300 group">
                <div>
                    <div class="course-banner group-hover:scale-105 transition-transform" style="background: linear-gradient(135deg, #7c3aed, #4c1d95);">
                        <span class="text-2xl md:text-3xl font-extrabold text-white tracking-widest uppercase">ADVANCED COURSE</span>
                    </div>
                    
                    <ul class="course-list text-left inline-block text-slate-300 text-lg md:text-xl font-bold tracking-wide mt-4 mx-auto w-full max-w-lg">
                        <li>Advanced Programming: C++ & Patterns</li>
                        <li>3D Game Architecture & Optimization</li>
                        <li>Multiplayer & Networking Essentials</li>
                        <li>6-Months Advanced Incubation Program</li>
                    </ul>
                </div>
                
                <div class="mt-8">
                    <div class="enroll-banner" style="background: linear-gradient(135deg, #8b5cf6, #5b21b6);">
                        <span class="text-lg font-bold text-white tracking-widest uppercase">VIEW DETAILS</span>
                    </div>
                </div>
            </a>
        </div>

        <!-- Coming Soon Teaser Banner -->
        <div class="mt-12 p-8 rounded-3xl bg-gradient-to-r from-slate-900/40 via-indigo-950/20 to-slate-900/40 border border-slate-700/30 backdrop-blur-sm flex flex-col md:flex-row items-center justify-between gap-6 hover:border-indigo-500/20 transition-all duration-300">
            <div class="flex flex-col gap-2 text-center md:text-left">
                <div class="inline-flex items-center gap-1.5 self-center md:self-start px-3 py-1 rounded-full bg-indigo-500/10 border border-indigo-500/25 text-xs font-semibold text-indigo-300 uppercase tracking-widest">
                    Curriculum Feedback
                </div>
                <h3 class="text-xl font-bold text-white">Help Shape Our Future Tracks</h3>
                <p class="text-slate-400 text-sm max-w-xl">
                    We are always looking for ways to improve our offerings. If there is a specific subject, tool, or specialized skill you want to learn, we would love to hear your ideas.
                </p>
            </div>
            <a href="#contact" class="flex items-center gap-2 text-sm text-brand-light font-medium bg-slate-800/40 px-5 py-3 rounded-full border border-slate-700/50 hover:bg-slate-800/80 hover:text-white transition-all cursor-pointer">
                <span>Suggest a Course</span>
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>

    </div>
</section>
