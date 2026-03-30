@extends('layouts.admin') @section('page-title', 'Keuangan') @section('content')
<div class="grid grid-cols-2 gap-4 mb-6">
    <div class="glass-card p-5 rounded-2xl border border-white/5"><p class="text-2xl font-bold text-primary">Rp {{ number_format($totalPaid, 0, ',', '.') }}</p><p class="text-xs text-on-surface-variant">Total Terbayar</p></div>
    <div class="glass-card p-5 rounded-2xl border border-white/5"><p class="text-2xl font-bold text-yellow-400">Rp {{ number_format($totalPending, 0, ',', '.') }}</p><p class="text-xs text-on-surface-variant">Menunggu Bayar</p></div>
</div>
<div class="glass-card rounded-2xl border border-white/5 overflow-x-auto">
    <table class="w-full text-sm">
        <thead>
            <tr class="text-on-surface-variant text-xs uppercase tracking-wider bg-surface-container-high/50">
                <th class="text-left py-3 px-4">Ref / Tipe</th>
                <th class="text-left py-3 px-4">Peserta & Pelatihan</th>
                <th class="text-left py-3 px-4">Jumlah</th>
                <th class="text-left py-3 px-4">Status & Bukti</th>
                <th class="text-right py-3 px-4">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payments as $p)
            <tr class="border-t border-white/5 {{ $p->status === 'unpaid' && $p->proof_of_payment ? 'bg-primary/5' : '' }}">
                <td class="py-3 px-4 align-top">
                    <p class="text-on-surface-variant text-xs font-mono">{{ $p->merchant_ref }}</p>
                    <span class="px-2 py-0.5 rounded-full bg-surface-bright text-on-surface-variant text-[9px] uppercase mt-1 inline-block">{{ $p->type }} • {{ $p->method ?? '-' }}</span>
                </td>
                <td class="py-3 px-4 align-top">
                    <p class="text-on-surface font-medium">{{ $p->registration->user->name }}</p>
                    <p class="text-on-surface-variant text-xs truncate max-w-[200px]">{{ $p->registration->training->title }}</p>
                </td>
                <td class="py-3 px-4 text-on-surface font-medium align-top">Rp {{ number_format($p->amount, 0, ',', '.') }}</td>
                <td class="py-3 px-4 align-top">
                    <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase {{ $p->status === 'paid' ? 'bg-green-500/20 text-green-400' : ($p->status === 'unpaid' ? 'bg-yellow-500/20 text-yellow-400' : 'bg-error/20 text-error') }}">{{ $p->status }}</span>
                    @if($p->proof_of_payment)
                        <a href="{{ asset('storage/'.$p->proof_of_payment) }}" target="_blank" class="block mt-2 text-[10px] text-blue-400 hover:underline flex items-center gap-1 w-max"><i class="fa-solid fa-receipt text-[12px]"></i> Lihat Bukti</a>
                    @endif
                </td>
                <td class="py-3 px-4 text-right align-top">
                    @if($p->status === 'unpaid')
                        <form method="POST" action="{{ route('admin.keuangan.approve', $p->id) }}" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui pembayaran ini (mengaktifkan peserta)?');">
                            @csrf
                            <button class="bg-primary/10 border border-primary/20 hover:bg-primary/20 text-primary px-3 py-1.5 rounded-lg text-xs font-bold transition-colors shadow-sm shadow-primary/10 active:scale-95">Approve Manual</button>
                        </form>
                    @endif
                </td>
            </tr>
            @empty
            <tr class="border-t border-white/5"><td colspan="5" class="py-4 px-4 text-center text-on-surface-variant text-sm">Belum ada transaksi pembayaran.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $payments->links() }}</div>
@endsection
