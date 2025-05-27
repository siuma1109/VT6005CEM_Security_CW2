<?php

use App\Services\AuthService;

$authService = new AuthService();
$user = $authService->getUser();
?>

<header class="bg-gov-gray border-b border-gray-700 shadow-lg">
    <nav class="container mx-auto px-6 py-3">
        <div class="flex items-center justify-between">
            <div class="text-xl font-bold">
                <a href="/" class="text-gov-text hover:text-gov-text-secondary transition-colors">HKID Card Management System</a>
            </div>
            <div class="flex items-center space-x-6">

                <?php if ($user): ?>
                    <span class="text-gov-text"><?= $user->name ?></span>
                <?php endif; ?>

                <button type="button" onclick="toggleDarkMode()" class="p-2 rounded-lg bg-gov-light-gray hover:bg-gray-600 transition-colors" title="Toggle dark mode">
                    <!-- Moon icon for light mode -->
                    <svg id="moon-icon" class="w-6 h-6 text-gov-text hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                    </svg>
                    <!-- Sun icon for dark mode -->
                    <svg id="sun-icon" class="w-6 h-6 text-gov-text" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </button>
                <?php if ($user): ?>
                    <a href="/logout" class="text-gov-text hover:text-gov-text-secondary transition-colors flex items-center space-x-2">
                        <span class="hidden md:inline">Logout</span>
                    </a>
                <?php else: ?>
                    <a href="/login" class="text-gov-text hover:text-gov-text-secondary transition-colors flex items-center space-x-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                        </svg>
                        <span class="hidden md:inline">Login</span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
</header>