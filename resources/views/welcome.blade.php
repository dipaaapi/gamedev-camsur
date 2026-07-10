<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark scroll-smooth">
<head>
    <x-head title="GameDev CamSur">
        /* Banner styles for Basic/Advanced course headings */
        .course-banner {
            background: linear-gradient(135deg, #1e3a8a, #312e81);
            transform: skewX(-15deg);
            display: inline-block;
            padding: 0.5rem 3rem;
            margin-bottom: 2rem;
            box-shadow: 4px 4px 0px rgba(0,0,0,0.3);
        }
        
        .course-banner span {
            transform: skewX(15deg);
            display: inline-block;
        }
        
        .enroll-banner {
            background: linear-gradient(135deg, #4f46e5, #3730a3);
            transform: skewX(-15deg);
            display: inline-block;
            padding: 0.5rem 2rem;
            margin-top: 1.5rem;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 4px 4px 0px rgba(0,0,0,0.3);
        }
        
        .enroll-banner:hover {
            transform: skewX(-15deg) translateY(-2px);
            box-shadow: 6px 6px 0px rgba(0,0,0,0.4);
        }
        
        .enroll-banner span {
            transform: skewX(15deg);
            display: inline-block;
        }
        
        .course-list li {
            position: relative;
            padding-left: 1.5rem;
            margin-bottom: 0.5rem;
        }
        
        .course-list li::before {
            content: "";
            position: absolute;
            left: 0;
            top: 0.5rem;
            width: 0.5rem;
            height: 0.5rem;
            background-color: #3b82f6;
        }
    </x-head>
</head>
<body class="antialiased min-h-screen flex flex-col relative overflow-x-hidden">
    <x-loading-screen />
    


    <!-- Navigation Bar -->
    <x-guest-nav activePage="home" />

    <!-- Main Content (Hero) -->
    @include('welcome.hero')

    <!-- Welcome to Game Dev Section -->
    @include('welcome.game-dev')

    <!-- Courses Section -->
    @include('welcome.courses')
    
    <!-- Certification Section -->
    @include('welcome.certification')

    <!-- Careers Section -->
    @include('welcome.careers')

    <!-- Contact Section -->
    @include('welcome.contact')

    <!-- Footer -->
    <x-sitemap />
    <x-footer />
    <x-scroll-to-top />
</body>
</html>
