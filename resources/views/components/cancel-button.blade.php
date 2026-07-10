@props([
    'href' => null,
    'type' => 'button',
    'variant' => 'cancel' {{-- 'cancel' or 'clear' --}}
])

@if($href)
    <a href="{{ $href }}" 
       x-data="{ isLight: document.documentElement.classList.contains('a11y-light-mode') }"
       x-init="
          const observer = new MutationObserver(() => {
              isLight = document.documentElement.classList.contains('a11y-light-mode');
          });
          observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
       "
       {{ $attributes->merge(['class' => 'inline-flex items-center justify-center px-6 py-2.5 -skew-x-12 text-sm font-semibold transition-all duration-200 cursor-pointer border shadow-[3px_3px_0px_rgba(0,0,0,0.3)]']) }}
       :style="isLight 
            ? ( '{{ $variant }}' === 'clear' 
                ? 'background: linear-gradient(135deg, #fee2e2, #fca5a5) !important; border-color: #fca5a5 !important; color: #991b1b !important;' 
                : 'background: linear-gradient(135deg, #ffffff, #f1f5f9) !important; border-color: #cbd5e1 !important; color: #1e293b !important;' )
            : ( '{{ $variant }}' === 'clear' 
                ? 'background: linear-gradient(135deg, #ef4444, #991b1b) !important; border-color: #f43f5e !important; color: #ffffff !important;' 
                : 'background: linear-gradient(135deg, #1e293b, #0f172a) !important; border-color: #334155 !important; color: #f8fafc !important;' )"
       @mouseenter="$el.style.transform = 'translateY(-2px) skewX(-12deg)'"
       @mouseleave="$el.style.transform = 'translateY(0) skewX(-12deg)'">
        <div class="skew-x-12" :style="isLight ? 'color: inherit !important;' : 'color: inherit !important;'">
            {{ $slot }}
        </div>
    </a>
@else
    <button type="{{ $type }}" 
       x-data="{ isLight: document.documentElement.classList.contains('a11y-light-mode') }"
       x-init="
          const observer = new MutationObserver(() => {
              isLight = document.documentElement.classList.contains('a11y-light-mode');
          });
          observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
       "
       {{ $attributes->merge(['class' => 'inline-flex items-center justify-center px-6 py-2.5 -skew-x-12 text-sm font-semibold transition-all duration-200 cursor-pointer border shadow-[3px_3px_0px_rgba(0,0,0,0.3)]']) }}
       :style="isLight 
            ? ( '{{ $variant }}' === 'clear' 
                ? 'background: linear-gradient(135deg, #fee2e2, #fca5a5) !important; border-color: #fca5a5 !important; color: #991b1b !important;' 
                : 'background: linear-gradient(135deg, #ffffff, #f1f5f9) !important; border-color: #cbd5e1 !important; color: #1e293b !important;' )
            : ( '{{ $variant }}' === 'clear' 
                ? 'background: linear-gradient(135deg, #ef4444, #991b1b) !important; border-color: #f43f5e !important; color: #ffffff !important;' 
                : 'background: linear-gradient(135deg, #1e293b, #0f172a) !important; border-color: #334155 !important; color: #f8fafc !important;' )"
       @mouseenter="$el.style.transform = 'translateY(-2px) skewX(-12deg)'"
       @mouseleave="$el.style.transform = 'translateY(0) skewX(-12deg)'">
        <div class="skew-x-12" :style="isLight ? 'color: inherit !important;' : 'color: inherit !important;'">
            {{ $slot }}
        </div>
    </button>
@endif
