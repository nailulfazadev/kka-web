<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Session;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function checkin(int $sessionId)
    {
        $session = Session::with('training')->findOrFail($sessionId);
        $user = auth()->user();

        // Verify user is registered
        $registration = $user->registrations()
            ->where('training_id', $session->training_id)
            ->whereIn('status', ['aktif', 'selesai'])
            ->first();

        if (!$registration) {
            return back()->with('error', 'Anda tidak terdaftar pada pelatihan ini.');
        }

        if ($session->session_date->isFuture()) {
            return back()->with('error', 'Presensi belum dibuka. Sesi dijadwalkan pada ' . $session->session_date->format('d M Y') . '.');
        }

        Attendance::updateOrCreate(
            ['user_id' => $user->id, 'session_id' => $sessionId],
            ['status' => 'hadir', 'checked_in_at' => now()]
        );

        return back()->with('success', 'Presensi berhasil dicatat!');
    }
}
