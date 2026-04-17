<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <style>
            html, body {
                scrollbar-width: none; /* Firefox */
                -ms-overflow-style: none; /* IE and Edge */
            }

            html::-webkit-scrollbar, 
            body::-webkit-scrollbar {
                display: none; /* Chrome, Safari, Opera */
            }
            #scrollBar {
                transition: width 0.2s ease-out;
                filter: drop-shadow(0 0 8px rgba(168, 85, 247, 0.6));
                z-index: 999;
            }
            #scrollBar::after {
                content: '';
                position: absolute;
                right: 0;
                width: 20px;
                height: 100%;
                background: rgba(255,255,255,0.4);
                border-radius: 50%;
                animation: pulse 1s infinite;
            }
            @keyframes pulse {
                0%,100% { opacity: 0.4; transform: scaleX(1); }
                50% { opacity: 0.6; transform: scaleX(1.2); }
            }
        </style>

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        <div id="scrollBar" class="fixed top-0 left-0 h-1 w-0 bg-gradient-to-r from-[#001e3c] via-[#002b5c] to-[#0077cc] shadow-lg rounded-full z-50"></div>

        @inertia
    </body>
    <script>
        const scrollBar = document.getElementById("scrollBar");

        window.addEventListener("scroll", () => {
            const scrollTop = window.scrollY;                     // How far user scrolled
            const docHeight = document.body.scrollHeight - window.innerHeight;
            const scrollPercent = (scrollTop / docHeight) * 100;
            scrollBar.style.width = scrollPercent + "%";
        });
    </script>
</html>
