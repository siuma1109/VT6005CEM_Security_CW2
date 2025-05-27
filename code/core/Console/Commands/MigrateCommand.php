<?php

namespace Core\Console\Commands;

use Core\Database\Migration\MigrationManager;
use PDO;

class MigrateCommand
{
    protected PDO $db;
    protected string $migrationsPath;

    public function __construct(PDO $db, string $migrationsPath)
    {
        $this->db = $db;
        $this->migrationsPath = $migrationsPath;
    }

    public function handle(array $args): void
    {
        $manager = new MigrationManager($this->db, $this->migrationsPath);
        if (in_array('--rollback', $args)) {
            $manager->rollback();
        } else {
            $manager->runMigrations();
        }
    }
}
