@extends('layouts.app')
@section('title', 'Status Pembayaran')
@section('content')
<section class="px-6 pt-10 flex flex-col items-center text-center">
    @if($payment->isPaid())
        <div class="w-20 h-20 rounded-full bg-green-500/20 flex items-center justify-center mb-4"><i class="fa-solid fa-circle-check text-4xl text-green-400"></i></div>
        <h2 class="text-2xl font-bold text-on-surface mb-2">Pembayaran Berhasil!</h2>
        <p class="text-on-surface-variant mb-6">Anda sekarang terdaftar di pelatihan ini.</p>
    @elseif($payment->isExpired())
        <div class="w-20 h-20 rounded-full bg-error/20 flex items-center justify-center mb-4"><i class="fa-solid fa-clock-rotate-left text-4xl text-error"></i></div>
        <h2 class="text-2xl font-bold text-on-surface mb-2">Pembayaran Expired</h2>
        <p class="text-on-surface-variant mb-6">Waktu pembayaran telah habis.</p>
    @else
        @if($payment->method === 'MANUAL' && $payment->proof_of_payment)
            <div class="w-20 h-20 rounded-full bg-blue-500/20 flex items-center justify-center mb-4"><i class="fa-solid fa-hourglass-half text-4xl text-blue-400"></i></div>
            <h2 class="text-2xl font-bold text-on-surface mb-2">Menunggu Konfirmasi</h2>
            <p class="text-on-surface-variant mb-6">Bukti bayar Anda sedang diverifikasi admin.</p>
        @else
            <div class="w-20 h-20 rounded-full bg-yellow-500/20 flex items-center justify-center mb-4"><i class="fa-regular fa-clock text-4xl text-yellow-400"></i></div>
            <h2 class="text-2xl font-bold text-on-surface mb-2">Menunggu Pembayaran</h2>
            <p class="text-on-surface-variant mb-6">Silakan selesaikan pembayaran sebelum {{ $payment->expired_at?->format('d M Y H:i') ?? '-' }}</p>
        @endif
    @endif
    <div class="glass-card rounded-2xl border border-white/5 p-5 w-full text-left mb-6">
        <p class="text-xs text-on-surface-variant mb-1">Ref: {{ $payment->merchant_ref }}</p>
        <p class="font-bold text-on-surface">{{ $payment->registration->training->title }}</p>
        <p class="text-primary font-bold text-lg mt-2">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
    </div>
    <a href="{{ url('/guru/pelatihan') }}" class="w-full text-center bg-primary/10 text-primary py-3 rounded-xl font-medium mb-3">Lihat Pelatihan Saya</a>
    <a href="{{ url('/') }}" class="text-on-surface-variant text-sm">Kembali ke beranda</a>
</section>
@endsection
