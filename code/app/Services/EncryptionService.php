<?php

namespace App\Services;

class EncryptionService
{
    public readonly string $key;

    public function __construct()
    {
        $this->key = $_ENV['APP_KEY'];
    }

    public function encrypt($data)
    {
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $encrypted = openssl_encrypt($data, 'aes-256-cbc', $this->key, 0, $iv);
        // Store IV with encrypted data using base64 encoding
        return base64_encode($iv . $encrypted);
    }

    public function decrypt($data)
    {
        // Decode the combined IV and encrypted data
        $decoded = base64_decode($data);
        $ivLength = openssl_cipher_iv_length('aes-256-cbc');
        $iv = substr($decoded, 0, $ivLength);
        $encrypted = substr($decoded, $ivLength);
        return openssl_decrypt($encrypted, 'aes-256-cbc', $this->key, 0, $iv);
    }
}
