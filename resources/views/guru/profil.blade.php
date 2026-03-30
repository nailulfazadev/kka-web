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
    @if ($errors->any())
        <div class="mb-6 p-4 rounded-xl bg-error-container/20 border border-error/20 text-error text-sm">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('guru.profil.update') }}" class="space-y-4">
        @csrf @method('PUT')
        <div><label class="text-sm font-medium text-on-surface-variant mb-1 block">Nama Lengkap</label><input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full bg-surface-container border-none rounded-xl py-3 px-4 text-on-surface focus:ring-1 focus:ring-primary/40" required></div>
        <div><label class="text-sm font-medium text-on-surface-variant mb-1 block">No. WhatsApp</label><input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full bg-surface-container border-none rounded-xl py-3 px-4 text-on-surface focus:ring-1 focus:ring-primary/40" placeholder="08xxxxxxxxxx"></div>
        <div><label class="text-sm font-medium text-on-surface-variant mb-1 block">Asal Sekolah / Instansi</label><input type="text" name="school" value="{{ old('school', $user->school) }}" class="w-full bg-surface-container border-none rounded-xl py-3 px-4 text-on-surface focus:ring-1 focus:ring-primary/40"></div>
        <div><label class="text-sm font-medium text-on-surface-variant mb-1 block">NUPTK (Jika ada)</label><input type="text" name="nuptk" value="{{ old('nuptk', $user->nuptk) }}" class="w-full bg-surface-container border-none rounded-xl py-3 px-4 text-on-surface focus:ring-1 focus:ring-primary/40"></div>
        <button type="submit" class="w-full bg-gradient-to-r from-primary-dim to-primary-container text-on-primary-container font-bold py-4 rounded-xl shadow-lg shadow-primary/20 active:scale-95 transition-transform mt-2">Simpan Perubahan Data</button>
    </form>

    {{-- Akun & Keamanan --}}
    <h3 class="text-xl font-bold text-on-surface mt-10 mb-6">Akun & Keamanan</h3>
    
    @if($user->google_id)
        <div class="glass-card p-4 rounded-xl border border-white/5 bg-surface-container pb-4 mb-6 flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-white flex items-center justify-center flex-shrink-0 shadow-sm">
                <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-6 h-6" alt="Google">
            </div>
            <div>
                <p class="font-bold text-on-surface text-sm">Tertaut dengan Google</p>
                <p class="text-[12px] text-on-surface-variant mt-0.5 leading-snug">Anda masuk menggunakan akun Google. Autentikasi sepenuhnya dikelola oleh Google yang aman.</p>
            </div>
        </div>
    @endif

    @if($user->password)
        <form method="POST" action="{{ route('guru.profil.password') }}" class="space-y-4 mb-10 {{ $user->google_id ? 'pt-6 border-t border-white/5 mt-6' : '' }}">
            @csrf @method('PUT')
            <p class="text-sm font-medium text-on-surface mb-2">Ubah Kata Sandi</p>
            <div><input type="password" name="current_password" class="w-full bg-surface-container border-none rounded-xl py-3 px-4 text-on-surface focus:ring-1 focus:ring-primary/40 text-sm" placeholder="Password Saat Ini" required></div>
            <div><input type="password" name="password" class="w-full bg-surface-container border-none rounded-xl py-3 px-4 text-on-surface focus:ring-1 focus:ring-primary/40 text-sm" placeholder="Password Baru (Min 8 karakter)" required></div>
            <div><input type="password" name="password_confirmation" class="w-full bg-surface-container border-none rounded-xl py-3 px-4 text-on-surface focus:ring-1 focus:ring-primary/40 text-sm" placeholder="Ulangi Password Baru" required></div>
            <button type="submit" class="w-full bg-surface-container-high border border-white/10 text-on-surface font-bold py-4 rounded-xl hover:bg-white/5 active:scale-95 transition-all text-sm mt-2">Perbarui Password</button>
        </form>
    @endif

    <form method="POST" action="{{ route('logout') }}" class="mt-12 pb-12">
        @csrf
        <button type="submit" class="w-full text-center text-error py-4 rounded-xl bg-error/10 border border-error/20 hover:bg-error/20 font-bold transition-colors">Keluar Aplikasi</button>
    </form>
</section>
@endsection
