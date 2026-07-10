@props([
    'name',
    'id' => null,
    'value' => null,
    'options' => [],
    'placeholder' => 'Select an option',
    'required' => false
])

<div x-data="{ 
        open: false, 
        selectedVal: @js($value), 
        options: @js($options),
        isLight: document.documentElement.classList.contains('a11y-light-mode'),
        get selectedLabel() {
            return this.options[this.selectedVal] !== undefined ? this.options[this.selectedVal] : '{{ $placeholder }}';
        }
     }" 
     x-init="
        $watch('selectedVal', val => {
            $el.value = val;
            $dispatch('input', val);
        }); 
        selectedVal = $el.getAttribute('value') || selectedVal;

        const observer = new MutationObserver(() => {
            isLight = document.documentElement.classList.contains('a11y-light-mode');
        });
        observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
     "
     class="relative w-full select-none text-left"
     @click.away="open = false"
     {{ $attributes }}>
    
    <!-- Hidden input to submit with the form -->
    <input type="hidden" name="{{ $name }}" id="{{ $id ?? $name }}" :value="selectedVal" @if($required) required @endif>

    <!-- Trigger Button -->
    <button type="button" 
            @click="open = !open" 
            class="w-full flex items-center justify-between px-4 py-2 border rounded-xl text-sm cursor-pointer text-left transition-all duration-200"
            :style="isLight 
                ? 'background-color: #ffffff !important; border-color: #cbd5e1 !important; color: #1e293b !important;' 
                : 'background-color: #1e293b !important; border-color: #334155 !important; color: #ffffff !important;'">
        <span x-text="selectedLabel" class="truncate" :style="isLight ? 'color: #1e293b !important;' : 'color: #ffffff !important;'"></span>
        <svg class="w-4 h-4 transition-transform duration-200" :class="{'rotate-180': open}" :style="isLight ? 'color: #64748b !important;' : 'color: #94a3b8 !important;'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>

    <!-- Options Dropdown -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="absolute z-50 w-full mt-2 border rounded-xl shadow-2xl overflow-hidden max-h-60 overflow-y-auto"
         :style="isLight 
            ? 'display: none; background-color: #ffffff !important; border-color: #cbd5e1 !important;' 
            : 'display: none; background-color: #0f172a !important; border-color: #334155 !important;'">
        <template x-for="(label, val) in options" :key="val">
            <button type="button" 
                    @click="selectedVal = val; open = false; $dispatch('change', val)" 
                    class="w-full text-left px-4 py-2.5 text-sm transition-colors duration-150 flex items-center justify-between cursor-pointer"
                    :style="selectedVal == val 
                        ? 'background-color: #4f46e5 !important; color: #ffffff !important; font-weight: 600;' 
                        : (isLight 
                            ? 'background-color: transparent !important; color: #1e293b !important;' 
                            : 'background-color: transparent !important; color: #cbd5e1 !important;')"
                    @mouseenter="$el.style.backgroundColor = '#4f46e5'; $el.style.color = '#ffffff'"
                    @mouseleave="if(selectedVal != val) { $el.style.backgroundColor = 'transparent'; $el.style.color = isLight ? '#1e293b' : '#cbd5e1'; }">
                <span x-text="label" :style="selectedVal == val ? 'color: #ffffff !important;' : ''"></span>
                <svg x-show="selectedVal == val" class="w-4 h-4" :style="selectedVal == val ? 'color: #ffffff !important;' : 'color: #818cf8 !important;'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                </svg>
            </button>
        </template>
    </div>
</div>
