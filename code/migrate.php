<?php

require_once __DIR__ . '/vendor/autoload.php';

use Core\Console\Commands\MigrateCommand;
use App\Database\Database;

// Get database connection
$db = Database::getInstance()->getConnection();

// Path to migrations
$migrationsPath = __DIR__ . '/database/migrations';

// Create and run the migrate command
$command = new MigrateCommand($db, $migrationsPath);
$command->handle($argv);
