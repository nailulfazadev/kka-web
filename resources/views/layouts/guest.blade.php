<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Academy Guru KKA')</title>

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "on-surface": "#e8f4ff", "surface": "#070b1a", "surface-container-highest": "#141c38",
                        "background": "#070b1a", "primary": "#7ecfff", "primary-container": "#0090c4",
                        "on-background": "#e8f4ff", "primary-dim": "#5bbde8", "on-primary-container": "#001830",
                        "on-primary": "#002040", "surface-variant": "#141c38", "on-surface-variant": "#a0aac8",
                        "surface-container-high": "#0f1530", "outline-variant": "#2a3458",
                        "surface-container": "#0d1228", "surface-bright": "#182040",
                        "surface-container-low": "#0a0e22", "outline": "#506080",
                        "error": "#ff716c", "error-container": "#9f0519",
                        "secondary": "#9b76f5", "secondary-container": "#5a32c4",
                        "accent": "#f5a623",
                    },
                    fontFamily: { "headline": ["Inter"], "body": ["Inter"] },
                    boxShadow: { 'glass': '0 8px 32px 0 rgba(0, 0, 0, 0.37)' }
                },
            },
        }
    </script>

    <style>
        .fa-icon { display: inline-flex; align-items: center; justify-content: center; }
        body { background-color: #070b1a; color: #e8f4ff; font-family: 'Inter', sans-serif; min-height: 100dvh; }
        .glass-card { background: rgba(20, 28, 56, 0.7); backdrop-filter: blur(20px); }

        html:not(.dark) body { background-color: #ffffff; color: #0a1a30; }
        html:not(.dark) .glass-card { background: rgba(255,255,255,0.85); backdrop-filter: blur(20px); box-shadow: 0 4px 16px rgba(0,0,0,0.06); border-color: rgba(0,0,0,0.06) !important; }
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
    </style>
    @stack('styles')
</head>
<body class="antialiased selection:bg-primary-container selection:text-on-primary-container">

<div class="max-w-[600px] mx-auto min-h-screen bg-surface relative overflow-x-hidden">
    <main class="px-6 py-10">
        @yield('content')
    </main>
</div>

<script>
    (function() {
        const saved = localStorage.getItem('theme');
        if (saved === 'light') document.documentElement.classList.remove('dark');
        else document.documentElement.classList.add('dark');
    })();
</script>
@stack('scripts')
</body>
</html>
