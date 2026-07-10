@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-brand-light text-start text-base font-medium text-white bg-slate-800/50 focus:outline-none focus:text-white focus:bg-slate-700/50 focus:border-brand-dark transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-slate-400 hover:text-white hover:bg-slate-800/30 hover:border-slate-600 focus:outline-none focus:text-white focus:bg-slate-800/30 focus:border-slate-600 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
