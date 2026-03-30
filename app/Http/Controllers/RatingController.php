<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function store(Request $request, int $trainingId)
    {
        $request->validate([
            'score' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
        ]);

        $user = auth()->user();
        $registration = $user->registrations()
            ->where('training_id', $trainingId)
            ->first();

        if (!$registration || (!$registration->isCompleted() && !$registration->facility_paid)) {
            return back()->with('error', 'Anda belum bisa memberikan rating.');
        }

        Rating::updateOrCreate(
            ['user_id' => $user->id, 'training_id' => $trainingId],
            ['score' => $request->score, 'review' => $request->review]
        );

        return back()->with('success', 'Rating berhasil disimpan!');
    }
}
