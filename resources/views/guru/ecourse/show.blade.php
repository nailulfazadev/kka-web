@extends('layouts.app')
@section('title', $training->title . ' - Ruang Kelas E-Course')

@section('content')
@php
function getYoutubeEmbedUrl($url) {
    if (!$url) return null;

    // Ambil ID video dari semua format YouTube (watch, short, live, embed)
    preg_match('/(?:youtu\.be\/|youtube\.com\/(?:watch\?v=|embed\/|live\/))([a-zA-Z0-9_-]+)/', $url, $matches);

    if (!isset($matches[1])) return null;

    $videoId = $matches[1];

    // Tambahkan parameter biar lebih optimal
    return "https://www.youtube.com/embed/{$videoId}?rel=0&modestbranding=1";
}

$activeSession = $training->sessions->where('id', $activeSessionId)->first();
$embedUrl = $activeSession ? getYoutubeEmbedUrl($activeSession->recording_link) : null;
@endphp

{{-- Main Container (No special height hacks, just standard scrolling content below the header) --}}
<div class="pt-16 pb-24">
    
    {{-- Video Player Container --}}
    <div class="bg-black w-full aspect-video relative flex-shrink-0 shadow-md">
        @if($embedUrl)
            <iframe 
    src="{{ $embedUrl }}" 
    class="w-full h-full absolute inset-0"
    frameborder="0"
    referrerpolicy="strict-origin-when-cross-origin"
    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
    allowfullscreen>
