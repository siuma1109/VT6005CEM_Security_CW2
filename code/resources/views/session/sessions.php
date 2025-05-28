<?php

use App\Services\CsrfService;
use App\Services\DatabaseSessionService;

$csrf_service = new CsrfService();
?>

<div class="max-w-4xl mx-auto">
    <div class="bg-gov-gray p-8 rounded-lg shadow-lg border border-gray-700">
        <h2 class="text-2xl font-bold mb-6 text-gov-text text-center">Your Sessions</h2>

        <?php if (empty($sessions)): ?>
            <div class="text-center text-gov-text-secondary py-8">
                <p>You have no active sessions.</p>
            </div>
        <?php else: ?>
            <div class="space-y-4">
                <?php foreach ($sessions as $session): ?>
                    <div class="bg-secondary-black p-6 rounded-lg border border-gray-700">
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <?php if ($session->id == DatabaseSessionService::getSession()->id): ?>
                                    <div class="bg-gov-gray p-4 rounded-lg border border-gray-700 mb-4 success-box">
                                        <h2 class="text-lg font-semibold text-gov-text mb-2">This is your current session</h2>
                                    </div>
                                <?php endif; ?>
                                <h3 class="text-lg font-semibold text-gov-text mb-2">Session Information</h3>
                                <p class="text-gov-text-secondary">
                                    <span class="font-medium">Session ID:</span>
                                    <?= htmlspecialchars($session->id) ?>
                                </p>
                                <p class="text-gov-text-secondary">
                                    <span class="font-medium">User ID:</span>
                                    <?= htmlspecialchars($session->user_id) ?>
                                </p>
                                <p class="text-gov-text-secondary">
                                    <span class="font-medium">IP Address:</span>
                                    <?= htmlspecialchars($session->ip_address) ?>
                                </p>
                                <p class="text-gov-text-secondary">
                                    <span class="font-medium">User Agent:</span>
                                    <?= htmlspecialchars($session->user_agent) ?>
                                </p>
                                <p class="text-gov-text-secondary">
                                    <span class="font-medium">Last Activity:</span>
                                    <?= date('Y-m-d H:i:s', $session->last_activity) ?>
                                </p>
                                <div class="mt-2">
                                    <p class="text-gov-text-secondary">
                                        <span class="font-medium">Payload:</span>
                                    </p>
                                    <pre class="bg-gray-800 p-2 rounded mt-1 text-sm text-gov-text-secondary overflow-x-auto">
                                        <?= trim(htmlspecialchars(json_encode(json_decode($session->payload), JSON_PRETTY_PRINT))) ?>
                                    </pre>
                                </div>
                                <div class="mt-4">
                                    <form action="/delete-session" method="POST" class="inline">
                                        <input type="hidden" name="csrf_token" value="<?= $csrf_service->generateToken() ?>">
                                        <input type="hidden" name="session_id" value="<?= htmlspecialchars($session->id) ?>">
                                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded transition-colors">
                                            Delete Session
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>