<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    public function index()
    {
        $registrations = auth()->user()->registrations()
            ->with(['training.certificateTemplates' => fn($q) => $q->where('is_active', true)])
            ->whereIn('status', ['aktif', 'selesai'])
            ->get();

        return view('guru.sertifikat.index', compact('registrations'));
    }

    public function download(int $id)
    {
        $user = auth()->user();
        $registration = $user->registrations()
            ->with(['training.certificateTemplates' => fn($q) => $q->where('is_active', true)])
            ->findOrFail($id);

        $training = $registration->training;

        if (!$training->facilities_released) {
            return back()->with('error', 'Sertifikat belum dibagikan oleh admin.');
        }

        // Check attendance threshold
        $totalSessions = $training->sessions()->count();
        $attended = $user->attendances()
            ->whereIn('session_id', $training->sessions()->pluck('id'))
            ->where('status', 'hadir')
            ->count();

        $percent = $totalSessions > 0 ? round(($attended / $totalSessions) * 100) : 0;

        if ($percent < $training->min_attendance_percent) {
            return back()->with('error', "Kehadiran Anda {$percent}%. Minimal {$training->min_attendance_percent}% untuk mengunduh sertifikat.");
        }

        // Check donasi pricing: facility must be paid
        if ($training->isDonasi() && !$registration->facility_paid) {
            return back()->with('error', 'Silakan bayar fasilitas terlebih dahulu untuk mengunduh sertifikat.');
        }

        $template = $training->certificateTemplates()->where('is_active', true)->first();
        if (!$template) {
            return back()->with('error', 'Template sertifikat belum tersedia.');
        }

        // Generate PDF using DomPDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('guru.sertifikat.pdf', compact('user', 'training', 'template'));
        // Set paper size to A4 Landscape, typical for certificates
        $pdf->setPaper('a4', 'landscape');
        // Enable remote images just in case
        $pdf->setOptions(['isRemoteEnabled' => true]);

        return $pdf->download('Sertifikat_' . \Illuminate\Support\Str::slug($user->name) . '_' . \Illuminate\Support\Str::slug($training->title) . '.pdf');
    }
}
