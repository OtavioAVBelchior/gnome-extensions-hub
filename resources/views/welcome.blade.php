<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>GNOME Extensions Hub</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            body { font-family: 'Inter', sans-serif; }
            @keyframes blob {
                0% { transform: translate(0px, 0px) scale(1); opacity: 0.5; }
                33% { transform: translate(30px, -50px) scale(1.1); opacity: 0.7; }
                66% { transform: translate(-20px, 20px) scale(0.9); opacity: 0.6; }
                100% { transform: translate(0px, 0px) scale(1); opacity: 0.5; }
            }
            .animate-blob {
                animation: blob 10s infinite alternate;
            }
            .animation-delay-2000 {
                animation-delay: 2s;
            }
            .animation-delay-4000 {
                animation-delay: 4s;
            }
        </style>
    </head>
    <body class="bg-slate-900 text-white min-h-screen flex flex-col items-center justify-center relative overflow-hidden">
        <!-- Background decorative elements -->
        <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-blue-500/30 rounded-full mix-blend-screen blur-3xl animate-blob"></div>
        <div class="absolute top-[20%] right-[-10%] w-96 h-96 bg-purple-500/30 rounded-full mix-blend-screen blur-3xl animate-blob animation-delay-2000"></div>
        <div class="absolute bottom-[-20%] left-[20%] w-96 h-96 bg-pink-500/30 rounded-full mix-blend-screen blur-3xl animate-blob animation-delay-4000"></div>

        <div class="z-10 text-center max-w-2xl px-6">
            <div class="mb-8 flex justify-center">
                <img src="{{ asset('images/app_logo.jpg') }}" alt="Logo" class="w-28 h-28 rounded-[1.2rem] shadow-[0_0_40px_rgba(168,85,247,0.3)] border border-white/10">
            </div>
            <h1 class="text-4xl md:text-5xl font-bold mb-4 tracking-tight">GNOME Extensions <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-500">Maintainer Hub</span></h1>
            <p class="text-slate-400 text-lg mb-10 leading-relaxed">
                Centralize the management of your GNOME Shell Extensions. Track issues, pull requests, and releases across GitHub and GitLab in one beautiful dashboard.
            </p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                @auth
                    <a href="{{ url('/admin') }}" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 transition shadow-lg shadow-blue-500/30">
                        Go to Dashboard
                    </a>
                @else
                    <a href="{{ route('login.github') }}" class="inline-flex items-center justify-center px-6 py-3 border border-slate-700 text-base font-medium rounded-md text-white bg-slate-800 hover:bg-slate-700 transition shadow-lg">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                        Login with GitHub
                    </a>
                @endauth
            </div>
        </div>
        <footer class="absolute bottom-6 text-slate-500 text-sm flex flex-col items-center gap-2">
            <span>Created by <a href="https://github.com/OtavioAVBelchior" target="_blank" class="hover:text-blue-400 transition">Otávio Belchior</a> &bull; v1.0.0</span>
        </footer>
    </body>
</html>
