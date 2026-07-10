<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark scroll-smooth">
<head>
    <x-head title="Course Admission - GameDev CamSur">
        .flow-step-card {
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.6), rgba(30, 41, 59, 0.4));
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: all 0.3s ease;
        }
        .flow-step-card:hover {
            border-color: rgba(99, 102, 241, 0.3);
            transform: translateY(-2px);
        }
        .cta-btn {
            background: linear-gradient(135deg, #4f46e5, #3730a3);
            transform: skewX(-15deg);
            display: inline-block;
            padding: 0.8rem 2.5rem;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 4px 4px 0px rgba(0,0,0,0.3);
        }
        .cta-btn:hover {
            transform: skewX(-15deg) translateY(-2px);
            box-shadow: 6px 6px 0px rgba(0,0,0,0.4);
        }
        .cta-btn span {
            transform: skewX(15deg);
            display: inline-block;
        }
    </x-head>
</head>
<body class="antialiased min-h-screen flex flex-col relative overflow-x-hidden bg-[#0f172a] text-slate-100">
    <x-loading-screen />
    <x-guest-nav />

    <main class="flex-grow flex flex-col items-center pt-32 pb-20 px-6 relative">
        <div class="max-w-4xl mx-auto w-full">
            <div class="mb-12">
                <a href="{{ route('courses.basic') }}" class="text-brand-light hover:text-white transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                    Back to Basic Course
                </a>
            </div>

            <!-- Page Header -->
            <div class="text-center mb-12">
                <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight text-white mb-4">
                    Course Qualification & <span class="bg-gradient-to-r from-indigo-400 to-purple-400 bg-clip-text text-transparent">Admission</span>
                </h1>
                <p class="text-slate-400 max-w-xl mx-auto">
                    To enroll in the Basic Game Development Course, applicants must complete a preliminary assessment to verify their general programming understanding.
                </p>
            </div>

            <!-- Steps Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                <div class="flow-step-card rounded-2xl p-6 relative overflow-hidden">
                    <div class="absolute top-4 right-4 text-5xl font-black text-slate-800/50 select-none">01</div>
                    <div class="w-10 h-10 bg-indigo-500/10 rounded-lg flex items-center justify-center text-indigo-400 mb-4">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 002-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                    </div>
                    <h3 class="text-lg font-bold text-white mb-2">1. Knowledge Assessment</h3>
                    <p class="text-slate-400 text-sm leading-relaxed">
                        Answer 5 multiple-choice questions about C# programming logic, variables, and game loops. Passing score is 80% (4/5).
                    </p>
                </div>

                <div class="flow-step-card rounded-2xl p-6 relative overflow-hidden">
                    <div class="absolute top-4 right-4 text-5xl font-black text-slate-800/50 select-none">02</div>
                    <div class="w-10 h-10 bg-purple-500/10 rounded-lg flex items-center justify-center text-purple-400 mb-4">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                    </div>
                    <h3 class="text-lg font-bold text-white mb-2">2. Receive Enrollment Key</h3>
                    <p class="text-slate-400 text-sm leading-relaxed">
                        Upon qualifying, you will immediately generate a unique Enrollment Number required to submit your admission papers.
                    </p>
                </div>

                <div class="flow-step-card rounded-2xl p-6 relative overflow-hidden">
                    <div class="absolute top-4 right-4 text-5xl font-black text-slate-800/50 select-none">03</div>
                    <div class="w-10 h-10 bg-teal-500/10 rounded-lg flex items-center justify-center text-teal-400 mb-4">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                    </div>
                    <h3 class="text-lg font-bold text-white mb-2">3. Requirements Form</h3>
                    <p class="text-slate-400 text-sm leading-relaxed">
                        Submit your contact info and check off necessary admission credentials (e.g. Birth Certificate, Good Moral) to reserve your slot.
                    </p>
                </div>
            </div>

            <!-- CTA Container -->
            <div class="text-center bg-slate-900/40 border border-slate-800/60 rounded-3xl p-8 backdrop-blur-sm shadow-xl">
                <h3 class="text-2xl font-bold text-white mb-4">Ready to show your skills?</h3>
                <p class="text-slate-400 mb-6 text-sm max-w-lg mx-auto">
                    The assessment takes about 5 minutes. Make sure you have a quiet environment and a basic familiarity with coding variables and logic flow.
                </p>
                <a href="{{ route('admission.assessment') }}" class="cta-btn">
                    <span class="text-base font-bold text-white tracking-widest uppercase">Start Assessment</span>
                </a>
            </div>
        </div>
    </main>

    <x-sitemap />
    <x-footer />
    <x-scroll-to-top />
</body>
</html>
