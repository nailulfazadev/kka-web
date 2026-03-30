@extends('layouts.app')
@section('title', 'Profil Saya')
@section('content')
<section class="px-6 pt-6">
    <h2 class="text-2xl font-bold text-on-surface mb-6">Profil Saya</h2>
    <div class="flex items-center gap-4 mb-8">
        <div class="w-16 h-16 rounded-full bg-primary/10 flex items-center justify-center overflow-hidden">
            @if($user->avatar)<img src="{{ $user->avatar }}" class="w-full h-full object-cover">@else<i class="fa-solid fa-user text-2xl text-primary"></i>@endif
        </div>
        <div><p class="text-lg font-bold text-on-surface">{{ $user->name }}</p><p class="text-sm text-on-surface-variant">{{ $user->email }}</p></div>
    </div>
    <form method="POST" action="{{ route('guru.profil.update') }}" class="space-y-4">
        @csrf @method('PUT')
        <div><label class="text-sm font-medium text-on-surface-variant mb-1 block">Nama</label><input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full bg-surface-container-highest border-none rounded-xl py-3 px-4 text-on-surface focus:ring-1 focus:ring-primary/40" required></div>
        <div><label class="text-sm font-medium text-on-surface-variant mb-1 block">No. HP</label><input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full bg-surface-container-highest border-none rounded-xl py-3 px-4 text-on-surface focus:ring-1 focus:ring-primary/40" placeholder="08xxxxxxxxxx"></div>
        <div><label class="text-sm font-medium text-on-surface-variant mb-1 block">Asal Sekolah</label><input type="text" name="school" value="{{ old('school', $user->school) }}" class="w-full bg-surface-container-highest border-none rounded-xl py-3 px-4 text-on-surface focus:ring-1 focus:ring-primary/40"></div>
        <div><label class="text-sm font-medium text-on-surface-variant mb-1 block">NUPTK</label><input type="text" name="nuptk" value="{{ old('nuptk', $user->nuptk) }}" class="w-full bg-surface-container-highest border-none rounded-xl py-3 px-4 text-on-surface focus:ring-1 focus:ring-primary/40"></div>
        <button type="submit" class="w-full bg-gradient-to-r from-primary-dim to-primary-container text-on-primary-container font-bold py-4 rounded-xl active:scale-95 transition-transform">Simpan Perubahan</button>
    </form>
    <form method="POST" action="{{ route('logout') }}" class="mt-6">
        @csrf
        <button type="submit" class="w-full text-center text-error py-3 rounded-xl glass-card border border-error/20 hover:bg-error/10 font-medium transition-colors">Keluar</button>
    </form>
</section>
@endsection
