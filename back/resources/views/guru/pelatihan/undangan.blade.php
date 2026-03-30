<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Undangan Pelatihan</title>
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; font-size: 14px; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 5px; margin-bottom: 20px; }
        .header img { width: 100%; max-width: 800px; }
        .header-text { text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px; }
        .header-text h1 { margin: 0; font-size: 24px; }
        .header-text p { margin: 5px 0 0; font-size: 14px; color: #666; }
        .content { margin-bottom: 30px; }
        table { border-collapse: collapse; margin-top: 20px; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f4f4f4; }
        .page-break { page-break-after: always; }
        .footer { text-align: right; margin-top: 50px; }
    </style>
</head>
<body>
    <div class="header">
        <img src="https://eguru125720839.wordpress.com/wp-content/uploads/2026/01/copeguru-1.jpg" alt="Kop Surat">
    </div>

    <div class="content">
        <p>Nomor: {{ str_pad($training->id, 3, '0', STR_PAD_LEFT) }}/UND/AGK/{{ date('Y') }}</p>
        <p>Hal: Undangan Peserta Pelatihan</p>
        <p>Tanggal: {{ date('d F Y') }}</p>

        <p><br>Kepada Yth.<br>
        <strong>{{ $user->name }}</strong><br>
        {{ $user->school ?? 'Instansi/Sekolah' }}</p>

        <p>Dengan hormat,</p>
        <p>Merujuk pada pendaftaran yang telah dilakukan, kami mengundang Bapak/Ibu untuk berpartisipasi dalam kegiatan pelatihan dengan rincian sebagai berikut:</p>

        <table>
            <tr>
                <td width="30%"><strong>Nama Pelatihan</strong></td>
                <td>: {{ $training->title }}</td>
            </tr>
            <tr>
                <td><strong>Tanggal Pelaksanaan</strong></td>
                <td>: {{ $training->start_date ? $training->start_date->format('d M Y') : '-' }} s.d {{ $training->end_date ? $training->end_date->format('d M Y') : '-' }}</td>
            </tr>
            <tr>
                <td><strong>Status Peserta</strong></td>
                <td>: Diterima / Aktif</td>
            </tr>
        </table>

        <p>Kami sangat mengharapkan kehadiran dan partisipasi aktif Bapak/Ibu dalam seluruh rangkaian kegiatan pelatihan ini. Rincian jadwal kegiatan terlampir pada halaman kedua surat undangan ini.</p>

        <p>Demikian undangan ini kami sampaikan. Atas perhatian dan kerjasamanya, kami ucapkan terima kasih.</p>
    </div>

    <div class="footer">
        <p>Hormat kami,</p>
        <p><br><br><br></p>
        <p><strong>Panitia Pelaksana</strong><br>Academy Guru KKA</p>
    </div>

    <div class="page-break"></div>

    <div class="header">
        <img src="https://eguru125720839.wordpress.com/wp-content/uploads/2026/01/copeguru-1.jpg" alt="Kop Surat">
    </div>

    <div class="header-text">
        <h1>LAMPIRAN JADWAL KEGIATAN</h1>
        <p>{{ $training->title }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="10%">Sesi</th>
                <th width="25%">Tanggal</th>
                <th width="25%">Waktu</th>
                <th width="40%">Materi / Kegiatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($training->sessions as $session)
            <tr>
                <td align="center">{{ $session->session_number ?? $loop->iteration }}</td>
                <td>{{ $session->session_date ? $session->session_date->format('l, d M Y') : '-' }}</td>
                <td>{{ $session->start_time ? \Carbon\Carbon::parse($session->start_time)->format('H:i') : '-' }} - {{ $session->end_time ? \Carbon\Carbon::parse($session->end_time)->format('H:i') : '-' }} WIB</td>
                <td>{{ $session->title ?? 'Materi Sesi ' . ($session->session_number ?? $loop->iteration) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
