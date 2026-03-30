<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sertifikat Pelatihan</title>
    <style>
        @page {
            margin: 0px;
        }
        body {
            margin: 0px;
            padding: 0px;
            width: 100%;
            height: 100%;
        }
        .container {
            position: relative;
            width: 100%;
            height: 100%;
        }
        .background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }
        .name {
            position: absolute;
            width: 100%; /* Important for centering if needed */
            font-family: 'sans-serif';
            font-weight: bold;
            z-index: 10;
        }
    </style>
</head>
<body>
    <div class="container">
        @if($template->front_image)
            {{-- Need absolute path or base64 for dompdf --}}
            @php
                $imagePath = storage_path('app/public/' . $template->front_image);
                $imageData = base64_encode(file_get_contents($imagePath));
                $src = 'data:image/jpeg;base64,'.$imageData;
            @endphp
            <img src="{{ $src }}" class="background" alt="Background">
        @endif

        @php
            $pos = $template->name_position;
            // Handle centering if x is 0 or very small
            $left = isset($pos['x']) ? $pos['x'] . 'px' : '0px';
            $top = isset($pos['y']) ? $pos['y'] . 'px' : '300px';
            $fontSize = isset($pos['fontSize']) ? $pos['fontSize'] . 'px' : '36px';
            $numFontSize = isset($pos['fontSize']) ? ($pos['fontSize'] * 0.4) . 'px' : '14px';
            $color = isset($pos['color']) ? $pos['color'] : '#000000';
            
            // Instansi styling (smaller font, a bit lower)
            $instansiFontSize = isset($pos['fontSize']) ? ($pos['fontSize'] * 0.5) . 'px' : '18px';
            $instansiTop = isset($pos['y']) && isset($pos['fontSize']) ? ($pos['y'] + $pos['fontSize'] * 1.5) . 'px' : '360px';
        @endphp
        
        @if($certificateNumber)
        <div style="position: absolute; top: 40px; width: 100%; text-align: center; font-family: 'sans-serif'; font-size: {{ $numFontSize }}; color: {{ $color }}; z-index: 10;">
            Nomor: {{ $certificateNumber }}
        </div>
        @endif

        <div class="name" style="left: 0px; top: {{ $top }}; font-size: {{ $fontSize }}; color: {{ $color }}; text-align: center; width: 100%;">
            {{ $user->name }}
        </div>
        @if($user->school)
        <div class="name" style="left: 0px; top: {{ $instansiTop }}; font-size: {{ $instansiFontSize }}; color: {{ $color }}; text-align: center; width: 100%;">
            {{ $user->school }}
        </div>
        @endif

        @if($executionRange)
        <div style="position: absolute; bottom: 200px; width: 100%; text-align: center; font-family: 'sans-serif'; font-size: 14px; color: {{ $color }}; z-index: 10;">
            Telah mengikuti pelatihan pada tanggal <strong>{{ $executionRange }}</strong>
        </div>
        @endif

        @if($issueDate)
        <div style="position: absolute; right: 120px; bottom: 160px; font-family: 'sans-serif'; font-size: 14px; color: {{ $color }}; z-index: 10; text-align: left;">
            {{ $issueDate }}
        </div>
        @endif

        {{-- Barcode / QR Code --}}
        <div style="position: absolute; left: 60px; bottom: 60px; z-index: 20; background: white; padding: 5px; border-radius: 5px;">
            <img src="data:image/png;base64,{{ $qrCode }}" width="70" height="70">
            <div style="font-family: 'sans-serif'; font-size: 7px; color: #666; margin-top: 2px; text-align: center;">Verified Certificate</div>
        </div>
    </div>

    @if($template->back_image)
        <div style="page-break-before: always;"></div>
        <div class="container">
            @php
                $backImagePath = storage_path('app/public/' . $template->back_image);
                $backImageData = '';
                if(file_exists($backImagePath)) {
                    $backImageData = base64_encode(file_get_contents($backImagePath));
                }
                $backSrc = 'data:image/jpeg;base64,'.$backImageData;
            @endphp
            @if($backImageData)
                <img src="{{ $backSrc }}" class="background" alt="Background Belakang">
            @endif
        </div>
    @endif
</body>
</html>
