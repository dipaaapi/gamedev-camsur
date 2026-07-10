<div class="relative">
    <button onclick="document.getElementById('a11y-nav-menu').classList.toggle('hidden'); event.stopPropagation();" class="text-sm font-medium text-slate-300 hover:text-white transition-colors px-4 py-2 flex items-center gap-2 rounded-full bg-slate-800/50 hover:bg-slate-700/50 border border-slate-700/50">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2zm9 7h-6v13h-2v-6h-2v6H9V9H3V7h18v2z"/></svg>
        <span class="hidden sm:inline">Accessibility</span>
    </button>
    
    <!-- Dropdown Menu -->
    <div id="a11y-nav-menu" class="hidden absolute right-0 mt-2 w-56 bg-slate-800/95 backdrop-blur-md border border-slate-700/50 rounded-2xl shadow-2xl py-2 z-[100]">
        <div class="text-xs font-bold text-slate-400 mb-2 px-4 pt-2 uppercase tracking-wider">Appearance</div>
        
        <button onclick="toggleA11yClass('a11y-light-mode')" class="flex items-center gap-3 px-4 py-2 text-sm text-white hover:bg-brand/20 w-full text-left transition-colors">
            <svg class="w-4 h-4 text-brand-light" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
            Toggle Light Mode
        </button>

        <button onclick="toggleA11yClass('a11y-high-contrast')" class="flex items-center gap-3 px-4 py-2 text-sm text-white hover:bg-brand/20 w-full text-left transition-colors">
            <svg class="w-4 h-4 text-brand-light" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" /></svg>
            High Contrast
        </button>
        
        <div class="h-px bg-slate-700/50 my-2"></div>
        <div class="text-xs font-bold text-slate-400 mb-2 px-4 uppercase tracking-wider">Reading</div>
        
        <button onclick="toggleA11yClass('a11y-large-text')" class="flex items-center gap-3 px-4 py-2 text-sm text-white hover:bg-brand/20 w-full text-left transition-colors">
            <svg class="w-4 h-4 text-brand-light" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" /></svg>
            Enlarge Text
        </button>
        
        <button onclick="toggleA11yClass('a11y-dyslexic')" class="flex items-center gap-3 px-4 py-2 text-sm text-white hover:bg-brand/20 w-full text-left transition-colors">
            <svg class="w-4 h-4 text-brand-light" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
            Dyslexia Font
        </button>
        
        <div class="h-px bg-slate-700/50 my-2"></div>
        
        <button onclick="resetA11y()" class="flex items-center gap-3 px-4 py-2 text-sm text-rose-400 hover:bg-rose-500/10 w-full text-left transition-colors rounded-b-xl">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
            Reset Settings
        </button>
    </div>
</div>

