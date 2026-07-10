<!-- Careers Section -->
<section id="careers" class="py-20 px-6 relative border-t border-slate-800/60 bg-slate-900/30">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-white mb-4">Your Future <span class="gradient-text">Career</span></h2>
            <p class="text-slate-400 text-lg max-w-2xl mx-auto">Discover the exciting paths you can take in the game development industry after graduating.</p>
        </div>
        
        <div class="flex flex-col lg:flex-row gap-12 items-center">
            <!-- Left Side: Careers Image (Un-cropped / Displayed fully) -->
            <div class="w-full lg:w-5/12 flex justify-center">
                <img src="{{ asset('careers.png') }}" alt="Careers in Game Development" class="w-full max-w-lg rounded-3xl border border-slate-800/60 shadow-2xl object-contain transition-transform duration-700 hover:scale-[1.02]">
            </div>

            <!-- Right Side: Career Paths Sliding Carousel (loops every 30s) -->
            <div class="w-full lg:w-7/12 flex flex-col justify-between self-stretch" 
                 x-data="{ 
                     activeTab: 0, 
                     totalTabs: {{ $careers->count() }},
                     interval: null,
                     touchStartX: 0,
                     touchEndX: 0,
                     startAutoplay() {
                         this.interval = setInterval(() => {
                             this.activeTab = (this.activeTab + 1) % this.totalTabs;
                         }, 30000);
                     },
                     stopAutoplay() {
                         if (this.interval) clearInterval(this.interval);
                     },
                     handleTouchStart(e) {
                         this.touchStartX = e.changedTouches[0].screenX;
                     },
                     handleTouchEnd(e) {
                         this.touchEndX = e.changedTouches[0].screenX;
                         this.handleSwipe();
                     },
                     handleSwipe() {
                         if (this.touchStartX - this.touchEndX > 50) {
                             // Swipe Left -> Next
                             this.activeTab = (this.activeTab + 1) % this.totalTabs;
                         } else if (this.touchEndX - this.touchStartX > 50) {
                             // Swipe Right -> Prev
                             this.activeTab = (this.activeTab - 1 + this.totalTabs) % this.totalTabs;
                         }
                     }
                 }"
                 x-init="startAutoplay()"
                 @mouseenter="stopAutoplay()"
                 @mouseleave="startAutoplay()"
                 @touchstart="handleTouchStart($event)"
                 @touchend="handleTouchEnd($event)">
                 
                 <!-- Upper Right Corner Navigation Arrows -->
                 <div class="flex items-center justify-between mb-6 border-b border-slate-800/45 pb-4">
                     <span class="text-xs font-bold uppercase tracking-widest text-slate-500">Featured Career Paths (Slide <span x-text="activeTab + 1"></span> of {{ $careers->count() }})</span>
                     <div class="flex items-center gap-3 pr-2">
                         <!-- Skewed Arrow Left (styled like course banners) -->
                         <x-skew-button @click="activeTab = (activeTab - 1 + totalTabs) % totalTabs" 
                                         class="w-12 h-10"
                                         aria-label="Previous Career">
                             <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="#ffffff" style="stroke: #ffffff !important;">
                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                             </svg>
                         </x-skew-button>
                         <!-- Skewed Arrow Right (styled like course banners) -->
                         <x-skew-button @click="activeTab = (activeTab + 1) % totalTabs" 
                                         class="w-12 h-10"
                                         aria-label="Next Career">
                             <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="#ffffff" style="stroke: #ffffff !important;">
                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                             </svg>
                         </x-skew-button>
                     </div>
                 </div>

                 <!-- Slider Track Window with fixed height to stabilize position -->
                 <div class="relative overflow-hidden w-full flex-1 h-[420px] lg:h-[360px]">
                     <div class="flex w-full h-full transition-transform duration-700 ease-out" 
                          :style="`transform: translateX(-${activeTab * 100}%);`"
                          style="will-change: transform;">
                          
                          @foreach($careers as $index => $career)
                              <!-- Slide {{ $index + 1 }}: {{ $career->title }} -->
                              <div class="w-full shrink-0 flex flex-col justify-center px-6 overflow-hidden">
                                  <span class="{{ $career->color_class }} font-bold uppercase tracking-wider text-xs">{{ $career->category }}</span>
                                  <h3 class="text-3xl font-extrabold text-white mt-1 mb-4">{{ $career->title }}</h3>
                                  <p class="text-slate-300 leading-relaxed mb-6">
                                      {{ $career->description }}
                                  </p>
                                  <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                                      <div class="bg-gradient-to-br from-indigo-900/60 to-slate-900/80 -skew-x-12 px-5 py-3 shadow-[4px_4px_0px_rgba(0,0,0,0.3)] border-l-4 {{ $career->border_color }}">
                                          <div class="skew-x-12">
                                              <span class="text-xs text-indigo-300 block font-bold uppercase tracking-wider mb-0.5">{{ $career->meta_key_1 }}</span>
                                              <span class="text-sm font-semibold text-white">{{ $career->meta_value_1 }}</span>
                                          </div>
                                      </div>
                                      <div class="bg-gradient-to-br from-indigo-900/60 to-slate-900/80 -skew-x-12 px-5 py-3 shadow-[4px_4px_0px_rgba(0,0,0,0.3)] border-l-4 {{ $career->border_color }}">
                                          <div class="skew-x-12">
                                              <span class="text-xs text-indigo-300 block font-bold uppercase tracking-wider mb-0.5">{{ $career->meta_key_2 }}</span>
                                              <span class="text-sm font-semibold text-white">{{ $career->meta_value_2 }}</span>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          @endforeach
                      </div>
                 </div>
            </div>
        </div>

        <!-- Explore All Careers Redirection Button -->
        <div class="mt-16 text-center">
            <a href="{{ route('careers.all') }}" 
               class="inline-block px-8 py-3.5 bg-gradient-to-r from-brand-light to-purple-500 hover:from-brand hover:to-purple-600 text-white font-bold uppercase tracking-wider text-xs rounded-full transition-all duration-300 shadow-[0_0_20px_rgba(129,140,248,0.25)] hover:shadow-[0_0_30px_rgba(129,140,248,0.4)] hover:-translate-y-0.5">
                Explore All 50 Career Pathways
            </a>
        </div>
    </div>
</section>
