<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class HCaptchaService
{
    protected $secretKey;

    public function __construct()
    {
        $this->secretKey = env('HCAPTCHA_SECRET');
    }

    public function verify($token, $remoteIp = null)
    {
        $response = Http::asForm()->post('https://hcaptcha.com/siteverify', [
            'secret' => $this->secretKey,
            'response' => $token,
            'remoteip' => $remoteIp ?? request()->ip(),
        ]);

        $responseData = $response->json();

        return $responseData['success'];
    }
}
