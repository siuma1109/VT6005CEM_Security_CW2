<?php

namespace App\Services;

class EncryptionService
{
    public readonly string $key;
    private const CIPHER = 'aes-256-cbc';
    private const IV_LENGTH = 16;

    public function __construct()
    {
        $this->key = $_ENV['APP_KEY'];
    }

    public function encrypt($data)
    {
        $iv = openssl_random_pseudo_bytes(self::IV_LENGTH);
        $encrypted = openssl_encrypt($data, self::CIPHER, $this->key, 0, $iv);
        // Store IV with encrypted data using base64 encoding
        return base64_encode($iv . $encrypted);
    }

    public function decrypt($data)
    {
        try {
            $decoded = base64_decode($data);
            if ($decoded === false) {
                return false;
            }

            // Ensure we have enough data for IV + encrypted content
            if (strlen($decoded) <= self::IV_LENGTH) {
                return false;
            }

            $iv = substr($decoded, 0, self::IV_LENGTH);
            $encrypted = substr($decoded, self::IV_LENGTH);

            $decrypted = openssl_decrypt($encrypted, self::CIPHER, $this->key, 0, $iv);
            return $decrypted !== false ? $decrypted : false;
        } catch (\Exception $e) {
            return false;
        }
    }
}
