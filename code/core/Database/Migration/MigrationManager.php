<?php

namespace Core\Database\Migration;

use PDO;

class MigrationManager
{
    protected PDO $db;
    protected string $migrationsTable = 'migrations';
    protected string $migrationsPath;

    public function __construct(PDO $db, string $migrationsPath)
    {
        $this->db = $db;
        $this->migrationsPath = $migrationsPath;
        $this->createMigrationsTable();
    }

    protected function createMigrationsTable(): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS {$this->migrationsTable} (
            id SERIAL PRIMARY KEY,
            migration VARCHAR(255) NOT NULL,
            batch INTEGER NOT NULL
        )";
        $this->db->exec($sql);
    }

    public function getMigrations(): array
    {
        $files = glob($this->migrationsPath . '/*.php');
        return array_map(function ($file) {
            return basename($file, '.php');
        }, $files);
    }

    public function getRanMigrations(): array
    {
        $stmt = $this->db->query("SELECT migration FROM {$this->migrationsTable} ORDER BY batch, migration");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getPendingMigrations(): array
    {
        return array_diff($this->getMigrations(), $this->getRanMigrations());
    }

    public function getNextBatchNumber(): int
    {
        $stmt = $this->db->query("SELECT MAX(batch) FROM {$this->migrationsTable}");
        return (int) $stmt->fetchColumn() + 1;
    }

    public function runMigrations(): void
    {
        $migrations = $this->getPendingMigrations();
        if (empty($migrations)) {
            echo "Nothing to migrate.\n";
            return;
        }

        $batch = $this->getNextBatchNumber();
        foreach ($migrations as $migration) {
            $this->runMigration($migration, $batch);
        }
    }

    protected function runMigration(string $migration, int $batch): void
    {
        $class = $this->getMigrationClass($migration);
        $instance = new $class($this->db);

        echo "Migrating: {$migration}\n";
        $instance->up();

        $stmt = $this->db->prepare("INSERT INTO {$this->migrationsTable} (migration, batch) VALUES (?, ?)");
        $stmt->execute([$migration, $batch]);

        echo "Migrated: {$migration}\n";
    }

    public function rollback(): void
    {
        $batch = $this->getLastBatchNumber();

        if ($batch === 0) {
            echo "Nothing to rollback.\n";
            return;
        }

        $migrations = $this->getMigrationsForBatch($batch);
        foreach (array_reverse($migrations) as $migration) {
            $this->rollbackMigration($migration);
        }
    }

    protected function getLastBatchNumber(): int
    {
        $stmt = $this->db->query("SELECT MAX(batch) FROM {$this->migrationsTable}");
        return (int) $stmt->fetchColumn();
    }

    protected function getMigrationsForBatch(int $batch): array
    {
        $stmt = $this->db->prepare("SELECT migration FROM {$this->migrationsTable} WHERE batch = ? ORDER BY migration");
        $stmt->execute([$batch]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    protected function rollbackMigration(string $migration): void
    {
        $class = $this->getMigrationClass($migration);
        $instance = new $class($this->db);

        echo "Rolling back: {$migration}\n";
        $instance->down();

        $stmt = $this->db->prepare("DELETE FROM {$this->migrationsTable} WHERE migration = ?");
        $stmt->execute([$migration]);

        echo "Rolled back: {$migration}\n";
    }

    protected function getMigrationClass(string $migration): string
    {
        $file = $this->migrationsPath . '/' . $migration . '.php';
        require_once $file;

        // Extract the class name from the filename
        // Remove the timestamp prefix (format: YYYY_MM_DD_HHMMSS_) and convert to PascalCase
        $className = preg_replace('/^\d{4}_\d{2}_\d{2}_\d{6}_/', '', $migration);
        $className = str_replace(['_', '-'], '', ucwords($className, '_'));

        // Return the fully qualified class name with namespace (without trailing backslash)
        return 'Database\\Migrations\\' . $className;
    }
}
