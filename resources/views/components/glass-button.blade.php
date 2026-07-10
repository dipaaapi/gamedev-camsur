<button {{ $attributes->merge(['type' => 'button', 'class' => 'w-full sm:w-auto px-8 py-4 rounded-full bg-slate-800/80 hover:bg-slate-700 border border-slate-700 text-white font-semibold text-lg backdrop-blur-sm transition-all duration-300 hover:-translate-y-1']) }}>
    {{ $slot }}
</button>
