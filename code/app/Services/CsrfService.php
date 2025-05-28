<?php

namespace App\Services;

class CsrfService
{
    private $token;

    private EncryptionService $encryptionService;

    public function __construct()
    {
        $this->token = 'This is my app CSRF Token';
        $this->encryptionService = new EncryptionService();
    }

    public function generateToken()
    {
        $encryptedToken = $this->encryptionService->encrypt($this->token);
        $this->token = $encryptedToken;
        return $encryptedToken;
    }

    public function validateToken($token)
    {
        if (!$this->token) {
            return false;
        }

        $decryptedToken = $this->encryptionService->decrypt($token);

        return hash_equals($this->token, $decryptedToken);
    }
}
