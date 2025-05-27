<div class="max-w-md mx-auto">
    <div class="bg-gov-gray p-8 rounded-lg shadow-lg border border-gray-700">
        <h2 class="text-2xl font-bold mb-6 text-gov-text text-center">Two-Factor Authentication</h2>

        <?php if (isset($errors)): ?>
            <div class="bg-red-500/10 border border-red-500 text-red-500 px-4 py-3 rounded mb-4">
                <?php foreach ($errors as $error): ?>
                    <p class="text-red-500"><?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="/mfa" method="POST" class="space-y-6">
            <div>
                <label for="mfa_code" class="block text-sm font-medium text-gov-text-secondary mb-2">Enter 6-digit code</label>
                <input type="text" id="mfa_code" name="mfa_code" required maxlength="6" pattern="[0-9]{6}"
                    class="w-full px-4 py-2 rounded bg-secondary-black border border-gray-700 text-gov-text focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors text-center tracking-widest text-2xl"
                    placeholder="000000" autocomplete="one-time-code">
            </div>

            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-gov-gray">
                Verify Code
            </button>
        </form>

        <div class="mt-6 text-center">
            <p class="text-sm text-gov-text-secondary">
                Didn't receive a code?
                <a href="/resend-mfa" class="text-blue-500 hover:text-blue-400 transition-colors">Resend code</a>
            </p>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mfaInput = document.getElementById('mfa_code');

        // Only allow numbers
        mfaInput.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        // Auto-focus the input
        mfaInput.focus();
    });
</script>