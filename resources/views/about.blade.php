<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark scroll-smooth">
<head>
    <x-head title="About Us - GameDev CamSur">
        .gradient-text {
            background: linear-gradient(to right, #818cf8, #c084fc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: inline-block;
        }
        @keyframes scanline {
            0% { top: 0%; }
            50% { top: 100%; }
            100% { top: 0%; }
        }
        .tech-scanline {
            animation: scanline 8s linear infinite;
        }
        @keyframes shutter-zoom {
            0% { transform: scale(1); filter: brightness(1); }
            15% { transform: scale(0.92); filter: brightness(0.9); }
            35% { transform: scale(1.15); filter: brightness(1.7) contrast(1.2); }
            70% { transform: scale(0.98); filter: brightness(0.95); }
            100% { transform: scale(1); filter: brightness(1); }
        }
        .shutter-zoom-active {
            animation: shutter-zoom 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards;
        }
    </x-head>
</head>
<body class="antialiased min-h-screen flex flex-col relative overflow-x-hidden">
    <x-loading-screen />
    


    <!-- Navigation Bar -->
    <x-guest-nav activePage="about" />

    <!-- Main Content (About Us) -->
    <main class="flex-grow flex flex-col items-center pt-32 pb-20 px-6 relative">
        <div class="max-w-6xl mx-auto w-full">
            <div class="text-center mb-16">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-slate-800/50 border border-slate-700/50 text-sm text-brand-light mb-8 backdrop-blur-sm">
                    <span class="relative flex h-2.5 w-2.5">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-brand-light opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-brand-light"></span>
                    </span>
                    Who We Are
                </div>
                
                <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight mb-8 leading-tight">
                    About<span class="gradient-text ml-1.5"> Us</span>
                </h1>
            </div>
            <!-- Story Section -->
            <section class="pt-24 pb-20 px-12 border-t border-slate-800/80 mb-12">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 items-start">
                    <div class="lg:col-span-1 flex flex-col gap-6">
                        <!-- Futuristic Holographic/Tech Dossier Panel for Governor's portrait -->
                        <div class="relative overflow-hidden rounded-2xl border border-indigo-500/30 bg-slate-950/60 p-4 shadow-[0_0_30px_rgba(99,102,241,0.15)] group">
                            <!-- HUD Corner Brackets -->
                            <div class="absolute top-2 left-2 w-3 h-3 border-t-2 border-l-2 border-indigo-500/80"></div>
                            <div class="absolute top-2 right-2 w-3 h-3 border-t-2 border-r-2 border-indigo-500/80"></div>
                            <div class="absolute bottom-2 left-2 w-3 h-3 border-b-2 border-l-2 border-indigo-500/80"></div>
                            <div class="absolute bottom-2 right-2 w-3 h-3 border-b-2 border-r-2 border-indigo-500/80"></div>

                            <!-- Scrolling Tech Scanline -->
                            <div class="absolute inset-x-0 h-0.5 bg-indigo-500/40 shadow-[0_0_8px_rgba(99,102,241,0.8)] pointer-events-none tech-scanline z-10 top-0 group-hover:opacity-0 transition-opacity duration-300"></div>
                            
                            <!-- Tech Dot Matrix grid overlay -->
                            <div class="absolute inset-0 bg-[radial-gradient(rgba(99,102,241,0.08)_1px,transparent_1px)] bg-[size:16px_16px] pointer-events-none z-10"></div>

                            <!-- Image Container (1x1 Aspect Ratio / Click to Shutter Zoom) -->
                            <div x-data="{ shutterActive: false }" 
                                 @click="shutterActive = true; setTimeout(() => shutterActive = false, 400)"
                                 class="relative overflow-hidden rounded-lg border border-indigo-500/20 aspect-square bg-slate-900/40 mb-3 p-4 flex items-center justify-center cursor-pointer select-none">
                                <img src="{{ asset('govlraytech.png') }}" 
                                     alt="Governor Luis Raymund &quot;LRay&quot; M. Villafuerte" 
                                     class="max-w-full max-h-full object-contain filter brightness-[0.85] contrast-[1.05] saturate-[0.8] transition-all duration-700 group-hover:scale-105 group-hover:brightness-100 group-hover:saturate-100"
                                     :class="shutterActive ? 'shutter-zoom-active' : ''">
                                <!-- Vignette / Holographic Overlay -->
                                <div class="absolute inset-0 bg-gradient-to-t from-indigo-950/40 via-transparent to-transparent pointer-events-none"></div>
                                <div class="absolute inset-0 border border-indigo-500/10 pointer-events-none"></div>
                            </div>

                            <!-- Dossier Meta Information -->
                            <div class="flex flex-col gap-1.5 pt-1">
                                <div class="flex items-center justify-between font-mono text-[9px] font-bold text-indigo-400 tracking-wider uppercase">
                                    <span>PROTOCOL: GAME DEV</span>
                                    <span class="flex h-1.5 w-1.5 relative">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-emerald-500"></span>
                                    </span>
                                </div>
                                <div class="h-px bg-slate-800/80 w-full"></div>
                                <div class="flex justify-between font-mono text-[8px] text-slate-400 dark:text-slate-500 uppercase tracking-widest">
                                    <span>ROLE: PROVINCIAL GOVERNOR</span>
                                    <span>LOC: PILI, CAMARINES SUR</span>
                                </div>
                            </div>
                        </div>

                        <!-- Logos Flex Row -->
                        <div class="logos-panel flex items-center justify-center gap-8 p-4 rounded-2xl bg-slate-900/20 border border-slate-800/40 mt-2 relative overflow-hidden">
                            <img src="{{ asset('lray_logo.png') }}" alt="LRay Logo" class="h-16 w-auto object-contain logo-invert-in-light filter brightness-95 opacity-80 hover:opacity-100 hover:brightness-100 transition-all duration-300 relative z-10">
                            <img src="{{ asset('logo.png') }}" alt="CamSur Logo" class="h-16 w-auto object-contain logo-invert-in-light filter brightness-95 opacity-80 hover:opacity-100 hover:brightness-100 transition-all duration-300 relative z-10">
                        </div>
                    </div>
                    <div class="lg:col-span-2 text-slate-400 text-lg leading-relaxed space-y-6">
                        <p>
                            GameDev CamSur was founded with a mission to build a thriving, supportive ecosystem for game developers in Camarines Sur. What started as a small group of passionate creators has grown into a vibrant community of programmers, artists, designers, and audio engineers.
                        </p>
                        <p>
                            We believe that world-class games can be built right here in our local province. By providing resources, networking opportunities, and educational programs, we aim to bridge the gap between aspiring talent and industry success.
                        </p>
                        <p class="border-t border-slate-800/60 pt-6">
                            <strong>Governor Luis Raymund "LRay" M. Villafuerte</strong> envisioned the province of Cam Sur as the pioneer in establishing the first IT hub managed and owned by the provincial government. Spearheading this plan through focusing on game development, a booming industry in this era.
                        </p>
                        <p>
                            The provincial government of Camarines Sur will create <strong>FREE GAME DEVELOPMENT COURSES</strong> that will equip students with the knowledge and skills to allow them to become competent game developers. The program will provide quality and best training and certifications from Unity to help them begin their career in game development.
                        </p>
                    </div>
                </div>
            </section>

            <!-- Mission & Vision Section -->
            <section class="pt-24 pb-20 px-12 border-t border-slate-800/80 mb-12">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20">
                    <!-- Mission -->
                    <div class="flex flex-col gap-4">
                        <span class="text-xs font-bold uppercase tracking-widest text-indigo-400">01 / Commitment</span>
                        <h2 class="text-4xl font-extrabold text-white tracking-tight">Our Mission</h2>
                        <p class="text-slate-300 leading-relaxed text-base mt-2">
                            The <strong class="text-white">Digital Literacy Program – Provincial Government of Camarines Sur</strong>, through its Information Communication Archives and Records Management Office, is committed to providing graduates with industry-relevant skills in game design and development, fostering linkages with international game development companies, facilitating outsourced projects, and producing Unity-certified professionals.
                        </p>
                        <p class="text-slate-500 leading-relaxed text-sm">
                            These graduates will become pioneers in cultivating the game development industry in the Bicol Region and beyond, equipped to contribute effectively to the digital economy and creative industries.
                        </p>
                    </div>

                    <!-- Vision -->
                    <div class="flex flex-col gap-4">
                        <span class="text-xs font-bold uppercase tracking-widest text-purple-400">02 / Future</span>
                        <h2 class="text-4xl font-extrabold text-white tracking-tight">Our Vision</h2>
                        <p class="text-slate-300 leading-relaxed text-base mt-2">
                            By 2030, the <strong class="text-white">Digital Literacy Program – Provincial Government of Camarines Sur</strong>, through its Information Communication Archives and Records Management Office, shall be recognized as a leading educational institution in industry-based game design and development training.
                        </p>
                        <p class="text-slate-500 leading-relaxed text-sm">
                            As a distinguished and tuition-free school, it shall provide internationally recognized education through innovative modules, serving as a catalyst for empowering the community and advancing human capital development in the modern Bicol Region.
                        </p>
                    </div>
                </div>
            </section>

            <!-- Service Pledge Section -->
            <section class="pt-24 pb-20 px-12 border-t border-slate-800/80 mb-12">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                    <!-- Left Column -->
                    <div class="lg:col-span-1">
                        <span class="text-xs font-bold uppercase tracking-widest text-pink-400">03 / Principles</span>
                        <h2 class="text-4xl font-extrabold text-white tracking-tight mt-2 mb-6">Service Pledge</h2>
                        <p class="text-slate-400 leading-relaxed text-sm">
                            We, the instructors and staff of the <strong class="text-white">Digital Literacy Program – Provincial Government of Camarines Sur</strong>, hereby commit to uphold these core standards of institutional excellence.
                        </p>
                    </div>

                    <!-- Right Column -->
                    <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-10">
                        @php
                            $pledges = [
                                "Deliver quality, industry-relevant instruction.",
                                "Ensure accessibility and inclusivity for all learners.",
                                "Uphold professionalism, integrity, and ethics.",
                                "Maintain a safe and conducive learning environment.",
                                "Promote community engagement and lifelong learning.",
                                "Support continuous improvement and institutional excellence."
                            ];
                            $colors = [
                                "text-indigo-500/30",
                                "text-purple-500/30",
                                "text-pink-500/30",
                                "text-blue-500/30",
                                "text-emerald-500/30",
                                "text-amber-500/30"
                            ];
                        @endphp
                        @foreach($pledges as $index => $pledge)
                            <div class="flex items-start gap-4 border-l border-slate-800 pl-4 py-1">
                                <span class="text-4xl font-black {{ $colors[$index % count($colors)] }} leading-none select-none">
                                    0{{ $index + 1 }}
                                </span>
                                <div class="flex flex-col">
                                    <p class="text-slate-300 text-sm leading-relaxed font-semibold">
                                        {{ $pledge }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

            <!-- About Our Services Section -->
            <section class="pt-24 pb-20 px-12 border-t border-slate-800/80 mb-10">
                <div class="text-center mb-16">
                    <span class="text-xs font-bold uppercase tracking-widest text-slate-500">Institutional Charter</span>
                    <h2 class="text-4xl font-extrabold text-white tracking-tight mt-2">About Our Services</h2>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 lg:gap-8 divide-y lg:divide-y-0 lg:divide-x divide-slate-800/80">
                    <!-- Admission -->
                    <div class="flex flex-col gap-4 pb-8 lg:pb-0 lg:px-8 first:pl-0 last:pr-0">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold uppercase tracking-widest text-indigo-400">01</span>
                            <h3 class="text-xl font-bold text-white tracking-tight">Admission & Registration</h3>
                        </div>
                        <p class="text-slate-400 text-sm leading-relaxed">
                            All Senior High School students enrolled in institutions under the Provincial Government of Camarines Sur shall serve as the primary beneficiaries of the specialized digital and technical skills training programs offered by the <strong>Digital Literacy Program – Provincial Government of Camarines Sur</strong>. Completion of the official registration process is required to secure a training slot and to obtain access to the prescribed course schedule, facilities, and instructional resources.
                        </p>
                    </div>

                    <!-- Certificate Issuance -->
                    <div class="flex flex-col gap-4 pt-8 lg:pt-0 lg:px-8 first:pl-0 last:pr-0">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold uppercase tracking-widest text-emerald-400">02</span>
                            <h3 class="text-xl font-bold text-white tracking-tight">Certificate Issuance</h3>
                        </div>
                        <p class="text-slate-400 text-sm leading-relaxed">
                            All graduates of the <strong>Digital Literacy Program – Provincial Government of Camarines Sur</strong> who have successfully completed their respective courses shall be recognized as the primary beneficiaries entitled to request the issuance of official certificates. Completion of the prescribed request and verification process is required to ensure the authenticity of records and to facilitate the release of duly certified certificates in accordance with established policies and procedures.
                        </p>
                    </div>

                    <!-- Feedback & Complaints -->
                    <div class="flex flex-col gap-4 pt-8 lg:pt-0 lg:px-8 first:pl-0 last:pr-0">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold uppercase tracking-widest text-amber-400">03</span>
                            <h3 class="text-xl font-bold text-white tracking-tight">Feedback & Complaints</h3>
                        </div>
                        <p class="text-slate-400 text-sm leading-relaxed">
                            The <strong>Digital Literacy Program – Provincial Government of Camarines Sur</strong> provides a complaint and feedback mechanism as part of its commitment to uphold the principles of R.A. 11032, otherwise known as the <em>Ease of Doing Business and Efficient Government Service Delivery Act of 2018</em>, and the PGCS PLEDGE. This policy ensures that all students, school representatives, and citizens of Camarines Sur have access to a formal process for seeking assistance, expressing dissatisfaction with service delivery, or reporting any violation of established service standards, particularly those concerning training programs and enrollment procedures.
                        </p>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <!-- Footer -->
    <x-sitemap />
    <x-footer />
    <x-scroll-to-top />
</body>
</html>
