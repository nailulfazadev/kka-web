@extends('layouts.app')
@section('title', 'Explore Pelatihan - Academy Guru KKA')

@section('content')

    {{-- ===================== HEADER ===================== --}}
    <section class="px-5 pt-8 pb-5">
        <span class="text-[10px] font-bold uppercase tracking-[0.28em] text-secondary flex items-center gap-2 mb-3">
            <span class="w-1.5 h-1.5 rounded-full bg-secondary inline-block"></span>
            Katalog Lengkap
        </span>
        <h1 class="text-[1.85rem] font-extrabold text-on-surface leading-tight tracking-tight mb-1">
            Explore <span class="text-primary">Pelatihan</span>
        </h1>
        <p class="text-on-surface-variant text-sm">Temukan pelatihan yang sesuai kebutuhan Anda</p>
    </section>

    {{-- ===================== SEARCH ===================== --}}
    <section class="px-5 mb-6">
        <form action="{{ url('/pelatihan') }}" method="GET">
            <input type="hidden" name="filter" value="{{ request('filter') }}">
            <input type="hidden" name="pricing" value="{{ request('pricing') }}">
            <div class="relative">
                <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none">
                    <i class="fa-solid fa-magnifying-glass text-outline text-sm"></i>
                </div>
                <input
                    name="search"
                    value="{{ request('search') }}"
                    type="text"
                    placeholder="Cari topik, kurikulum, atau instruktur..."
                    class="w-full bg-surface-container-highest rounded-2xl py-4 pl-11 pr-14 text-on-surface text-sm placeholder:text-outline border-none focus:ring-1 focus:ring-primary/30 transition-all shadow-inner"
                />
                <button type="submit"
                    class="absolute right-2 top-1/2 -translate-y-1/2 bg-primary/15 hover:bg-primary/25 transition-colors w-10 h-10 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-arrow-right text-primary text-sm"></i>
                </button>
            </div>
        </form>
    </section>

    {{-- ===================== STATUS FILTER ===================== --}}
    <section class="mb-3">
        <div class="flex gap-2 px-5 overflow-x-auto hide-scrollbar">
            @foreach(['semua' => 'Semua', 'aktif' => 'Aktif', 'mendatang' => 'Mendatang', 'selesai' => 'Selesai'] as $key => $label)
                @php
                    $isActive = request('filter', '') === $key || (request('filter') === null && $key === 'semua');
                @endphp
                <a href="{{ url('/pelatihan?filter=' . ($key === 'semua' ? '' : $key) . '&pricing=' . request('pricing', '')) }}"
                   class="flex-shrink-0 px-4 py-2 rounded-2xl text-xs font-semibold transition-colors
                   {{ $isActive ? 'bg-primary text-on-primary shadow-sm shadow-primary/20' : 'glass-card border border-white/5 text-on-surface-variant' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>
    </section>

    {{-- ===================== PRICING FILTER ===================== --}}
    <section class="mb-7">
        <div class="flex gap-2 px-5 overflow-x-auto hide-scrollbar">
            @php
                $pricingOptions = [
                    'all'      => ['label' => 'Semua Harga', 'icon' => 'fa-layer-group'],
                    'free'     => ['label' => 'Gratis',      'icon' => 'fa-gift'],
                    'berbayar' => ['label' => 'Berbayar',    'icon' => 'fa-crown'],
                    'donasi'   => ['label' => 'Donasi',      'icon' => 'fa-heart'],
                ];
            @endphp
            @foreach($pricingOptions as $key => $opt)
                @php
                    $isActive = request('pricing', '') === $key || (request('pricing') === null && $key === 'all');
                @endphp
                <a href="{{ url('/pelatihan?pricing=' . ($key === 'all' ? '' : $key) . '&filter=' . request('filter', '')) }}"
                   class="flex-shrink-0 flex items-center gap-1.5 px-4 py-2 rounded-2xl text-xs font-semibold transition-colors
                   {{ $isActive ? 'bg-secondary-container text-on-secondary-container' : 'glass-card border border-white/5 text-on-surface-variant' }}">
                    <i class="fa-solid {{ $opt['icon'] }} text-[10px]"></i>
                    {{ $opt['label'] }}
                </a>
            @endforeach
        </div>
    </section>

    {{-- ===================== RESULT COUNT ===================== --}}
    <section class="px-5 mb-4">
        <p class="text-xs text-on-surface-variant">
            Menampilkan <span class="font-semibold text-on-surface">{{ $trainings->total() }}</span> pelatihan
            @if(request('search'))
                untuk "<span class="text-primary font-medium">{{ request('search') }}</span>"
            @endif
        </p>
    </section>

    {{-- ===================== TRAINING GRID ===================== --}}
    <section class="px-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @forelse($trainings as $training)
                <a href="{{ route('pelatihan.show', $training->slug) }}"
                   class="glass-card rounded-[1.75rem] overflow-hidden border border-white/5 block active:scale-[0.98] transition-transform">

                    {{-- Thumbnail --}}
                    <div class="relative bg-surface-container-high">
                        @if($training->thumbnail)
                            <img src="{{ asset('storage/' . $training->thumbnail) }}"
                                 class="w-full h-full object-cover"
                                 alt="{{ $training->title }}">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-surface-container-high to-surface-container-highest">
                                <i class="fa-solid fa-graduation-cap text-3xl text-outline/40"></i>
                            </div>
                        @endif

                        {{-- Gradient overlay --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-black/35 via-transparent to-transparent"></div>

                        {{-- Badges --}}
                        <div class="absolute top-3 left-3 flex gap-1.5">
                            @php
                                $statusColor = match($training->status) {
                                    'aktif'     => 'bg-secondary/90 text-on-primary-container',
                                    'mendatang' => 'bg-tertiary-fixed/80 text-on-primary-container',
                                    default     => 'bg-outline/50 text-on-surface',
                                };
                                $pricingColor = match($training->pricing_type) {
                                    'free'     => 'bg-surface-bright/80 text-on-surface',
                                    'berbayar' => 'bg-primary-container/90 text-on-primary-container',
                                    default    => 'bg-surface-bright/80 text-on-surface',
                                };
                                $pricingIcon = match($training->pricing_type) {
                                    'free'     => 'fa-gift',
                                    'berbayar' => 'fa-crown',
                                    default    => 'fa-heart',
                                };
                            @endphp
                            <span class="inline-flex items-center gap-1 {{ $statusColor }} backdrop-blur-md text-[9px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wide">
                                <span class="w-1 h-1 rounded-full bg-current opacity-70"></span>
                                {{ ucfirst($training->status) }}
                            </span>
                            <span class="inline-flex items-center gap-1 {{ $pricingColor }} backdrop-blur-md text-[9px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wide">
                                <i class="fa-solid {{ $pricingIcon }} text-[8px]"></i>
                                {{ ucfirst($training->pricing_type) }}
                            </span>
                        </div>

                        {{-- Participants bottom-right --}}
                        <div class="absolute bottom-3 right-3 flex items-center gap-1 bg-black/35 backdrop-blur-sm px-2.5 py-1 rounded-lg">
                            <i class="fa-solid fa-users text-white/70 text-[9px]"></i>
                            <span class="text-white/80 text-[9px] font-medium">{{ $training->participants_count ?? 0 }}</span>
                        </div>
                    </div>

                    {{-- Body --}}
                    <div class="p-4">
                        <h3 class="font-bold text-sm text-on-surface leading-snug mb-3 line-clamp-2">{{ $training->title }}</h3>

                        {{-- Rating + Price row --}}
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-1.5">
                                <i class="fa-solid fa-star text-yellow-400 text-[11px]"></i>
                                <span class="text-xs font-bold text-on-surface">{{ $training->ratings_avg_score ? number_format($training->ratings_avg_score, 1) : '0.0' }}</span>
                                <span class="text-outline text-xs">/5</span>
                            </div>
                            <span class="text-primary font-extrabold text-sm">
                                @if($training->pricing_type === 'free')
                                    Gratis
                                @elseif($training->pricing_type === 'berbayar')
                                    Rp&nbsp;{{ number_format($training->price, 0, ',', '.') }}
                                @else
                                    Donasi
                                @endif
                            </span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-2 py-20 flex flex-col items-center text-center">
                    <div class="w-20 h-20 rounded-3xl bg-surface-container-high flex items-center justify-center mb-5">
                        <i class="fa-solid fa-magnifying-glass-minus text-3xl text-outline"></i>
                    </div>
                    <p class="text-on-surface font-bold text-base mb-1">Tidak ada hasil</p>
                    <p class="text-on-surface-variant text-sm mb-5">Coba ubah filter atau kata kunci pencarian Anda.</p>
                    <a href="{{ url('/pelatihan') }}"
                       class="inline-flex items-center gap-2 bg-primary/10 text-primary text-sm font-semibold px-5 py-3 rounded-2xl hover:bg-primary/18 transition-colors">
                        <i class="fa-solid fa-rotate-left text-xs"></i>
                        Reset Semua Filter
                    </a>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($trainings->hasPages())
            <div class="mt-8 mb-4">
                {{ $trainings->links() }}
            </div>
        @endif
    </section>

@endsection