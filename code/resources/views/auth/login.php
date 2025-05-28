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

        <form action="/login" method="POST" class="space-y-6">
            <input type="hidden" name="csrf_token" value="<?= $csrf_service->generateToken() ?>">

            <div>
                <label for="email" class="block text-sm font-medium text-gov-text-secondary mb-2">Email</label>
                <input type="email" id="email" name="email" required
                    class="w-full px-4 py-2 rounded bg-secondary-black border border-gray-700 text-gov-text focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                    placeholder="Enter your email" value="<?= htmlspecialchars($login_data['email'] ?? '') ?>">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gov-text-secondary mb-2">Password</label>
                <input type="password" id="password" name="password" required
                    class="w-full px-4 py-2 rounded bg-secondary-black border border-gray-700 text-gov-text focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                    placeholder="Enter your password">
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input type="checkbox" id="remember" name="remember"
                        class="h-4 w-4 rounded border-gray-700 bg-secondary-black text-blue-500 focus:ring-blue-500 focus:ring-offset-0">
                    <label for="remember" class="ml-2 block text-sm text-gov-text-secondary">Remember me</label>
                </div>
                <!-- <a href="/forgot-password" class="text-sm text-blue-500 hover:text-blue-400 transition-colors">Forgot password?</a> -->
            </div>

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