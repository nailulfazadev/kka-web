<?php

namespace App\Http\Controllers;

use App\Models\Training;
use Illuminate\Http\Request;

class TrainingController extends Controller
{
    public function explore(Request $request)
    {
        $query = Training::whereIn('status', ['aktif', 'mendatang', 'selesai'])
            ->withCount(['registrations as participants_count' => fn($q) => $q->whereIn('status', ['aktif', 'selesai'])])
            ->withAvg('ratings', 'score');

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('filter') && in_array($request->filter, ['aktif', 'mendatang', 'selesai'])) {
            $query->where('status', $request->filter);
        }

        if ($request->filled('pricing') && in_array($request->pricing, ['free', 'berbayar', 'donasi'])) {
            $query->where('pricing_type', $request->pricing);
        }

        if ($request->filled('type') && in_array($request->type, ['live', 'ecourse'])) {
            $query->where('is_ecourse', $request->type === 'ecourse');
        }

        $trainings = $query->latest()->paginate(12);

        return view('pages.explore', compact('trainings'));
    }

    public function show(string $slug)
    {
        $training = Training::where('slug', $slug)
            ->with(['sessions', 'creator', 'certificateTemplates' => fn($q) => $q->where('is_active', true)])
            ->withCount(['registrations as participants_count' => fn($q) => $q->whereIn('status', ['aktif', 'selesai'])])
            ->withAvg('ratings', 'score')
            ->firstOrFail();

        $registration = null;
        $attendancePercent = 0;
        $ratings = $training->ratings()->with('user')->latest()->take(5)->get();

        if (auth()->check()) {
            $registration = $training->registrations()->where('user_id', auth()->id())->first();
            if ($registration) {
                $totalSessions = $training->sessions()->count();
                $attended = auth()->user()->attendances()
                    ->whereIn('session_id', $training->sessions()->pluck('id'))
                    ->where('status', 'hadir')
                    ->count();
                $attendancePercent = $totalSessions > 0 ? round(($attended / $totalSessions) * 100) : 0;
            }
        }

        return view('pages.training-detail', compact('training', 'registration', 'attendancePercent', 'ratings'));
    }
}
