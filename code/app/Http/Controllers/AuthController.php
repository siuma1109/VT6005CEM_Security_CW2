<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AuthService;
use App\Services\DatabaseSessionService;
use App\Services\PasswordService;

class AuthController
{
    private AuthService $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
    }

    public function register()
    {
        $register_errors = DatabaseSessionService::get('register_errors');
        $register_data = DatabaseSessionService::get('register_data');

        return view('main_page', [
            'content' => view('auth/register', [
                'register_errors' => $register_errors,
                'register_data' => $register_data
            ])
        ]);
    }

    public function registerPost()
    {
        try {
            DatabaseSessionService::setOnce('register_data', [
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                // not saving password to session for security reasons
                // 'password' => $_POST['password'],
                // 'password_confirmation' => $_POST['password_confirmation']
            ]);

            if (!isset($_POST['name']) || !isset($_POST['email']) || !isset($_POST['password']) || !isset($_POST['password_confirmation'])) {
                DatabaseSessionService::setOnce('register_errors', ['Name, email, password and confirm password are required']);
                return header('Location: /register');
            }

            if ($_POST['password'] !== $_POST['password_confirmation']) {
                DatabaseSessionService::setOnce('register_errors', ['Passwords do not match']);
                return header('Location: /register');
            }

            if (PasswordService::isPwnPassword($_POST['password'])) {
                DatabaseSessionService::setOnce('register_errors', ['Password is pwned']);
                return header('Location: /register');
            }

            $checkUser = User::where('email', $_POST['email'])->first();
            if ($checkUser) {
                DatabaseSessionService::setOnce('register_errors', ['User with this email already exists']);
                return header('Location: /register');
            }

            $user = User::create([
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                'password' => PasswordService::hash($_POST['password'])
            ]);

            //DatabaseSessionService::setUser($user);
            DatabaseSessionService::remove('register_data');
            DatabaseSessionService::remove('register_errors');
            DatabaseSessionService::set('user_email', $user->email);
            $this->authService->sendMfaCode($user);
            return header('Location: /mfa');
        } catch (\Exception $e) {
            error_log('Register error: ' . $e->getMessage());
            return header('Location: /register?error=An error occurred during registration');
        }
    }

    public function login()
    {
        $login_data = DatabaseSessionService::get('login_data');
        $errors = DatabaseSessionService::get('login_errors');

        return view('main_page', [
            'content' => view('auth/login', [
                'login_data' => $login_data,
                'errors' => $errors,
            ])
        ]);
    }

    public function loginPost()
    {
        try {
            if (!isset($_POST['email']) || !isset($_POST['password'])) {
                DatabaseSessionService::setOnce('login_errors', ['Email and password are required']);
                return header('Location: /login');
            }

            $email = $_POST['email'];
            DatabaseSessionService::setOnce('login_data', [
                'email' => $email,
            ]);

            // Check if IP is locked
            $lockError = $this->authService->isLocked();
            if ($lockError) {
                DatabaseSessionService::setOnce('login_errors', [$lockError['error']]);
                return header('Location: /login');
            }

            $user = User::where('email', $email)->first();

            if (!$user || !PasswordService::verify($_POST['password'], $user->password)) {
                $error = $this->authService->handleFailedLogin($email);
                DatabaseSessionService::setOnce('login_errors', [$error['error']]);
                return header('Location: /login');
            }

            // Successful login
            $this->authService->handleSuccessfulLogin($email);

            //DatabaseSessionService::setUser($user);

            // Send MFA code
            $this->authService->sendMfaCode($user);
            DatabaseSessionService::set('user_email', $user->email);

            return header('Location: /mfa');
        } catch (\Exception $e) {
            echo $e->getMessage();
            echo $e->getLine();
            echo $e->getFile();
            error_log('Login error: ' . $e->getMessage());
            return header('Location: /login?error=An error occurred during login');
        }
    }

    public function logout()
    {
        DatabaseSessionService::destroy();
        return header('Location: /');
    }

    public function mfa()
    {
        $errors = DatabaseSessionService::get('mfa_errors');
        $mfa_data = DatabaseSessionService::get('mfa_data');

        return view('main_page', [
            'content' => view('auth/mfa', [
                'errors' => $errors,
                'mfa_data' => $mfa_data
            ])
        ]);
    }

    public function mfaPost()
    {
        $email = DatabaseSessionService::get('user_email');
        if (!isset($_POST['mfa_code']) || !isset($email)) {
            DatabaseSessionService::setOnce('mfa_errors', ['MFA code is required']);
            return header('Location: /mfa');
        }

        if (strlen($_POST['mfa_code']) !== 6) {
            DatabaseSessionService::setOnce('mfa_errors', ['MFA code must be 6 digits']);
            return header('Location: /');
        }

        $user = User::where('email', $email)->first();
        if (!$user) {
            DatabaseSessionService::setOnce('mfa_errors', ['Invalid credentials']);
            return header('Location: /mfa');
        }

        $verify_result = $this->authService->verifyMfaCode($user, $_POST['mfa_code']);
        if (!$verify_result) {
            DatabaseSessionService::setOnce('mfa_errors', ['Invalid credentials']);
            return header('Location: /mfa');
        }

        DatabaseSessionService::setUser($user);
        DatabaseSessionService::remove('mfa_errors');
        DatabaseSessionService::remove('mfa_data');
        return header('Location: /');
    }
}
