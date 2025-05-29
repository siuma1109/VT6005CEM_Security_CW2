<?php

use App\Services\CsrfService;

$csrf_service = new CsrfService();
?>

<div class="max-w-md mx-auto">
    <div class="bg-gov-gray p-8 rounded-lg shadow-lg border border-gray-700">
        <h2 class="text-2xl font-bold mb-6 text-gov-text text-center">Login</h2>

        <?php if (isset($errors)): ?>
            <div class="bg-red-500/10 border border-red-500 text-red-500 px-4 py-3 rounded mb-4">
                <?php foreach ($errors as $error): ?>
                    <p class="text-red-500"><?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="/login" method="POST" class="space-y-6" id="login-form">
            <input type="hidden" name="csrf_token" value="<?= $csrf_service->generateToken() ?>">
            <input type="hidden" name="h-captcha-response" id="h-captcha-response">

            <div>
                <label for="email" class="block text-sm font-medium text-gov-text-secondary mb-2">Email</label>
                <input type="email" id="email" name="email" required
                    class="w-full px-4 py-2 rounded bg-secondary-black border border-gray-700 text-gov-text focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                    placeholder="Enter your email" value="<?= htmlspecialchars($login_data['email'] ?? '') ?>">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gov-text-secondary mb-2">Password</label>
                <div class="relative">
                    <input type="password" id="password" name="password" required
                        class="w-full px-4 py-2 rounded bg-secondary-black border border-gray-700 text-gov-text focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                        placeholder="Enter your password">
                    <button type="button" id="toggle-password" class="absolute right-2 top-2 text-gray-500">
                        Show
                    </button>
                </div>
            </div>

            <div id="h-captcha-container" class="h-captcha" data-sitekey="10000000-ffff-ffff-ffff-000000000001"></div>

            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-gov-gray">
                Sign in
            </button>
        </form>

        <div class="mt-6 text-center">
            <p class="text-sm text-gov-text-secondary">
                Don't have an account?
                <a href="/register" class="text-blue-500 hover:text-blue-400 transition-colors">Register here</a>
            </p>
        </div>
    </div>
</div>

<script src="https://js.hcaptcha.com/1/api.js" async defer></script>
<script>
    window.onload = function() {
        document.getElementById('login-form').addEventListener('submit', function(e) {
            e.preventDefault();

            if (typeof hcaptcha === 'undefined') {
                alert('Please wait for the security check to load');
                return;
            }

            const response = hcaptcha.getResponse();
            document.getElementById('h-captcha-response').value = response;
            document.getElementById('login-form').submit();
        });

        document.getElementById('toggle-password').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const toggleButton = this;
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleButton.textContent = 'Hide';
            } else {
                passwordInput.type = 'password';
                toggleButton.textContent = 'Show';
            }
        });
    };
</script>