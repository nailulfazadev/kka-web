@extends('layouts.app')
@section('title', 'Sertifikat Saya')
@section('content')
<section class="px-6 pt-6">
    <h2 class="text-2xl font-bold text-on-surface mb-6">Sertifikat Saya</h2>
    <div class="space-y-4">
        @forelse($registrations as $reg)
        @php $training = $reg->training; $hasTemplate = $training->certificateTemplates->isNotEmpty(); @endphp
        <div class="glass-card rounded-2xl border border-white/5 p-5">
            <h4 class="font-semibold text-on-surface mb-2">{{ $training->title }}</h4>
            <p class="text-xs text-on-surface-variant mb-3">{{ $training->start_date->format('d M') }} - {{ $training->end_date->format('d M Y') }}</p>
            <div class="flex flex-wrap gap-2 mt-4">
                @if($training->facilities_released)
                    @if($training->isDonasi() && !$reg->facility_paid)
                        <span class="text-xs text-yellow-400 self-center">💰 Bayar donasi fasilitas untuk Sertifikat</span>
                    @else
                        <a href="{{ route('guru.sertifikat.download', $reg->id) }}" class="inline-flex items-center gap-2 bg-primary/10 text-primary px-4 py-2 rounded-xl text-xs font-bold hover:bg-primary/20"><i class="fa-solid fa-award"></i> Unduh Sertifikat</a>
                    @endif
                    
                    <a href="{{ route('guru.pelatihan.undangan', $reg->id) }}" class="inline-flex items-center gap-2 bg-blue-500/10 text-blue-400 px-4 py-2 rounded-xl text-xs font-bold hover:bg-blue-500/20"><i class="fa-solid fa-envelope-open-text"></i> Unduh Undangan</a>
                    @if($training->google_drive_link)
                        <a href="{{ $training->google_drive_link }}" target="_blank" class="inline-flex items-center gap-2 bg-green-500/10 text-green-400 px-4 py-2 rounded-xl text-xs font-bold hover:bg-green-500/20"><i class="fa-brands fa-google-drive"></i> Drive Materi</a>
                    @endif
                @else
                    <span class="text-[10px] items-center flex text-outline self-center px-2 py-1 border border-white/5 rounded-lg">Fasilitas Belum Tersedia</span>
                @endif
            </div>
            @if(!$training->facilities_released)
                <p class="mt-3 text-[10px] text-outline">Semua fasilitas (sertifikat, undangan, materi) belum dibagikan oleh admin.</p>
            @endif
        </div>
        @empty
            <div class="text-center py-12"><i class="fa-solid fa-award text-4xl text-outline mb-2"></i><p class="text-on-surface-variant">Belum ada sertifikat.</p></div>
        @endforelse
    </div>
</section>
@endsection
