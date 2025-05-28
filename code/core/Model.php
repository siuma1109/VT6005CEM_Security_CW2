<?php

namespace Core;

use PDO;
use App\Database\Database;

abstract class Model
{
    protected static $connection;
    protected static $table;
    protected $attributes = [];
    protected $original = [];
    protected $exists = false;

    // Query builder properties
    protected static $query = '';
    protected static $where = [];
    protected static $orderBy = [];
    protected static $limit = null;
    protected static $offset = null;
    protected static $bindings = [];

    // Static initialization block
    public static function initialize()
    {
        if (!static::$connection) {
            static::$connection = Database::getInstance()->getConnection();
        }
    }

    public function __construct(array $attributes = [])
    {
        static::initialize();

        // If id is provided, fetch the record from database
        if (isset($attributes['id'])) {
            $id = $attributes['id'];
            unset($attributes['id']); // Remove id from attributes to avoid duplicate setting

            $query = "SELECT * FROM " . static::getTable() . " WHERE id = ?";
            $stmt = static::getConnection()->prepare($query);
            $stmt->execute([$id]);

            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $this->fill($row);
                $this->exists = true;
                return;
            }
        }

        $this->fill($attributes);
    }

    public static function setConnection(PDO $connection)
    {
        static::$connection = $connection;
    }

    public static function getConnection()
    {
        if (!static::$connection) {
            throw new \Exception('Database connection not set');
        }
        return static::$connection;
    }

    public function fill(array $attributes)
    {
        foreach ($attributes as $key => $value) {
            $this->setAttribute($key, $value);
        }
        return $this;
    }

    public function setAttribute($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    public function getAttribute($key)
    {
        return $this->attributes[$key] ?? null;
    }

    public function __get($key)
    {
        return $this->getAttribute($key);
    }

    public function __set($key, $value)
    {
        $this->setAttribute($key, $value);
    }

    protected static function getTable()
    {
        if (static::$table) {
            return static::$table;
        }

        // Convert class name to table name (e.g., UserModel -> users)
        $class = (new \ReflectionClass(static::class))->getShortName();
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $class)) . 's';
    }

    public static function query()
    {
        static::resetQuery();
        return new static();
    }

    protected static function resetQuery()
    {
        static::$query = '';
        static::$where = [];
        static::$orderBy = [];
        static::$limit = null;
        static::$offset = null;
        static::$bindings = [];
    }

    public static function where($column, $operator = null, $value = null)
    {
        if (func_num_args() === 2) {
            $value = $operator;
            $operator = '=';
        }

        static::$where[] = [
            'column' => $column,
            'operator' => $operator,
            'value' => $value
        ];
        static::$bindings[] = $value;

        return new static();
    }

    public static function whereIn($column, array $values)
    {
        $placeholders = implode(',', array_fill(0, count($values), '?'));
        static::$where[] = [
            'column' => $column,
            'operator' => 'IN',
            'value' => "($placeholders)"
        ];
        static::$bindings = array_merge(static::$bindings, $values);

        return new static();
    }

    public static function orderBy($column, $direction = 'ASC')
    {
        static::$orderBy[] = [
            'column' => $column,
            'direction' => strtoupper($direction) === 'DESC' ? 'DESC' : 'ASC'
        ];
        return new static();
    }

    public static function limit($limit, $offset = 0)
    {
        static::$limit = $limit;
        static::$offset = $offset;
        return new static();
    }

    protected static function buildQuery()
    {
        $query = "SELECT * FROM " . static::getTable();

        if (!empty(static::$where)) {
            $query .= " WHERE ";
            $conditions = [];
            foreach (static::$where as $where) {
                if ($where['operator'] === 'IN') {
                    $conditions[] = "{$where['column']} IN {$where['value']}";
                } else {
                    $conditions[] = "{$where['column']} {$where['operator']} ?";
                }
            }
            $query .= implode(' AND ', $conditions);
        }

        if (!empty(static::$orderBy)) {
            $query .= " ORDER BY ";
            $orders = [];
            foreach (static::$orderBy as $order) {
                $orders[] = "{$order['column']} {$order['direction']}";
            }
            $query .= implode(', ', $orders);
        }

        if (static::$limit !== null) {
            $query .= " LIMIT ?";
            static::$bindings[] = static::$limit;

            if (static::$offset !== null) {
                $query .= " OFFSET ?";
                static::$bindings[] = static::$offset;
            }
        }

        return $query;
    }

    public static function get()
    {
        $query = static::buildQuery();
        $stmt = static::getConnection()->prepare($query);
        $stmt->execute(static::$bindings);
        static::resetQuery();
        return $stmt->fetchAll(PDO::FETCH_CLASS, static::class);
    }

    public static function first(): null | Model
    {
        static::limit(1);
        $query = static::buildQuery();
        $stmt = static::getConnection()->prepare($query);
        $stmt->execute(static::$bindings);
        static::resetQuery();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$result) {
            return null;
        }

        $model = new static();
        $model->fill($result);
        $model->exists = true;
        return $model;
    }

    public static function count()
    {
        $query = "SELECT COUNT(*) as count FROM " . static::getTable();

        if (!empty(static::$where)) {
            $query .= " WHERE ";
            $conditions = [];
            foreach (static::$where as $where) {
                if ($where['operator'] === 'IN') {
                    $conditions[] = "{$where['column']} IN {$where['value']}";
                } else {
                    $conditions[] = "{$where['column']} {$where['operator']} ?";
                }
            }
            $query .= implode(' AND ', $conditions);
        }

        $stmt = static::getConnection()->prepare($query);
        $stmt->execute(static::$bindings);
        static::resetQuery();
        return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    }

    public static function all()
    {
        return static::query()->get();
    }

    public static function find($id)
    {
        return static::where('id', $id)->first();
    }

    public static function create(array $attributes): Model|null
    {
        $model = new static($attributes);
        return $model->save();
    }

    public function save(): Model|null
    {
        if ($this->exists) {
            return $this->update();
        }
        return $this->insert();
    }

    protected function insert(): Model|null
    {
        $attributes = $this->attributes;
        $attributes = array_intersect_key($attributes, array_flip($this->fillable));
        $columns = implode(', ', array_keys($attributes));
        $values = implode(', ', array_fill(0, count($attributes), '?'));

        $query = "INSERT INTO " . static::getTable() . " ($columns) VALUES ($values) RETURNING id";
        $stmt = static::getConnection()->prepare($query);

        if ($stmt->execute(array_values($attributes))) {
            $this->id = $stmt->fetchColumn();
            $this->exists = true;
            return $this;
        }
        return null;
    }

    protected function update(): Model|null
    {
        $attributes = array_intersect_key($this->attributes, array_flip($this->fillable));
        $sets = [];
        $values = [];

        foreach ($attributes as $key => $value) {
            $sets[] = "$key = ?";
            $values[] = $value;
        }

        if (empty($sets)) {
            return $this;
        }

        $query = "UPDATE " . static::getTable() . " SET " . implode(', ', $sets) . " WHERE id = ?";
        $values[] = $this->id;

        $stmt = static::getConnection()->prepare($query);
        if ($stmt->execute($values)) {
            return $this;
        }
        return null;
    }

    public function delete()
    {
        if (!$this->exists) {
            return false;
        }

        $query = "DELETE FROM " . static::getTable() . " WHERE id = ?";
        $stmt = static::getConnection()->prepare($query);
        return $stmt->execute([$this->id]);
    }
}
