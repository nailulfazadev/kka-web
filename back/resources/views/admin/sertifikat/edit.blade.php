@extends('layouts.admin') @section('page-title', 'Edit Template Sertifikat') @section('content')
<form method="POST" action="{{ route('admin.cert-template.update', $template) }}" enctype="multipart/form-data" class="max-w-2xl space-y-4">
    @csrf @method('PUT')
    <div><label class="text-sm font-medium text-on-surface-variant mb-1 block">Pelatihan</label><select name="training_id" class="w-full bg-surface-container-highest border-none rounded-xl py-3 px-4 text-on-surface" required>@foreach(\App\Models\Training::orderBy('title')->get() as $t)<option value="{{ $t->id }}" @selected($t->id == $template->training_id)>{{ $t->title }}</option>@endforeach</select></div>
    <div><label class="text-sm font-medium text-on-surface-variant mb-1 block">Nama Template</label><input type="text" name="name" value="{{ old('name', $template->name) }}" class="w-full bg-surface-container-highest border-none rounded-xl py-3 px-4 text-on-surface" required></div>
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="text-sm font-medium text-on-surface-variant mb-1 block">Gambar Depan</label>
            @if($template->front_image)
                <img src="{{ asset('storage/' . $template->front_image) }}" class="h-16 object-cover mb-2 rounded border border-white/5">
            @endif
            <input type="file" name="front_image" accept="image/*" class="w-full bg-surface-container-highest border-none rounded-xl py-3 px-4 text-on-surface">
            <span class="text-[10px] text-on-surface-variant">Kosongkan jika tidak diubah</span>
        </div>
        <div>
            <label class="text-sm font-medium text-on-surface-variant mb-1 block">Gambar Belakang</label>
            @if($template->back_image)
                <img src="{{ asset('storage/' . $template->back_image) }}" class="h-16 object-cover mb-2 rounded border border-white/5">
            @endif
            <input type="file" name="back_image" accept="image/*" class="w-full bg-surface-container-highest border-none rounded-xl py-3 px-4 text-on-surface">
            <span class="text-[10px] text-on-surface-variant">Kosongkan jika tidak diubah</span>
        </div>
    </div>
    <h4 class="text-sm font-bold text-on-surface mt-4">Posisi Nama</h4>
    <div class="grid grid-cols-2 gap-4">
        <div><label class="text-xs text-on-surface-variant">X</label><input type="number" name="name_x" value="{{ old('name_x', $template->name_position['x'] ?? 400) }}" class="w-full bg-surface-container-highest border-none rounded-xl py-2 px-3 text-on-surface text-sm" required></div>
        <div><label class="text-xs text-on-surface-variant">Y</label><input type="number" name="name_y" value="{{ old('name_y', $template->name_position['y'] ?? 500) }}" class="w-full bg-surface-container-highest border-none rounded-xl py-2 px-3 text-on-surface text-sm" required></div>
        <div><label class="text-xs text-on-surface-variant">Ukuran Font</label><input type="number" name="name_font_size" value="{{ old('name_font_size', $template->name_position['fontSize'] ?? 36) }}" class="w-full bg-surface-container-highest border-none rounded-xl py-2 px-3 text-on-surface text-sm" required></div>
        <div><label class="text-xs text-on-surface-variant">Warna (#hex)</label><input type="text" name="name_color" value="{{ old('name_color', $template->name_position['color'] ?? '#000000') }}" class="w-full bg-surface-container-highest border-none rounded-xl py-2 px-3 text-on-surface text-sm" required></div>
    </div>
    <button type="submit" class="w-full bg-gradient-to-r from-primary-dim to-primary-container text-on-primary-container font-bold py-4 rounded-xl">Simpan Perubahan</button>
</form>
@endsection
