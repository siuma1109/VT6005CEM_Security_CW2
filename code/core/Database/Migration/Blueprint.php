<?php

namespace Core\Database\Migration;

class Blueprint
{
    protected string $table;
    protected array $columns = [];
    protected array $primaryKey = [];
    protected array $foreignKeys = [];
    protected array $indexes = [];

    public function __construct(string $table)
    {
        $this->table = $table;
    }

    public function id(): self
    {
        return $this->bigIncrements('id');
    }

    public function bigIncrements(string $column): self
    {
        $this->columns[] = "{$column} BIGSERIAL PRIMARY KEY";
        return $this;
    }

    public function string(string $column, int $length = 255): self
    {
        $this->columns[] = "{$column} VARCHAR({$length})";
        return $this;
    }

    public function text(string $column): self
    {
        $this->columns[] = "{$column} TEXT";
        return $this;
    }

    public function integer(string $column): self
    {
        $this->columns[] = "{$column} INTEGER";
        return $this;
    }

    public function bigInteger(string $column): self
    {
        $this->columns[] = "{$column} BIGINT";
        return $this;
    }

    public function boolean(string $column): self
    {
        $this->columns[] = "{$column} BOOLEAN";
        return $this;
    }

    public function timestamp(string $column): self
    {
        $this->columns[] = "{$column} TIMESTAMP";
        return $this;
    }

    public function timestamps(): self
    {
        $this->timestamp('created_at');
        $this->timestamp('updated_at');
        return $this;
    }

    public function foreign(string $column): self
    {
        $this->foreignKeys[] = $column;
        return $this;
    }

    public function references(string $column): self
    {
        $this->foreignKeys[] = "REFERENCES {$column}";
        return $this;
    }

    public function on(string $table): self
    {
        $this->foreignKeys[] = "ON {$table}";
        return $this;
    }

    public function unique(): self
    {
        $lastColumn = array_pop($this->columns);
        $this->columns[] = $lastColumn . ' UNIQUE';
        return $this;
    }

    public function primary(): self
    {
        $lastColumn = array_pop($this->columns);
        $this->columns[] = $lastColumn . ' PRIMARY KEY';
        return $this;
    }

    public function foreignId(string $column): self
    {
        $this->columns[] = "{$column} BIGINT";
        return $this;
    }

    public function nullable(): self
    {
        $lastColumn = array_pop($this->columns);
        $this->columns[] = $lastColumn . ' NULL';
        return $this;
    }

    public function index(): self
    {
        $lastColumn = array_pop($this->columns);
        $this->columns[] = $lastColumn;

        // Extract just the column name from the definition
        preg_match('/^(\w+)\s+/', $lastColumn, $matches);
        $columnName = $matches[1] ?? '';

        if ($columnName) {
            $this->indexes[] = "CREATE INDEX {$this->table}_{$columnName}_index ON {$this->table} ({$columnName})";
        }

        return $this;
    }

    public function longText(string $column): self
    {
        $this->columns[] = "{$column} TEXT";
        return $this;
    }

    public function toSql(): string
    {
        $sql = "CREATE TABLE IF NOT EXISTS {$this->table} (";
        $sql .= implode(', ', $this->columns);

        if (!empty($this->foreignKeys)) {
            $sql .= ', ' . implode(' ', $this->foreignKeys);
        }

        $sql .= ')';

        if (!empty($this->indexes)) {
            $sql .= '; ' . implode('; ', $this->indexes);
        }

        return $sql;
    }
}
