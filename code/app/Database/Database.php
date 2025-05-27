<?php

namespace App\Database;

use PDO;
use PDOException;

class Database
{
    private static $instance = null;
    private $connection;

    private function __construct()
    {
        $config = require __DIR__ . '/../../config/database.php';
        $default = $config['default'];
        $connection = $config['connections'][$default];

        try {
            $dsn = sprintf(
                "%s:host=%s;dbname=%s",
                $connection['driver'],
                $connection['host'],
                $connection['database']
            );

            $this->connection = new PDO(
                $dsn,
                $connection['username'],
                $connection['password'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::ATTR_ORACLE_NULLS => PDO::NULL_NATURAL
                ]
            );

            // Set charset using PostgreSQL's client_encoding
            $this->connection->exec("SET client_encoding TO '" . $connection['charset'] . "'");
            // Set sequence name format for PostgreSQL
            $this->connection->exec("SET search_path TO public");
        } catch (PDOException $e) {
            throw new \Exception("Connection failed: " . $e->getMessage());
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->connection;
    }
}
