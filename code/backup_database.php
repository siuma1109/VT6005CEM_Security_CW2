<?php

require_once __DIR__ . '/vendor/autoload.php';

use Core\Console\Commands\BackupCommand;

// Create and run the backup command
$command = new BackupCommand();
$command->handle($argv);
