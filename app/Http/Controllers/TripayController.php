<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Services\TripayService;
use App\Services\StarSenderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class TripayController extends Controller
{
    public function callback(Request $request)
    {
        $tripay = new TripayService();

        if (!$tripay->verifyCallback($request)) {
            return response()->json(['success' => false, 'message' => 'Invalid signature'], 403);
        }

        $data = json_decode($request->getContent());
        $merchantRef = $data->merchant_ref ?? null;

        $payment = Payment::where('merchant_ref', $merchantRef)->first();
        if (!$payment) {
            return response()->json(['success' => false, 'message' => 'Payment not found'], 404);
        }

        $status = match ($data->status ?? '') {
            'PAID' => 'paid',
            'EXPIRED' => 'expired',
            'FAILED' => 'failed',
            default => $payment->status,
        };

        $payment->update([
            'status' => $status,
            'paid_at' => $status === 'paid' ? now() : null,
            'tripay_response' => json_encode($data),
        ]);

        if ($status === 'paid') {
            $registration = $payment->registration;

            if ($payment->type === 'registration') {
                $registration->update(['status' => 'aktif']);
            } elseif ($payment->type === 'facility') {
                $registration->update(['facility_paid' => true]);
            }

            // Send WA notification to admin
            try {
                $starsender = new StarSenderService();
                $adminPhone = config('services.starsender.admin_phone', '');
                if ($adminPhone) {
                    $user = $registration->user;
                    $training = $registration->training;
                    $msg = "💰 Pembayaran Baru!\n\nPeserta: {$user->name}\nPelatihan: {$training->title}\nJumlah: Rp " . number_format($payment->amount, 0, ',', '.') . "\nTipe: {$payment->type}";
                    $starsender->sendMessage($adminPhone, $msg);
                }
            } catch (\Exception $e) {
                Log::warning('StarSender notification failed: ' . $e->getMessage());
            }
        }

        return response()->json(['success' => true]);
    }
}
