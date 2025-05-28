<?php

namespace Core\Console\Commands;

use App\Services\DatabaseService;

class BackupCommand
{
    public function __construct() {}

    public function handle(array $args): void
    {
        $databaseService = new DatabaseService();
        if (in_array('--restore', $args)) {
            $databaseService->restore();
        } else {
            $databaseService->backup();
        }

    }
}
