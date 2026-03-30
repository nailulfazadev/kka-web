@extends('layouts.guest')
@section('title', 'Login - Academy Guru KKA')

@section('content')
<div class="flex flex-col items-center justify-center min-h-[80vh]">
    <div class="w-full max-w-md">
        {{-- Logo --}}
        <div class="text-center mb-8">
            <a href="{{ url('/') }}" class="flex justify-center gap-3 mb-6">
                <img src="{{ asset('/images/logo.png') }}" alt="Logo" class="h-16">
            </a>
            <h1 class="text-2xl font-bold text-on-surface">Masuk ke Akun</h1>
            <p class="text-sm text-on-surface-variant">Platform Pelatihan Guru Terpercaya</p>
        </div>

        {{-- Google Login --}}
        <a href="{{ route('auth.google') }}" class="flex items-center justify-center gap-3 w-full py-4 px-6 rounded-2xl glass-card border border-white/10 hover:bg-white/5 transition-all mb-6 font-medium text-on-surface">
            <svg class="w-5 h-5" viewBox="0 0 24 24"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
            Login dengan Google
        </a>

        <div class="flex items-center gap-4 mb-6">
            <div class="flex-1 h-px bg-outline-variant/30"></div>
            <span class="text-xs text-on-surface-variant uppercase tracking-wider">atau</span>
            <div class="flex-1 h-px bg-outline-variant/30"></div>
        </div>

        {{-- Login Form --}}
        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf
            <div>
                <label class="text-sm font-medium text-on-surface-variant mb-1 block">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="w-full bg-surface-container-highest border-none rounded-xl py-3 px-4 text-on-surface placeholder:text-outline focus:ring-1 focus:ring-primary/40" placeholder="guru@sekolah.id" required>
                @error('email') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="text-sm font-medium text-on-surface-variant mb-1 block">Password</label>
                <input type="password" name="password" class="w-full bg-surface-container-highest border-none rounded-xl py-3 px-4 text-on-surface placeholder:text-outline focus:ring-1 focus:ring-primary/40" placeholder="••••••••" required>
                @error('password') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="flex items-center gap-2">
                <input type="checkbox" name="remember" id="remember" class="rounded border-outline-variant text-primary focus:ring-primary/40">
                <label for="remember" class="text-sm text-on-surface-variant">Ingat saya</label>
            </div>
            <button type="submit" class="w-full bg-gradient-to-r from-primary-dim to-primary-container text-on-primary-container font-bold py-4 rounded-xl shadow-lg shadow-primary/10 active:scale-95 transition-transform">
                Masuk
            </button>
        </form>

        <p class="text-center text-sm text-on-surface-variant mt-6 mb-3">
            Belum punya akun? <a href="{{ route('register') }}" class="text-primary font-semibold">Daftar Gratis</a>
        </p>
        <hr>
        <p class="text-center text-sm text-on-surface-variant mt-3">
           <a href="/" class="text-primary font-semibold">Kembali ke Home</a>
        </p>
    </div>
</div>
@endsection
