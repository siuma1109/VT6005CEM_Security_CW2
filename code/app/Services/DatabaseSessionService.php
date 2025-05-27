<?php

namespace App\Services;

use App\Models\Session;
use App\Models\User;

class DatabaseSessionService
{
    private static $session = null;

    public static function start()
    {
        if (self::$session === null) {
            $sessionId = $_COOKIE['session_id'] ?? null;

            if ($sessionId) {
                $session = Session::where('id', $sessionId)->first();
                if (!$session) {
                    self::$session = self::createSession();
                } else {
                    self::$session = $session;
                }
            }

            if (!self::$session) {
                try {
                    self::$session = self::createSession();
                    if (self::$session) {
                        setcookie('session_id', self::$session->id, [
                            'expires' => time() + 86400, // 24 hours
                            'path' => '/',
                            'secure' => true,
                            'httponly' => true,
                            'samesite' => 'Lax'
                        ]);
                    }
                } catch (\Exception $e) {
                    error_log('Session creation error: ' . $e->getMessage());
                    // If session creation fails, try one more time with a new ID
                    self::$session = self::createSession();
                }
            }

            if (self::$session) {
                self::updateActivity(self::$session);
            }
        }

        return self::$session;
    }

    public static function setUser(User $user)
    {
        $session = self::start();
        $session->user_id = $user->id;
        $session->save();
    }

    public static function set($key, $value)
    {
        $session = self::start();
        self::updatePayload($session, [$key => $value]);
    }

    public static function setOnce($key, $value)
    {
        $session = self::start();
        $payload = self::getPayload($session);

        if (!isset($payload[$key])) {
            self::updatePayload($session, [$key => $value]);
            self::updatePayload($session, [$key . '_is_once' => true]);
        }
    }

    public static function getUser(): User|null
    {
        $session = self::start();
        if (!$session) {
            return null;
        }
        $user = User::find($session->user_id);
        return $user;
    }

    public static function get($key)
    {
        $session = self::start();
        $payload = self::getPayload($session);

        $value = $payload[$key] ?? null;

        if (isset($payload[$key . '_is_once'])) {
            unset($payload[$key]);
            unset($payload[$key . '_is_once']);
            $session->payload = json_encode($payload);
            $session->save();
        }

        return $value;
    }

    public static function has($key)
    {
        $session = self::start();
        $payload = self::getPayload($session);
        return isset($payload[$key]);
    }

    public static function remove($key)
    {
        $session = self::start();
        $payload = self::getPayload($session);
        unset($payload[$key]);
        $session->payload = json_encode($payload);
        return $session->save();
    }

    public static function destroy()
    {
        if (self::$session) {
            self::$session->delete();
            setcookie('session_id', '', [
                'expires' => time() - 3600,
                'path' => '/',
                'secure' => true,
                'httponly' => true,
                'samesite' => 'Lax'
            ]);
            self::$session = null;
        }
    }

    public static function createSession($userId = null)
    {
        try {
            $sessionId = bin2hex(random_bytes(32));

            $session = new Session();
            $session->id = $sessionId;
            $session->user_id = $userId;
            $session->ip_address = $_SERVER['REMOTE_ADDR'] ?? null;
            $session->user_agent = $_SERVER['HTTP_USER_AGENT'] ?? null;
            $session->payload = json_encode([]);
            $session->last_activity = time();

            if (!$session->save()) {
                throw new \Exception('Failed to save session');
            }

            return $session;
        } catch (\Exception $e) {
            error_log('Session creation failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public static function updateActivity(Session $session)
    {
        $session->last_activity = time();
        return $session->save();
    }

    public static function updatePayload(Session $session, $data)
    {
        $currentPayload = json_decode($session->payload, true) ?? [];
        $newPayload = array_merge($currentPayload, $data);
        $session->payload = json_encode($newPayload);
        return $session->save();
    }

    public static function getPayload(Session $session)
    {
        return json_decode($session->payload, true) ?? [];
    }
}
