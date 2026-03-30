<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel - Academy Guru KKA')</title>

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "on-surface": "#dee5ff", "surface": "#060e20", "surface-container-lowest": "#000000",
                        "surface-container-highest": "#192540", "background": "#060e20", "primary": "#6af2de",
                        "primary-container": "#10b7a5", "on-background": "#dee5ff", "primary-dim": "#5ae4d0",
                        "on-primary-container": "#002b26", "on-primary": "#00594f", "surface-variant": "#192540",
                        "secondary-fixed": "#62fae3", "on-surface-variant": "#a3aac4",
                        "surface-container-high": "#141f38", "outline-variant": "#40485d",
                        "secondary-container": "#006b5f", "surface-container": "#0f1930",
                        "surface-bright": "#1f2b49", "surface-container-low": "#091328",
                        "secondary": "#62fae3", "outline": "#6d758c", "error": "#ff716c",
                        "error-container": "#9f0519", "on-error-container": "#ffa8a3",
                        "on-secondary-container": "#dcfff7", "tertiary": "#e4fff9",
                    },
                    fontFamily: { "headline": ["Inter"], "body": ["Inter"] },
                    boxShadow: { 'glass': '0 8px 32px 0 rgba(0, 0, 0, 0.37)' }
                },
            },
        }
    </script>

    <style>
        .fa-icon { display: inline-flex; align-items: center; justify-content: center; }
        body { background-color: #060e20; color: #dee5ff; font-family: 'Inter', sans-serif; }
        .glass-card { background: rgba(25, 37, 64, 0.7); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); }

        html:not(.dark) body { background-color: #ffffffff; color: #1e293b; }
        html:not(.dark) .glass-card { background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(20px); box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06); border-color: rgba(0, 0, 0, 0.06) !important; }
        html:not(.dark) .bg-surface { background-color: #f8fafc; }
        html:not(.dark) .bg-surface-container { background-color: #f1f5f9; }
        html:not(.dark) .bg-surface-container-high { background-color: #e2e8f0; }
        html:not(.dark) .bg-surface-container-highest { background-color: #cbd5e1; }
        html:not(.dark) .text-on-surface { color: #1e293b; }
        html:not(.dark) .text-on-surface-variant { color: #64748b; }
        html:not(.dark) .text-primary { color: #0d9488; }
        html:not(.dark) .border-white\/5 { border-color: rgba(0, 0, 0, 0.06); }

        html:not(.dark) .admin-sidebar { background: rgba(255, 255, 255, 0.9) !important; border-color: rgba(0, 0, 0, 0.06) !important; }
        html:not(.dark) .admin-topbar { background: rgba(255, 255, 255, 0.85) !important; border-color: rgba(0, 0, 0, 0.06) !important; }
        html:not(.dark) .hover\:bg-white\/5:hover { background-color: rgba(0, 0, 0, 0.04) !important; }
        html:not(.dark) .hover\:text-on-surface:hover { color: #0f172a !important; }
    </style>
    @stack('styles')
</head>
<body class="antialiased">
<div class="flex min-h-screen">
    {{-- Sidebar --}}
    <aside id="admin-sidebar" class="admin-sidebar fixed lg:static inset-y-0 left-0 z-50 w-64 bg-surface-container-low border-r border-white/5 transform -translate-x-full lg:translate-x-0 transition-transform duration-300">
        <div class="p-6 border-b border-white/5">
            <h1 class="text-lg font-bold text-primary">Admin Panel</h1>
            <p class="text-xs text-on-surface-variant">Academy Guru KKA</p>
        </div>
        <nav class="p-4 space-y-1">
            @php $route = request()->route()?->getName() ?? ''; @endphp
            @php
                $faIcons = [
                    'dashboard' => 'fa-solid fa-chart-pie',
                    'school' => 'fa-solid fa-graduation-cap',
                    'group' => 'fa-solid fa-users',
                    'fact_check' => 'fa-solid fa-clipboard-check',
                    'payments' => 'fa-solid fa-wallet',
                    'workspace_premium' => 'fa-solid fa-certificate',
                    'settings' => 'fa-solid fa-gear',
                ];
            @endphp
            @foreach([
                ['admin.dashboard', 'dashboard', 'Dashboard'],
                ['admin.pelatihan.index', 'school', 'Kelola Pelatihan'],
                ['admin.peserta.index', 'group', 'Peserta'],
                ['admin.presensi.index', 'fact_check', 'Presensi'],
                ['admin.keuangan.index', 'payments', 'Keuangan'],
                ['admin.cert-template.index', 'workspace_premium', 'Template Sertifikat'],
                ['admin.pengaturan.index', 'settings', 'Pengaturan'],
            ] as $item)
                <a href="{{ route($item[0]) ?? '#' }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-colors
                   {{ str_contains($route, $item[0]) ? 'bg-primary/10 text-primary' : 'text-on-surface-variant hover:bg-white/5 hover:text-on-surface' }}">
                    <i class="{{ $faIcons[$item[1]] ?? 'fa-solid fa-circle' }} text-lg w-6 text-center"></i>
                    {{ $item[2] }}
                </a>
            @endforeach
        </nav>
        <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-white/5">
            <form method="POST" action="{{ route('logout') ?? '#' }}">
                @csrf
                <button class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-error hover:bg-error/10 w-full transition-colors">
                    <i class="fa-solid fa-right-from-bracket text-lg"></i>
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    {{-- Main Content --}}
    <div class="flex-1 min-h-screen">
        {{-- Top Bar --}}
        <header class="admin-topbar sticky top-0 z-40 bg-surface/80 backdrop-blur-xl border-b border-white/5 flex items-center justify-between px-6 h-16">
            <div class="flex items-center gap-4">
                <button onclick="document.getElementById('admin-sidebar').classList.toggle('-translate-x-full')" class="lg:hidden p-2 rounded-lg hover:bg-white/5">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <h2 class="text-lg font-semibold text-on-surface">@yield('page-title', 'Dashboard')</h2>
            </div>
            <div class="flex items-center gap-3">
                <button onclick="toggleTheme()" class="p-2 rounded-lg hover:bg-white/5">
                    <i class="fa-solid fa-sun text-on-surface-variant dark-icon"></i>
                    <i class="fa-solid fa-moon text-on-surface-variant light-icon hidden"></i>
                </button>
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-primary-container flex items-center justify-center">
                        <i class="fa-solid fa-user text-sm text-on-primary-container"></i>
                    </div>
                    <span class="text-sm text-on-surface font-medium hidden sm:block">{{ auth()->user()->name ?? 'Admin' }}</span>
                </div>
            </div>
        </header>

        <main class="p-6">
            @if(session('success'))
                <div class="mb-4 p-4 rounded-xl bg-primary-container/20 border border-primary/20 text-primary text-sm">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="mb-4 p-4 rounded-xl bg-error-container/20 border border-error/20 text-error text-sm">{{ session('error') }}</div>
            @endif
            @yield('content')
        </main>
    </div>
</div>

{{-- Sidebar Overlay --}}
<div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden" onclick="document.getElementById('admin-sidebar').classList.add('-translate-x-full'); this.classList.add('hidden')"></div>

<script>
    function toggleTheme() {
        const html = document.documentElement;
        html.classList.toggle('dark');
        localStorage.setItem('theme', html.classList.contains('dark') ? 'dark' : 'light');
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
    (function() {
        const saved = localStorage.getItem('theme');
        if (saved === 'light') document.documentElement.classList.remove('dark');
        else document.documentElement.classList.add('dark');
        document.addEventListener('DOMContentLoaded', updateThemeIcons);
    })();

    // Sidebar toggle
    document.getElementById('admin-sidebar')?.addEventListener('transitionend', function() {
        const overlay = document.getElementById('sidebar-overlay');
        if (!this.classList.contains('-translate-x-full')) overlay.classList.remove('hidden');
        else overlay.classList.add('hidden');
    });
</script>
@stack('scripts')
</body>
</html>
