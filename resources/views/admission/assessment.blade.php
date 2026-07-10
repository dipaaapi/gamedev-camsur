<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark scroll-smooth">
<head>
    <x-head title="Skills Assessment - GameDev CamSur">
        .quiz-card {
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.8), rgba(30, 41, 59, 0.6));
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
        }
        .option-label {
            background-color: rgba(15, 23, 42, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: all 0.2s ease;
        }
        .option-label:hover {
            background-color: rgba(79, 70, 229, 0.1);
            border-color: rgba(99, 102, 241, 0.3);
        }
        .option-radio:checked + .option-label {
            background-color: rgba(79, 70, 229, 0.25);
            border-color: #6366f1;
            box-shadow: 0 0 12px rgba(99, 102, 241, 0.3);
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
        .skew-btn:hover:not(:disabled) {
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
        <div class="max-w-3xl mx-auto w-full">
            <div class="mb-8">
                <a href="{{ route('admission.selection') }}" class="text-brand-light hover:text-white transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                    Cancel Assessment
                </a>
            </div>

            <!-- Page Header -->
            <div class="text-center mb-8">
                <span class="text-xs font-bold text-indigo-400 uppercase tracking-widest px-3 py-1 rounded-full bg-indigo-500/10 border border-indigo-500/20 mb-3 inline-block">Admission Qualification</span>
                <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight text-white">
                    Basic Game Development Assessment
                </h1>
            </div>

            @if(session('error'))
                <div class="mb-6 p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-200 text-sm flex items-center gap-3">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <!-- Quiz form -->
            <form action="{{ route('admission.assessment.submit') }}" method="POST" id="assessment-form">
                @csrf
                
                <!-- Card Container -->
                <div class="quiz-card rounded-3xl p-8 backdrop-blur-md shadow-2xl relative overflow-hidden mb-8">
                    <!-- Progress bar -->
                    <div class="absolute top-0 left-0 w-full h-1.5 bg-slate-800">
                        <div id="quiz-progress-bar" class="h-full bg-gradient-to-r from-indigo-500 to-purple-500 transition-all duration-300" style="width: 20%;"></div>
                    </div>

                    <!-- Step Indicators -->
                    <div class="flex justify-between items-center text-sm font-bold text-slate-500 mb-6">
                        <span>QUESTION <span id="current-question-num" class="text-white">1</span> OF 5</span>
                        <span id="quiz-timer" class="text-indigo-400">05:00</span>
                    </div>

                    @foreach($questions as $index => $q)
                        <div class="quiz-step {{ $index > 0 ? 'hidden' : '' }}" data-step="{{ $index + 1 }}">
                            <h3 class="text-xl font-bold text-white mb-6">{{ $index + 1 }}. {{ $q->question }}</h3>
                            <div class="space-y-3">
                                <label class="relative block cursor-pointer">
                                    <input type="radio" name="answers[{{ $q->id }}]" value="A" class="option-radio sr-only" {{ $index === 0 ? 'required' : '' }}>
                                    <div class="option-label p-4 rounded-xl text-slate-300 font-medium">{{ $q->option_a }}</div>
                                </label>
                                <label class="relative block cursor-pointer">
                                    <input type="radio" name="answers[{{ $q->id }}]" value="B" class="option-radio sr-only">
                                    <div class="option-label p-4 rounded-xl text-slate-300 font-medium">{{ $q->option_b }}</div>
                                </label>
                                <label class="relative block cursor-pointer">
                                    <input type="radio" name="answers[{{ $q->id }}]" value="C" class="option-radio sr-only">
                                    <div class="option-label p-4 rounded-xl text-slate-300 font-medium">{{ $q->option_c }}</div>
                                </label>
                                <label class="relative block cursor-pointer">
                                    <input type="radio" name="answers[{{ $q->id }}]" value="D" class="option-radio sr-only">
                                    <div class="option-label p-4 rounded-xl text-slate-300 font-medium">{{ $q->option_d }}</div>
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Control Buttons -->
                <div class="flex justify-between items-center">
                    <button type="button" id="prev-btn" class="skew-btn opacity-50 cursor-not-allowed" disabled>
                        <span>Previous</span>
                    </button>
                    <button type="button" id="next-btn" class="skew-btn">
                        <span>Next Question</span>
                    </button>
                    <button type="submit" id="submit-btn" class="skew-btn hidden">
                        <span>Finish & Submit</span>
                    </button>
                </div>
            </form>
        </div>
    </main>

    <x-sitemap />
    <x-footer />
    <x-scroll-to-top />

    <script>
        (function() {
            let currentStep = 1;
            const totalSteps = 5;
            const prevBtn = document.getElementById('prev-btn');
            const nextBtn = document.getElementById('next-btn');
            const submitBtn = document.getElementById('submit-btn');
            const progressBar = document.getElementById('quiz-progress-bar');
            const stepNum = document.getElementById('current-question-num');
            const steps = document.querySelectorAll('.quiz-step');

            function updateQuizUI() {
                steps.forEach(step => {
                    const stepVal = parseInt(step.getAttribute('data-step'));
                    if (stepVal === currentStep) {
                        step.classList.remove('hidden');
                    } else {
                        step.classList.add('hidden');
                    }
                });

                // Update progress bar
                progressBar.style.width = `${(currentStep / totalSteps) * 100}%`;
                stepNum.textContent = currentStep;

                // Configure control buttons
                if (currentStep === 1) {
                    prevBtn.disabled = true;
                    prevBtn.classList.add('opacity-50', 'cursor-not-allowed');
                } else {
                    prevBtn.disabled = false;
                    prevBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                }

                if (currentStep === totalSteps) {
                    nextBtn.classList.add('hidden');
                    submitBtn.classList.remove('hidden');
                } else {
                    nextBtn.classList.remove('hidden');
                    submitBtn.classList.add('hidden');
                }
            }

            nextBtn.addEventListener('click', () => {
                // Validate if answered current question
                const currentQuestion = document.querySelector(`.quiz-step[data-step="${currentStep}"] input[type="radio"]:checked`);
                if (!currentQuestion) {
                    alert("Please select an answer before proceeding.");
                    return;
                }
                
                if (currentStep < totalSteps) {
                    currentStep++;
                    updateQuizUI();
                }
            });

            prevBtn.addEventListener('click', () => {
                if (currentStep > 1) {
                    currentStep--;
                    updateQuizUI();
                }
            });

            // Set up a 5-minute timer
            let timeLeft = 300;
            const timerEl = document.getElementById('quiz-timer');
            const interval = setInterval(() => {
                if (timeLeft <= 0) {
                    clearInterval(interval);
                    alert("Time is up! Your quiz will be submitted automatically.");
                    document.getElementById('assessment-form').submit();
                } else {
                    timeLeft--;
                    const minutes = Math.floor(timeLeft / 60);
                    const seconds = timeLeft % 60;
                    timerEl.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                }
            }, 1000);

            // Clear timer on submit
            document.getElementById('assessment-form').addEventListener('submit', () => {
                clearInterval(interval);
            });
        })();
    </script>
</body>
</html>
