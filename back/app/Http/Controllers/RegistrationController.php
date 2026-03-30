<?php

namespace App\Http\Controllers;

use App\Models\Training;
use App\Models\Registration;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RegistrationController extends Controller
{
    public function store(Request $request, int $trainingId)
    {
        $training = Training::findOrFail($trainingId);
        $user = auth()->user();

        // Check if already registered
        $existing = Registration::where('user_id', $user->id)->where('training_id', $training->id)->first();
        if ($existing) {
            return back()->with('error', 'Anda sudah terdaftar pada pelatihan ini.');
        }

        if ($training->isFree() || $training->isDonasi()) {
            // Free & Donasi: langsung aktif
            Registration::create([
                'user_id' => $user->id,
                'training_id' => $training->id,
                'status' => 'aktif',
            ]);
            return redirect("/guru/pelatihan")->with('success', 'Berhasil mendaftar pelatihan!');
        }

        // Berbayar: buat registration pending + payment
        $registration = Registration::create([
            'user_id' => $user->id,
            'training_id' => $training->id,
            'status' => 'pending',
        ]);

        $payment = Payment::create([
            'registration_id' => $registration->id,
            'type' => 'registration',
            'merchant_ref' => 'INV-' . strtoupper(Str::random(8)),
            'amount' => $training->price,
            'status' => 'unpaid',
        ]);

        return redirect("/pembayaran/{$payment->id}");
    }
}
