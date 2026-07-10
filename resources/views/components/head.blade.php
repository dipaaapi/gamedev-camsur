<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>
    @php
        $cleanedTitle = $title ?? '';
        if ($cleanedTitle === config('app.name') || $cleanedTitle === 'Laravel' || $cleanedTitle === 'GameDev CamSur') {
            $cleanedTitle = '';
        }
        $cleanedTitle = str_replace([' - GameDev CamSur', ' - Laravel', 'GameDev CamSur - ', 'Laravel - ', 'Public Information - GameDev CamSur', 'All Game Dev Careers - GameDev CamSur', 'Basic Course - GameDev CamSur', 'Advanced Course - GameDev CamSur'], ['','','','','Public Information','All Game Dev Careers','Basic Course','Advanced Course'], $cleanedTitle);
        
        if (!$cleanedTitle) {
            if (request()->routeIs('home') || request()->path() === '/') {
                $cleanedTitle = 'Home';
            } elseif (request()->routeIs('login')) {
                $cleanedTitle = 'Login';
            } elseif (request()->routeIs('register')) {
                $cleanedTitle = 'Register';
            } elseif (request()->routeIs('password.request')) {
                $cleanedTitle = 'Forgot Password';
            } elseif (request()->routeIs('password.reset')) {
                $cleanedTitle = 'Reset Password';
            } elseif (request()->routeIs('verification.notice')) {
                $cleanedTitle = 'Verify Email';
            } elseif (request()->routeIs('confirm-password')) {
                $cleanedTitle = 'Confirm Password';
            }
        }
    @endphp
    CamSur Software Development Training Program{{ $cleanedTitle ? ' | ' . $cleanedTitle : '' }}
</title>
<link rel="icon" type="image/png" href="{{ asset('logo.png') }}">

<!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<!-- Tailwind CSS v4 CDN -->
<script src="https://unpkg.com/@tailwindcss/browser@4"></script>

@vite(['resources/css/app.css', 'resources/js/app.js'])

<style type="text/tailwindcss">
    @theme {
        --font-sans: 'Outfit', sans-serif;
        --color-brand: #6366f1;
        --color-brand-light: #818cf8;
        --color-brand-dark: #4f46e5;
    }
    
    body {
        font-family: 'Outfit', sans-serif;
        background-color: #0f172a;
        color: #f8fafc;
    }

    p {
        text-align: justify;
    }

    .glass-nav {
        background: rgba(15, 23, 42, 0.7);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }
    
    .glass-panel {
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
    }

    .gradient-text {
        background: linear-gradient(to right, #818cf8, #c084fc);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .animate-swipe-1 {
        animation: swipe-left 1s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        opacity: 0;
    }
    .animate-swipe-2 {
        animation: swipe-left 1s cubic-bezier(0.16, 1, 0.3, 1) 0.2s forwards;
        opacity: 0;
    }
    .animate-swipe-3 {
        animation: swipe-left 1s cubic-bezier(0.16, 1, 0.3, 1) 0.4s forwards;
        opacity: 0;
    }
    
    @keyframes swipe-left {
        from {
            opacity: 0;
            transform: translateX(100px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    /* Explicit theme toggle visibility */
    html:not(.dark) .show-in-dark,
    html.a11y-light-mode .show-in-dark { display: none !important; }
    
    html.dark:not(.a11y-light-mode) .show-in-light { display: none !important; }

    /* Hide background glows in non-default modes */
    html.a11y-light-mode .background-glow-container,
    html.a11y-high-contrast .background-glow-container {
        display: none !important;
    }

    {{ $slot ?? '' }}
</style>
