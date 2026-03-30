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
                        "on-surface": "#dee5ff", "surface": "#060e20", "surface-container-highest": "#192540",
                        "background": "#060e20", "primary": "#6af2de", "primary-container": "#10b7a5",
                        "on-background": "#dee5ff", "primary-dim": "#5ae4d0", "on-primary-container": "#002b26",
                        "on-primary": "#00594f", "surface-variant": "#192540", "on-surface-variant": "#a3aac4",
                        "surface-container-high": "#141f38", "outline-variant": "#40485d",
                        "surface-container": "#0f1930", "surface-bright": "#1f2b49",
                        "surface-container-low": "#091328", "outline": "#6d758c",
                        "error": "#ff716c", "error-container": "#9f0519",
                        "secondary": "#62fae3", "secondary-container": "#006b5f",
                    },
                    fontFamily: { "headline": ["Inter"], "body": ["Inter"] },
                    boxShadow: { 'glass': '0 8px 32px 0 rgba(0, 0, 0, 0.37)' }
                },
            },
        }
    </script>

    <style>
        .fa-icon { display: inline-flex; align-items: center; justify-content: center; }
        body { background-color: #060e20; color: #dee5ff; font-family: 'Inter', sans-serif; min-height: 100dvh; }
        .glass-card { background: rgba(25, 37, 64, 0.7); backdrop-filter: blur(20px); }

        html:not(.dark) body { background-color: #ffffffff; color: #1e293b; }
        html:not(.dark) .glass-card { background: rgba(255,255,255,0.85); backdrop-filter: blur(20px); box-shadow: 0 4px 16px rgba(0,0,0,0.06); border-color: rgba(0,0,0,0.06) !important; }
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
