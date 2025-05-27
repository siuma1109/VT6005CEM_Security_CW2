<?php

namespace Core\Database\Migration;

use PDO;

abstract class Migration implements MigrationInterface
{
    protected PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Execute a SQL statement
     *
     * @param string $sql
     * @return void
     */
    protected function execute(string $sql): void
    {
        $this->db->exec($sql);
    }

    /**
     * Create a new table
     *
     * @param string $table
     * @param callable $callback
     * @return void
     */
    protected function createTable(string $table, callable $callback): void
    {
        $blueprint = new Blueprint($table);
        $callback($blueprint);
        $this->execute($blueprint->toSql());
    }

    /**
     * Drop a table
     *
     * @param string $table
     * @return void
     */
    protected function dropTable(string $table): void
    {
        $this->execute("DROP TABLE IF EXISTS {$table}");
    }
}
