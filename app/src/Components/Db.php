<?php

namespace App\Components;

use PDO;

class Db
{
    protected static $instance;
    private $connect;

    private function __construct(PDO $pdo)
    {
        $this->connect = $pdo;
    }

    public static function getInstance(): Db
    {
        if (static::$instance === null) {
            self::$instance = new static();
        }

        return static::$instance;
    }

    public function getConnect(): PDO
    {
        return $this->connect;
    }

    public function insert(string $table, array $values): int
    {
        $columns = '`' . implode('`, `', array_keys($values)) . '`';
        $placeholders = implode(', ', array_fill(0, count($values), '?'));

        $this->connect->prepare("INSERT INTO $table ($columns) VALUE ($placeholders)")
            ->execute(array_values($values));

        return $this->connect->lastInsertId();
    }

    public function update(string $table, array $sets, array $where): bool
    {
        $setPlaceholder = '`' . implode('` = ?, `', array_keys($sets)) . '`';
        $setPlaceholder .= ' = ?';
        $wherePlaceholder = '`' . implode('` = ?, `', array_keys($where)) . '` = ?';
        return $this->connect->prepare("UPDATE $table SET $setPlaceholder WHERE $wherePlaceholder")
            ->execute(array_merge(array_values($sets), array_values($where)));
    }

    public function select(string $table, array $where = []): \PDOStatement
    {
        $sql = "SELECT * FROM {$table}";

        if ($where) {
            $wherePlaceholder = '' . implode('`` = ?, ', array_keys($where)) . ' = ?';
            $sql .= " WHERE $wherePlaceholder";
        }
        return $this->sql($sql, ...array_values($where));
    }

    public function sql(string $sql, ...$params): \PDOStatement
    {
        $sth = $this->connect->prepare($sql);
        $sth->execute($params);
        return $sth;
    }

    private function __clone()
    {
    }
}
