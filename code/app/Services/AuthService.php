<?php

namespace App\Services;

use App\Models\User;
use App\Services\DatabaseSessionService;

class AuthService
{
    private const MAX_LOGIN_ATTEMPTS = 5;
    private const LOCKOUT_MINUTES = 30;

    private function getClientIp(): string
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        return $ip;
    }

    private function storeAttemptedEmail(string $email): void
    {
        $ip = $this->getClientIp();
        $attemptedEmails = DatabaseSessionService::get('attempted_emails_' . $ip) ?? [];

        // Add new email if not already in array
        if (!in_array($email, $attemptedEmails)) {
            $attemptedEmails[] = $email;
            DatabaseSessionService::set('attempted_emails_' . $ip, $attemptedEmails);
        }
    }

    public function getAttemptedEmails(): array
    {
        $ip = $this->getClientIp();
        return DatabaseSessionService::get('attempted_emails_' . $ip) ?? [];
    }

    public function getUser()
    {
        return DatabaseSessionService::getUser();
    }

    public function handleFailedLogin(string $email): array
    {
        $ip = $this->getClientIp();
        $attempts = DatabaseSessionService::get('login_attempts_' . $ip) ?? 0;
        $attempts++;
        DatabaseSessionService::set('login_attempts_' . $ip, $attempts);
        DatabaseSessionService::set('last_login_attempt_' . $ip, date('Y-m-d H:i:s'));

        // Store the attempted email
        $this->storeAttemptedEmail($email);

        if ($attempts >= self::MAX_LOGIN_ATTEMPTS) {
            $lockoutUntil = date('Y-m-d H:i:s', strtotime('+' . self::LOCKOUT_MINUTES . ' minutes'));
            DatabaseSessionService::set('locked_until_' . $ip, $lockoutUntil);
            return ['error' => 'Too many failed attempts. Please try again later.'];
        }

        $remainingAttempts = self::MAX_LOGIN_ATTEMPTS - $attempts;
        return ['error' => "Invalid credentials. {$remainingAttempts} attempts remaining."];
    }

    public function handleSuccessfulLogin(string $email): void
    {
        $ip = $this->getClientIp();
        DatabaseSessionService::remove('login_attempts_' . $ip);
        DatabaseSessionService::remove('last_login_attempt_' . $ip);
        DatabaseSessionService::remove('locked_until_' . $ip);
        // Keep the attempted emails for security monitoring
    }

    public function isLocked(): ?array
    {
        $ip = $this->getClientIp();
        $lockedUntil = DatabaseSessionService::get('locked_until_' . $ip);

        if (!$lockedUntil) {
            return null;
        }

        if (strtotime($lockedUntil) > time()) {
            $remainingTime = ceil((strtotime($lockedUntil) - time()) / 60);
            return ['error' => "Please try again in {$remainingTime} minutes."];
        }

        // If lock has expired, reset the lock
        DatabaseSessionService::remove('locked_until_' . $ip);
        DatabaseSessionService::remove('login_attempts_' . $ip);
        return null;
    }
}
