<?php

use App\Services\DatabaseSessionService;

$user = DatabaseSessionService::getUser();
?>

<div class="max-w-2xl mx-auto">
    <?php if ($message): ?>
        <div class="bg-green-500 text-white p-4 rounded-lg mb-4">
            <?= $message ?>
        </div>
    <?php endif; ?>
    <!-- Main Content -->
    <div class="bg-gov-gray p-6 rounded-lg shadow-lg border border-gray-700">
        <h2 class="text-2xl font-bold mb-4 text-gov-text">Public Services</h2>
        <div class="space-y-4">
            <a href="/make_appointment" class="block bg-secondary-black hover-light text-gov-text font-bold py-3 px-4 rounded transition duration-300 shadow-md text-center">
                Make Appointment
            </a>
            <a href="/appointments" class="block bg-secondary-black hover-light text-gov-text font-bold py-3 px-4 rounded transition duration-300 shadow-md text-center">
                Applied Appointments
            </a>
        </div>
    </div>

    <?php if (isset($user) && $user): ?>
        <!-- Session Management Section -->
        <div class="bg-gov-gray p-6 rounded-lg shadow-lg border border-gray-700 mt-6">
            <h2 class="text-2xl font-bold mb-4 text-gov-text">Session Management</h2>
            <div class="space-y-4">
                <a href="/sessions" class="block bg-secondary-black hover-light text-gov-text font-bold py-3 px-4 rounded transition duration-300 shadow-md text-center">
                    View Active Sessions
                </a>
            </div>
        </div>
    <?php endif; ?>
</div>