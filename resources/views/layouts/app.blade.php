<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>
            @php
                $pageTitle = $title ?? null;
                if (!$pageTitle) {
                    if (request()->routeIs('dashboard')) {
                        $pageTitle = 'Dashboard';
                    } elseif (request()->routeIs('users.*')) {
                        $pageTitle = 'User Management';
                    } elseif (request()->routeIs('id-cards.*')) {
                        $pageTitle = 'ID Generation';
                    } elseif (request()->routeIs('activity-logs.*')) {
                        $pageTitle = 'Activity Logs';
                    } elseif (request()->routeIs('profile.*')) {
                        $pageTitle = 'Profile';
                    }
                }
            @endphp
            CamSur Software Development Training Program{{ $pageTitle ? ' | ' . $pageTitle : '' }}
        </title>
        <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts (Tailwind CDN + Alpine JS) -->
        <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

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

            select, option, input[type="text"], input[type="date"], input[type="number"], textarea {
                background-color: #1e293b !important;
                color: #f8fafc !important;
                border-color: #334155 !important;
            }

            input::placeholder, textarea::placeholder {
                color: #64748b !important;
            }

            html.a11y-light-mode select, 
            html.a11y-light-mode option,
            html.a11y-light-mode input[type="text"], 
            html.a11y-light-mode input[type="date"], 
            html.a11y-light-mode input[type="number"], 
            html.a11y-light-mode textarea {
                background-color: #ffffff !important;
                color: #1e293b !important;
                border-color: #cbd5e1 !important;
            }

            html.a11y-light-mode input::placeholder,
            html.a11y-light-mode textarea::placeholder {
                color: #94a3b8 !important;
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

            /* Light mode overrides for management dropdown */
            html.a11y-light-mode .management-dropdown {
                background-color: #ffffff !important;
                border-color: #cbd5e1 !important;
            }
            html.a11y-light-mode .management-dropdown-link {
                color: #1e293b !important;
            }
            html.a11y-light-mode .management-dropdown-link:hover {
                background-color: #f1f5f9 !important;
                color: #0f172a !important;
            }
        </style>
    </head>
    <body class="font-sans antialiased min-h-screen flex flex-col relative overflow-x-hidden bg-[#0f172a]">
        <x-loading-screen />
        


        <div class="min-h-screen flex flex-col relative z-10">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="glass-nav shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 text-white">
                        {{ $header }}
                    </div>
                </header>
            @endisset



            <!-- Page Content -->
            <main class="flex-grow">
                {{ $slot }}
            </main>

            <x-footer />
            <x-scroll-to-top />
        </div>

        <!-- Global Toast Container -->
        <div id="toast-container" class="fixed top-5 right-5 z-[9999] flex flex-col gap-3 pointer-events-none"></div>

        <script>
            window.showToast = function (message, type = 'error') {
                const container = document.getElementById('toast-container');
                if (!container) return;

                const toast = document.createElement('div');
                toast.className = `flex items-center gap-3 px-5 py-3.5 rounded-2xl border backdrop-blur-md shadow-2xl transition-all duration-300 opacity-0 translate-y-2 pointer-events-auto max-w-sm`;
                
                const isLight = document.documentElement.classList.contains('a11y-light-mode');
                
                let bg, border, text, icon;
                if (type === 'success') {
                    bg = isLight ? '#ecfdf5' : 'rgba(16, 185, 129, 0.1)';
                    border = isLight ? '#a7f3d0' : 'rgba(16, 185, 129, 0.2)';
                    text = isLight ? '#065f46' : '#34d399';
                    icon = `<svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>`;
                } else {
                    bg = isLight ? '#fff5f5' : 'rgba(239, 68, 68, 0.1)';
                    border = isLight ? '#feb2b2' : 'rgba(239, 68, 68, 0.2)';
                    text = isLight ? '#9b2c2c' : '#f87171';
                    icon = `<svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>`;
                }

                toast.style.backgroundColor = bg;
                toast.style.borderColor = border;
                toast.style.color = text;

                toast.innerHTML = `
                    ${icon}
                    <div class="text-sm font-bold">${message}</div>
                `;

                container.appendChild(toast);

                setTimeout(() => {
                    toast.classList.remove('opacity-0', 'translate-y-2');
                    toast.classList.add('opacity-100', 'translate-y-0');
                }, 10);

                setTimeout(() => {
                    toast.classList.remove('opacity-100', 'translate-y-0');
                    toast.classList.add('opacity-0', '-translate-y-2');
                    setTimeout(() => {
                        toast.remove();
                    }, 300);
                }, 4000);
            };
        </script>
    </body>
</html>
