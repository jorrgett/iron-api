<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class MensajeroHelper
{

    protected $url;

    public function __construct()
    {
        $this->url = 'https://mensajero-api.maxcodex.com/api/send_message';
    }

    public function sendCodePhone($phone, $message, $code)
    {
        $json_body = [
            'recipients' => $phone,
            'message'    => "{$message} {$code}",
            'app'        => 'ABCopilot',
            'token'      => '$2y$10$rVaT/nSKHHmukwfdEo6xXu1gioqowcCARGZFJsCQln6ObKiXOyDJy',
            'stm'        => true
        ];

        $send = Http::post($this->url, $json_body);

        return $send;
    }
}
