<?php

namespace App\Services;

class DatabaseService
{

    private array $config;
    private string $backupPath;

    private EncryptionService $encryptionService;


    public function __construct()
    {
        $this->config = require __DIR__ . '/../../config/database.php';
        $this->backupPath = __DIR__ . '/../../backups/database';
        $this->encryptionService = new EncryptionService();

        // Create backup directory if it doesn't exist
        if (!file_exists($this->backupPath)) {
            mkdir($this->backupPath, 0755, true);
        }
    }

    public function backup()
    {
        // Generate backup filename with timestamp
        $timestamp = date('Y-m-d_H-i-s');
        $backupFile = "backup_{$timestamp}.sql";
        $backupPath = $this->backupPath . '/' . $backupFile;

        // Get database connection details from PDO
        $host = $this->config['connections']['pgsql']['host'];
        $dbname = $this->config['connections']['pgsql']['database'];

        // Create backup using pg_dump
        $command = sprintf(
            'PGPASSWORD=%s pg_dump -h %s -U %s -d %s -F p > %s',
            $this->config['connections']['pgsql']['password'],
            $host,
            $this->config['connections']['pgsql']['username'],
            $dbname,
            $backupPath
        );

        exec($command);

        // Read the backup file
        $backupContent = file_get_contents($backupPath);

        // Encrypt the backup content
        $encryptedContent = $this->encryptionService->encrypt($backupContent);

        // Save the encrypted backup
        file_put_contents($backupPath, $encryptedContent);

        return $backupPath;
    }

    public function restore()
    {
        // Get the latest backup file
        $backupFiles = glob($this->backupPath . '/*.sql');
        $latestBackup = end($backupFiles);
        echo 'latestBackup: ' . $latestBackup . "\n";

        // Read the encrypted backup file
        $encryptedContent = file_get_contents($latestBackup);
        echo 'encryptedContent length: ' . strlen($encryptedContent) . "\n";

        // Decrypt the backup content
        $decryptedContent = $this->encryptionService->decrypt($encryptedContent);
        echo 'decryptedContent length: ' . strlen($decryptedContent) . "\n";

        // Create database if it doesn't exist
        $createDbCommand = sprintf(
            'PGPASSWORD=%s psql -h %s -U %s -d postgres -c "CREATE DATABASE %s WITH OWNER = %s;" 2>/dev/null || true',
            $this->config['connections']['pgsql']['password'],
            $this->config['connections']['pgsql']['host'],
            $this->config['connections']['pgsql']['username'],
            $this->config['connections']['pgsql']['database'],
            $this->config['connections']['pgsql']['username']
        );

        exec($createDbCommand);

        // Save decrypted content to a temporary file
        $tempFile = tempnam(sys_get_temp_dir(), 'db_restore_');
        file_put_contents($tempFile, $decryptedContent);

        // Restore the backup
        $command = sprintf(
            'PGPASSWORD=%s psql -h %s -U %s -d %s -f %s',
            $this->config['connections']['pgsql']['password'],
            $this->config['connections']['pgsql']['host'],
            $this->config['connections']['pgsql']['username'],
            $this->config['connections']['pgsql']['database'],
            $tempFile
        );

        exec($command);

        // Clean up temporary file
        unlink($tempFile);
    }
}
