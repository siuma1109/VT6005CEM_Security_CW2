<?php

// CSRF Protection
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<div class="max-w-4xl mx-auto">
    <div class="bg-gov-gray p-8 rounded-lg shadow-lg border border-gray-700">
        <h2 class="text-2xl font-bold mb-6 text-gov-text text-center">HKID Card Appointment Application</h2>

        <?php if (isset($errors)): ?>
            <div class="bg-red-500/10 border border-red-500 text-red-500 px-4 py-3 rounded mb-4">
                <?php foreach ($errors as $error): ?>
                    <p class="text-red-500"><?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="/make_appointment" method="POST" class="space-y-6" id="appointmentForm">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

            <!-- Personal Information Section -->
            <div class="space-y-6">
                <h3 class="text-xl font-semibold text-gov-text mb-4">Personal Information</h3>

                <!-- English Name -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="english_first_name" class="block text-sm font-medium text-gov-text-secondary mb-2">English First Name</label>
                        <input type="text" id="english_first_name" name="english_first_name" required
                            pattern="[A-Za-z\s-']{1,50}"
                            class="w-full px-4 py-2 rounded bg-secondary-black border border-gray-700 text-gov-text focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                            placeholder="Enter your first name">
                    </div>
                    <div>
                        <label for="english_last_name" class="block text-sm font-medium text-gov-text-secondary mb-2">English Last Name</label>
                        <input type="text" id="english_last_name" name="english_last_name" required
                            pattern="[A-Za-z\s-']{1,50}"
                            class="w-full px-4 py-2 rounded bg-secondary-black border border-gray-700 text-gov-text focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                            placeholder="Enter your last name">
                    </div>
                </div>

                <!-- HKID Number -->
                <div>
                    <label for="hkid" class="block text-sm font-medium text-gov-text-secondary mb-2">HKID Number</label>
                    <input type="text" id="hkid" name="hkid" required
                        pattern="[A-Z]{1,2}[0-9]{6}(\([0-9A]\))?"
                        class="w-full px-4 py-2 rounded bg-secondary-black border border-gray-700 text-gov-text focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                        placeholder="e.g., A123456(7)"
                        title="Please enter a valid HKID number (e.g., A123456(7))">
                    <p class="mt-1 text-sm text-gov-text-secondary">Format: 1-2 letters followed by 6 digits, optionally followed by (1 digit or letter)</p>
                </div>

                <!-- Appointment Details -->
                <div class="space-y-4">
                    <h3 class="text-xl font-semibold text-gov-text mb-4">Appointment Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="appointment_date" class="block text-sm font-medium text-gov-text-secondary mb-2">Preferred Date</label>
                            <input type="date" id="appointment_date" name="appointment_date" required
                                min="<?= date('Y-m-d', strtotime('+2 days')) ?>"
                                class="w-full px-4 py-2 rounded bg-secondary-black border border-gray-700 text-gov-text focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
                        </div>
                        <div>
                            <label for="appointment_time" class="block text-sm font-medium text-gov-text-secondary mb-2">Preferred Time</label>
                            <select id="appointment_time" name="appointment_time" required
                                class="w-full px-4 py-2 rounded bg-secondary-black border border-gray-700 text-gov-text focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
                                <option value="">Select time</option>
                                <option value="09:00">09:00 AM</option>
                                <option value="10:00">10:00 AM</option>
                                <option value="11:00">11:00 AM</option>
                                <option value="14:00">02:00 PM</option>
                                <option value="15:00">03:00 PM</option>
                                <option value="16:00">04:00 PM</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label for="venue" class="block text-sm font-medium text-gov-text-secondary mb-2">Preferred Venue</label>
                        <select id="venue" name="venue" required
                            class="w-full px-4 py-2 rounded bg-secondary-black border border-gray-700 text-gov-text focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
                            <option value="">Select venue</option>
                            <option value="Hong Kong Island">Hong Kong Island Office</option>
                            <option value="Kowloon">Kowloon Office</option>
                            <option value="New Territories">New Territories Office</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between mt-6">
                <button type="button" onclick="window.history.back()"
                    class="px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white font-bold rounded transition-colors focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 focus:ring-offset-gov-gray">
                    Back
                </button>
                <button type="submit"
                    class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-gov-gray">
                    Submit Application
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('appointmentForm');

        form.addEventListener('submit', function(e) {
            e.preventDefault();

            // Client-side validation
            if (!validateForm()) {
                return;
            }

            // If validation passes, submit the form
            form.submit();
        });

        function validateForm() {
            // Add any additional client-side validation here
            return true;
        }
    });
</script>