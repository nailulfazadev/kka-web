<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Training;
use App\Models\Registration;
use App\Models\Payment;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPeserta = User::where('role', 'guru')->count();
        $pelatihanAktif = Training::where('status', 'aktif')->count();
        $pendapatan = Payment::where('status', 'paid')->sum('amount');
        $sertifikatTerbit = Registration::where('certificate_eligible', true)->count();

        $recentRegistrations = Registration::with(['user', 'training', 'payments'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalPeserta', 'pelatihanAktif', 'pendapatan', 'sertifikatTerbit', 'recentRegistrations'
        ));
    }
}
