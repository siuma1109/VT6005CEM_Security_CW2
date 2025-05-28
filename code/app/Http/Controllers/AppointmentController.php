<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Services\DatabaseSessionService;
use App\Services\EncryptionService;

class AppointmentController
{
    public readonly EncryptionService $encryptionService;

    public function __construct()
    {
        $this->encryptionService = new EncryptionService();
    }

    public function makeAppointment()
    {
        return view(
            'main_page',
            [
                'content' => view('appointment/make_appointment')
            ]
        );
    }

    public function makeAppointmentPost()
    {
        $data = $_POST;
        $data['hkid'] = $this->encryptionService->encrypt($data['hkid']);
        $data['user_id'] = DatabaseSessionService::getUser()->id;
        Appointment::create($data);
        DatabaseSessionService::setOnce('message', 'Appointment created successfully');
        return header('Location: /');
    }

    public function appointments()
    {
        $user = DatabaseSessionService::getUser();
        $appointments = Appointment::where('user_id', $user->id)->get();
        foreach ($appointments as $appointment) {
            $appointment->hkid = $this->encryptionService->decrypt($appointment->hkid);
        }
        return view(
            'main_page',
            [
                'content' => view('appointment/appointments', [
                    'appointments' => $appointments,
                ])
            ]
        );
    }
}
