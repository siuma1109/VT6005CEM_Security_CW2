<?php

namespace App\Services;

class HCaptchaService
{
    private const VERIFY_URL = 'https://hcaptcha.com/siteverify';
    private const SECRET_KEY = '0x0000000000000000000000000000000000000000'; // Replace with your secret key

    public function verify(string $response): bool
    {
        if (empty($response)) {
            return false;
        }

        $data = [
            'secret' => self::SECRET_KEY,
            'response' => $response
        ];

        $options = [
            'http' => [
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
            ]
        ];

        $context = stream_context_create($options);
        $result = file_get_contents(self::VERIFY_URL, false, $context);

        if ($result === false) {
            return false;
        }

        $result = json_decode($result, true);
        return $result['success'] ?? false;
    }
}
