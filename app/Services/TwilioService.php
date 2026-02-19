<?php

namespace App\Services;

use Twilio\Rest\Client;

class TwilioService
{
    protected $client;
    protected $from;

    public function __construct()
    {
        $sid = config('services.twilio.sid');
        $token = config('services.twilio.token');
        $this->from = config('services.twilio.from');

        if ($sid && $token) {
            $this->client = new Client($sid, $token);
        }
    }

    public function sendSms($to, $message)
    {
        if (!$this->client) {
            \Log::warning('Twilio client not initialized. Would send SMS to ' . $to . ': ' . $message);
            return false;
        }

        try {
            $this->client->messages->create($to, [
                'from' => $this->from,
                'body' => $message,
            ]);
            return true;
        } catch (\Exception $e) {
            \Log::error('Twilio Error: ' . $e->getMessage());
            return false;
        }
    }
}
