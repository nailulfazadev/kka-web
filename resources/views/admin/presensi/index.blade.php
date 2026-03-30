@extends('layouts.admin') @section('page-title', 'Presensi') @section('content')
<div class="flex items-center gap-4 mb-6">
    <h3 class="text-lg font-semibold text-on-surface">Rekap Presensi</h3>
    <form method="GET" class="ml-auto flex items-center gap-2"><select name="training_id" class="bg-surface-container-highest border-none rounded-xl py-2 px-3 text-on-surface text-sm"><option value="">Pilih Pelatihan</option>@foreach($trainings as $t)<option value="{{ $t->id }}" @selected(request('training_id')==$t->id)>{{ $t->title }}</option>@endforeach</select><button class="bg-primary text-on-primary px-3 py-2 rounded-xl text-sm">Lihat</button></form>
</div>
@if($selectedTraining)
<div class="glass-card rounded-2xl border border-white/5 overflow-x-auto p-4">
    <table class="w-full text-sm"><thead><tr class="text-on-surface-variant text-xs"><th class="text-left py-2 px-2">Nama</th>@foreach($selectedTraining->sessions as $s)<th class="text-center py-2 px-2">S{{ $s->session_number }}</th>@endforeach<th class="text-center py-2 px-2">%</th></tr></thead>
    <tbody>@foreach($selectedTraining->registrations as $reg)@php $userAtt = $attendances->get($reg->user_id, collect()); $total = $selectedTraining->sessions->count(); $hadir = $userAtt->where('status', 'hadir')->count(); @endphp
    <tr class="border-t border-white/5"><td class="py-2 px-2 text-on-surface">{{ $reg->user->name }}</td>@foreach($selectedTraining->sessions as $s)@php $a = $userAtt->firstWhere('session_id', $s->id); @endphp<td class="text-center py-2 px-2">@if($a && $a->status==='hadir')<span class="text-green-400">✓</span>@elseif($a && $a->status==='izin')<span class="text-yellow-400">i</span>@else<span class="text-outline">—</span>@endif</td>@endforeach<td class="text-center py-2 px-2 font-bold {{ $total > 0 && ($hadir/$total*100) >= $selectedTraining->min_attendance_percent ? 'text-green-400' : 'text-error' }}">{{ $total > 0 ? round($hadir/$total*100) : 0 }}%</td></tr>@endforeach
    </tbody></table>
</div>
@else<p class="text-on-surface-variant">Pilih pelatihan untuk melihat rekap presensi.</p>@endif
@endsection
