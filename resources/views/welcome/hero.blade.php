<!-- Main Content (Hero) -->
<main id="home" class="flex-grow flex items-stretch justify-center pt-32 pb-0 px-6 relative bg-[#0b215e] overflow-hidden">
    <!-- Floating Elements Background & Network Pattern -->
    <div class="absolute inset-0 z-0 opacity-40 pointer-events-none">
        <!-- Simple subtle gradient glow -->
        <div class="absolute top-20 right-1/4 w-[40rem] h-[40rem] bg-blue-500/20 rounded-full blur-[120px]"></div>
        <!-- Network pattern -->
        <svg class="absolute inset-0 w-full h-full" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="network" x="0" y="0" width="80" height="80" patternUnits="userSpaceOnUse">
                    <circle cx="40" cy="40" r="1.5" fill="rgba(255,255,255,0.15)"></circle>
                    <circle cx="10" cy="10" r="1" fill="rgba(255,255,255,0.1)"></circle>
                    <circle cx="70" cy="20" r="2" fill="rgba(255,255,255,0.2)"></circle>
                    <line x1="40" y1="40" x2="10" y2="10" stroke="rgba(255,255,255,0.05)" stroke-width="0.5"></line>
                    <line x1="40" y1="40" x2="70" y2="20" stroke="rgba(255,255,255,0.05)" stroke-width="0.5"></line>
                </pattern>
            </defs>
            <rect x="0" y="0" width="100%" height="100%" fill="url(#network)"></rect>
        </svg>
    </div>

    <div class="max-w-7xl mx-auto flex flex-col lg:flex-row items-center gap-12 relative z-10 w-full min-h-[80vh]">
        <!-- Left Side: Text and Logos -->
        <div class="w-full lg:w-1/2 flex flex-col justify-center text-center lg:text-left pt-12 pb-20">
            <p class="text-[1.15rem] md:text-2xl font-light leading-relaxed md:leading-loose mb-8 !text-slate-200 tracking-wide max-w-2xl animate-swipe-1">
                Introducing the <span class="font-bold !text-white tracking-widest">Camsur Software Development Training Program</span>—a free and accessible opportunity for everyone to acquire essential coding and tech skills that can positively transform your life. Our vision is to position Camarines Sur as a leader in government-run IT hubs, with a special emphasis on game development.
            </p>
            
            <h2 class="text-3xl md:text-[2.5rem] font-extrabold !text-white mb-10 tracking-wider drop-shadow-md leading-tight animate-swipe-2">
                Governor Luis Raymund <br class="hidden md:block"/> "LRay" M. Villafuerte
            </h2>
            
            <div class="flex items-center justify-center lg:justify-start gap-8 mt-4 animate-swipe-3">
                <!-- Province of CamSur Seal -->
                <img src="{{ asset('logo.png') }}" alt="Province of Camarines Sur" class="w-20 h-20 object-contain drop-shadow-[0_0_15px_rgba(255,255,255,0.2)] hover:scale-105 transition-transform" onerror="this.src='https://via.placeholder.com/100/1e293b/94a3b8?text=CS+Seal'">
                <!-- Other Logo (Training Logo) -->
                <img src="{{ asset('lray_logo.png') }}" alt="Training Program Logo" class="w-20 h-20 object-contain drop-shadow-[0_0_15px_rgba(255,255,255,0.2)] hover:scale-105 transition-transform" onerror="this.src='https://via.placeholder.com/100/1e293b/94a3b8?text=Logo'">
                <!-- SEAICT Logo -->
                <img src="{{ asset('seaict_logo.png') }}" alt="Southeast Asian Institute of Computer Technology (SEAICT)" class="w-20 h-20 object-contain drop-shadow-[0_0_15px_rgba(255,255,255,0.2)] hover:scale-105 transition-transform" onerror="this.src='https://via.placeholder.com/100/1e293b/94a3b8?text=SEAICT'">
            </div>
        </div>
        
        <!-- Right Side: Governor Portrait -->
        <div class="w-full lg:w-1/2 flex justify-center lg:justify-end items-end self-stretch">
            <img src="{{ asset('govlray.png') }}" alt="Gov. Luis Lray Villafuerte" class="relative block w-full max-w-[32rem] xl:max-w-[42rem] h-auto drop-shadow-[0_15px_35px_rgba(0,0,0,0.6)] hover:scale-[1.02] transition-transform duration-500 origin-bottom" onerror="this.src='https://via.placeholder.com/600x700/1e293b/94a3b8?text=Governor+Portrait'">
        </div>
    </div>
</main>
