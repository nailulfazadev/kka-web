<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekap Peserta Pelatihan</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 15px; }
        .header h1 { margin: 0; font-size: 20px; text-transform: uppercase; }
        .header p { margin: 5px 0 0; font-size: 14px; color: #555; }
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { padding: 4px; border: none; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 11px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background-color: #f0f0f0; text-align: center; font-weight: bold; }
        .text-center { text-align: center; }
        .badge-hadir { color: green; font-weight: bold; }
        .badge-alpa { color: red; }
    </style>
</head>
<body>
    <div class="header">
        <h1>REKAPITULASI PESERTA & PRESENSI</h1>
        <p>ACADEMY GURU KKA</p>
    </div>

    <table class="info-table">
        <tr>
            <td width="15%"><strong>Pelatihan</strong></td>
            <td width="35%">: {{ $training->title }}</td>
            <td width="15%"><strong>Tanggal</strong></td>
            <td width="35%">: {{ $training->start_date ? $training->start_date->format('d/m/Y') : '-' }} - {{ $training->end_date ? $training->end_date->format('d/m/Y') : '-' }}</td>
        </tr>
        <tr>
            <td><strong>Total Peserta</strong></td>
            <td>: {{ $registrations->count() }} Orang</td>
            <td><strong>Total Sesi</strong></td>
            <td>: {{ $sessions->count() }} Pertemuan</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th width="5%" rowspan="2">No</th>
                <th width="25%" rowspan="2">Nama Peserta</th>
                <th width="20%" rowspan="2">Instansi/Sekolah</th>
                <th colspan="{{ $sessions->count() }}">Kehadiran Sesi (H)</th>
                <th width="10%" rowspan="2">Persentase</th>
            </tr>
            <tr>
                @foreach($sessions as $session)
                    <th>H{{ $loop->iteration }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($registrations as $reg)
                @php
                    $user = $reg->user;
                    $attendedCount = 0;
                @endphp
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->school ?? '-' }}</td>
                    
                    @foreach($sessions as $session)
                        @php
                            $att = $user->attendances->where('session_id', $session->id)->first();
                            if ($att && $att->status === 'hadir') {
                                $attendedCount++;
                                $statusLabel = '<span class="badge-hadir">Hadir</span>';
                            } else {
                                $statusLabel = '<span class="badge-alpa">Alpa</span>';
                            }
                        @endphp
                        <td class="text-center">{!! $statusLabel !!}</td>
                    @endforeach

                    @php
                        $percent = $sessions->count() > 0 ? round(($attendedCount / $sessions->count()) * 100) : 0;
                    @endphp
                    <td class="text-center"><strong>{{ $percent }}%</strong></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
