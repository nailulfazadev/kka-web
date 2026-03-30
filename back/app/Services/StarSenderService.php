<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class StarSenderService
{
    protected string $apiKey;
    protected string $deviceId;

    public function __construct()
    {
        $this->apiKey = config('services.starsender.api_key', '');
        $this->deviceId = config('services.starsender.device_id', '');
    }

    public function sendMessage(string $phone, string $message): bool
    {
        if (empty($this->apiKey)) return false;

        // Format phone to 62xxx
        $phone = preg_replace('/^0/', '62', $phone);
        $phone = preg_replace('/^\+/', '', $phone);

        try {
            $response = Http::withHeaders([
                'Authorization' => $this->apiKey,
            ])->post('https://api.starsender.online/api/send', [
                'messageType' => 'text',
                'to' => $phone,
                'body' => $message,
                'device_id' => $this->deviceId,
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::warning('StarSender failed: ' . $e->getMessage());
            return false;
        }
    }

    public function sendBulk(array $phones, string $message): array
    {
        $results = [];
        foreach ($phones as $phone) {
            $results[$phone] = $this->sendMessage($phone, $message);
        }
        return $results;
    }
}
