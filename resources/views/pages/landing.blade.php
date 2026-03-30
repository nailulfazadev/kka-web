@extends('layouts.app')
@section('title', 'Academy Guru KKA - Platform Pelatihan Guru Terpercaya')

@section('content')


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

    {{-- ===================== HERO ===================== --}}
    <section class="relative px-5 pt-12 pb-10 overflow-hidden">

        {{-- Ambient blobs --}}
        <div class="pointer-events-none absolute -top-20 -right-20 w-72 h-72 rounded-full bg-primary/10 blur-[120px]"></div>
        <div class="pointer-events-none absolute top-40 -left-16 w-52 h-52 rounded-full bg-secondary/8 blur-[90px]"></div>

        {{-- Eyebrow label --}}
        <div class="flex items-center gap-2 mb-5">
            <span class="w-1.5 h-1.5 rounded-full bg-secondary inline-block animate-pulse"></span>
            <span class="text-[10px] font-bold uppercase tracking-[0.28em] text-secondary">E-Learning Platform</span>
        </div>

        {{-- Headline --}}
        <h1 class="text-[2.6rem] font-extrabold text-on-surface leading-[1.12] tracking-tight mb-5">
            Belajar Lebih<br/>
            <span class="text-primary">Cerdas.</span>
            <span class="text-on-surface-variant font-light"> Mengajar</span><br/>
            <span class="text-on-surface">Lebih Berdampak.</span>
        </h1>

        {{-- Sub --}}
        <p class="text-on-surface-variant text-[0.95rem] leading-relaxed mb-8 max-w-sm">
            Kurikulum pelatihan guru berbasis kompetensi — dirancang untuk era pendidikan modern. Mulai belajar kapan saja, di mana saja.
        </p>

        {{-- CTA Row --}}
        <div class="flex items-center gap-3 flex-wrap">
            <a href="{{ url('/pelatihan') }}"
               class="inline-flex items-center gap-2.5 bg-gradient-to-br from-primary-dim to-primary-container text-on-primary-container font-bold text-sm px-6 py-3.5 rounded-2xl shadow-lg shadow-primary/15 active:scale-95 transition-transform">
                Mulai Pelatihan
                <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
            @guest
                <a href="{{ route('register') }}"
                   class="inline-flex items-center gap-2 text-on-surface-variant text-sm font-medium px-4 py-3.5 rounded-2xl border border-white/8 hover:bg-white/4 transition-colors">
                    <i class="fa-regular fa-user text-xs"></i>
                    Daftar Gratis
                </a>
            @endguest
        </div>

        {{-- Floating trust badge --}}
        <div class="mt-10 inline-flex items-center gap-3 glass-card px-4 py-3 rounded-2xl border border-white/8">
            <div class="flex -space-x-2">
                <div class="w-7 h-7 rounded-full bg-primary/30 border-2 border-surface flex items-center justify-center text-[9px] font-bold text-primary">G</div>
                <div class="w-7 h-7 rounded-full bg-secondary/30 border-2 border-surface flex items-center justify-center text-[9px] font-bold text-secondary">A</div>
                <div class="w-7 h-7 rounded-full bg-tertiary-fixed/30 border-2 border-surface flex items-center justify-center text-[9px] font-bold text-tertiary-fixed">R</div>
                <div class="w-7 h-7 rounded-full bg-outline/20 border-2 border-surface flex items-center justify-center text-[9px] font-bold text-outline">+</div>
            </div>
            <div>
                <p class="text-[11px] font-semibold text-on-surface leading-none mb-0.5">500+ Guru Bergabung</p>
                <div class="flex items-center gap-1">
                    <i class="fa-solid fa-star text-yellow-400 text-[9px]"></i>
                    <span class="text-[10px] text-on-surface-variant">Dipercaya se-Indonesia</span>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== SEARCH ===================== --}}
    <section class="px-5 mb-10">
        <form action="{{ url('/pelatihan') }}" method="GET">
            <div class="relative">
                <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none">
                    <i class="fa-solid fa-magnifying-glass text-outline text-sm"></i>
                </div>
                <input
                    name="search"
                    type="text"
                    placeholder="Cari topik pelatihan, kurikulum..."
                    class="w-full bg-surface-container-highest rounded-2xl py-4 pl-11 pr-14 text-on-surface text-sm placeholder:text-outline border-none focus:ring-1 focus:ring-primary/30 transition-all shadow-inner"
                />
                <button type="submit"
                    class="absolute right-2 top-1/2 -translate-y-1/2 bg-primary/15 hover:bg-primary/25 transition-colors w-10 h-10 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-arrow-right text-primary text-sm"></i>
                </button>
            </div>
        </form>
    </section>

    {{-- ===================== STATS ===================== --}}
    <section class="px-5 mb-12">
        <div class="grid grid-cols-3 gap-3">
            <div class="glass-card rounded-[1.5rem] border border-white/5 p-4 flex flex-col items-center text-center">
                <span class="text-2xl font-extrabold text-primary leading-none mb-1">500+</span>
                <span class="text-[9px] uppercase tracking-widest text-on-surface-variant font-medium leading-tight">Guru<br/>Aktif</span>
            </div>
            <div class="glass-card rounded-[1.5rem] border border-white/5 p-4 flex flex-col items-center text-center">
                <span class="text-2xl font-extrabold text-secondary leading-none mb-1">50+</span>
                <span class="text-[9px] uppercase tracking-widest text-on-surface-variant font-medium leading-tight">Modul<br/>Pelatihan</span>
            </div>
            <div class="glass-card rounded-[1.5rem] border border-white/5 p-4 flex flex-col items-center text-center">
                <span class="text-2xl font-extrabold text-tertiary-fixed leading-none mb-1">1K+</span>
                <span class="text-[9px] uppercase tracking-widest text-on-surface-variant font-medium leading-tight">Sertifikat<br/>Diterbitkan</span>
            </div>
        </div>
    </section>

    {{-- ===================== CATEGORY CHIPS ===================== --}}
    <!-- <section class="mb-8">
        <div class="flex gap-2.5 px-5 overflow-x-auto hide-scrollbar">
            @php
                $categories = [
                    ['icon' => 'fa-chalkboard-user', 'label' => 'Pedagogik'],
                    ['icon' => 'fa-laptop-code', 'label' => 'Teknologi'],
                    ['icon' => 'fa-users', 'label' => 'Manajemen'],
                    ['icon' => 'fa-brain', 'label' => 'Kurikulum'],
                    ['icon' => 'fa-chart-line', 'label' => 'Asesmen'],
                ];
            @endphp
            @foreach($categories as $cat)
                <a href="{{ url('/pelatihan?kategori=' . strtolower($cat['label'])) }}"
                   class="flex-shrink-0 flex items-center gap-2 glass-card border border-white/5 px-4 py-2.5 rounded-2xl text-xs font-semibold text-on-surface-variant hover:text-primary hover:border-primary/20 transition-colors">
                    <i class="fa-solid {{ $cat['icon'] }} text-[11px]"></i>
                    {{ $cat['label'] }}
                </a>
            @endforeach
        </div>
    </section> -->

    {{-- ===================== FEATURED TRAININGS ===================== --}}
    <section class="mb-12">
        <div class="px-5 flex justify-between items-center mb-5">
            <div>
                <h2 class="text-[1.1rem] font-bold text-on-surface">Pelatihan Populer</h2>
                <p class="text-[11px] text-on-surface-variant mt-0.5">Pilihan terbaik bulan ini</p>
            </div>
            <a href="{{ url('/pelatihan') }}"
               class="flex items-center gap-1.5 text-primary text-xs font-semibold bg-primary/8 px-3 py-2 rounded-xl hover:bg-primary/14 transition-colors">
                Semua
                <i class="fa-solid fa-chevron-right text-[10px]"></i>
            </a>
        </div>

        <div class="flex overflow-x-auto gap-4 px-5 hide-scrollbar snap-x">
            @forelse($featuredTrainings as $training)
                <a href="{{ route('pelatihan.show', $training->slug) }}"
                   class="snap-start flex-shrink-0 w-[260px] glass-card rounded-[1.75rem] overflow-hidden border border-white/5 block active:scale-[0.98] transition-transform">

                    {{-- Thumbnail --}}
                    <div class="h-36 relative bg-surface-container-high">
                        @if($training->thumbnail)
                            <img src="{{ asset('storage/' . $training->thumbnail) }}"
                                 class="w-full h-full object-cover"
                                 alt="{{ $training->title }}">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-surface-container-high to-surface-container-highest">
                                <i class="fa-solid fa-graduation-cap text-3xl text-outline/50"></i>
                            </div>
                        @endif

                        {{-- Gradient overlay --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>

                        {{-- Badge --}}
                        <div class="absolute top-3 left-3 flex flex-col gap-1 items-start">
                            @if($training->pricing_type === 'free')
                                <span class="inline-flex items-center gap-1 bg-secondary/90 backdrop-blur-md text-on-primary-container text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wide">
                                    <i class="fa-solid fa-gift text-[8px]"></i> Gratis
                                </span>
                            @elseif($training->pricing_type === 'berbayar')
                                <span class="inline-flex items-center gap-1 bg-primary-container/90 backdrop-blur-md text-on-primary-container text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wide">
                                    <i class="fa-solid fa-crown text-[8px]"></i> Premium
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 bg-tertiary-fixed/80 backdrop-blur-md text-on-primary-container text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wide">
                                    <i class="fa-solid fa-heart text-[8px]"></i> Donasi
                                </span>
                            @endif
                            @if($training->is_ecourse)
                            <span class="inline-flex items-center gap-1 bg-blue-500/80 text-white backdrop-blur-md text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wide shadow-md">
                                <i class="fa-solid fa-play text-[8px]"></i> E-Course
                            </span>
                            @endif
                        </div>

                        {{-- Duration bottom-right --}}
                        <div class="absolute bottom-3 right-3 flex items-center gap-1 bg-black/40 backdrop-blur-sm px-2 py-1 rounded-lg">
                            <i class="fa-regular fa-clock text-white/70 text-[9px]"></i>
                            <span class="text-white/80 text-[9px] font-medium">Self-paced</span>
                        </div>
                    </div>

                    {{-- Body --}}
                    <div class="p-5">
                        <h3 class="font-bold text-sm text-on-surface leading-snug mb-2 line-clamp-2">{{ $training->title }}</h3>

                        {{-- Date / E-Course Info --}}
                        <div class="flex items-center gap-1.5 mb-3">
                            <i class="fa-regular fa-calendar text-outline text-[11px]"></i>
                            <span class="text-[11px] text-on-surface-variant font-medium">
                                @if($training->is_ecourse)
                                    Waktu Fleksibel (Bebas Akses)
                                @else
                                    @if($training->end_date && \Carbon\Carbon::parse($training->end_date)->endOfDay()->isPast())
                                        <span class="text-error font-bold">Pelaksanaan Ditutup</span>
                                    @elseif($training->start_date)
                                        Mulai: <span class="text-on-surface">{{ \Carbon\Carbon::parse($training->start_date)->translatedFormat('d F Y') }}</span>
                                    @else
                                        Jadwal Menyusul
                                    @endif
                                @endif
                            </span>
                        </div>

                        {{-- Meta row --}}
                        <div class="flex items-center gap-2 mb-4">
                            <div class="flex items-center gap-1">
                                <i class="fa-solid fa-star text-yellow-400 text-[11px]"></i>
                                <span class="text-xs font-bold text-on-surface">{{ $training->ratings_avg_score ? number_format($training->ratings_avg_score, 1) : '0.0' }}</span>
                            </div>
                            <span class="text-outline text-xs">·</span>
                            <div class="flex items-center gap-1 text-on-surface-variant text-xs">
                                <i class="fa-solid fa-users text-[10px]"></i>
                                <span>{{ $training->participants_count ?? 0 }}</span>
                            </div>
                        </div>

                        {{-- Price + arrow --}}
                        <div class="flex justify-between items-center">
                            <span class="text-primary font-extrabold text-sm">
                                @if($training->pricing_type === 'free')
                                    Gratis
                                @elseif($training->pricing_type === 'berbayar')
                                    Rp&nbsp;{{ number_format($training->price, 0, ',', '.') }}
                                @else
                                    Donasi
                                @endif
                            </span>
                            <div class="w-8 h-8 rounded-xl bg-primary/10 flex items-center justify-center">
                                <i class="fa-solid fa-arrow-right text-primary text-xs"></i>
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="w-full text-center py-16 px-8">
                    <div class="w-16 h-16 rounded-3xl bg-surface-container-high flex items-center justify-center mx-auto mb-4">
                        <i class="fa-solid fa-graduation-cap text-2xl text-outline"></i>
                    </div>
                    <p class="text-on-surface font-semibold mb-1">Belum ada pelatihan</p>
                    <p class="text-on-surface-variant text-sm">Segera hadir — pantau terus!</p>
                </div>
            @endforelse
        </div>
    </section>

    {{-- ===================== WHY KKA STRIP ===================== --}}
    <section class="px-5 mb-12">
        <h2 class="text-[1.1rem] font-bold text-on-surface mb-5">Mengapa Academy KKA?</h2>
        <div class="flex flex-col gap-3">
            @php
                $features = [
                    ['icon' => 'fa-shield-halved',  'color' => 'text-primary',        'bg' => 'bg-primary/10',        'title' => 'Kurikulum Terverifikasi',    'desc' => 'Dirancang bersama pakar pendidikan nasional.'],
                    ['icon' => 'fa-certificate',    'color' => 'text-secondary',      'bg' => 'bg-secondary/10',      'title' => 'Sertifikat Diakui',         'desc' => 'Berlaku untuk pengajuan angka kredit guru.'],
                    ['icon' => 'fa-mobile-screen',  'color' => 'text-tertiary-fixed', 'bg' => 'bg-tertiary-fixed/10', 'title' => 'Belajar Fleksibel',         'desc' => 'Akses penuh lewat HP kapan saja, di mana saja.'],
                    ['icon' => 'fa-comments',       'color' => 'text-primary',        'bg' => 'bg-primary/10',        'title' => 'Komunitas Aktif',           'desc' => 'Forum diskusi sesama guru se-Indonesia.'],
                ];
            @endphp
            @foreach($features as $f)
                <div class="glass-card border border-white/5 rounded-[1.5rem] px-5 py-4 flex items-center gap-4">
                    <div class="w-11 h-11 rounded-2xl {{ $f['bg'] }} flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid {{ $f['icon'] }} {{ $f['color'] }} text-base"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-on-surface leading-tight mb-0.5">{{ $f['title'] }}</p>
                        <p class="text-xs text-on-surface-variant leading-relaxed">{{ $f['desc'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ===================== CTA BANNER ===================== --}}
    <section class="px-5 mb-12">
        <div class="relative rounded-[2rem] overflow-hidden bg-slate-900 border border-white/10 p-7 shadow-xl">

            {{-- Decorative --}}
            <div class="pointer-events-none absolute -top-8 -right-8 w-40 h-40 rounded-full bg-primary/20 blur-3xl"></div>
            <div class="pointer-events-none absolute bottom-0 right-4 opacity-10 text-white">
                <i class="fa-solid fa-graduation-cap text-8xl"></i>
            </div>

            <span class="inline-flex items-center gap-1.5 bg-primary/20 text-primary-content text-[10px] font-bold px-3 py-1.5 rounded-full uppercase tracking-widest mb-4">
                <i class="fa-solid fa-bolt text-[9px] text-primary"></i> <span class="text-primary-100 text-white">Mulai Sekarang</span>
            </span>

            <h3 class="text-xl font-extrabold text-white mb-2 leading-snug">
                Siap Menjadi<br/>Guru Penggerak?
            </h3>
            <p class="text-slate-300 text-sm mb-6 leading-relaxed">
                Akses ratusan modul eksklusif & bergabung bersama komunitas guru terbaik Indonesia.
            </p>

            @guest
                <a href="{{ route('register') }}"
                   class="flex items-center justify-between bg-white/5 hover:bg-white/10 transition-colors rounded-2xl px-5 py-4 group border border-white/5">
                    <div>
                        <p class="text-white font-bold text-sm leading-none mb-1">Buat Akun Gratis</p>
                        <p class="text-slate-400 text-xs">Tidak butuh kartu kredit</p>
                    </div>
                    <div class="w-9 h-9 rounded-xl bg-primary/20 flex items-center justify-center group-hover:bg-primary/40 transition-colors">
                        <i class="fa-solid fa-arrow-right text-primary text-sm"></i>
                    </div>
                </a>
            @else
                <a href="{{ url('/pelatihan') }}"
                   class="flex items-center justify-between bg-white/5 hover:bg-white/10 transition-colors rounded-2xl px-5 py-4 group border border-white/5">
                    <div>
                        <p class="text-white font-bold text-sm leading-none mb-1">Jelajahi Semua Pelatihan</p>
                        <p class="text-slate-400 text-xs">50+ modul tersedia</p>
                    </div>
                    <div class="w-9 h-9 rounded-xl bg-primary/20 flex items-center justify-center group-hover:bg-primary/40 transition-colors">
                        <i class="fa-solid fa-arrow-right text-primary text-sm"></i>
                    </div>
                </a>
            @endguest
        </div>
    </section>

@endsection