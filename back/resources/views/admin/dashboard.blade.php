@extends('layouts.admin')
@section('page-title', 'Dashboard')
@section('content')
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    @foreach([['Total Peserta', $totalPeserta, 'group', 'primary'], ['Pelatihan Aktif', $pelatihanAktif, 'school', 'secondary'], ['Pendapatan', 'Rp ' . number_format($pendapatan, 0, ',', '.'), 'payments', 'tertiary'], ['Sertifikat Terbit', $sertifikatTerbit, 'workspace_premium', 'primary-dim']] as [$label, $value, $icon, $color])
        <div class="glass-card rounded-2xl border border-white/5 p-5">
            @php
                $faAdminIcons = [
                    'groups' => 'fa-solid fa-users',
                    'school' => 'fa-solid fa-graduation-cap',
                    'event_note' => 'fa-solid fa-calendar-check',
                    'payments' => 'fa-solid fa-wallet',
                ];
            @endphp
            <i class="{{ $faAdminIcons[$icon] ?? 'fa-solid fa-circle' }} text-{{ $color }} mb-2 text-2xl"></i>
            <p class="text-2xl font-bold text-on-surface">{{ $value }}</p>
            <p class="text-xs text-on-surface-variant">{{ $label }}</p>
        </div>
    @endforeach
</div>
<div class="glass-card rounded-2xl border border-white/5 p-6 mb-8">
    <h3 class="text-lg font-bold text-on-surface mb-4">Registrasi Terbaru</h3>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="text-on-surface-variant text-xs uppercase tracking-wider"><th class="text-left py-3 px-2">Nama</th><th class="text-left py-3 px-2">Pelatihan</th><th class="text-left py-3 px-2">Status</th><th class="text-left py-3 px-2">Tanggal</th></tr></thead>
            <tbody>
                @foreach($recentRegistrations as $reg)
                <tr class="border-t border-white/5">
                    <td class="py-3 px-2 text-on-surface">{{ $reg->user->name }}</td>
                    <td class="py-3 px-2 text-on-surface-variant truncate max-w-[200px]">{{ $reg->training->title }}</td>
                    <td class="py-3 px-2"><span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase {{ $reg->status === 'aktif' ? 'bg-green-500/20 text-green-400' : ($reg->status === 'pending' ? 'bg-yellow-500/20 text-yellow-400' : 'bg-outline/20 text-outline') }}">{{ $reg->status }}</span></td>
                    <td class="py-3 px-2 text-on-surface-variant text-xs">{{ $reg->created_at->format('d/m/Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="flex gap-3">
    <a href="{{ route('admin.pelatihan.create') }}" class="flex-1 flex items-center justify-center gap-2 bg-gradient-to-r from-primary-dim to-primary-container text-on-primary-container py-3 rounded-xl font-bold text-sm"><i class="fa-solid fa-plus text-sm"></i> Buat Pelatihan</a>
    <a href="{{ route('admin.presensi.index') }}" class="flex-1 flex items-center justify-center gap-2 glass-card border border-white/5 text-on-surface py-3 rounded-xl font-medium text-sm hover:bg-white/5"><i class="fa-solid fa-clipboard-check text-sm"></i> Rekap Presensi</a>
</div>
@endsection
