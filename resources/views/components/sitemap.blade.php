<div class="border-t border-slate-800/60 bg-slate-900/50 backdrop-blur-md">
    <div class="max-w-7xl mx-auto px-6 py-12">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-12 md:gap-8">
            <div class="col-span-1 md:col-span-2">
                <div class="flex items-center gap-2 mb-6">
                    <x-brand-logo />
                </div>
                <p class="text-slate-400 text-sm max-w-md leading-relaxed">
                    A vibrant community of game developers, artists, and enthusiasts based in Camarines Sur. Empowering the local tech scene through collaboration and creativity.
                </p>
            </div>
            
            <div>
                <h3 class="text-white font-semibold mb-6">Sitemap</h3>
                <ul class="space-y-4 text-sm text-slate-400">
                    <li><a href="{{ url('/') }}#home" class="hover:text-brand-light transition-colors">Home</a></li>
                    <li><a href="{{ route('about') }}" class="hover:text-brand-light transition-colors">About Us</a></li>
                    <li><a href="{{ route('public-info') }}" class="hover:text-brand-light transition-colors">Public Information</a></li>
                    <li><a href="{{ url('/') }}#contact" class="hover:text-brand-light transition-colors">Contact Us</a></li>
                </ul>
            </div>

            <div>
                <h3 class="text-white font-semibold mb-6">Academy</h3>
                <ul class="space-y-4 text-sm text-slate-400">
                    <li><a href="{{ route('courses.basic') }}" class="hover:text-brand-light transition-colors">Basic Course</a></li>
                    <li><a href="{{ route('courses.advanced') }}" class="hover:text-brand-light transition-colors">Advanced Course</a></li>
                    <li><a href="{{ url('/') }}#careers" class="hover:text-brand-light transition-colors">Featured Careers</a></li>
                    <li><a href="{{ route('careers.all') }}" class="hover:text-brand-light transition-colors">All Careers</a></li>
                </ul>
            </div>
            
            <div>
                <h3 class="text-white font-semibold mb-6">Connect</h3>
                <ul class="space-y-4 text-sm text-slate-400">
                    <li><a href="#" class="hover:text-brand-light transition-colors">Discord Server</a></li>
                    <li><a href="#" class="hover:text-brand-light transition-colors">Facebook Group</a></li>
                    <li><a href="{{ route('login') }}" class="hover:text-brand-light transition-colors">Portal Login</a></li>
                    <li><a href="{{ route('register') }}" class="hover:text-brand-light transition-colors">Portal Register</a></li>
                </ul>
            </div>
        </div>
        
        <div class="mt-12 flex flex-wrap justify-center gap-4">
            <div class="skew-x-[-15deg] px-6 py-2 shadow-lg shadow-red-900/20" style="background: linear-gradient(135deg, #991b1b, #dc2626) !important;">
                <span class="block skew-x-[15deg] font-bold tracking-wider" style="color: #ffffff !important;">FOR MORE INFORMATION</span>
            </div>
            <a href="mailto:gamedev@camsur.com" class="skew-x-[-15deg] px-6 py-2 shadow-lg shadow-blue-900/20 hover:-translate-y-1 transition-transform" style="background: linear-gradient(135deg, #1e3a8a, #2563eb) !important;">
                <span class="block skew-x-[15deg] font-bold flex items-center gap-2" style="color: #ffffff !important;">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                    gamedev@camsur.com
                </span>
            </a>
            <a href="tel:09472925851" class="skew-x-[-15deg] px-6 py-2 shadow-lg shadow-green-900/20 hover:-translate-y-1 transition-transform" style="background: linear-gradient(135deg, #22c55e, #10b981) !important;">
                <span class="block skew-x-[15deg] font-bold flex items-center gap-2" style="color: #ffffff !important;">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                    09472925851
                </span>
            </a>
            <a href="#" class="skew-x-[-15deg] px-6 py-2 shadow-lg shadow-blue-900/20 hover:-translate-y-1 transition-transform" style="background: linear-gradient(135deg, #2563eb, #60a5fa) !important;">
                <span class="block skew-x-[15deg] font-bold flex items-center gap-2" style="color: #ffffff !important;">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/></svg>
                    Facebook
                </span>
            </a>
            <a href="#" class="skew-x-[-15deg] px-6 py-2 shadow-lg shadow-pink-900/20 hover:-translate-y-1 transition-transform" style="background: linear-gradient(135deg, #ec4899, #8b5cf6, #f97316) !important;">
                <span class="block skew-x-[15deg] font-bold flex items-center gap-2" style="color: #ffffff !important;">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                    Instagram
                </span>
            </a>
            <a href="#" class="skew-x-[-15deg] px-6 py-2 shadow-lg shadow-red-900/20 hover:-translate-y-1 transition-transform" style="background: linear-gradient(135deg, #dc2626, #ef4444) !important;">
                <span class="block skew-x-[15deg] font-bold flex items-center gap-2" style="color: #ffffff !important;">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                    YouTube
                </span>
            </a>
        </div>
    </div>
</div>
