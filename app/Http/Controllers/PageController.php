<?php

namespace App\Http\Controllers;

use App\Models\Training;

class PageController extends Controller
{
    public function landing()
    {
        $featuredTrainings = Training::whereIn('status', ['aktif', 'mendatang'])
            ->withCount(['registrations as participants_count' => function ($q) {
                $q->whereIn('status', ['aktif', 'selesai']);
            }])
            ->withAvg('ratings', 'score')
            ->latest()
            ->take(6)
            ->get();

        return view('pages.landing', compact('featuredTrainings'));
    }
}
