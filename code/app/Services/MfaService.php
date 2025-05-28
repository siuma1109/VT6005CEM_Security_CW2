<?php

namespace App\Services;

use App\Models\User;

class MfaService
{
    public readonly string $code;
    private MailService $mailService;

    public function __construct()
    {
        $this->mailService = new MailService();
        $this->code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    public function sendToEmail(User $user): bool
    {
        $user->mfa_code = $this->code;
        $user->mfa_code_expires_at = date('Y-m-d H:i:s', strtotime('+10 minutes'));
        $user->save();
        return $this->mailService->send($user->email, 'MFA Code', 'Your MFA code is: ' . $this->code);
    }

    public function verifyCode(User $user, string $code): bool
    {
        if (!$user->mfa_code || !$user->mfa_code_expires_at) {
            return false;
        }

        $currentTime = date('Y-m-d H:i:s');
        return $user->mfa_code === $code && strtotime($user->mfa_code_expires_at) > strtotime($currentTime);
    }
}
