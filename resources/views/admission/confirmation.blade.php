<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark scroll-smooth">
<head>
    <x-head title="Admission Submitted - GameDev CamSur">
        .success-card {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(15, 23, 42, 0.8));
            border: 1px solid rgba(16, 185, 129, 0.3);
            box-shadow: 0 0 25px rgba(16, 185, 129, 0.15);
        }
        .skew-btn {
            background: linear-gradient(135deg, #4f46e5, #3730a3);
            transform: skewX(-15deg);
            display: inline-block;
            padding: 0.8rem 2.5rem;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 4px 4px 0px rgba(0,0,0,0.3);
        }
        .skew-btn:hover {
            transform: skewX(-15deg) translateY(-2px);
            box-shadow: 6px 6px 0px rgba(0,0,0,0.4);
        }
        .skew-btn span {
            transform: skewX(15deg);
            display: inline-block;
        }
    </x-head>
</head>
<body class="antialiased min-h-screen flex flex-col relative overflow-x-hidden bg-[#0f172a] text-slate-100">
    <x-loading-screen />
    <x-guest-nav />

    <main class="flex-grow flex flex-col items-center pt-32 pb-20 px-6 relative">
        <div class="max-w-2xl mx-auto w-full text-center">
            
            <div class="success-card rounded-3xl p-8 md:p-12 backdrop-blur-md shadow-2xl relative overflow-hidden mb-8">
                <!-- Checkmark -->
                <div class="w-20 h-20 bg-emerald-500/10 border border-emerald-500/30 rounded-full flex items-center justify-center text-emerald-400 mx-auto mb-6 shadow-[0_0_15px_rgba(16,185,129,0.2)]">
                    <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                </div>

                <h1 class="text-3xl font-extrabold text-white mb-2">Admission Submitted!</h1>
                <p class="text-slate-400 text-sm mb-8">
                    Your skills assessment score and credentials were saved successfully.
                </p>

                <!-- Details block -->
                <div class="bg-[#070b19] border border-slate-800 rounded-2xl p-6 text-left space-y-4 mb-8">
                    <div class="flex justify-between items-center py-2 border-b border-slate-800/80">
                        <span class="text-slate-400 text-sm font-semibold">Enrollee Name</span>
                        <span class="text-white font-bold">{{ $admission->full_name }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-slate-800/80">
                        <span class="text-slate-400 text-sm font-semibold">Enrollment ID</span>
                        <span class="font-mono text-emerald-400 font-bold tracking-wider text-base">{{ $admission->enrollment_number }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-slate-800/80">
                        <span class="text-slate-400 text-sm font-semibold">Assessment Score</span>
                        <span class="text-indigo-400 font-bold">{{ $admission->score }} / 5 (Passed)</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-slate-400 text-sm font-semibold">Application Status</span>
                        <span class="px-2.5 py-1 text-xs font-extrabold uppercase tracking-widest bg-amber-500/10 border border-amber-500/20 text-amber-400 rounded">
                            {{ $admission->status }}
                        </span>
                    </div>
                </div>

                <p class="text-slate-400 text-xs leading-relaxed">
                    Please print or keep a copy of your **Enrollment ID**. Our team will evaluate your credentials and email you regarding the confirmation of your slot.
                </p>
            </div>

            <!-- Home button -->
            <a href="{{ url('/') }}" class="skew-btn">
                <span class="text-base font-bold text-white tracking-widest uppercase">Back to Home</span>
            </a>
        </div>
    </main>

    <x-sitemap />
    <x-footer />
    <x-scroll-to-top />
</body>
</html>
