<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Payment;

class FinanceController extends Controller
{
    public function index()
    {
        $payments = Payment::with(['registration.user', 'registration.training'])->latest()->paginate(20);
        $totalPaid = Payment::where('status', 'paid')->sum('amount');
        $totalPending = Payment::where('status', 'unpaid')->sum('amount');
        return view('admin.keuangan.index', compact('payments', 'totalPaid', 'totalPending'));
    }

    public function approve($id)
    {
        $payment = Payment::with('registration')->findOrFail($id);

        if ($payment->status !== 'unpaid') {
            return back()->with('error', 'Hanya pembayaran yang berstatus unpaid yang dapat disetujui.');
        }

        $payment->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        $payment->registration->update([
            'status' => 'aktif'
        ]);

        return back()->with('success', 'Pembayaran berhasil disetujui. Peserta sekarang aktif dalam pelatihan.');
    }
}
