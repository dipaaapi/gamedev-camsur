<!-- Loading Screen Overlay -->
<div id="loading-screen" class="fixed inset-0 z-[9999] flex flex-col items-center justify-center bg-[#070b19] transition-opacity duration-500 overflow-hidden">
    <!-- Parallax background elements -->
    <div class="absolute inset-0 pointer-events-none overflow-hidden">
        <!-- Floating glowing circles with pulse animation -->
        <div class="parallax-shape absolute top-1/4 left-1/4 w-[35rem] h-[35rem] bg-blue-500/10 rounded-full blur-[100px] animate-pulse" style="animation-duration: 6s; transform: translate(0px, 0px);"></div>
        <div class="parallax-shape absolute bottom-1/4 right-1/4 w-[40rem] h-[40rem] bg-purple-500/10 rounded-full blur-[120px] animate-pulse" style="animation-duration: 8s; transform: translate(0px, 0px);"></div>
        
        <!-- Network/Grid style background with light opacity -->
        <svg class="absolute inset-0 w-full h-full opacity-10" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="loader-grid" x="0" y="0" width="40" height="40" patternUnits="userSpaceOnUse">
                    <circle cx="20" cy="20" r="1" fill="#fff"></circle>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#loader-grid)"></rect>
        </svg>
    </div>
    
    <!-- Central Loading Container -->
    <div class="relative flex flex-col items-center justify-center scale-95 md:scale-100 transition-all duration-300">
        <!-- Rotating outer rings -->
        <div class="absolute w-64 h-64 rounded-full border border-dashed border-slate-700/40 animate-spin" style="animation-duration: 25s;"></div>
        <div class="absolute w-72 h-72 rounded-full border border-dashed border-brand-light/20 animate-spin" style="animation-duration: 18s; animation-direction: reverse;"></div>
        
        <!-- Pulsing radial gradient glow -->
        <div class="absolute w-60 h-60 rounded-full bg-gradient-to-tr from-brand-light to-purple-500 opacity-20 blur-2xl animate-ping" style="animation-duration: 4s;"></div>

        <!-- GIF container (no square background, larger gif) -->
        <div class="relative w-64 h-64 flex items-center justify-center">
            <img src="{{ asset('cubes.gif') }}" alt="Loading..." class="w-60 h-60 object-contain relative z-10" style="transform: translate(-15px, 10px);">
        </div>
        
        <!-- Loading text with glowing animation -->
        <div class="mt-8 flex flex-col items-center relative">
            <span class="text-2xl font-black tracking-[0.3em] text-white uppercase bg-gradient-to-r from-brand-light via-purple-400 to-brand-light bg-[length:200%_auto] animate-[gradient_3s_linear_infinite] bg-clip-text -webkit-background-clip-text -webkit-text-fill-color:transparent drop-shadow-[0_0_15px_rgba(99,102,241,0.3)]">Loading</span>
        </div>
    </div>
</div>

<!-- Interactive Background Glows (moved from background-glows component) -->
<div class="background-glow-container absolute inset-0 pointer-events-none overflow-hidden -z-10">
    <div class="background-image-parallax absolute inset-0 bg-cover bg-center opacity-[0.06] transition-transform duration-300 ease-out" style="background-image: url('/bgcapitol.png'); filter: grayscale(100%) brightness(0.5); transform: scale(1.05);"></div>
    <div class="background-glow-1 absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[400px] bg-brand/20 blur-[120px] rounded-full transition-transform duration-300 ease-out"></div>
    <div class="background-glow-2 absolute bottom-0 right-0 w-[600px] h-[600px] bg-purple-500/10 blur-[120px] rounded-full transition-transform duration-300 ease-out"></div>
</div>

<style>
    @keyframes gradient {
        0% { bg-position: 0% center; }
        100% { bg-position: 200% center; }
    }
</style>

<script>
    (function() {
        // Combined Parallax effect on mouse move
        document.addEventListener('mousemove', function(e) {
            // Background glows parallax (always runs unless accessibility mode is active)
            const html = document.documentElement;
            if (!html.classList.contains('a11y-light-mode') && !html.classList.contains('a11y-high-contrast')) {
                const amountGlow = 30; // parallax sensitivity
                const xGlow = (e.clientX - window.innerWidth / 2) / amountGlow;
                const yGlow = (e.clientY - window.innerHeight / 2) / amountGlow;

                const glow1 = document.querySelector('.background-glow-1');
                const glow2 = document.querySelector('.background-glow-2');
                const bgImage = document.querySelector('.background-image-parallax');

                if (glow1) {
                    glow1.style.transform = `translate(calc(-50% + ${xGlow}px), ${yGlow}px)`;
                }
                if (glow2) {
                    glow2.style.transform = `translate(${xGlow * -0.7}px, ${yGlow * -0.7}px)`;
                }
                if (bgImage) {
                    bgImage.style.transform = `scale(1.05) translate(${xGlow * -0.15}px, ${yGlow * -0.15}px)`;
                }
            }

            // Loading screen parallax (only when visible)
            const loader = document.getElementById('loading-screen');
            if (loader && window.getComputedStyle(loader).display !== 'none') {
                const amount = 25; // Lower number = more movement
                const x = (e.clientX - window.innerWidth / 2) / amount;
                const y = (e.clientY - window.innerHeight / 2) / amount;
                
                // Shift background shapes
                const shapes = loader.querySelectorAll('.parallax-shape');
                shapes.forEach((shape, index) => {
                    const factor = (index + 1) * 1.5; // More movement
                    shape.style.transform = `translate(${x * factor}px, ${y * factor}px)`;
                });

                // Shift background SVG grid slightly
                const grid = loader.querySelector('svg');
                if (grid) {
                    grid.style.transform = `translate(${x * 0.3}px, ${y * 0.3}px)`;
                }

                // Counter-shift the central cube container to create a real 3D depth effect
                const content = loader.querySelector('.relative.flex.flex-col');
                if (content) {
                    content.style.transform = `translate(${-x * 0.8}px, ${-y * 0.8}px)`;
                }
            }
        });

        // Hide loading screen on page load completion
        window.addEventListener('load', function() {
            const loader = document.getElementById('loading-screen');
            if (loader) {
                loader.classList.add('opacity-0');
                setTimeout(() => {
                    loader.style.display = 'none';
                }, 500);
            }
        });

        // Show loading screen on standard page transitions
        document.addEventListener('click', function(e) {
            const link = e.target.closest('a');
            if (link && 
                link.href && 
                !link.href.includes('#') && 
                link.target !== '_blank' && 
                !e.ctrlKey && 
                !e.metaKey && 
                link.hostname === window.location.hostname &&
                !link.href.startsWith('mailto:') &&
                !link.href.startsWith('tel:') &&
                !link.href.startsWith('javascript:')
            ) {
                const loader = document.getElementById('loading-screen');
                if (loader) {
                    loader.style.display = 'flex';
                    loader.offsetHeight; // Force reflow
                    loader.classList.remove('opacity-0');
                }
            }
        });

        // Show loading screen on form submissions
        document.addEventListener('submit', function(e) {
            const form = e.target;
            if (form && form.target !== '_blank') {
                const loader = document.getElementById('loading-screen');
                if (loader) {
                    loader.style.display = 'flex';
                    loader.offsetHeight;
                    loader.classList.remove('opacity-0');
                }
            }
        });
    })();
</script>
