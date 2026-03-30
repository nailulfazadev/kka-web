@extends('layouts.admin')
@section('page-title', 'Edit Pelatihan')
@section('content')
<form method="POST" action="{{ route('admin.pelatihan.update', $training) }}" enctype="multipart/form-data" class="max-w-2xl space-y-4">
    @csrf @method('PUT')
    <div><label class="text-sm font-medium text-on-surface-variant mb-1 block">Judul</label><input type="text" name="title" value="{{ old('title', $training->title) }}" class="w-full bg-surface-container-highest border-none rounded-xl py-3 px-4 text-on-surface focus:ring-1 focus:ring-primary/40" required></div>
    <div><label class="text-sm font-medium text-on-surface-variant mb-1 block">Deskripsi</label><textarea name="description" rows="4" class="w-full bg-surface-container-highest border-none rounded-xl py-3 px-4 text-on-surface focus:ring-1 focus:ring-primary/40">{{ old('description', $training->description) }}</textarea></div>
    <div class="grid grid-cols-2 gap-4">
        <div><label class="text-sm font-medium text-on-surface-variant mb-1 block">Kategori</label><select name="pricing_type" class="w-full bg-surface-container-highest border-none rounded-xl py-3 px-4 text-on-surface"><option value="free" @selected($training->pricing_type==='free')>Gratis</option><option value="berbayar" @selected($training->pricing_type==='berbayar')>Berbayar</option><option value="donasi" @selected($training->pricing_type==='donasi')>Donasi</option></select></div>
        <div><label class="text-sm font-medium text-on-surface-variant mb-1 block">Status</label><select name="status" class="w-full bg-surface-container-highest border-none rounded-xl py-3 px-4 text-on-surface"><option value="draft" @selected($training->status==='draft')>Draft</option><option value="aktif" @selected($training->status==='aktif')>Aktif</option><option value="mendatang" @selected($training->status==='mendatang')>Mendatang</option><option value="selesai" @selected($training->status==='selesai')>Selesai</option></select></div>
    </div>
    <div class="grid grid-cols-2 gap-4">
        <div><label class="text-sm font-medium text-on-surface-variant mb-1 block">Harga</label><input type="number" name="price" value="{{ old('price', $training->price) }}" class="w-full bg-surface-container-highest border-none rounded-xl py-3 px-4 text-on-surface"></div>
        <div><label class="text-sm font-medium text-on-surface-variant mb-1 block">Harga Fasilitas</label><input type="number" name="facility_price" value="{{ old('facility_price', $training->facility_price) }}" class="w-full bg-surface-container-highest border-none rounded-xl py-3 px-4 text-on-surface"></div>
    </div>
    <div class="grid grid-cols-2 gap-4">
        <div><label class="text-sm font-medium text-on-surface-variant mb-1 block">Mulai</label><input type="date" name="start_date" value="{{ old('start_date', $training->start_date->format('Y-m-d')) }}" class="w-full bg-surface-container-highest border-none rounded-xl py-3 px-4 text-on-surface" required></div>
        <div><label class="text-sm font-medium text-on-surface-variant mb-1 block">Selesai</label><input type="date" name="end_date" value="{{ old('end_date', $training->end_date->format('Y-m-d')) }}" class="w-full bg-surface-container-highest border-none rounded-xl py-3 px-4 text-on-surface" required></div>
    </div>
    <div><label class="text-sm font-medium text-on-surface-variant mb-1 block">Min. Kehadiran (%)</label><input type="number" name="min_attendance_percent" value="{{ old('min_attendance_percent', $training->min_attendance_percent) }}" class="w-full bg-surface-container-highest border-none rounded-xl py-3 px-4 text-on-surface" required></div>
    <div><label class="text-sm font-medium text-on-surface-variant mb-1 block">Google Drive Link</label><input type="url" name="google_drive_link" value="{{ old('google_drive_link', $training->google_drive_link) }}" class="w-full bg-surface-container-highest border-none rounded-xl py-3 px-4 text-on-surface focus:ring-1 focus:ring-primary/40" placeholder="https://drive.google.com/..."></div>
    <div><label class="text-sm font-medium text-on-surface-variant mb-1 block">Link Grup WhatsApp</label><input type="url" name="whatsapp_link" value="{{ old('whatsapp_link', $training->whatsapp_link) }}" class="w-full bg-surface-container-highest border-none rounded-xl py-3 px-4 text-on-surface focus:ring-1 focus:ring-primary/40" placeholder="https://chat.whatsapp.com/..."></div>
    <div>
        <label class="text-sm font-medium text-on-surface-variant mb-1 block">Thumbnail</label>
        @if($training->thumbnail)
            <div class="mb-3">
                <img src="{{ asset('storage/' . $training->thumbnail) }}" alt="Thumbnail Preview" class="w-32 h-32 object-cover rounded-xl border border-white/10 shadow-md">
            </div>
        @endif
        <input type="file" name="thumbnail" accept="image/*" class="w-full bg-surface-container-highest border-none rounded-xl py-3 px-4 text-on-surface">
        <p class="text-[10px] text-on-surface-variant mt-1">Biarkan kosong jika tidak ingin mengganti thumbnail saat ini.</p>
    </div>
    <div class="glass-card border border-primary/20 bg-primary/5 rounded-xl p-4 flex items-center justify-between">
        <div>
            <h4 class="font-bold text-on-surface text-sm">Bagikan Fasilitas</h4>
            <p class="text-[10px] text-on-surface-variant">Jika diaktifkan, peserta bisa mengunduh sertifikat, undangan, rekap presensi, dan materi Google Drive.</p>
        </div>
        <label class="relative inline-flex items-center cursor-pointer">
            <input type="checkbox" name="facilities_released" value="1" class="sr-only peer" @checked($training->facilities_released)>
            <div class="w-11 h-6 bg-surface-container-highest peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
        </label>
    </div>
    <div class="flex gap-4 items-center">
        <button type="submit" class="flex-1 bg-gradient-to-r from-primary-dim to-primary-container text-on-primary-container font-bold py-4 rounded-xl active:scale-95 transition-transform">Simpan Perubahan</button>
        <a href="{{ route('admin.jadwal.index', $training->id) }}" class="px-6 bg-surface-container-highest border border-primary/30 text-primary font-bold py-4 rounded-xl whitespace-nowrap active:scale-95 transition-transform">➡️ Kelola Sesi & Materi</a>
    </div>
</form>
@endsection
