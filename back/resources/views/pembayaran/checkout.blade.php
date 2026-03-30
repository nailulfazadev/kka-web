@extends('layouts.app')
@section('title', 'Checkout Pembayaran')
@section('content')
<section class="px-6 pt-6">
    <a href="{{ url()->previous() }}" class="inline-flex items-center gap-1 text-on-surface-variant text-sm mb-4"><i class="fa-solid fa-arrow-left text-sm"></i> Kembali</a>
    <h2 class="text-xl font-bold text-on-surface mb-6">Checkout Pembayaran</h2>
    <div class="glass-card rounded-2xl border border-white/5 p-5 mb-6">
        <p class="text-sm text-on-surface-variant mb-1">Pelatihan</p>
        <p class="font-bold text-on-surface mb-3">{{ $payment->registration->training->title }}</p>
        <p class="text-sm text-on-surface-variant mb-1">Tipe Pembayaran</p>
        <p class="font-medium text-on-surface mb-3 capitalize">{{ $payment->type }}</p>
        <p class="text-sm text-on-surface-variant mb-1">Total</p>
        <p class="text-2xl font-bold text-primary">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
    </div>
    <form method="POST" action="{{ route('pembayaran.create', $payment->id) }}" class="space-y-4" enctype="multipart/form-data">
        @csrf
        <label class="text-sm font-medium text-on-surface-variant block mb-2">Pilih Metode Pembayaran</label>
        <div class="space-y-3 max-h-80 overflow-y-auto hide-scrollbar">
            
            {{-- Manual Transfer --}}
            <label class="glass-card rounded-xl border border-primary/30 p-4 flex flex-col gap-3 cursor-pointer hover:bg-white/5 transition-colors relative">
                <div class="flex items-center gap-4">
                    <input type="radio" name="method" value="MANUAL" class="text-primary focus:ring-primary peer" required onclick="document.getElementById('manual-fields').classList.remove('hidden')">
                    <div class="flex-1">
                        <p class="text-sm font-bold text-primary">Transfer Manual Bank</p>
                        <p class="text-xs text-on-surface-variant">BCA / Mandiri / Dana</p>
                    </div>
                </div>
                
                <div id="manual-fields" class="hidden mt-2 pt-3 border-t border-white/5">
                    <div class="mb-3 p-3 rounded-lg bg-surface-container-highest border border-white/5">
                        <p class="text-xs text-on-surface-variant mb-2">Silakan transfer sejumlah <strong>Rp {{ number_format($payment->amount, 0, ',', '.') }}</strong> ke salah satu rekening ini:</p>
                        <ul class="text-xs text-on-surface font-mono space-y-1">
                            <li>BCA: 1234567890 a/n Academy Guru</li>
                            <li>Mandiri: 0987654321 a/n Academy Guru</li>
                            <li>DANA: 081234567890 a/n Academy Guru</li>
                        </ul>
                    </div>
                    <label class="block text-xs font-bold text-on-surface mb-1">Upload Bukti Pembayaran *</label>
                    <input type="file" name="proof_of_payment" accept="image/*,.pdf" class="w-full bg-surface-container-highest border-none rounded-lg py-2 px-3 text-xs text-on-surface">
                </div>
            </label>

            {{-- Tripay Channels --}}
            {{-- @foreach($channels as $channel)
                <label class="glass-card rounded-xl border border-white/5 p-4 flex items-center gap-4 cursor-pointer hover:bg-white/5 transition-colors">
                    <input type="radio" name="method" value="{{ $channel['code'] }}" class="text-primary focus:ring-primary" onclick="document.getElementById('manual-fields').classList.add('hidden')">
                    <img src="{{ $channel['icon_url'] ?? '' }}" alt="" class="h-6 object-contain">
                    <div>
                        <p class="text-sm font-medium text-on-surface">{{ $channel['name'] }}</p>
                        <p class="text-xs text-on-surface-variant">Fee: Rp {{ number_format($channel['total_fee']['flat'] ?? 0, 0, ',', '.') }}</p>
                    </div>
                </label>
            @endforeach --}}

            {{-- Coming Soon Payment Gateway --}}
            <label class="glass-card rounded-xl border border-white/5 p-4 flex items-center gap-4 cursor-not-allowed opacity-60">
                <input type="radio" name="method" value="GATEWAY" class="text-primary focus:ring-primary" disabled>
                <div class="flex-1">
                    <p class="text-sm font-bold text-on-surface">Pembayaran Otomatis</p>
                    <p class="text-xs text-on-surface-variant">QRIS, E-Wallet (OVO/Dana), Virtual Account</p>
                </div>
                <span class="px-2 py-1 bg-surface-container-highest text-on-surface-variant text-[9px] font-bold rounded-md uppercase tracking-wider">Coming Soon</span>
            </label>
        </div>
        <button type="submit" class="w-full bg-gradient-to-r from-primary-dim to-primary-container text-on-primary-container font-bold py-4 rounded-xl active:scale-95 transition-transform mt-4">Kirim Pembayaran</button>
    </form>
</section>
@endsection
