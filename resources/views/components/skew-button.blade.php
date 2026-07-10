@props([
    'href' => null,
    'bg' => 'linear-gradient(135deg, #4f46e5, #3730a3)',
    'class' => ''
])

@php
    $defaultClasses = 'flex items-center justify-center text-white transition-all duration-200 cursor-pointer -skew-x-12 shadow-[3px_3px_0px_rgba(0,0,0,0.3)] hover:-translate-y-0.5 border border-indigo-500/20';
@endphp

@if($href)
    <a href="{{ $href }}" 
       {{ $attributes->merge(['class' => "$defaultClasses $class"]) }}
       style="background: {{ $bg }}; color: #ffffff !important;">
        <div class="skew-x-12" style="color: #ffffff !important;">
            {{ $slot }}
        </div>
    </a>
@else
    <button {{ $attributes->merge(['class' => "$defaultClasses $class", 'type' => 'button']) }}
            style="background: {{ $bg }}; color: #ffffff !important;">
        <div class="skew-x-12" style="color: #ffffff !important;">
            {{ $slot }}
        </div>
    </button>
@endif
