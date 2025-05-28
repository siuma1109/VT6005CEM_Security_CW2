<div class="max-w-4xl mx-auto">
    <div class="bg-gov-gray p-8 rounded-lg shadow-lg border border-gray-700">
        <h2 class="text-2xl font-bold mb-6 text-gov-text text-center">Your Appointments</h2>

        <?php if (empty($appointments)): ?>
            <div class="text-center text-gov-text-secondary py-8">
                <p>You have no appointments yet.</p>
                <a href="/make_appointment" class="inline-block mt-4 px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded transition-colors">
                    Make an Appointment
                </a>
            </div>
        <?php else: ?>
            <div class="space-y-4">
                <?php foreach ($appointments as $appointment): ?>
                    <div class="bg-secondary-black p-6 rounded-lg border border-gray-700">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gov-text mb-2">Personal Information</h3>
                                <p class="text-gov-text-secondary">
                                    <span class="font-medium">Name:</span>
                                    <?= htmlspecialchars($appointment->english_first_name . ' ' . $appointment->english_last_name) ?>
                                </p>
                                <p class="text-gov-text-secondary">
                                    <span class="font-medium">HKID:</span>
                                    <?= htmlspecialchars($appointment->hkid) ?>
                                </p>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gov-text mb-2">Appointment Details</h3>
                                <p class="text-gov-text-secondary">
                                    <span class="font-medium">Date:</span>
                                    <?= htmlspecialchars($appointment->appointment_date) ?>
                                </p>
                                <p class="text-gov-text-secondary">
                                    <span class="font-medium">Time:</span>
                                    <?= htmlspecialchars($appointment->appointment_time) ?>
                                </p>
                                <p class="text-gov-text-secondary">
                                    <span class="font-medium">Venue:</span>
                                    <?= htmlspecialchars($appointment->venue) ?>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="mt-6 text-center">
                <a href="/make_appointment" class="inline-block px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded transition-colors">
                    Make Another Appointment
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>