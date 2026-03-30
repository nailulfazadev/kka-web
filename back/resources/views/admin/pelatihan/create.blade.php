@extends('layouts.admin')
@section('page-title', 'Buat Pelatihan')
@section('content')
<form method="POST" action="{{ route('admin.pelatihan.store') }}" enctype="multipart/form-data" class="max-w-2xl space-y-4">
    @csrf
    <div><label class="text-sm font-medium text-on-surface-variant mb-1 block">Judul Pelatihan</label><input type="text" name="title" value="{{ old('title') }}" class="w-full bg-surface-container-highest border-none rounded-xl py-3 px-4 text-on-surface focus:ring-1 focus:ring-primary/40" required>@error('title')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror</div>
    <div><label class="text-sm font-medium text-on-surface-variant mb-1 block">Deskripsi</label><textarea name="description" rows="4" class="w-full bg-surface-container-highest border-none rounded-xl py-3 px-4 text-on-surface focus:ring-1 focus:ring-primary/40">{{ old('description') }}</textarea></div>
    <div class="grid grid-cols-2 gap-4">
        <div><label class="text-sm font-medium text-on-surface-variant mb-1 block">Kategori Harga</label><select name="pricing_type" class="w-full bg-surface-container-highest border-none rounded-xl py-3 px-4 text-on-surface focus:ring-1 focus:ring-primary/40"><option value="free">Gratis</option><option value="berbayar">Berbayar</option><option value="donasi">Donasi</option></select></div>
        <div><label class="text-sm font-medium text-on-surface-variant mb-1 block">Status</label><select name="status" class="w-full bg-surface-container-highest border-none rounded-xl py-3 px-4 text-on-surface focus:ring-1 focus:ring-primary/40"><option value="draft">Draft</option><option value="aktif">Aktif</option><option value="mendatang">Mendatang</option><option value="selesai">Selesai</option></select></div>
    </div>
    <div class="grid grid-cols-2 gap-4">
        <div><label class="text-sm font-medium text-on-surface-variant mb-1 block">Harga (Berbayar)</label><input type="number" name="price" value="{{ old('price', 0) }}" class="w-full bg-surface-container-highest border-none rounded-xl py-3 px-4 text-on-surface focus:ring-1 focus:ring-primary/40" min="0"></div>
        <div><label class="text-sm font-medium text-on-surface-variant mb-1 block">Harga Fasilitas (Donasi)</label><input type="number" name="facility_price" value="{{ old('facility_price', 0) }}" class="w-full bg-surface-container-highest border-none rounded-xl py-3 px-4 text-on-surface focus:ring-1 focus:ring-primary/40" min="0"></div>
    </div>
    <div class="grid grid-cols-2 gap-4">
        <div><label class="text-sm font-medium text-on-surface-variant mb-1 block">Tanggal Mulai</label><input type="date" name="start_date" value="{{ old('start_date') }}" class="w-full bg-surface-container-highest border-none rounded-xl py-3 px-4 text-on-surface focus:ring-1 focus:ring-primary/40" required></div>
        <div><label class="text-sm font-medium text-on-surface-variant mb-1 block">Tanggal Selesai</label><input type="date" name="end_date" value="{{ old('end_date') }}" class="w-full bg-surface-container-highest border-none rounded-xl py-3 px-4 text-on-surface focus:ring-1 focus:ring-primary/40" required></div>
    </div>
    <div><label class="text-sm font-medium text-on-surface-variant mb-1 block">Min. Kehadiran (%)</label><input type="number" name="min_attendance_percent" value="{{ old('min_attendance_percent', 80) }}" class="w-full bg-surface-container-highest border-none rounded-xl py-3 px-4 text-on-surface focus:ring-1 focus:ring-primary/40" min="0" max="100" required></div>
    <div><label class="text-sm font-medium text-on-surface-variant mb-1 block">Google Drive Link</label><input type="url" name="google_drive_link" value="{{ old('google_drive_link') }}" class="w-full bg-surface-container-highest border-none rounded-xl py-3 px-4 text-on-surface focus:ring-1 focus:ring-primary/40" placeholder="https://drive.google.com/..."></div>
    <div><label class="text-sm font-medium text-on-surface-variant mb-1 block">Link Grup WhatsApp</label><input type="url" name="whatsapp_link" value="{{ old('whatsapp_link') }}" class="w-full bg-surface-container-highest border-none rounded-xl py-3 px-4 text-on-surface focus:ring-1 focus:ring-primary/40" placeholder="https://chat.whatsapp.com/..."></div>
    <div><label class="text-sm font-medium text-on-surface-variant mb-1 block">Thumbnail</label><input type="file" name="thumbnail" accept="image/*" class="w-full bg-surface-container-highest border-none rounded-xl py-3 px-4 text-on-surface"></div>
    <div class="glass-card border border-primary/20 bg-primary/5 rounded-xl p-4 flex items-center justify-between">
        <div>
            <h4 class="font-bold text-on-surface text-sm">Bagikan Fasilitas</h4>
            <p class="text-[10px] text-on-surface-variant">Jika diaktifkan, peserta bisa mengunduh sertifikat, undangan, rekap presensi, dan materi Google Drive.</p>
        </div>
        <label class="relative inline-flex items-center cursor-pointer">
            <input type="checkbox" name="facilities_released" value="1" class="sr-only peer">
            <div class="w-11 h-6 bg-surface-container-highest peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
        </label>
    </div>
    <button type="submit" class="w-full bg-gradient-to-r from-primary-dim to-primary-container text-on-primary-container font-bold py-4 rounded-xl active:scale-95 transition-transform">Simpan Pelatihan</button>
</form>
@endsection
