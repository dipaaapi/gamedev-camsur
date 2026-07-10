<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark scroll-smooth">
<head>
    <x-head title="Admission Form - GameDev CamSur">
        .admission-card {
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.8), rgba(30, 41, 59, 0.6));
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
        }
        .form-input {
            background-color: rgba(15, 23, 42, 0.95) !important;
            border: 1px solid rgba(255, 255, 255, 0.15) !important;
            color: #ffffff !important;
            transition: all 0.3s;
        }
        .form-input:focus {
            border-color: #6366f1 !important;
            box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2) !important;
            outline: none;
        }
        .skew-btn {
            background: linear-gradient(135deg, #10b981, #059669);
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
        <div class="max-w-3xl mx-auto w-full">
            <!-- Page Header -->
            <div class="text-center mb-8">
                <span class="text-xs font-bold text-emerald-400 uppercase tracking-widest px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 mb-3 inline-block">Qualified Applicant</span>
                <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight text-white">
                    Submit Admission Details
                </h1>
                <p class="text-slate-400 mt-2 text-sm">
                    Enter your enrollment number and upload/verify your school credentials.
                </p>
            </div>

            <!-- Card Container -->
            <div class="admission-card rounded-3xl p-8 md:p-10 backdrop-blur-md shadow-2xl relative overflow-hidden">
                
                @if($errors->any())
                    <div class="mb-6 p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-200 text-sm">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admission.submit') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Primary Credentials Section -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label class="block text-slate-400 text-sm font-semibold mb-2" for="enrollment_number">Enrollment Number *</label>
                            <input type="text" name="enrollment_number" id="enrollment_number" 
                                   value="{{ old('enrollment_number', session('enrollment_number')) }}" 
                                   class="form-input w-full p-3 rounded-xl font-mono text-emerald-400 text-lg uppercase font-bold tracking-wider" 
                                   placeholder="ENR-YYYY-XXXXX" required readonly>
                            <span class="text-xs text-slate-500 mt-1 block">Pre-filled upon passing the quiz.</span>
                        </div>

                        <div>
                            <label class="block text-slate-400 text-sm font-semibold mb-2" for="course">Selected Course</label>
                            <input type="text" name="course" id="course" value="Basic Game Development" 
                                   class="form-input w-full p-3 rounded-xl opacity-70" readonly>
                        </div>
                    </div>

                    <!-- Personal Information Section -->
                    <h3 class="text-lg font-bold text-white mb-4 pb-2 border-b border-slate-800">Personal Contact Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div class="md:col-span-2">
                            <label class="block text-slate-400 text-sm font-semibold mb-2" for="full_name">Full Name (Last, First, Middle) *</label>
                            <input type="text" name="full_name" id="full_name" value="{{ old('full_name') }}" 
                                   class="form-input w-full p-3 rounded-xl" placeholder="e.g. Dela Cruz, Juan Santos" required>
                        </div>

                        <div>
                            <label class="block text-slate-400 text-sm font-semibold mb-2" for="email">Active Email Address *</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" 
                                   class="form-input w-full p-3 rounded-xl" placeholder="e.g. juan@example.com" required>
                        </div>

                        <div>
                            <label class="block text-slate-400 text-sm font-semibold mb-2" for="phone">Phone / Mobile Number *</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone') }}" 
                                   class="form-input w-full p-3 rounded-xl" placeholder="e.g. 09171234567" required>
                        </div>
                    </div>

                    <!-- Requirements Submission Checklist -->
                    <h3 class="text-lg font-bold text-white mb-4 pb-2 border-b border-slate-800">Required Admission Credentials</h3>
                    <p class="text-slate-400 text-xs mb-4">
                        Please indicate which credentials you are submitting now. You may check them and optionally attach digital copies (PDF/JPG, max 2MB).
                    </p>

                    <div class="space-y-4 mb-8 bg-[#0a0f1d] p-6 rounded-2xl border border-slate-800/80">
                        <!-- Requirement 1 -->
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 py-2 border-b border-slate-800/60 last:border-0">
                            <div class="flex items-start gap-3">
                                <input type="checkbox" name="req_good_moral" id="req_good_moral" value="1" 
                                       class="mt-1 w-4 h-4 rounded text-emerald-500 bg-slate-900 border-slate-700 focus:ring-emerald-500" 
                                       {{ old('req_good_moral') ? 'checked' : '' }}>
                                <label for="req_good_moral" class="cursor-pointer">
                                    <span class="block text-sm font-bold text-slate-200">Certificate of Good Moral Character</span>
                                    <span class="block text-xs text-slate-400">Issued by your previous school principal or registrar.</span>
                                </label>
                            </div>
                            <input type="file" name="file_good_moral" class="text-xs text-slate-400 file:mr-4 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-slate-800 file:text-slate-300 hover:file:bg-slate-700 cursor-pointer">
                        </div>

                        <!-- Requirement 2 -->
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 py-2 border-b border-slate-800/60 last:border-0">
                            <div class="flex items-start gap-3">
                                <input type="checkbox" name="req_birth_cert" id="req_birth_cert" value="1" 
                                       class="mt-1 w-4 h-4 rounded text-emerald-500 bg-slate-900 border-slate-700 focus:ring-emerald-500"
                                       {{ old('req_birth_cert') ? 'checked' : '' }}>
                                <label for="req_birth_cert" class="cursor-pointer">
                                    <span class="block text-sm font-bold text-slate-200">PSA Birth Certificate</span>
                                    <span class="block text-xs text-slate-400">Photocopy or scanned digital copy of PSA/NSO birth certificate.</span>
                                </label>
                            </div>
                            <input type="file" name="file_birth_cert" class="text-xs text-slate-400 file:mr-4 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-slate-800 file:text-slate-300 hover:file:bg-slate-700 cursor-pointer">
                        </div>

                        <!-- Requirement 3 -->
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 py-2 border-b border-slate-800/60 last:border-0">
                            <div class="flex items-start gap-3">
                                <input type="checkbox" name="req_card" id="req_card" value="1" 
                                       class="mt-1 w-4 h-4 rounded text-emerald-500 bg-slate-900 border-slate-700 focus:ring-emerald-500"
                                       {{ old('req_card') ? 'checked' : '' }}>
                                <label for="req_card" class="cursor-pointer">
                                    <span class="block text-sm font-bold text-slate-200">High School Report Card / Form 137</span>
                                    <span class="block text-xs text-slate-400">Scanned certified copy of your grades or report card.</span>
                                </label>
                            </div>
                            <input type="file" name="file_card" class="text-xs text-slate-400 file:mr-4 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-slate-800 file:text-slate-300 hover:file:bg-slate-700 cursor-pointer">
                        </div>

                        <!-- Requirement 4 -->
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 py-2 border-b border-slate-800/60 last:border-0">
                            <div class="flex items-start gap-3">
                                <input type="checkbox" name="req_picture" id="req_picture" value="1" 
                                       class="mt-1 w-4 h-4 rounded text-emerald-500 bg-slate-900 border-slate-700 focus:ring-emerald-500"
                                       {{ old('req_picture') ? 'checked' : '' }}>
                                <label for="req_picture" class="cursor-pointer">
                                    <span class="block text-sm font-bold text-slate-200">2x2 ID Picture</span>
                                    <span class="block text-xs text-slate-400">Recent photo with formal collar and white background.</span>
                                </label>
                            </div>
                            <input type="file" name="file_picture" class="text-xs text-slate-400 file:mr-4 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-slate-800 file:text-slate-300 hover:file:bg-slate-700 cursor-pointer">
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="text-center">
                        <button type="submit" class="skew-btn font-extrabold text-white tracking-widest uppercase">
                            <span>Submit Admission Form</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <x-sitemap />
    <x-footer />
    <x-scroll-to-top />
</body>
</html>
