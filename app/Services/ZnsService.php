<?php

namespace App\Services;

use App\Models\ZnsLog;
use Illuminate\Support\Facades\Log;

class ZnsService
{
    public function send(string $template, string $phone, array $payload = []): ZnsLog
    {
        $log = ZnsLog::create([
            'template' => $template,
            'phone' => $phone,
            'payload' => $payload,
            'status' => 'simulated',
            'response' => json_encode(['mock' => true, 'message' => 'ZNS giả lập – chưa kết nối API']),
        ]);

        Log::info("ZNS mock [{$template}] → {$phone}", $payload);

        return $log;
    }

    public function billCreated(string $phone, array $data): ZnsLog
    {
        return $this->send('bill_created', $phone, $data);
    }

    public function billReminder(string $phone, array $data, int $level = 1): ZnsLog
    {
        return $this->send("bill_reminder_{$level}", $phone, $data);
    }

    public function paymentConfirmed(string $phone, array $data): ZnsLog
    {
        return $this->send('payment_confirmed', $phone, $data);
    }

    public function contractExpiring(string $phone, array $data): ZnsLog
    {
        return $this->send('contract_expiring', $phone, $data);
    }
}