<style>
    /* Accessibility Classes */
    
    /* Light Mode Override */
    html.a11y-light-mode body {
        background-color: #f8fafc !important;
        color: #0f172a !important;
    }
    html.a11y-light-mode .bg-slate-900\/30,
    html.a11y-light-mode .bg-slate-900\/40,
    html.a11y-light-mode .bg-slate-900\/50,
    html.a11y-light-mode .glass-nav,
    html.a11y-light-mode section,
    html.a11y-light-mode footer {
        background: #ffffff !important;
        border-color: #cbd5e1 !important;
        backdrop-filter: none !important;
        -webkit-backdrop-filter: none !important;
    }
    html.a11y-light-mode .bg-slate-800\/30,
    html.a11y-light-mode .bg-slate-800\/50,
    html.a11y-light-mode .bg-slate-800\/80,
    html.a11y-light-mode .border-slate-800\/50,
    html.a11y-light-mode .border-slate-800\/60,
    html.a11y-light-mode .border-slate-700\/50,
    html.a11y-light-mode .hover\:bg-slate-700:hover,
    html.a11y-light-mode .bg-slate-800\/95 {
        background: #f1f5f9 !important;
        border-color: #cbd5e1 !important;
    }
    html.a11y-light-mode .text-slate-400,
    html.a11y-light-mode .text-slate-300,
    html.a11y-light-mode .text-slate-200,
    html.a11y-light-mode .text-slate-100,
    html.a11y-light-mode .text-slate-50,
    html.a11y-light-mode .text-white {
        color: #334155 !important;
    }
    html.a11y-light-mode .course-banner,
    html.a11y-light-mode .course-banner *,
    html.a11y-light-mode .enroll-banner,
    html.a11y-light-mode .enroll-banner * {
        color: #ffffff !important;
    }
    html.a11y-light-mode h1, html.a11y-light-mode h2, html.a11y-light-mode h3 {
        color: #0f172a !important;
    }

    html.a11y-light-mode .logo-invert-in-light {
        filter: invert(1) brightness(0.2) opacity(0.8) !important;
    }
    html.a11y-light-mode .logo-invert-in-light:hover {
        filter: invert(1) brightness(0) opacity(1) !important;
    }
    
    html.a11y-light-mode .logos-panel {
        background-image: url('/bgcapitol.png') !important;
        background-attachment: fixed !important;
        background-position: center !important;
        background-repeat: no-repeat !important;
        background-size: cover !important;
    }
    html.a11y-light-mode .logos-panel::before {
        content: '';
        position: absolute;
        inset: 0;
        background-color: rgba(255, 255, 255, 0.85) !important;
        z-index: 1;
    }

    /* High Contrast Override */
    html.a11y-high-contrast body {
        background-color: #000 !important;
        color: #ffff00 !important; 
    }
    html.a11y-high-contrast * {
        border-color: #ffff00 !important;
    }
    html.a11y-high-contrast .bg-slate-800\/30,
    html.a11y-high-contrast .bg-slate-800\/50,
    html.a11y-high-contrast .bg-slate-800\/80,
    html.a11y-high-contrast .hover\:bg-slate-700:hover,
    html.a11y-high-contrast .bg-slate-800\/95,
    html.a11y-high-contrast .bg-slate-900\/50,
    html.a11y-high-contrast .glass-nav,
    html.a11y-high-contrast section,
    html.a11y-high-contrast footer {
        background: #000 !important;
        border: 2px solid #ffff00 !important;
    }
    html.a11y-high-contrast .text-slate-400,
    html.a11y-high-contrast .text-slate-300,
    html.a11y-high-contrast .text-brand-light,
    html.a11y-high-contrast .text-white {
        color: #ffff00 !important;
    }
    html.a11y-high-contrast .gradient-text {
        background: none !important;
        -webkit-text-fill-color: #ffff00 !important;
        color: #ffff00 !important;
    }
    html.a11y-high-contrast svg {
        color: #ffff00 !important;
    }
    html.a11y-high-contrast .bg-gradient-to-r,
    html.a11y-high-contrast .bg-gradient-to-br {
        background: #000 !important;
        border: 2px solid #ffff00 !important;
    }
    
    html.a11y-large-text body {
        font-size: 115% !important;
    }
    html.a11y-large-text p, 
    html.a11y-large-text a, 
    html.a11y-large-text span, 
    html.a11y-large-text button { 
        font-size: 110% !important; 
        line-height: 1.6 !important;
    }
    html.a11y-large-text h1 { font-size: 140% !important; }
    html.a11y-large-text h2 { font-size: 130% !important; }
    html.a11y-large-text h3 { font-size: 120% !important; }

    html.a11y-dyslexic body, 
    html.a11y-dyslexic * {
        font-family: 'Comic Sans MS', 'OpenDyslexic', sans-serif !important;
        letter-spacing: 0.05em !important;
        word-spacing: 0.1em !important;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const settings = JSON.parse(localStorage.getItem('a11y-settings')) || {};
        Object.keys(settings).forEach(key => {
            if (settings[key]) {
                document.documentElement.classList.add(key);
            }
        });
        
        // If light mode is active, make sure dark class is removed
        if (document.documentElement.classList.contains('a11y-light-mode')) {
            document.documentElement.classList.remove('dark');
        }
        
        // Close menu on outside click
        document.addEventListener('click', (e) => {
            const menu = document.getElementById('a11y-nav-menu');
            const button = menu.previousElementSibling;
            if (menu && !menu.contains(e.target) && !button.contains(e.target)) {
                menu.classList.add('hidden');
            }
        });
    });

    function toggleA11yClass(className) {
        // High contrast and Light mode are mutually exclusive
        if (className === 'a11y-light-mode') {
            document.documentElement.classList.remove('a11y-high-contrast');
            // Toggle light mode class first
            const isCurrentlyLight = document.documentElement.classList.contains('a11y-light-mode');
            if (isCurrentlyLight) {
                // Turning OFF light mode -> Restore dark mode
                document.documentElement.classList.add('dark');
            } else {
                // Turning ON light mode -> Remove dark mode
                document.documentElement.classList.remove('dark');
            }
        } else if (className === 'a11y-high-contrast') {
            document.documentElement.classList.remove('a11y-light-mode');
            document.documentElement.classList.add('dark');
        }
        
        document.documentElement.classList.toggle(className);
        saveA11ySettings();
    }

    function resetA11y() {
        document.documentElement.classList.remove('a11y-light-mode', 'a11y-high-contrast', 'a11y-large-text', 'a11y-dyslexic');
        document.documentElement.classList.add('dark');
        saveA11ySettings();
    }

    function saveA11ySettings() {
        const settings = {
            'a11y-light-mode': document.documentElement.classList.contains('a11y-light-mode'),
            'a11y-high-contrast': document.documentElement.classList.contains('a11y-high-contrast'),
            'a11y-large-text': document.documentElement.classList.contains('a11y-large-text'),
            'a11y-dyslexic': document.documentElement.classList.contains('a11y-dyslexic')
        };
        localStorage.setItem('a11y-settings', JSON.stringify(settings));
    }
</script>