</iframe>
        @else
            <div class="w-full h-full flex flex-col items-center justify-center text-white/50 absolute inset-0 bg-surface-container-highest">
                <i class="fa-solid fa-video-slash text-4xl mb-3 opacity-30"></i>
                <p class="font-medium text-xs">Video tidak tersedia</p>
            </div>
        @endif
    </div>

    {{-- Content Area --}}
    <div class="p-5">
        @if($activeSession)
            {{-- Header info --}}
            <div class="mb-5">
                <span class="inline-flex items-center gap-1.5 bg-blue-500/10 text-blue-400 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-widest mb-2">
                    Topik {{ $activeSession->session_number }}
                </span>
                <h1 class="text-xl font-bold text-on-surface leading-snug">{{ $activeSession->title ?? 'Materi Topik ' . $activeSession->session_number }}</h1>
            </div>

            {{-- Action Cards --}}
            <div class="flex flex-col gap-3 mb-6">
                {{-- Download Material --}}
                @php
                    $materials = [];
                    if ($activeSession->material_link) {
                        $materials[] = $activeSession->material_link;
                    }
                    $facs = is_array($activeSession->facilities) ? $activeSession->facilities : (json_decode($activeSession->facilities, true) ?? []);
                    foreach($facs as $fac) {
                        if (!empty($fac) && !in_array($fac, $materials)) {
                            $materials[] = $fac;
                        }
                    }
                @endphp

                @foreach($materials as $idx => $mat)
                <a href="{{ $mat }}" target="_blank" class="glass-card p-4 rounded-2xl border border-white/5 hover:bg-white/5 transition-colors flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center flex-shrink-0 text-primary">
                        <i class="fa-regular fa-file-pdf text-lg"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-on-surface text-sm">Unduh Modul / Materi {{ count($materials) > 1 ? ($idx + 1) : '' }}</h4>
                        <p class="text-[11px] text-on-surface-variant">Baca materi pendukung</p>
                    </div>
                </a>
                @endforeach

                {{-- Mark as Done --}}
                @php
                    $isFinished = $activeSession->attendances->where('user_id', auth()->id())->where('status', 'hadir')->isNotEmpty();
                @endphp

                @if($isFinished)
                    <div class="glass-card p-4 rounded-2xl border border-green-500/20 bg-green-500/5 flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-green-500/20 flex items-center justify-center flex-shrink-0 text-green-500">
                            <i class="fa-solid fa-check text-lg"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-green-500 text-sm">Topik Selesai</h4>
                            <p class="text-[11px] text-green-400/80">Anda telah menyelesaikan bagian ini.</p>
                        </div>
                    </div>
                @else
                    <form action="{{ route('guru.pelatihan.topic.done', [$registration->id, $activeSession->id]) }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit" class="w-full glass-card p-4 rounded-2xl border border-blue-500/30 bg-blue-500/10 active:bg-blue-500/20 transition-colors flex items-center gap-4 text-left">
                            <div class="w-10 h-10 rounded-xl bg-blue-500/20 flex items-center justify-center flex-shrink-0 text-blue-400">
                                <i class="fa-solid fa-check-double text-lg"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-blue-400 text-sm">Tandai Selesai & Lanjut</h4>
                                <p class="text-[11px] text-blue-300">Selesaikan materi ini</p>
                            </div>
                        </button>
                    </form>
                @endif

                {{-- AI Tutor Link --}}
                <a href="{{ route('guru.pelatihan.chat', $training->id) }}" class="glass-card p-4 rounded-2xl border border-indigo-500/20 bg-indigo-500/5 hover:bg-indigo-500/10 transition-colors flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-indigo-500/20 flex items-center justify-center flex-shrink-0 text-indigo-400">
                        <i class="fa-solid fa-robot text-lg"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-indigo-400 text-sm">Tanya Asisten AI Pribadi</h4>
                        <p class="text-[11px] text-indigo-300">Diskusikan materi E-Course secara interaktif & privat</p>
                    </div>
                </a>
            </div>

            {{-- Course Progress & Outline --}}
            <div class="mt-6">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="font-bold text-base text-on-surface">Daftar Topik</h3>
                    <span class="text-xs font-bold text-primary">{{ $attendancePercent }}% Selesai</span>
                </div>
                
                {{-- Progress bar --}}
                <div class="w-full h-1.5 bg-surface-container-highest rounded-full overflow-hidden mb-4">
                    <div class="h-full bg-primary transition-all duration-500" style="width: {{ $attendancePercent }}%"></div>
                </div>

                {{-- Course Completion Banner --}}
                @if($attendancePercent == 100)
                <div class="mb-4 glass-card p-5 rounded-2xl border border-green-500/30 bg-green-500/10 flex flex-col items-center text-center">
                    <div class="w-12 h-12 bg-green-500/20 rounded-full flex items-center justify-center text-green-500 mb-2">
                        <i class="fa-solid fa-trophy text-xl"></i>
                    </div>
                    <h3 class="font-bold text-lg text-white mb-1">E-Course Selesai! 🎉</h3>
                    <p class="text-xs text-on-surface-variant mb-4">Selamat! Anda telah menyelesaikan semua materi.</p>
                    <a href="{{ route('guru.sertifikat.index') }}" class="w-full bg-green-500 active:bg-green-600 text-white font-bold py-3 px-4 rounded-xl text-sm flex items-center justify-center gap-2 shadow-[0_0_15px_rgba(34,197,94,0.4)]">
                        <i class="fa-solid fa-award"></i> Lihat Sertifikat Anda
                    </a>
                </div>
                @endif

                {{-- List --}}
                <div class="space-y-2">
                    @foreach($training->sessions as $session)
                        @php
                            $isActive = $activeSessionId == $session->id;
                            $isFinishedList = $session->attendances->where('user_id', auth()->id())->where('status', 'hadir')->isNotEmpty();
                        @endphp
                        
                        <a href="{{ route('guru.pelatihan.show', ['id' => $registration->id, 'topic' => $session->id]) }}" 
                           class="flex items-center gap-3 p-3 rounded-xl border {{ $isActive ? 'border-primary/30 bg-primary/5' : 'border-white/5 bg-surface-container-low hover:bg-surface-container' }} transition-colors">
                            
                            {{-- Icon Status --}}
                            <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 {{ $isFinishedList ? 'bg-green-500/20 text-green-500' : ($isActive ? 'bg-primary/20 text-primary' : 'bg-surface-container-high text-on-surface-variant') }}">
                                @if($isFinishedList)
                                    <i class="fa-solid fa-check text-[10px]"></i>
                                @elseif($isActive)
                                    <i class="fa-solid fa-play text-[9px] ml-0.5"></i>
                                @else
                                    <span class="text-[10px] font-bold">{{ $session->session_number }}</span>
                                @endif
                            </div>
                            
                            {{-- Title --}}
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm truncate {{ $isActive ? 'font-bold text-primary' : ($isFinishedList ? 'font-medium text-on-surface-variant' : 'font-medium text-on-surface') }}">
                                    {{ $session->title ?? 'Topik ' . $session->session_number }}
                                </h4>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <span class="text-[9px] {{ $isActive ? 'text-primary/70' : 'text-on-surface-variant' }} flex items-center gap-1">
                                        <i class="fa-solid fa-play-circle text-[8px]"></i> Video
                                    </span>
                                    @php
                                        $hasMaterial = $session->material_link || (is_array($session->facilities) && count($session->facilities) > 0) || (is_string($session->facilities) && count(json_decode($session->facilities, true) ?? []) > 0);
                                    @endphp
                                    @if($hasMaterial)
                                    <span class="text-[9px] {{ $isActive ? 'text-primary/70' : 'text-on-surface-variant' }} flex items-center gap-1">
                                        <i class="fa-solid fa-file-pdf text-[8px]"></i> Modul
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
