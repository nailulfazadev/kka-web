@extends('layouts.admin')
@section('page-title', 'Kelola Pelatihan')
@section('content')
<div class="flex items-center justify-between mb-6">
    <h3 class="text-lg font-semibold text-on-surface">Daftar Pelatihan</h3>
    <a href="{{ route('admin.pelatihan.create') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-primary-dim to-primary-container text-on-primary-container px-4 py-2 rounded-xl text-sm font-bold"><i class="fa-solid fa-plus text-sm"></i> Tambah</a>
</div>
<div class="glass-card rounded-2xl border border-white/5 overflow-hidden">
    <table class="w-full text-sm">
        <thead><tr class="text-on-surface-variant text-xs uppercase tracking-wider bg-surface-container-high/50"><th class="text-left py-3 px-4">Pelatihan</th><th class="text-left py-3 px-4">Tipe</th><th class="text-left py-3 px-4">Status</th><th class="text-left py-3 px-4">Peserta</th><th class="text-left py-3 px-4">Aksi</th></tr></thead>
        <tbody>
            @foreach($trainings as $t)
            <tr class="border-t border-white/5 hover:bg-white/5">
                <td class="py-3 px-4 text-on-surface font-medium">
                    {{ $t->title }}
                    @if($t->is_ecourse) <span class="ml-2 text-[9px] bg-blue-500/20 text-blue-400 px-2 py-0.5 rounded-full uppercase tracking-widest">E-Course</span> @endif
                </td>
                <td class="py-3 px-4"><span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase {{ $t->pricing_type === 'free' ? 'bg-green-500/20 text-green-400' : ($t->pricing_type === 'berbayar' ? 'bg-purple-500/20 text-purple-400' : 'bg-yellow-500/20 text-yellow-400') }}">{{ $t->pricing_type }}</span></td>
                <td class="py-3 px-4"><span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase {{ $t->status === 'aktif' ? 'bg-green-500/20 text-green-400' : 'bg-outline/20 text-outline' }}">{{ $t->status }}</span></td>
                <td class="py-3 px-4 text-on-surface-variant">{{ $t->participants_count }}</td>
                <td class="py-3 px-4 flex gap-2">
                    <a href="{{ route('admin.pelatihan.edit', $t) }}" class="text-primary hover:underline text-xs">Edit</a>
                    <a href="{{ route('admin.jadwal.index', $t->id) }}" class="text-secondary hover:underline text-xs">Sesi/Topik</a>
                    @if(!$t->is_ecourse)
                        <form method="POST" action="{{ route('admin.pelatihan.duplicate', $t) }}" class="inline" onsubmit="return confirm('Duplikat riwayat pelatihan ini menjadi produk E-Course baru?')">@csrf<button class="text-yellow-400 hover:underline text-xs">Duplikat E-Course</button></form>
                    @endif
                    <form method="POST" action="{{ route('admin.pelatihan.destroy', $t) }}" class="inline" onsubmit="return confirm('Hapus pelatihan ini?')">@csrf @method('DELETE')<button class="text-error hover:underline text-xs">Hapus</button></form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $trainings->links() }}</div>
@endsection
