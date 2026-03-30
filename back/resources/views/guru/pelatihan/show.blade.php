@extends('layouts.app')
@section('title', $training->title)
@section('content')
<section class="px-6 pt-6">
    <a href="{{ route('guru.pelatihan.index') }}" class="inline-flex items-center gap-1 text-on-surface-variant text-sm mb-4"><i class="fa-solid fa-arrow-left text-sm"></i> Kembali</a>
    <h2 class="text-xl font-bold text-on-surface mb-4">{{ $training->title }}</h2>
    <div class="glass-card rounded-2xl border border-white/5 p-4 mb-6">
        <div class="flex items-center justify-between mb-2">
            <span class="text-sm text-on-surface-variant">Kehadiran: {{ $attendancePercent }}%</span>
            <span class="text-xs text-on-surface-variant">Min. {{ $training->min_attendance_percent }}%</span>
        </div>
        <div class="h-3 rounded-full bg-surface-container-highest overflow-hidden">
            <div class="h-full rounded-full bg-gradient-to-r from-primary-dim to-primary" style="width: {{ $attendancePercent }}%"></div>
        </div>
    </div>

    @php
        $todaySessions = $training->sessions->filter(fn($s) => $s->session_date->isToday());
        $otherSessions = $training->sessions->filter(fn($s) => !$s->session_date->isToday());
    @endphp

    @if($todaySessions->isNotEmpty())
    <div class="flex items-center gap-3 mb-4 mt-8">
        <span class="relative flex h-3 w-3">
          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
          <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
        </span>
        <h3 class="text-lg font-bold text-on-surface">Sedang Berlangsung / Hari Ini</h3>
    </div>
    
    <div class="space-y-4 mb-8">
        @foreach($todaySessions as $session)
        <div class="glass-card rounded-[2rem] border border-primary/30 p-6 bg-gradient-to-br from-primary/10 to-transparent relative overflow-hidden">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-primary/20 rounded-full blur-2xl"></div>
            
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-base font-bold text-on-surface">{{ $session->title ?? 'Sesi ' . $session->session_number }}</p>
                    @php $att = $session->attendances->first(); @endphp
                    @if($att && $att->status === 'hadir')
                        <span class="text-xs bg-green-500/20 text-green-400 px-3 py-1 rounded-full font-bold">✓ Hadir</span>
                    @else
                        <form method="POST" action="{{ route('guru.presensi.checkin', $session->id) }}">
                            @csrf
                            <button class="px-4 py-2 bg-gradient-to-r from-primary-dim to-primary-container text-on-primary-container rounded-xl text-xs font-bold active:scale-95 transition-transform shadow-lg shadow-primary/20">Presensi Sekarang</button>
                        </form>
                    @endif
                </div>
                <p class="text-sm text-on-surface-variant mb-5">
                    <i class="fa-regular fa-clock text-sm align-middle"></i> {{ $session->start_time }} - {{ $session->end_time }} WIB
                </p>
                
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    @if($session->zoom_link)
                    <a href="{{ $session->zoom_link }}" target="_blank" class="flex items-center justify-center gap-2 bg-blue-500/20 hover:bg-blue-500/30 text-blue-400 border border-blue-500/30 py-3 rounded-xl text-sm font-bold transition-colors">
                        <i class="fa-solid fa-video text-base"></i> Gabung Zoom
                    </a>
                    @else
                    <div class="flex items-center justify-center gap-2 bg-surface-container-highest text-on-surface-variant border border-white/5 py-3 rounded-xl text-sm font-bold opacity-50 cursor-not-allowed">
                        <i class="fa-solid fa-video-slash text-base"></i> Belum Ada Zoom
                    </div>
                    @endif

                    @if($session->recording_link)
                    <a href="{{ $session->recording_link }}" target="_blank" class="flex items-center justify-center gap-2 bg-red-500/20 hover:bg-red-500/30 text-red-400 border border-red-500/30 py-3 rounded-xl text-sm font-bold transition-colors">
                        <i class="fa-solid fa-play text-base"></i> Tonton Rekaman
                    </a>
                    @else
                    <div class="flex items-center justify-center gap-2 bg-surface-container-highest text-on-surface-variant border border-white/5 py-3 rounded-xl text-sm font-bold opacity-50 cursor-not-allowed">
                        <i class="fa-solid fa-play text-base opacity-50"></i> Belum Ada Rekaman
                    </div>
                    @endif

                    @php $facs = is_array($session->facilities) ? $session->facilities : (json_decode($session->facilities, true) ?? []); @endphp
                    @forelse($facs as $idx => $fac)
                    <a href="{{ $fac }}" target="_blank" class="flex items-center justify-center gap-2 bg-tertiary/20 hover:bg-tertiary/30 text-tertiary border border-tertiary/30 py-3 rounded-xl text-sm font-bold transition-colors">
                        <i class="fa-solid fa-file-lines text-base"></i> Materi {{ count($facs) > 1 ? $idx + 1 : '' }}
                    </a>
                    @empty
                    <div class="flex items-center justify-center gap-2 bg-surface-container-highest text-on-surface-variant border border-white/5 py-3 rounded-xl text-sm font-bold opacity-50 cursor-not-allowed">
                        <i class="fa-solid fa-ban text-base"></i> Belum Ada Materi
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    @if($otherSessions->isNotEmpty())
    <h3 class="text-lg font-bold text-on-surface mb-3 mt-8">{{ $todaySessions->isNotEmpty() ? 'Jadwal Sesi Lainnya' : 'Jadwal & Presensi' }}</h3>
    <div class="space-y-3 mb-8">
        @foreach($otherSessions as $session)
        <div class="glass-card rounded-2xl border border-white/5 p-4">
            <div class="flex items-center justify-between mb-2">
                <p class="text-sm font-medium text-on-surface">{{ $session->title ?? 'Sesi ' . $session->session_number }}</p>
                @php $att = $session->attendances->first(); @endphp
                @if($att && $att->status === 'hadir')
                    <span class="text-xs text-green-400 font-bold">✓ Hadir</span>
                @elseif($session->session_date->isFuture())
                    <span class="text-[10px] text-outline px-2 py-1 bg-surface-container-highest rounded border border-white/5">Belum Mulai</span>
                @else
                    <form method="POST" action="{{ route('guru.presensi.checkin', $session->id) }}">
                        @csrf
                        <button class="px-3 py-1 bg-primary/10 text-primary rounded-lg text-xs font-bold hover:bg-primary/20">Presensi</button>
                    </form>
                @endif
            </div>
            <p class="text-xs text-on-surface-variant">{{ $session->session_date->format('D, d M Y') }} • {{ $session->start_time }} - {{ $session->end_time }}</p>
            <div class="flex gap-3 mt-3 border-t border-white/5 pt-2">
                @if($session->zoom_link)<a href="{{ $session->zoom_link }}" target="_blank" class="flex items-center gap-1 text-[10px] text-blue-400 hover:text-blue-300"><i class="fa-solid fa-video text-[12px]"></i> Zoom</a>@endif
                @if($session->recording_link)<a href="{{ $session->recording_link }}" target="_blank" class="flex items-center gap-1 text-[10px] text-red-400 hover:text-red-300"><i class="fa-solid fa-play text-[12px]"></i> Rekaman</a>@endif
                @php $facs = is_array($session->facilities) ? $session->facilities : (json_decode($session->facilities, true) ?? []); @endphp
                @foreach($facs as $idx => $fac)
                    <a href="{{ $fac }}" target="_blank" class="flex items-center gap-1 text-[10px] text-tertiary hover:opacity-80"><i class="fa-solid fa-file-lines text-[12px]"></i> Materi {{ count($facs) > 1 ? $idx + 1 : '' }}</a>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
    @endif

    <div class="grid {{ $training->whatsapp_link ? 'grid-cols-2' : 'grid-cols-1' }} gap-3 mt-6 mb-4">
        @if($training->whatsapp_link)
            <a href="{{ $training->whatsapp_link }}" target="_blank" class="flex items-center justify-center gap-2 bg-green-500/10 hover:bg-green-500/20 text-green-600 dark:text-green-400 border border-green-500/20 py-4 rounded-xl font-bold text-sm transition-colors">
                <i class="fa-brands fa-whatsapp text-lg"></i> Grup WhatsApp
            </a>
        @endif
        <a href="{{ route('guru.pelatihan.chat', $training->id) }}" class="flex items-center justify-center gap-2 bg-blue-500/10 hover:bg-blue-500/20 text-blue-600 dark:text-blue-400 border border-blue-500/20 py-4 rounded-xl font-bold text-sm transition-colors">
            <i class="fa-solid fa-comments text-lg"></i> Forum Diskusi
        </a>
    </div>

    {{-- Fasilitas Pelatihan --}}
    @if($training->facilities_released)
    <h3 class="text-lg font-bold text-on-surface mb-3 mt-8">Fasilitas Pelatihan</h3>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-8">
        {{-- Undangan --}}
        <a href="{{ route('guru.pelatihan.undangan', $registration->id) }}" class="flex items-center gap-3 p-4 glass-card rounded-2xl border border-white/5 hover:bg-white/5 transition-colors">
            <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary">
                <i class="fa-solid fa-envelope-open-text"></i>
            </div>
            <div>
                <p class="font-bold text-sm text-on-surface">Unduh Undangan</p>
                <p class="text-[10px] text-on-surface-variant">Surat undangan resmi pelatihan</p>
            </div>
        </a>

        {{-- Sertifikat --}}
        <a href="{{ route('guru.sertifikat.download', $registration->id) }}" class="flex items-center gap-3 p-4 glass-card rounded-2xl border border-white/5 hover:bg-white/5 transition-colors">
            <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary">
                <i class="fa-solid fa-award"></i>
            </div>
            <div>
                <p class="font-bold text-sm text-on-surface">Unduh Sertifikat</p>
                <p class="text-[10px] text-on-surface-variant">Sertifikat kelulusan 32JP</p>
            </div>
        </a>

        {{-- Link Materi Google Drive --}}
        @if($training->google_drive_link)
        <a href="{{ $training->google_drive_link }}" target="_blank" class="flex items-center gap-3 p-4 glass-card rounded-2xl border border-white/5 hover:bg-white/5 transition-colors">
            <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary">
                <i class="fa-brands fa-google-drive"></i>
            </div>
            <div>
                <p class="font-bold text-sm text-on-surface">Link Materi (Drive)</p>
                <p class="text-[10px] text-on-surface-variant">Akses kumpulan materi & laporan</p>
            </div>
        </a>
        @endif

        {{-- Rekap Peserta (Admin Only) --}}
        @if(auth()->user()->isAdmin())
        <a href="{{ route('admin.pelatihan.rekap-peserta', $training->id) }}" class="flex items-center gap-3 p-4 glass-card rounded-2xl border border-red-500/20 bg-red-500/5 hover:bg-red-500/10 transition-colors">
            <div class="w-10 h-10 rounded-full bg-red-500/10 flex items-center justify-center text-red-500">
                <i class="fa-solid fa-users-viewfinder"></i>
            </div>
            <div>
                <p class="font-bold text-sm text-red-500">Rekap Peserta (Admin)</p>
                <p class="text-[10px] text-red-400">PDF Daftar Kehadiran Peserta</p>
            </div>
        </a>
        @endif
    </div>
    @endif
</section>
@endsection
