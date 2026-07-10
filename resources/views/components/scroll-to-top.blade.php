<!-- Scroll to Top Button -->
<button id="scrollToTopBtn" onclick="window.scrollTo({top: 0, behavior: 'smooth'})" class="fixed bottom-6 right-6 z-50 w-12 h-12 bg-slate-800/80 hover:bg-slate-700 backdrop-blur-sm border border-slate-700/50 rounded-full flex items-center justify-center text-white shadow-lg transition-all duration-300 opacity-0 translate-y-10 pointer-events-none group" aria-label="Scroll to top">
    <svg class="w-6 h-6 group-hover:-translate-y-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
    </svg>
</button>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const scrollToTopBtn = document.getElementById('scrollToTopBtn');
        
        // Show/hide based on scroll position
        window.addEventListener('scroll', () => {
            if (window.scrollY > 300) {
                scrollToTopBtn.classList.remove('opacity-0', 'translate-y-10', 'pointer-events-none');
                scrollToTopBtn.classList.add('opacity-100', 'translate-y-0');
            } else {
                scrollToTopBtn.classList.remove('opacity-100', 'translate-y-0');
                scrollToTopBtn.classList.add('opacity-0', 'translate-y-10', 'pointer-events-none');
            }
        });
    });
</script>
