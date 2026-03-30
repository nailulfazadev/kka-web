@extends('layouts.admin')
@section('page-title', 'Jadwal — ' . $training->title)
@section('content')
<div class="flex items-center justify-between mb-4 mt-6">
    <h3 class="text-lg font-bold text-on-surface">Kelola Jadwal & Materi Sesi</h3>
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.pelatihan.rekap-peserta', $training->id) }}" class="text-xs bg-red-500/10 text-red-500 border border-red-500/20 px-3 py-1.5 rounded-lg hover:bg-red-500/20 font-bold flex items-center gap-1 transition-colors">
            <i class="fa-solid fa-users-viewfinder"></i> Rekap Peserta
        </a>
        <a href="{{ route('admin.pelatihan.edit', $training->id) }}" class="text-xs text-primary font-medium hover:underline flex items-center gap-1 bg-surface-container-highest px-3 py-1.5 rounded-lg">
            <i class="fa-solid fa-arrow-left text-sm"></i> Kembali
        </a>
    </div>
</div>

<form method="POST" action="{{ route('admin.jadwal.store', $training->id) }}" class="glass-card rounded-2xl border border-white/5 p-5 mb-8 space-y-4 shadow-sm relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-primary/5 to-transparent pointer-events-none"></div>
    @csrf
    <h4 class="text-sm font-bold text-primary mb-2 relative z-10">Tambah Sesi Baru</h4>
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-3 relative z-10">
        <input type="number" name="session_number" placeholder="Sesi # (e.g. 1)" class="bg-surface-container-highest border-none rounded-xl py-3 px-4 text-on-surface text-sm col-span-1 focus:ring-1 focus:ring-primary/40" required>
        <input type="text" name="title" placeholder="Judul Pembahasan" class="bg-surface-container-highest border-none rounded-xl py-3 px-4 text-on-surface text-sm lg:col-span-2 focus:ring-1 focus:ring-primary/40">
        <input type="date" name="session_date" class="bg-surface-container-highest border-none rounded-xl py-3 px-4 text-on-surface text-sm lg:col-span-2 focus:ring-1 focus:ring-primary/40" required>
        
        <input type="time" name="start_time" class="bg-surface-container-highest border-none rounded-xl py-3 px-4 text-on-surface text-sm focus:ring-1 focus:ring-primary/40" required>
        <input type="time" name="end_time" class="bg-surface-container-highest border-none rounded-xl py-3 px-4 text-on-surface text-sm focus:ring-1 focus:ring-primary/40" required>
        <input type="url" name="zoom_link" placeholder="Link Zoom" class="bg-surface-container-highest border-none rounded-xl py-3 px-4 text-on-surface text-sm lg:col-span-1 focus:ring-1 focus:ring-primary/40">
        <input type="url" name="recording_link" placeholder="YouTube Live / Rekaman" class="bg-surface-container-highest border-none rounded-xl py-3 px-4 text-on-surface text-sm lg:col-span-2 focus:ring-1 focus:ring-primary/40">
    </div>

    <div class="relative z-10 p-4 bg-surface-container-highest/20 rounded-xl border border-white/5">
        <label class="text-xs font-bold text-on-surface-variant block mb-2">Materi / Fasilitas Pendukung (Gdrive, PDF, dll)</label>
        <div id="createFacilitiesContainer" class="space-y-2">
            <input type="url" name="facilities[]" class="w-full bg-surface-container-highest border-none rounded-xl py-3 px-4 text-on-surface text-sm focus:ring-1 focus:ring-primary/40" placeholder="https://...">
        </div>
        <button type="button" onclick="addFacility('createFacilitiesContainer')" class="mt-3 text-[11px] text-primary font-bold hover:underline bg-primary/10 px-3 py-1.5 rounded-lg">+ Tambah Link Lainnya</button>
    </div>

    <button type="submit" class="w-full bg-primary text-on-primary px-4 py-3.5 rounded-xl text-sm font-bold mt-2 hover:bg-primary/90 transition-colors shadow-lg shadow-primary/20 relative z-10">Simpan Sesi Baru</button>
</form>

