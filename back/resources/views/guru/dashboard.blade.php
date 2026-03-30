@extends('layouts.app')
@section('title', 'Dashboard Guru - Academy Guru KKA')

@section('content')
    <section class="px-6 pt-6 pb-4">
        <h2 class="text-2xl font-bold text-on-surface mb-1">Halo, {{ auth()->user()->name }}! 👋</h2>
        <p class="text-on-surface-variant text-sm">{{ now()->translatedFormat('l, d F Y') }}</p>
    </section>

    {{-- Upcoming Session --}}
    @if($upcomingSession)
    <section class="px-6 mb-8">
        <div class="glass-card rounded-[2rem] border border-white/5 p-6 relative overflow-hidden">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-primary/10 rounded-full blur-[60px]"></div>
            <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-secondary mb-3 block">Sesi Berikutnya</span>
            <h3 class="text-lg font-bold text-on-surface mb-2">{{ $upcomingSession->title ?? 'Sesi ' . $upcomingSession->session_number }}</h3>
            <p class="text-sm text-on-surface-variant mb-1">{{ $upcomingSession->training_title ?? '' }}</p>
            <p class="text-xs text-on-surface-variant mb-4">
                <i class="fa-regular fa-calendar text-sm align-middle"></i>
                {{ $upcomingSession->session_date->format('D, d M Y') }} • {{ $upcomingSession->start_time }} - {{ $upcomingSession->end_time }}
            </p>
            @if($upcomingSession->zoom_link)
                <a href="{{ $upcomingSession->zoom_link }}" target="_blank" class="inline-flex items-center gap-2 bg-gradient-to-r from-primary-dim to-primary-container text-on-primary-container font-bold px-6 py-3 rounded-xl text-sm active:scale-95 transition-transform">
                    <i class="fa-solid fa-video text-sm"></i> Gabung Zoom
                </a>
            @endif
        </div>
    </section>
    @endif

    {{-- Quick Stats --}}
    <section class="px-6 mb-8">
        <div class="grid grid-cols-2 gap-4">
            <div class="glass-card p-5 rounded-2xl border border-white/5 text-center">
                <span class="text-2xl font-bold text-primary">{{ $activeCount }}</span>
                <p class="text-xs text-on-surface-variant mt-1">Pelatihan Aktif</p>
            </div>
            <div class="glass-card p-5 rounded-2xl border border-white/5 text-center">
                <span class="text-2xl font-bold text-secondary">{{ $completedCount }}</span>
                <p class="text-xs text-on-surface-variant mt-1">Selesai</p>
            </div>
        </div>
    </section>

    {{-- My Trainings --}}
    <section class="px-6 mb-8">
        <h3 class="text-lg font-bold text-on-surface mb-4">Pelatihan Saya</h3>
        @forelse($myTrainings as $reg)
            <a href="{{ route('guru.pelatihan.show', $reg->id) }}" class="glass-card rounded-2xl border border-white/5 p-4 mb-3 flex items-center gap-4 hover:bg-white/5 transition-colors block">
                <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-graduation-cap text-primary"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <h4 class="text-sm font-semibold text-on-surface truncate">{{ $reg->training->title }}</h4>
                    <p class="text-xs text-on-surface-variant">{{ $reg->training->sessions->count() }} sesi</p>
                </div>
                <span class="px-2 py-1 rounded-full text-[9px] font-bold uppercase {{ $reg->status === 'aktif' ? 'bg-green-500/20 text-green-400' : 'bg-outline/20 text-outline' }}">
                    {{ $reg->status }}
                </span>
            </a>
        @empty
            <div class="text-center py-8">
                <i class="fa-solid fa-graduation-cap text-3xl text-outline mb-2"></i>
                <p class="text-on-surface-variant text-sm">Belum ada pelatihan.</p>
                <a href="{{ url('/pelatihan') }}" class="text-primary text-sm font-semibold mt-2 block">Jelajahi Pelatihan →</a>
            </div>
        @endforelse
    </section>
@endsection
