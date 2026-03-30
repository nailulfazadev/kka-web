@extends('layouts.admin') @section('page-title', 'Buat Template Sertifikat') @section('content')
<form method="POST" action="{{ route('admin.cert-template.store') }}" enctype="multipart/form-data" class="max-w-2xl space-y-4">
    @csrf
    <div><label class="text-sm font-medium text-on-surface-variant mb-1 block">Pelatihan</label><select name="training_id" class="w-full bg-surface-container-highest border-none rounded-xl py-3 px-4 text-on-surface" required>@foreach(\App\Models\Training::orderBy('title')->get() as $t)<option value="{{ $t->id }}">{{ $t->title }}</option>@endforeach</select></div>
    <div><label class="text-sm font-medium text-on-surface-variant mb-1 block">Nama Template</label><input type="text" name="name" class="w-full bg-surface-container-highest border-none rounded-xl py-3 px-4 text-on-surface" required></div>
    <div class="grid grid-cols-2 gap-4">
        <div><label class="text-sm font-medium text-on-surface-variant mb-1 block">Gambar Depan</label><input type="file" name="front_image" accept="image/*" class="w-full bg-surface-container-highest border-none rounded-xl py-3 px-4 text-on-surface" required></div>
        <div><label class="text-sm font-medium text-on-surface-variant mb-1 block">Gambar Belakang</label><input type="file" name="back_image" accept="image/*" class="w-full bg-surface-container-highest border-none rounded-xl py-3 px-4 text-on-surface"></div>
    </div>
    <h4 class="text-sm font-bold text-on-surface mt-4">Posisi Nama</h4>
    <div class="grid grid-cols-2 gap-4">
        <div><label class="text-xs text-on-surface-variant">X</label><input type="number" name="name_x" value="400" class="w-full bg-surface-container-highest border-none rounded-xl py-2 px-3 text-on-surface text-sm" required></div>
        <div><label class="text-xs text-on-surface-variant">Y</label><input type="number" name="name_y" value="500" class="w-full bg-surface-container-highest border-none rounded-xl py-2 px-3 text-on-surface text-sm" required></div>
        <div><label class="text-xs text-on-surface-variant">Ukuran Font</label><input type="number" name="name_font_size" value="36" class="w-full bg-surface-container-highest border-none rounded-xl py-2 px-3 text-on-surface text-sm" required></div>
        <div><label class="text-xs text-on-surface-variant">Warna (#hex)</label><input type="text" name="name_color" value="#000000" class="w-full bg-surface-container-highest border-none rounded-xl py-2 px-3 text-on-surface text-sm" required></div>
    </div>
    <button type="submit" class="w-full bg-gradient-to-r from-primary-dim to-primary-container text-on-primary-container font-bold py-4 rounded-xl">Simpan Template</button>
</form>
@endsection
