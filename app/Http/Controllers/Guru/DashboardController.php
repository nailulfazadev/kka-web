<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Training;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $myTrainings = $user->registrations()
            ->with('training.sessions')
            ->whereIn('status', ['aktif', 'selesai'])
            ->latest()
            ->get();

        // Find next upcoming session across all active trainings
        $upcomingSession = null;
        foreach ($myTrainings as $reg) {
            $session = $reg->training->sessions
                ->where('session_date', '>=', today())
                ->sortBy('session_date')
                ->first();
            if ($session && (!$upcomingSession || $session->session_date < $upcomingSession->session_date)) {
                $upcomingSession = $session;
                $upcomingSession->training_title = $reg->training->title;
            }
        }

        $activeCount = $myTrainings->where('status', 'aktif')->count();
        $completedCount = $myTrainings->where('status', 'selesai')->count();

        return view('guru.dashboard', compact('myTrainings', 'upcomingSession', 'activeCount', 'completedCount'));
    }
}
