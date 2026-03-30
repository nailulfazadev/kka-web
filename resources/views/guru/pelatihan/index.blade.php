@extends('layouts.app')
@section('title', 'Pelatihan Saya')
@section('content')
<section class="px-6 pt-6">
    <h2 class="text-2xl font-bold text-on-surface mb-6">Pelatihan Saya</h2>
    <div class="flex gap-3 mb-6">
        <button onclick="showTab('active')" id="tab-active" class="px-4 py-2 rounded-full text-sm font-medium bg-primary text-on-primary">Aktif</button>
        <button onclick="showTab('completed')" id="tab-completed" class="px-4 py-2 rounded-full text-sm font-medium bg-surface-container-highest text-on-surface-variant">Selesai</button>
    </div>
    <div id="panel-active" class="space-y-3">
        @forelse($active as $reg)
            <a href="{{ route('guru.pelatihan.show', $reg->id) }}" class="glass-card rounded-2xl border border-white/5 p-4 flex items-center gap-4 hover:bg-white/5 transition-colors block">
                <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center flex-shrink-0"><i class="fa-solid fa-graduation-cap text-primary"></i></div>
                <div class="flex-1 min-w-0"><h4 class="text-sm font-semibold text-on-surface truncate">{{ $reg->training->title }}</h4><p class="text-xs text-on-surface-variant">{{ $reg->training->pricing_type }} • {{ $reg->training->sessions->count() }} sesi</p></div>
                <i class="fa-solid fa-chevron-right text-outline"></i>
            </a>
        @empty
            <p class="text-center text-on-surface-variant py-8">Tidak ada pelatihan aktif. <a href="{{ url('/pelatihan') }}" class="text-primary font-semibold">Jelajahi</a></p>
        @endforelse
    </div>
    <div id="panel-completed" class="space-y-3 hidden">
        @forelse($completed as $reg)
            <a href="{{ route('guru.pelatihan.show', $reg->id) }}" class="glass-card rounded-2xl border border-white/5 p-4 flex items-center gap-4 block">
                <div class="w-12 h-12 rounded-xl bg-secondary/10 flex items-center justify-center flex-shrink-0"><i class="fa-solid fa-circle-check text-secondary"></i></div>
                <div class="flex-1 min-w-0"><h4 class="text-sm font-semibold text-on-surface truncate">{{ $reg->training->title }}</h4></div>
            </a>
        @empty
            <p class="text-center text-on-surface-variant py-8">Belum ada pelatihan selesai.</p>
        @endforelse
    </div>
</section>
@push('scripts')
<script>
function showTab(tab) {
    document.getElementById('panel-active').classList.toggle('hidden', tab !== 'active');
    document.getElementById('panel-completed').classList.toggle('hidden', tab !== 'completed');
    document.getElementById('tab-active').classList.toggle('bg-primary', tab === 'active');
    document.getElementById('tab-active').classList.toggle('text-on-primary', tab === 'active');
    document.getElementById('tab-active').classList.toggle('bg-surface-container-highest', tab !== 'active');
    document.getElementById('tab-active').classList.toggle('text-on-surface-variant', tab !== 'active');
    document.getElementById('tab-completed').classList.toggle('bg-primary', tab === 'completed');
    document.getElementById('tab-completed').classList.toggle('text-on-primary', tab === 'completed');
    document.getElementById('tab-completed').classList.toggle('bg-surface-container-highest', tab !== 'completed');
    document.getElementById('tab-completed').classList.toggle('text-on-surface-variant', tab !== 'completed');
}
</script>
@endpush
@endsection
