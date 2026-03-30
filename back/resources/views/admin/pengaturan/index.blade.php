@extends('layouts.admin') @section('page-title', 'Pengaturan') @section('content')
<div class="max-w-2xl space-y-6">
    <div class="glass-card rounded-2xl border border-white/5 p-6">
        <h3 class="font-bold text-on-surface mb-3">Informasi Aplikasi</h3>
        <p class="text-sm text-on-surface-variant">Academy Guru KKA v1.0</p>
        <p class="text-sm text-on-surface-variant">Laravel {{ app()->version() }}</p>
    </div>
    <div class="glass-card rounded-2xl border border-white/5 p-6">
        <h3 class="font-bold text-on-surface mb-3">Konfigurasi API</h3>
        <div class="space-y-2 text-sm">
            <div class="flex justify-between"><span class="text-on-surface-variant">Tripay</span><span class="text-primary">{{ config('services.tripay.api_key') ? '✓ Terkonfigurasi' : '✗ Belum' }}</span></div>
            <div class="flex justify-between"><span class="text-on-surface-variant">StarSender</span><span class="text-primary">{{ config('services.starsender.api_key') ? '✓ Terkonfigurasi' : '✗ Belum' }}</span></div>
            <div class="flex justify-between"><span class="text-on-surface-variant">Google OAuth</span><span class="text-primary">{{ config('services.google.client_id') ? '✓ Terkonfigurasi' : '✗ Belum' }}</span></div>
        </div>
    </div>
</div>
@endsection
