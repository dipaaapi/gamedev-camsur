<!-- Certification Section -->
<section class="py-32 px-6 relative bg-fixed bg-cover bg-center border-t border-slate-200 dark:border-slate-800/60 transition-colors duration-300" style="background-image: url('{{ asset('bg%20unity.jpg') }}');">
    <!-- Overlay for readability -->
    <div class="absolute inset-0 bg-white/85 dark:bg-slate-950/80 backdrop-blur-[2px] transition-colors duration-300"></div>

    <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center gap-12 relative z-10">
        <div class="w-full md:w-1/2 flex flex-col gap-6 items-center md:items-start text-center md:text-left">
            <h2 class="text-4xl md:text-5xl font-extrabold text-slate-900 dark:text-white mb-2 uppercase tracking-wide drop-shadow-sm">
                Get <span class="text-blue-600 dark:text-brand-light">Certified</span>
            </h2>
            <p class="text-slate-800 dark:text-slate-200 text-[1.1rem] mt-4 leading-relaxed font-semibold">
                Complete our intensive program and earn your official certification to kickstart your career in the gaming industry. Fast-track your path to becoming an Associate Game Developer in just 3 months!
            </p>
        </div>
        <div class="w-full md:w-1/2 flex justify-center">
             <!-- Light Mode Image -->
             <img src="{{ asset('cert_default.gif') }}" alt="Certification Demo" class="w-full max-w-lg drop-shadow-2xl hover:scale-[1.02] transition-transform duration-500 show-in-light" onerror="this.src='https://via.placeholder.com/600x400/f8fafc/0f172a?text=Cert+Default'">
             <!-- Dark Mode GIF -->
             <img src="{{ asset('cert_dark.gif') }}" alt="Certification Demo Dark" class="w-full max-w-lg drop-shadow-[0_0_30px_rgba(59,130,246,0.3)] hover:scale-[1.02] transition-transform duration-500 show-in-dark" onerror="this.src='https://via.placeholder.com/600x400/0f172a/f8fafc?text=Cert+Dark'">
        </div>
    </div>
</section>