<div class="space-y-4">
    @foreach($training->sessions as $session)
    <div x-data="{ expanded: false }" class="glass-card rounded-2xl border border-white/5 p-5 shadow-sm hover:border-white/10 transition-colors">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="bg-primary/20 text-primary text-[10px] font-bold px-2 py-0.5 rounded-md">Sesi {{ $session->session_number }}</span>
                    <h4 class="text-sm font-bold text-on-surface">{{ $session->title ?? 'Tanpa Judul' }}</h4>
                </div>
                <div class="flex items-center gap-3 text-xs text-on-surface-variant mt-2 font-medium">
                    <span class="flex items-center gap-1"><i class="fa-regular fa-calendar text-[14px]"></i> {{ $session->session_date->format('d M Y') }}</span>
                    <span class="flex items-center gap-1"><i class="fa-regular fa-clock text-[14px]"></i> {{ \Carbon\Carbon::parse($session->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($session->end_time)->format('H:i') }}</span>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <button @click="expanded = !expanded" class="text-primary text-xs font-bold hover:underline flex items-center gap-1 bg-primary/10 px-3 py-1.5 rounded-lg transition-colors">
                    <i class="text-[14px]" :class="expanded ? 'fa-solid fa-chevron-up' : 'fa-solid fa-pen-to-square'"></i>
                    <span x-text="expanded ? 'Tutup' : 'Edit Sesi'"></span>
                </button>
                <form method="POST" action="{{ route('admin.jadwal.destroy', $session->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus sesi ini beserta semua materialnya?')">
                    @csrf @method('DELETE')
                    <button class="text-error text-xs font-bold hover:underline flex items-center gap-1 bg-error/10 px-3 py-1.5 rounded-lg transition-colors">
                        <i class="fa-solid fa-trash text-[14px]"></i>
                    </button>
                </form>
            </div>
        </div>
        
        <div x-show="expanded" x-collapse x-cloak class="mt-5 pt-5 border-t border-white/5">
            <form method="POST" action="{{ route('admin.jadwal.update', $session->id) }}" class="space-y-4">
                @csrf @method('PUT')
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
                    <input type="text" name="title" value="{{ $session->title }}" placeholder="Judul Pembahasan" class="bg-surface-container-highest border-none rounded-xl py-3 px-4 text-on-surface text-sm lg:col-span-2 focus:ring-1 focus:ring-primary/40">
                    <input type="date" name="session_date" value="{{ $session->session_date->format('Y-m-d') }}" class="bg-surface-container-highest border-none rounded-xl py-3 px-4 text-on-surface text-sm focus:ring-1 focus:ring-primary/40" required>
                    <div class="grid grid-cols-2 gap-2">
                        <input type="time" name="start_time" value="{{ \Carbon\Carbon::parse($session->start_time)->format('H:i') }}" class="bg-surface-container-highest border-none rounded-xl py-3 px-4 text-on-surface text-sm focus:ring-1 focus:ring-primary/40" required>
                        <input type="time" name="end_time" value="{{ \Carbon\Carbon::parse($session->end_time)->format('H:i') }}" class="bg-surface-container-highest border-none rounded-xl py-3 px-4 text-on-surface text-sm focus:ring-1 focus:ring-primary/40" required>
                    </div>
                    
                    <input type="url" name="zoom_link" value="{{ $session->zoom_link }}" placeholder="Link Zoom" class="bg-surface-container-highest border-none rounded-xl py-3 px-4 text-on-surface text-sm lg:col-span-2 focus:ring-1 focus:ring-primary/40">
                    <input type="url" name="recording_link" value="{{ $session->recording_link }}" placeholder="YouTube Live / Rekaman" class="bg-surface-container-highest border-none rounded-xl py-3 px-4 text-on-surface text-sm lg:col-span-2 focus:ring-1 focus:ring-primary/40">
                </div>
                
                <div class="bg-surface-container-highest/30 p-4 rounded-xl border border-white/5">
                    <label class="text-xs font-bold text-on-surface-variant block mb-2">Materi / Fasilitas Sesi Ini</label>
                    <div id="editFacilitiesContainer-{{$session->id}}" class="space-y-2">
                        @php $fs = is_array($session->facilities) ? $session->facilities : (json_decode($session->facilities, true) ?? []); @endphp
                        @forelse($fs as $f)
                            <input type="url" name="facilities[]" value="{{ $f }}" class="w-full bg-surface-container-highest border-none rounded-xl py-3 px-4 text-on-surface text-sm focus:ring-1 focus:ring-primary/40" placeholder="https://...">
                        @empty
                            <input type="url" name="facilities[]" class="w-full bg-surface-container-highest border-none rounded-xl py-3 px-4 text-on-surface text-sm focus:ring-1 focus:ring-primary/40" placeholder="https://...">
                        @endforelse
                    </div>
                    <button type="button" onclick="addFacility('editFacilitiesContainer-{{$session->id}}')" class="mt-3 text-[11px] text-primary font-bold hover:underline bg-primary/10 px-3 py-1.5 rounded-lg">+ Tambah Link Lainnya</button>
                </div>

                <div class="flex justify-end gap-3 mt-4 pt-2">
                    <button type="button" @click="expanded = false" class="px-5 py-2.5 rounded-xl text-xs font-bold text-on-surface bg-surface-container border border-white/5 hover:bg-surface-container-highest transition-colors">Batal</button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-bold text-on-primary bg-primary shadow-md shadow-primary/20 hover:bg-primary/90 transition-colors">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
    @endforeach
</div>

<script>
    function addFacility(containerId) {
        const container = document.getElementById(containerId);
        const input = document.createElement('input');
        input.type = 'url';
        input.name = 'facilities[]';
        input.className = 'w-full bg-surface-container-highest border-none rounded-xl py-3 px-4 text-on-surface text-sm mt-2 focus:ring-1 focus:ring-primary/40';
        input.placeholder = 'https://...';
        container.appendChild(input);
    }
</script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<style>
    [x-cloak] { display: none !important; }
</style>
@endsection
