<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <x-head :title="config('app.name', 'Laravel')">
        .gradient-text {
            background: linear-gradient(to right, #818cf8, #c084fc);
        }
    </x-head>
</head>
<body class="antialiased min-h-screen flex flex-col relative overflow-hidden bg-[#0f172a]">
    <x-loading-screen />
    


    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 z-10 relative">
        <div class="mb-8">
            <a href="/" class="flex items-center gap-3 cursor-pointer group">
                <x-brand-logo class="h-16 w-auto" />
            </a>
        </div>

        <div class="w-full mt-6 relative z-10 px-4 sm:px-0 flex justify-center">
            {{ $slot }}
        </div>
    </div>
</body>
</html>
