<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Payment;

class TripayService
{
    protected string $apiKey;
    protected string $privateKey;
    protected string $merchantCode;
    protected string $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.tripay.api_key', '');
        $this->privateKey = config('services.tripay.private_key', '');
        $this->merchantCode = config('services.tripay.merchant_code', '');
        $this->baseUrl = config('services.tripay.mode', 'sandbox') === 'production'
            ? 'https://tripay.co.id/api'
            : 'https://tripay.co.id/api-sandbox';
    }

    public function getPaymentChannels(): array
    {
        $response = Http::withToken($this->apiKey)->get("{$this->baseUrl}/merchant/payment-channel");
        return $response->json('data', []);
    }

    public function createTransaction(Payment $payment, string $method): ?object
    {
        $registration = $payment->registration;
        $user = $registration->user;

        $signature = hash_hmac('sha256', $this->merchantCode . $payment->merchant_ref . $payment->amount, $this->privateKey);

        $response = Http::withToken($this->apiKey)->post("{$this->baseUrl}/transaction/create", [
            'method' => $method,
            'merchant_ref' => $payment->merchant_ref,
            'amount' => (int) $payment->amount,
            'customer_name' => $user->name,
            'customer_email' => $user->email,
            'customer_phone' => $user->phone ?? '',
            'order_items' => [
                [
                    'name' => $registration->training->title,
                    'price' => (int) $payment->amount,
                    'quantity' => 1,
                ],
            ],
            'callback_url' => url('/tripay/callback'),
            'return_url' => url("/pembayaran/{$payment->id}/status"),
            'expired_time' => now()->addHours(24)->timestamp,
            'signature' => $signature,
        ]);

        if ($response->successful()) {
            $data = $response->json('data');
            $payment->update([
                'tripay_reference' => $data['reference'] ?? null,
                'tripay_response' => json_encode($data),
                'expired_at' => isset($data['expired_time']) ? \Carbon\Carbon::createFromTimestamp($data['expired_time']) : null,
            ]);
            return (object) $data;
        }

        return null;
    }

    public function verifyCallback($request): bool
    {
        $callbackSignature = $request->server('HTTP_X_CALLBACK_SIGNATURE', '');
        $json = $request->getContent();
        $signature = hash_hmac('sha256', $json, $this->privateKey);

        return hash_equals($signature, $callbackSignature);
    }
}
