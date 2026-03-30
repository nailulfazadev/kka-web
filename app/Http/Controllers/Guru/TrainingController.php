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

        if ($training->is_ecourse) {
            return $this->showEcourse($registration);
        }

        $totalSessions = $training->sessions->count();
        $attended = $training->sessions->filter(fn($s) => $s->attendances->where('status', 'hadir')->isNotEmpty())->count();
        $attendancePercent = $totalSessions > 0 ? round(($attended / $totalSessions) * 100) : 0;

        return view('guru.pelatihan.show', compact('registration', 'training', 'attendancePercent'));
    }

    private function showEcourse($registration)
    {
        $training = $registration->training;
        
        $totalSessions = $training->sessions->count();
        $attended = $training->sessions->filter(fn($s) => $s->attendances->where('status', 'hadir')->isNotEmpty())->count();
        $attendancePercent = $totalSessions > 0 ? round(($attended / $totalSessions) * 100) : 0;

        if ($attendancePercent === 100 && $registration->status !== 'selesai') {
            $registration->update(['status' => 'selesai']);
        }

        $activeSessionId = request()->query('topic');
        if (!$activeSessionId && $training->sessions->isNotEmpty()) {
            // Find first uncompleted session, or default to first session
            $firstUncompleted = $training->sessions->first(fn($s) => $s->attendances->isEmpty());
            $activeSessionId = $firstUncompleted ? $firstUncompleted->id : $training->sessions->first()->id;
        }

        return view('guru.ecourse.show', compact('registration', 'training', 'attendancePercent', 'activeSessionId'));
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

    public function donasi(\Illuminate\Http\Request $request, int $id)
    {
        $request->validate(['amount' => 'required|numeric|min:10000']);
        $user = auth()->user();
        $registration = $user->registrations()->with('training')->findOrFail($id);
        $training = $registration->training;

        if (!$training->isDonasi() || $registration->facility_paid) {
            return back()->with('info', 'Fasilitas sudah aktif atau tidak memerlukan donasi.');
        }

        $payment = \App\Models\Payment::create([
            'registration_id' => $registration->id,
            'type' => 'facility',
            'amount' => $request->amount,
            'merchant_ref' => 'FAS-' . time() . '-' . $registration->id,
            'status' => 'unpaid',
            'expired_at' => now()->addHours(24),
        ]);

        return redirect()->route('pembayaran.checkout', $payment->id);
    }

    public function markTopicDone(int $registrationId, int $sessionId)
    {
        $user = auth()->user();
        $registration = $user->registrations()->with('training.sessions')->findOrFail($registrationId);
        
        if (!$registration->training->is_ecourse) abort(404);

        $session = $registration->training->sessions()->findOrFail($sessionId);

        // Mark attendance as hadir
        \App\Models\Attendance::updateOrCreate(
            ['user_id' => $user->id, 'session_id' => $session->id],
            ['status' => 'hadir']
        );

        // Figure out the next topic
        $nextSession = $registration->training->sessions()
            ->where('session_number', '>', $session->session_number)
            ->first();

        // Check overall progress
        $totalSessions = $registration->training->sessions->count();
        $attended = $registration->training->sessions->filter(fn($s) => 
            $s->attendances()->where('user_id', $user->id)->where('status', 'hadir')->exists()
        )->count() + 1; // current request
        
        if ($attended >= $totalSessions && $registration->status !== 'selesai') {
             $registration->update(['status' => 'selesai']);
             return redirect()->route('guru.pelatihan.show', $registrationId)->with('success', 'Selamat! Anda telah menyelesaikan E-Course ini.');
        }

        $nextUrl = route('guru.pelatihan.show', $registrationId);
        if ($nextSession) {
            $nextUrl .= '?topic=' . $nextSession->id;
        }

        return redirect($nextUrl)->with('success', 'Topik ditandai selesai.');
    }
}
