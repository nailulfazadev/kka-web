<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Academy Guru KKA - Platform Pelatihan Guru Terpercaya')</title>

    {{-- Tailwind CSS CDN --}}
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
    {{-- Font Awesome 6 --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="icon" href="{{ asset('/images/icon.png') }}">

    {{-- PWA Manifest --}}
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#060e20">

    {{-- Tailwind Config --}}
    <!-- <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        // === DARK MODE PALETTE (Default) ===
                        "on-surface": "#dee5ff",
                        "inverse-on-surface": "#4d556b",
                        "on-secondary-fixed": "#004840",
                        "surface": "#060e20",
                        "surface-container-lowest": "#000000",
                        "error": "#ff716c",
                        "surface-container-highest": "#192540",
                        "error-container": "#9f0519",
                        "background": "#060e20",
                        "inverse-primary": "#006b60",
                        "primary": "#6af2de",
                        "error-dim": "#d7383b",
                        "tertiary-fixed": "#91feef",
                        "inverse-surface": "#faf8ff",
                        "primary-container": "#10b7a5",
                        "on-background": "#dee5ff",
                        "on-error-container": "#ffa8a3",
                        "on-secondary-container": "#dcfff7",
                        "primary-dim": "#5ae4d0",
                        "on-primary-container": "#002b26",
                        "on-tertiary-fixed-variant": "#006d64",
                        "on-primary": "#00594f",
                        "on-primary-fixed": "#00443c",
                        "on-secondary": "#005c52",
                        "tertiary-fixed-dim": "#83efe1",
                        "surface-variant": "#192540",
                        "secondary-dim": "#50ebd5",
                        "secondary-fixed": "#62fae3",
                        "on-surface-variant": "#a3aac4",
                        "surface-container-high": "#141f38",
                        "tertiary-dim": "#74e1d3",
                        "outline-variant": "#40485d",
                        "on-secondary-fixed-variant": "#00675c",
                        "secondary-container": "#006b5f",
                        "primary-fixed": "#6af2de",
                        "on-tertiary-fixed": "#004e47",
                        "on-primary-fixed-variant": "#006359",
                        "tertiary-container": "#91feef",
                        "surface-container": "#0f1930",
                        "surface-dim": "#060e20",
                        "surface-bright": "#1f2b49",
                        "surface-container-low": "#091328",
                        "secondary-fixed-dim": "#50ebd5",
                        "on-error": "#490006",
                        "primary-fixed-dim": "#5ae4d0",
                        "secondary": "#62fae3",
                        "on-tertiary": "#006b62",
                        "on-tertiary-container": "#006259",
                        "surface-tint": "#6af2de",
                        "outline": "#6d758c",
                        "tertiary": "#e4fff9",
                        // === LIGHT MODE OVERRIDES (used via light: prefix) ===
                        "light-surface": "#f8fafc",
                        "light-surface-container": "#f1f5f9",
                        "light-surface-container-high": "#e2e8f0",
                        "light-surface-container-highest": "#cbd5e1",
                        "light-on-surface": "#1e293b",
                        "light-on-surface-variant": "#64748b",
                        "light-primary": "#0d9488",
                        "light-primary-container": "#14b8a6",
                        "light-outline": "#94a3b8",
                    },
                    fontFamily: {
                        "headline": ["Inter"],
                        "body": ["Inter"],
                        "label": ["Inter"]
                    },
                    borderRadius: {"DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px"},
                    boxShadow: {
                        'glass': '0 8px 32px 0 rgba(0, 0, 0, 0.37)',
                        'glass-light': '0 4px 16px 0 rgba(0, 0, 0, 0.08)',
                    }
                },
            },
        }
    </script>

    <style>
        /* Font Awesome sizing helpers */
        .fa-icon { display: inline-flex; align-items: center; justify-content: center; }

        /* === DARK MODE (default) === */
        body {
            background-color: #060e20;
            color: #dee5ff;
            font-family: 'Inter', sans-serif;
        }

        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        .glass-card {
            background: rgba(25, 37, 64, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }

        /* === LIGHT MODE === */
        html:not(.dark) body {
            background-color: #ffffffff;
            color: #1e293b;
        }

        html:not(.dark) .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
            border-color: rgba(0, 0, 0, 0.06) !important;
        }

        html:not(.dark) .bg-surface { background-color: #f8fafc; }
        html:not(.dark) .bg-surface-container { background-color: #f1f5f9; }
        html:not(.dark) .bg-surface-container-low { background-color: #f8fafc; }
        html:not(.dark) .bg-surface-container-high { background-color: #e2e8f0; }
        html:not(.dark) .bg-surface-container-highest { background-color: #cbd5e1; }
        html:not(.dark) .bg-surface-variant { background-color: #e2e8f0; }
        html:not(.dark) .text-on-surface { color: #0b0b28ff; }
        html:not(.dark) .text-on-surface-variant { color: #64748b; }
        html:not(.dark) .text-on-background { color: #1e293b; }
        html:not(.dark) .text-outline { color: #94a3b8; }
        html:not(.dark) .text-primary { color: #0d9488; }
        html:not(.dark) .text-secondary { color: #14b8a6; }
        html:not(.dark) .border-white\/5 { border-color: rgba(0, 0, 0, 0.06); }
        html:not(.dark) .border-white\/10 { border-color: rgba(0, 0, 0, 0.08); }

        /* Light mode navbar */
        html:not(.dark) header.bg-slate-950\/70 {
            background: rgba(255, 255, 255, 0.85) !important;
            border-color: rgba(0, 0, 0, 0.06) !important;
        }
        html:not(.dark) header .text-teal-400 { color: #0d9488; }
        html:not(.dark) header .text-slate-400 { color: #64748b; }
        html:not(.dark) header .bg-gradient-to-r { background: linear-gradient(to right, #0d9488, #14b8a6); -webkit-background-clip: text; }

        /* Light mode bottom nav */
        html:not(.dark) nav.bg-slate-950\/80 {
            background: rgba(255, 255, 255, 0.9) !important;
            border-color: rgba(0, 0, 0, 0.06) !important;
            box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.06);
        }
        html:not(.dark) nav .text-teal-300 { color: #0f766e !important; }
        html:not(.dark) nav .bg-teal-500\/10 { background: rgba(13, 148, 136, 0.1) !important; }
        html:not(.dark) nav .text-slate-500 { color: #64748b !important; }
        html:not(.dark) nav .hover\:text-teal-200:hover { color: #0d9488 !important; }

        body { min-height: max(884px, 100dvh); }
    </style> -->

    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        // === DARK MODE PALETTE (Default) ===
                        // Brand: Cyan-Blue-Purple gradient, Orange accent, Navy dark
                        "on-surface": "#e8f4ff",
                        "inverse-on-surface": "#3a4060",
                        "on-secondary-fixed": "#0a0040",
                        "surface": "#070b1a",
                        "surface-container-lowest": "#000000",
                        "error": "#ff716c",
                        "surface-container-highest": "#141c38",
                        "error-container": "#9f0519",
                        "background": "#070b1a",
                        "inverse-primary": "#3a2abf",
                        "primary": "#7ecfff",          /* cyan-blue, top of gradient */
                        "error-dim": "#d7383b",
                        "tertiary-fixed": "#c4a8ff",
                        "inverse-surface": "#f0f4ff",
                        "primary-container": "#00a8d4",  /* mid cyan */
                        "on-background": "#e8f4ff",
                        "on-error-container": "#ffa8a3",
                        "on-secondary-container": "#ede0ff",
                        "primary-dim": "#5bbde8",
                        "on-primary-container": "#001830",
                        "on-tertiary-fixed-variant": "#6a2ed4",
                        "on-primary": "#002040",
                        "on-primary-fixed": "#001535",
                        "on-secondary": "#1a005a",
                        "tertiary-fixed-dim": "#b294f5",
                        "surface-variant": "#141c38",
                        "secondary-dim": "#9b76f5",
                        "secondary-fixed": "#c4a8ff",
                        "on-surface-variant": "#a0aac8",
                        "surface-container-high": "#0f1530",
                        "tertiary-dim": "#a882e8",
                        "outline-variant": "#2a3458",
                        "on-secondary-fixed-variant": "#5a32c4",
                        "secondary-container": "#5a32c4",  /* purple badge color */
                        "primary-fixed": "#7ecfff",
                        "on-tertiary-fixed": "#200050",
                        "on-primary-fixed-variant": "#2a5ec4",
                        "tertiary-container": "#c4a8ff",
                        "surface-container": "#0d1228",
                        "surface-dim": "#070b1a",
                        "surface-bright": "#182040",
                        "surface-container-low": "#0a0e22",
                        "secondary-fixed-dim": "#9b76f5",
                        "on-error": "#490006",
                        "primary-fixed-dim": "#5bbde8",
                        "secondary": "#9b76f5",           /* purple, bottom of gradient */
                        "on-tertiary": "#5a2ab8",
                        "on-tertiary-container": "#4a20a8",
                        "surface-tint": "#7ecfff",
                        "outline": "#506080",
                        "tertiary": "#f0e8ff",
                        "accent-orange": "#f5a623",        /* Guru orange */
                        // === LIGHT MODE OVERRIDES ===
                        "light-surface": "#f0f8ff",
                        "light-surface-container": "#e4f0ff",
                        "light-surface-container-high": "#cce0ff",
                        "light-surface-container-highest": "#b8d0f5",
                        "light-on-surface": "#0a1a30",
                        "light-on-surface-variant": "#3a5070",
                        "light-primary": "#0090c4",
                        "light-primary-container": "#00a8d4",
                        "light-outline": "#7090b0",
                    },
                    fontFamily: {
                        "headline": ["Inter"],
                        "body": ["Inter"],
                        "label": ["Inter"]
                    },
                    borderRadius: {"DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px"},
                    boxShadow: {
                        'glass': '0 8px 32px 0 rgba(0, 0, 0, 0.37)',
                        'glass-light': '0 4px 16px 0 rgba(0, 0, 0, 0.08)',
                    }
                },
            },
        }
    </script>

    <style>
        /* Font Awesome sizing helpers */
        .fa-icon { display: inline-flex; align-items: center; justify-content: center; }

        /* === DARK MODE (default) === */
        body {
            background-color: #070b1a;
            color: #e8f4ff;
            font-family: 'Inter', sans-serif;
        }

        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        .glass-card {
            background: rgba(20, 28, 56, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }

        /* === LIGHT MODE === */
        html:not(.dark) body {
            background-color: #ffffff;
            color: #0a1a30;
        }

        html:not(.dark) .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
            border-color: rgba(0, 0, 0, 0.06) !important;
        }

        html:not(.dark) .bg-surface { background-color: #f0f8ff; }
        html:not(.dark) .bg-surface-container { background-color: #e4f0ff; }
        html:not(.dark) .bg-surface-container-low { background-color: #f0f8ff; }
        html:not(.dark) .bg-surface-container-high { background-color: #cce0ff; }
        html:not(.dark) .bg-surface-container-highest { background-color: #b8d0f5; }
        html:not(.dark) .bg-surface-variant { background-color: #cce0ff; }
        html:not(.dark) .text-on-surface { color: #0a1a30; }
        html:not(.dark) .text-on-surface-variant { color: #3a5070; }
        html:not(.dark) .text-on-background { color: #0a1a30; }
        html:not(.dark) .text-outline { color: #7090b0; }
        html:not(.dark) .text-primary { color: #0090c4; }
        html:not(.dark) .text-secondary { color: #7b4ff0; }
        html:not(.dark) .border-white\/5 { border-color: rgba(0, 0, 0, 0.06); }
        html:not(.dark) .border-white\/10 { border-color: rgba(0, 0, 0, 0.08); }

        /* Light mode navbar */
        html:not(.dark) header.bg-slate-950\/70 {
            background: rgba(255, 255, 255, 0.85) !important;
            border-color: rgba(0, 0, 0, 0.06) !important;
        }
        html:not(.dark) header .text-teal-400 { color: #0090c4; }
        html:not(.dark) header .text-slate-400 { color: #3a5070; }
        html:not(.dark) header .bg-gradient-to-r { background: linear-gradient(to right, #00bcd4, #7b4ff0); -webkit-background-clip: text; }

        /* Light mode bottom nav */
        html:not(.dark) nav.bg-slate-950\/80 {
            background: rgba(255, 255, 255, 0.9) !important;
            border-color: rgba(0, 0, 0, 0.06) !important;
            box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.06);
        }
        html:not(.dark) nav .text-teal-300 { color: #0090c4 !important; }
        html:not(.dark) nav .bg-teal-500\/10 { background: rgba(0, 144, 196, 0.1) !important; }
        html:not(.dark) nav .text-slate-500 { color: #3a5070 !important; }
        html:not(.dark) nav .hover\:text-teal-200:hover { color: #0090c4 !important; }

        body { min-height: max(884px, 100dvh); }
    </style>

    @stack('styles')
</head>
<body class="antialiased selection:bg-primary-container selection:text-on-primary-container">

<div class="max-w-[600px] mx-auto min-h-screen bg-surface relative overflow-x-hidden">

    {{-- TopAppBar --}}
    <header class="fixed top-0 w-full max-w-[600px] z-50 bg-slate-950/70 backdrop-blur-xl border-b border-white/10 flex items-center justify-between px-6 h-16">
        <div class="flex items-center gap-3">
            <a href="{{ url('/') }}" class="flex items-center gap-3">
                <img src="{{ asset('/images/logo1.png') }}" alt="Logo" class="h-8">
            </a>
        </div>
        <div class="flex items-center gap-2">
            {{-- Theme Toggle --}}
            <button onclick="toggleTheme()" class="hover:bg-white/5 transition-colors p-2 rounded-full" title="Toggle Dark/Light Mode">
                <i class="fa-solid fa-sun text-slate-400 dark-icon"></i>
                <i class="fa-solid fa-moon text-slate-400 light-icon hidden"></i>
            </button>
            {{-- Search --}}
            <a href="{{ route('pelatihan.explore') ?? '#' }}" class="hover:bg-white/5 transition-colors p-2 rounded-full">
                <i class="fa-solid fa-magnifying-glass text-slate-400"></i>
            </a>
            {{-- Profile --}}
            @auth
                <a href="{{ url('/guru/profil') }}" class="w-8 h-8 rounded-full bg-surface-container-highest border border-outline-variant/20 flex items-center justify-center">
                    @if(auth()->user()->avatar)
                        <img src="{{ auth()->user()->avatar }}" class="w-full h-full rounded-full object-cover" alt="Avatar">
                    @else
                        <i class="fa-solid fa-user text-xs text-teal-400"></i>
                    @endif
                </a>
            @else
                <a href="{{ route('login') }}" class="w-8 h-8 rounded-full bg-surface-container-highest border border-outline-variant/20 flex items-center justify-center">
                    <i class="fa-solid fa-user text-xs text-teal-400"></i>
                </a>
            @endauth
        </div>
    </header>

    {{-- Main Content --}}
    <main class="pb-24 pt-16">
        @if(session('success'))
            <div class="mx-6 mt-4 p-4 rounded-xl bg-primary-container/20 border border-primary/20 text-primary text-sm">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mx-6 mt-4 p-4 rounded-xl bg-error-container/20 border border-error/20 text-error text-sm">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    {{-- BottomNavBar --}}
    <nav class="fixed bottom-0 w-full max-w-[600px] z-50 rounded-t-[2rem] bg-slate-950/80 backdrop-blur-lg border-t border-white/5 flex justify-around items-center h-20 px-4 pb-safe shadow-[0_-10px_30px_rgba(0,0,0,0.5)]">
        @php $currentRoute = request()->route()?->getName() ?? ''; @endphp

        <a class="flex flex-col items-center justify-center {{ str_contains($currentRoute, 'landing') || $currentRoute == '' ? 'text-teal-300 bg-teal-500/10 rounded-2xl px-3 py-1' : 'text-slate-500 hover:text-teal-200' }} active:scale-90 transition-transform" href="{{ url('/') }}">
            <i class="fa-solid fa-house text-lg"></i>
            <span class="text-[10px] font-medium uppercase tracking-widest font-inter mt-1">Beranda</span>
        </a>

        <a class="flex flex-col items-center justify-center {{ str_contains($currentRoute, 'explore') ? 'text-teal-300 bg-teal-500/10 rounded-2xl px-3 py-1' : 'text-slate-500 hover:text-teal-200' }} active:scale-90 transition-transform" href="{{ url('/pelatihan') }}">
            <i class="fa-solid fa-compass text-lg"></i>
            <span class="text-[10px] font-medium uppercase tracking-widest font-inter mt-1">Explore</span>
        </a>

        <a class="flex flex-col items-center justify-center {{ str_contains($currentRoute, 'guru.pelatihan') ? 'text-teal-300 bg-teal-500/10 rounded-2xl px-3 py-1' : 'text-slate-500 hover:text-teal-200' }} active:scale-90 transition-transform" href="{{ url('/guru/pelatihan') }}">
            <i class="fa-solid fa-graduation-cap text-lg"></i>
            <span class="text-[10px] font-medium uppercase tracking-widest font-inter mt-1">Pelatihan</span>
        </a>

        <a class="flex flex-col items-center justify-center {{ str_contains($currentRoute, 'sertifikat') ? 'text-teal-300 bg-teal-500/10 rounded-2xl px-3 py-1' : 'text-slate-500 hover:text-teal-200' }} active:scale-90 transition-transform" href="{{ url('/guru/sertifikat') }}">
            <i class="fa-solid fa-award text-lg"></i>
            <span class="text-[10px] font-medium uppercase tracking-widest font-inter mt-1">Sertifikat</span>
        </a>

        <a class="flex flex-col items-center justify-center {{ str_contains($currentRoute, 'profil') ? 'text-teal-300 bg-teal-500/10 rounded-2xl px-3 py-1' : 'text-slate-500 hover:text-teal-200' }} active:scale-90 transition-transform" href="{{ url('/guru/profil') }}">
            <i class="fa-solid fa-user text-lg"></i>
            <span class="text-[10px] font-medium uppercase tracking-widest font-inter mt-1">Profil</span>
        </a>
    </nav>
  
  {{-- Floating AI CS Button --}}
    <div class="fixed bottom-24 w-full max-w-[600px] left-1/2 -translate-x-1/2 pointer-events-none z-[100]">
        <div class="flex justify-end px-6">
            <button onclick="toggleCSChat()" class="w-14 h-14 rounded-full bg-gradient-to-br from-primary to-secondary shadow-lg shadow-primary/20 flex items-center justify-center text-on-primary pointer-events-auto active:scale-95 transition-transform relative">
                <i class="fa-solid fa-headset text-xl"></i>
                <span class="absolute -top-1 -right-1 flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-accent-orange opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-accent-orange"></span>
                </span>
            </button>
        </div>
    </div>
    {{-- CS Chat Drawer --}}
    <div id="cs-drawer" class="fixed inset-0 z-[110] invisible pointer-events-none">
        <div id="cs-overlay" class="absolute inset-0 bg-black/40 backdrop-blur-sm opacity-0 transition-opacity duration-300"></div>
        <div id="cs-panel" class="absolute bottom-0 left-0 right-0 w-full max-w-[600px] mx-auto h-[80vh] bg-surface rounded-t-[2.5rem] border-t border-white/10 translate-y-full transition-transform duration-300 flex flex-col pointer-events-auto">
            {{-- Header --}}
            <div class="px-6 py-5 flex items-center justify-between border-b border-white/5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-primary/10 flex items-center justify-center">
                        <i class="fa-solid fa-robot text-primary"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-on-surface">Asisten AI KKA</h3>
                        <p class="text-[10px] text-primary font-bold uppercase tracking-widest">Customer Service</p>
                    </div>
                </div>
                <button onclick="toggleCSChat()" class="p-2 hover:bg-white/5 rounded-full transition-colors">
                    <i class="fa-solid fa-xmark text-on-surface-variant"></i>
                </button>
            </div>
            {{-- Chat Area --}}
            <div id="cs-messages" class="flex-1 overflow-y-auto p-6 space-y-4 hide-scrollbar">
                {{-- Initial Message --}}
                <div class="flex justify-start">
                    <div class="max-w-[85%] bg-surface-container-highest rounded-2xl rounded-tl-none px-4 py-3 text-sm text-on-surface shadow-sm animate-fade-in">
                        Halo! Saya Asisten AI Akademi Guru KKA. 👋<br><br>
                        Ada yang bisa saya bantu terkait pelatihan, pendaftaran, atau informasi lainnya?
                    </div>
                </div>
            </div>
            {{-- Input Area --}}
            <div class="p-6 border-t border-white/5 bg-surface-container-low">
                <form id="cs-form" class="flex gap-2">
                    <input id="cs-input" type="text" placeholder="Tanyakan sesuatu..." class="flex-1 bg-surface-container-highest border-none rounded-2xl py-3 px-4 text-sm focus:ring-1 focus:ring-primary/40 text-on-surface" required autocomplete="off">
                    <button type="submit" id="cs-send" class="w-12 h-12 shrink-0 rounded-full bg-primary flex items-center justify-center text-on-primary active:scale-90 transition-transform shadow-lg shadow-primary/10">
                        <i class="fa-solid fa-paper-plane"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>

{{-- Theme Toggle Script --}}
<script>
    function toggleTheme() {
        const html = document.documentElement;
        const isDark = html.classList.contains('dark');
        if (isDark) {
            html.classList.remove('dark');
            localStorage.setItem('theme', 'light');
        } else {
            html.classList.add('dark');
            localStorage.setItem('theme', 'dark');
        }
        updateThemeIcons();
    }

    function updateThemeIcons() {
        const isDark = document.documentElement.classList.contains('dark');
        document.querySelectorAll('.dark-icon').forEach(el => {
            if (isDark) el.classList.remove('hidden');
            else el.classList.add('hidden');
        });
        document.querySelectorAll('.light-icon').forEach(el => {
            if (isDark) el.classList.add('hidden');
            else el.classList.remove('hidden');
        });
    }

    // Initialize theme
    (function() {
        const saved = localStorage.getItem('theme');
        
        // Logika Waktu: Malam (18:00 - 05:59) dianggap Dark
        const hour = new Date().getHours();
        const isNightTime = hour >= 18 || hour < 6;

        if (saved === 'light') {
            document.documentElement.classList.remove('dark');
        } else if (saved === 'dark') {
            document.documentElement.classList.add('dark');
        } else {
            // Jika user belum pernah pilih (saved null), gunakan logika waktu
            if (isNightTime) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }
        
        document.addEventListener('DOMContentLoaded', updateThemeIcons);
    })();
</script>
  
<script>
function toggleCSChat() {
    const drawer = document.getElementById('cs-drawer');
    const overlay = document.getElementById('cs-overlay');
    const panel = document.getElementById('cs-panel');
    const isVisible = !drawer.classList.contains('invisible');

    if (isVisible) {
        panel.classList.add('translate-y-full');
        overlay.classList.remove('opacity-100');
        setTimeout(() => drawer.classList.add('invisible'), 300);
    } else {
        drawer.classList.remove('invisible');
        setTimeout(() => {
            panel.classList.remove('translate-y-full');
            overlay.classList.add('opacity-100');
            loadCSMessages();
        }, 10);
    }
}

async function loadCSMessages() {
    const container = document.getElementById('cs-messages');
    try {
        const res = await fetch('{{ route("cs.messages") }}');
        const data = await res.json();
        if (data.length > 0) {
            container.innerHTML = '';
            data.forEach(msg => appendMessage(msg.message, msg.is_bot));
            container.scrollTop = container.scrollHeight;
        }
    } catch (e) {}
}

function appendMessage(text, isBot) {
    const container = document.getElementById('cs-messages');
    const div = document.createElement('div');
    div.className = `flex ${isBot ? 'justify-start' : 'justify-end'}`;
    
    const content = document.createElement('div');
    content.className = `max-w-[85%] px-4 py-3 text-sm shadow-sm ${isBot ? 'bg-surface-container-highest rounded-2xl rounded-tl-none text-on-surface' : 'bg-primary text-on-primary rounded-2xl rounded-tr-none'}`;

    // 1. Deteksi URL dan ubah menjadi link yang bisa diklik (Target Blank)
    const urlRegex = /(https?:\/\/[^\s]+)/g;
    let formattedText = text.replace(urlRegex, function(url) {
        return `<a href="${url}" target="_blank" rel="noopener noreferrer" class="underline font-bold text-primary-light hover:opacity-80">${url}</a>`;
    });

    // 2. Ubah newline menjadi <br>
    formattedText = formattedText.replace(/\n/g, '<br>');

    content.innerHTML = formattedText;
    
    div.appendChild(content);
    container.appendChild(div);
    container.scrollTop = container.scrollHeight;
}

  document.getElementById('cs-form').onsubmit = async (e) => {
    e.preventDefault();
    const input = document.getElementById('cs-input');
    const btn = document.getElementById('cs-send');
    const message = input.value.trim();
    if (!message) return;

    appendMessage(message, false);
    input.value = '';
    input.disabled = true;
    btn.disabled = true;

    try {
        const res = await fetch('{{ route("cs.send") }}', {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json', 
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json' 
            },
            body: JSON.stringify({ message })
        });

        // 1. Ambil data JSON-nya dulu
        const data = await res.json();

        // 2. Cek apakah statusnya 403 (Limit)
        if (res.status === 403) {
            appendMessage(data.message || "Batas chat tercapai. Silakan login.", true);
            
            // Tambahkan tombol login agar user mudah klik
            const container = document.getElementById('cs-messages');
            const loginDiv = document.createElement('div');
            loginDiv.className = 'flex justify-center my-4';
            loginDiv.innerHTML = `
                <a href="{{ route('login') }}" class="bg-primary text-white px-6 py-2 rounded-xl text-xs font-bold shadow-md hover:scale-105 transition-transform">
                    Login Sekarang
                </a>
            `;
            container.appendChild(loginDiv);
            container.scrollTop = container.scrollHeight;
            
            // Biarkan tombol tetap mati agar tidak spam chat lagi
            btn.disabled = true; 
            return; 
        }

        // 3. Jika sukses (200 OK)
        if (res.ok) {
            appendMessage(data.reply, true);
        } else {
            // Jika error server lain (500)
            throw new Error("Server Error");
        }

    } catch (err) {
        // Blok ini HANYA akan jalan jika koneksi internet mati 
        // atau JSON gagal di-parse (res.json() error)
        console.error(err);
        appendMessage("Maaf, terjadi gangguan koneksi. Coba lagi nanti.", true);
    } finally {
        // Jika tidak limit, aktifkan lagi inputnya
        if (!btn.disabled) {
            input.disabled = false;
            btn.disabled = false;
            input.focus();
        }
    }
};
  
</script>

{{-- PWA Service Worker --}}
<script>
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/sw.js').catch(() => {});
    }
</script>

@stack('scripts')
</body>
</html>
