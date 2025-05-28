<?php

namespace App\Http\Controllers;

use App\Models\Session;
use App\Services\DatabaseSessionService;

class SessionController
{
    public function sessions()
    {
        $user = DatabaseSessionService::getUser();
        $sessions = Session::where('user_id', $user->id)->get();
        return view('main_page', [
            'content' => view('session/sessions', [
                'sessions' => $sessions
            ])
        ]);
    }

    public function deleteSession()
    {
        $user = DatabaseSessionService::getUser();
        $sessionId = $_POST['session_id'] ?? null;

        if (!$sessionId) {
            header('Location: /sessions');
            exit;
        }

        $session = Session::where('id', $sessionId)
            ->where('user_id', $user->id)
            ->first();

        if (!$session) {
            header('Location: /sessions');
            exit;
        }

        $session->delete();
        header('Location: /sessions');
        exit;
    }
}