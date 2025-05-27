<div class="max-w-md mx-auto">
    <div class="bg-gov-gray p-8 rounded-lg shadow-lg border border-gray-700">
        <h2 class="text-2xl font-bold mb-6 text-gov-text text-center">Register</h2>

        <?php if (isset($errors)): ?>
            <div class="bg-red-500/10 border border-red-500 text-red-500 px-4 py-3 rounded mb-4">
                <?php foreach ($errors as $error): ?>
                    <p class="text-red-500"><?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="/register" method="POST" class="space-y-6">

            <?php if (isset($register_errors)): ?>
                <div class="bg-red-500/10 border border-red-500 text-red-500 px-4 py-3 rounded mb-4">
                    <?php foreach ($register_errors as $error): ?>
                        <p class="text-red-500"><?= htmlspecialchars($error) ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div>
                <label for="name" class="block text-sm font-medium text-gov-text-secondary mb-2">Full Name</label>
                <input type="text" id="name" name="name" required
                    class="w-full px-4 py-2 rounded bg-secondary-black border border-gray-700 text-gov-text focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                    placeholder="Enter your full name" value="<?= htmlspecialchars($register_data['name'] ?? '') ?>">
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gov-text-secondary mb-2">Email</label>
                <input type="email" id="email" name="email" required
                    class="w-full px-4 py-2 rounded bg-secondary-black border border-gray-700 text-gov-text focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                    placeholder="Enter your email" value="<?= htmlspecialchars($register_data['email'] ?? '') ?>">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gov-text-secondary mb-2">Password</label>
                <input type="password" id="password" name="password" required
                    class="w-full px-4 py-2 rounded bg-secondary-black border border-gray-700 text-gov-text focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                    placeholder="Create a password" value="<?= htmlspecialchars($register_data['password'] ?? '') ?>">
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gov-text-secondary mb-2">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required
                    class="w-full px-4 py-2 rounded bg-secondary-black border border-gray-700 text-gov-text focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                    placeholder="Confirm your password" value="<?= htmlspecialchars($register_data['password_confirmation'] ?? '') ?>">
            </div>

            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-gov-gray">
                Create Account
            </button>
        </form>

        <div class="mt-6 text-center">
            <p class="text-sm text-gov-text-secondary">
                Already have an account?
                <a href="/login" class="text-blue-500 hover:text-blue-400 transition-colors">Login here</a>
            </p>
        </div>
    </div>
</div>