@extends('layouts.app')
@section('title', $training->title . ' - Academy Guru KKA')

@section('content')
    {{-- Header --}}
    <section class="px-6 pt-6">
        <a href="{{ url()->previous() }}" class="inline-flex items-center gap-2 text-on-surface-variant text-sm mb-4 hover:text-primary transition-colors">
            <i class="fa-solid fa-arrow-left text-sm"></i> Kembali
        </a>
    </section>

    {{-- Banner --}}
    <section class="px-6 mb-6">
        <div class="rounded-[2rem] overflow-hidden bg-surface-container-high relative">
            @if($training->thumbnail)
                <img src="{{ asset('storage/' . $training->thumbnail) }}" class="w-full h-full object-cover">
            @else
                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-primary/20 to-secondary/20">
                    <i class="fa-solid fa-graduation-cap text-6xl text-primary/30"></i>
                </div>
            @endif
        </div>
    </section>

    {{-- Info Card --}}
    <section class="px-6 mb-8">
        <div class="glass-card rounded-[2rem] border border-white/5 p-6">
            <div class="flex items-start justify-between mb-3">
                <h2 class="text-2xl font-bold text-on-surface leading-tight flex-1 pr-4">{{ $training->title }}</h2>
                <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider flex-shrink-0
                    {{ $training->status === 'aktif' ? 'bg-green-500/20 text-green-400' : ($training->status === 'mendatang' ? 'bg-yellow-500/20 text-yellow-400' : 'bg-outline/20 text-outline') }}">
                    {{ $training->status }}
                </span>
            </div>
            <!-- <p class="text-on-surface-variant text-sm mb-3">Oleh: {{ $training->creator->name ?? 'Admin' }}</p> -->
            <div class="flex items-center gap-4 text-sm text-on-surface-variant mb-4">
                @if($training->is_ecourse)
                    <span class="flex items-center gap-1 font-semibold text-blue-400"><i class="fa-solid fa-infinity text-sm"></i> Akses Selamanya</span>
                    <span class="flex items-center gap-1"><i class="fa-solid fa-play text-sm"></i> {{ $training->sessions->count() }} Topik Belajar</span>
                @else
                    <span class="flex items-center gap-1"><i class="fa-regular fa-calendar text-sm"></i> {{ $training->start_date->format('d M') }} - {{ $training->end_date->format('d M Y') }}</span>
                @endif
            </div>
            <div class="flex items-center justify-between">
                <span class="text-2xl font-bold text-primary">
                    @if($training->isFree()) Gratis
                    @elseif($training->isBerbayar()) Rp {{ number_format($training->price, 0, ',', '.') }}
                    @else Donasi @endif
                </span>
                <div class="flex items-center gap-2">
                    <div class="flex items-center text-yellow-400">
                        <i class="fa-solid fa-star text-sm"></i>
                        <span class="text-sm font-bold ml-1">{{ $training->ratings_avg_score ? number_format($training->ratings_avg_score, 1) : '0.0' }}</span>
                    </div>
                    <span class="text-on-surface-variant text-xs">({{ $training->participants_count }} peserta)</span>
                </div>
            </div>
        </div>
    </section>

    {{-- Description --}}
    @if($training->description)
    <section class="px-6 mb-8">
        <h3 class="text-lg font-bold text-on-surface mb-3">Deskripsi</h3>
        <p class="text-on-surface-variant text-sm leading-relaxed">{{ $training->description }}</p>
    </section>
    @endif

    {{-- Jadwal Pertemuan --}}
    <section class="px-6 mb-8">
        <h3 class="text-lg font-bold text-on-surface mb-4">{{ $training->is_ecourse ? 'Kurikulum & Topik Belajar' : 'Jadwal & Materi Sesi' }}</h3>
        <div class="space-y-3">
            @foreach($training->sessions as $session)
                <div class="glass-card rounded-2xl border border-white/5 p-4">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                            <span class="text-sm font-bold text-primary">{{ $session->session_number }}</span>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-on-surface">{{ $session->title ?? ($training->is_ecourse ? 'Topik ' : 'Sesi ') . $session->session_number }}</p>
                            @if(!$training->is_ecourse)
                            <p class="text-[11px] text-on-surface-variant mt-0.5">
                                <i class="fa-regular fa-calendar text-[11px] align-middle"></i> {{ $session->session_date->format('d M Y') }} •
                                <i class="fa-regular fa-clock text-[11px] align-middle"></i> {{ $session->start_time }} - {{ $session->end_time }} WIB
                            </p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="flex flex-wrap gap-2 pl-[3.25rem]">
                        @php 
                            $facs = is_array($session->facilities) ? $session->facilities : (json_decode($session->facilities, true) ?? []); 
                            $isRegistered = $registration && $registration->isActive();
                        @endphp
                        
                        @if($session->zoom_link)
                            @if($isRegistered && ($session->isLive() || $session->isUpcoming()))
                                <a href="{{ $session->zoom_link }}" target="_blank" class="flex items-center gap-1 bg-blue-500/20 text-blue-400 px-3 py-1.5 rounded-lg text-[10px] font-bold hover:bg-blue-500/30 transition-colors">
                                    <i class="fa-solid fa-video text-[12px]"></i> {{ $session->isLive() ? 'LIVE ZOOM' : 'Link Zoom' }}
                                </a>
                            @else
                                <div class="flex items-center gap-1 bg-surface-container-highest text-on-surface-variant px-3 py-1.5 rounded-lg text-[10px] font-medium border border-white/5">
                                    <i class="fa-solid fa-video text-[12px]"></i> Zoom
                                </div>
                            @endif
                        @endif

                        @if($session->recording_link)
                            @if($isRegistered)
                                <a href="{{ $session->recording_link }}" target="_blank" class="flex items-center gap-1 bg-red-500/20 text-red-400 px-3 py-1.5 rounded-lg text-[10px] font-bold hover:bg-red-500/30 transition-colors">
                                    <i class="fa-solid fa-play text-[12px]"></i> Tonton Rekaman
                                </a>
                            @else
                                <div class="flex items-center gap-1 bg-surface-container-highest text-on-surface-variant px-3 py-1.5 rounded-lg text-[10px] font-medium border border-white/5">
                                    <i class="fa-solid fa-play text-[12px]"></i> Rekaman Sesi
                                </div>
                            @endif
                        @endif

                        @forelse($facs as $idx => $fac)
                            @if($isRegistered)
                                <a href="{{ $fac }}" target="_blank" class="flex items-center gap-1 bg-tertiary/20 text-tertiary px-3 py-1.5 rounded-lg text-[10px] font-bold hover:bg-tertiary/30 transition-colors">
                                    <i class="fa-solid fa-file-lines text-[12px]"></i> Materi {{ count($facs) > 1 ? $idx + 1 : '' }}
                                </a>
                            @else
                                <div class="flex items-center gap-1 bg-surface-container-highest text-on-surface-variant px-3 py-1.5 rounded-lg text-[10px] font-medium border border-white/5">
                                    <i class="fa-solid fa-file-lines text-[12px]"></i> Materi {{ count($facs) > 1 ? $idx + 1 : '' }}
                                </div>
                            @endif
                        @empty
                            @if(!$session->zoom_link && !$session->recording_link)
                                <span class="text-[10px] text-outline italic">Belum ada lampiran.</span>
                            @endif
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Attendance Progress --}}
    @if($registration)
    <section class="px-6 mb-8">
        <h3 class="text-lg font-bold text-on-surface mb-3">Progres Kehadiran</h3>
        <div class="glass-card rounded-2xl border border-white/5 p-4">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm text-on-surface-variant">{{ $attendancePercent }}% Tercapai</span>
                <span class="text-xs text-on-surface-variant">Min. {{ $training->min_attendance_percent }}%</span>
            </div>
            <div class="h-3 rounded-full bg-surface-container-highest overflow-hidden">
                <div class="h-full rounded-full bg-gradient-to-r from-primary-dim to-primary transition-all" style="width: {{ $attendancePercent }}%"></div>
            </div>
        </div>
    </section>
    @endif

    {{-- Google Drive Link --}}
    @if($training->google_drive_link && $registration)
    <section class="px-6 mb-8">
        <a href="{{ $training->google_drive_link }}" target="_blank" class="glass-card rounded-2xl border border-white/5 p-4 flex items-center gap-4 hover:bg-white/5 transition-colors block">
            <i class="fa-solid fa-folder-open text-2xl text-primary"></i>
            <div>
                <p class="text-sm font-medium text-on-surface">Materi & Laporan</p>
                <p class="text-xs text-on-surface-variant">Akses Google Drive pelatihan</p>
            </div>
            <i class="fa-solid fa-arrow-up-right-from-square ml-auto text-outline"></i>
        </a>
    </section>
    @endif

    {{-- Ratings --}}
    @if($ratings->count())
    <section class="px-6 mb-8">
        <h3 class="text-lg font-bold text-on-surface mb-4">Ulasan Peserta</h3>
        <div class="space-y-3">
            @foreach($ratings as $rating)
                <div class="glass-card rounded-2xl border border-white/5 p-4">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center">
                            <span class="text-xs font-bold text-primary">{{ substr($rating->user->name, 0, 1) }}</span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-on-surface">{{ $rating->user->name }}</p>
                            <div class="flex text-yellow-400">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fa-solid fa-star text-sm" style="opacity: {{ $i <= $rating->score ? 1 : 0.3 }}"></i>
                                @endfor
                            </div>
                        </div>
                    </div>
                    @if($rating->review)
                        <p class="text-sm text-on-surface-variant">{{ $rating->review }}</p>
                    @endif
                </div>
            @endforeach
        </div>
    </section>
    @endif

    {{-- CTA --}}
    <section class="px-6 pb-16">
        @if(!$registration)
            <div class="fixed bottom-20 left-0 right-0 w-full max-w-[600px] mx-auto px-6 py-4 glass-card border-t border-white/5 z-40 shadow-[0_-10px_20px_rgba(0,0,0,0.1)]">
                @php
                    $isClosed = !$training->is_ecourse && $training->end_date && \Carbon\Carbon::parse($training->end_date)->endOfDay()->isPast();
                @endphp
                
                @if($isClosed)
                    <button type="button" disabled class="w-full bg-surface-container border border-error/20 text-error font-bold py-4 rounded-xl opacity-80 cursor-not-allowed flex items-center justify-center gap-2">
                        <i class="fa-solid fa-lock"></i> Pendaftaran Ditutup
                    </button>
                @else
                    <form method="POST" action="{{ auth()->check() ? route('guru.pelatihan.daftar', $training->id) : '#' }}">
                        @csrf
                        @guest
                            <a href="{{ route('login') }}" class="block w-full text-center bg-gradient-to-r from-primary-dim to-primary-container text-on-primary-container font-bold py-4 rounded-xl shadow-lg shadow-primary/10">
                                Login untuk Mendaftar
                            </a>
                        @else
                            <button type="submit" class="w-full bg-gradient-to-r from-primary-dim to-primary-container text-on-primary-container font-bold py-4 rounded-xl shadow-lg shadow-primary/10 active:scale-95 transition-transform">
                                @if($training->isFree()) Daftar Gratis
                                @elseif($training->isBerbayar()) Daftar — Rp {{ number_format($training->price, 0, ',', '.') }}
                                @else Daftar Gratis (Donasi) @endif
                            </button>
                        @endguest
                    </form>
                @endif
            </div>
        @else
            {{-- Main Mulai Pelatihan button --}}
            @if($registration->isActive() || $registration->isCompleted())
                <a href="{{ route('guru.pelatihan.show', $registration->id) }}" class="flex items-center justify-center gap-3 w-full bg-gradient-to-r from-primary-dim to-primary-container text-on-primary-container font-bold py-4 rounded-xl shadow-lg shadow-primary/10 active:scale-95 transition-transform mb-3">
                    <i class="fa-solid fa-play"></i> Mulai Pelatihan
                </a>
            @else
                <div class="w-full text-center bg-yellow-500/10 text-yellow-600 dark:text-yellow-400 font-bold py-4 rounded-xl border border-yellow-500/20 mb-3">
                    <i class="fa-solid fa-clock mr-1"></i> Menunggu Aktivasi ({{ $registration->status }})
                </div>
            @endif

            <div class="grid grid-cols-1 gap-3">
                @php
                    $isPending = strtolower($registration->status) === 'pending';
                    $showForum = !$isPending;
                @endphp

                {{-- WhatsApp Group --}}
                @if($training->whatsapp_link)
                     <a href="{{ $training->whatsapp_link }}" target="_blank" class="flex items-center justify-center gap-2 bg-blue-500/10 hover:bg-blue-500/20 text-blue-600 dark:text-blue-400 border border-blue-500/20 py-4 rounded-xl font-bold text-sm transition-colors">
                        <i class="fa-brands fa-telegram text-lg"></i> Komunitas Telegram
                    </a>
                @endif

                {{-- Forum Diskusi --}}
                @if($showForum = 'a')
                <a href="{{ route('guru.pelatihan.chat', $training->id) }}" class="flex items-center justify-center gap-2 bg-blue-500/10 hover:bg-blue-500/20 text-blue-600 dark:text-blue-400 border border-blue-500/20 py-3 rounded-xl font-bold text-sm transition-colors {{ $training->whatsapp_link ? '' : 'col-span-2' }}">
                    <i class="fa-solid fa-comments text-lg"></i> Forum Diskusi
                </a>
                @endif
            </div>
        @endif
    </section>
@endsection
