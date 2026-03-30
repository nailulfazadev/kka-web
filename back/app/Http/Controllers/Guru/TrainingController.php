<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Registration;

class TrainingController extends Controller
{
    public function index()
    {
        $registrations = auth()->user()->registrations()
            ->with(['training.sessions', 'payments'])
            ->latest()
            ->get();

        $active = $registrations->where('status', 'aktif');
        $completed = $registrations->where('status', 'selesai');

        return view('guru.pelatihan.index', compact('active', 'completed'));
    }

    public function show(int $id)
    {
        $registration = auth()->user()->registrations()
            ->with(['training.sessions.attendances' => fn($q) => $q->where('user_id', auth()->id()), 'training.creator'])
            ->findOrFail($id);

        $training = $registration->training;
        $totalSessions = $training->sessions->count();
        $attended = $training->sessions->filter(fn($s) => $s->attendances->where('status', 'hadir')->isNotEmpty())->count();
        $attendancePercent = $totalSessions > 0 ? round(($attended / $totalSessions) * 100) : 0;

        return view('guru.pelatihan.show', compact('registration', 'training', 'attendancePercent'));
    }

    public function downloadUndangan(int $id)
    {
        $user = auth()->user();
        $registration = $user->registrations()->with('training.sessions')->findOrFail($id);
        $training = $registration->training;

        if (!$training->facilities_released) {
            return back()->with('error', 'Fasilitas undangan belum dibagikan oleh admin.');
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('guru.pelatihan.undangan', compact('user', 'training'));
        $pdf->setOptions(['isRemoteEnabled' => true]);
        return $pdf->download('Undangan_Pelatihan_' . \Illuminate\Support\Str::slug($training->title) . '.pdf');
    }
}
