<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Services\TripayService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function checkout(int $id)
    {
        $payment = Payment::with('registration.training', 'registration.user')->findOrFail($id);

        if ($payment->registration->user_id !== auth()->id()) abort(403);

        $tripay = new TripayService();
        $channels = $tripay->getPaymentChannels();

        return view('pembayaran.checkout', compact('payment', 'channels'));
    }

    public function create(Request $request, int $id)
    {
        $payment = Payment::findOrFail($id);
        if ($payment->registration->user_id !== auth()->id()) abort(403);

        $request->validate(['method' => 'required|string']);

        if ($request->method === 'MANUAL') {
            $request->validate([
                'proof_of_payment' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048'
            ]);

            $path = $request->file('proof_of_payment')->store('payments', 'public');
            
            $payment->update([
                'method' => 'MANUAL',
                'proof_of_payment' => $path,
            ]);

            return redirect()->route('pembayaran.status', $payment->id)
                ->with('success', 'Bukti pembayaran berhasil diunggah. Menunggu konfirmasi admin.');
        }

        $tripay = new TripayService();
        $result = $tripay->createTransaction($payment, $request->method);

        if ($result && isset($result->checkout_url)) {
            return redirect($result->checkout_url);
        }

        return back()->with('error', 'Gagal membuat transaksi. Coba lagi.');
    }

    public function status(int $id)
    {
        $payment = Payment::with('registration.training')->findOrFail($id);
        if ($payment->registration->user_id !== auth()->id()) abort(403);

        return view('pembayaran.status', compact('payment'));
    }
}
