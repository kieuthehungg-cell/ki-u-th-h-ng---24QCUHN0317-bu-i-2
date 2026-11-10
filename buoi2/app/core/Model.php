<?php
// app/core/Model.php

abstract class Model
{
    protected static string $table = '';

    public static function db(): PDO
    {
        return Database::getInstance();
    }

    public static function find(int $id): ?array
    {
        $stmt = static::db()->prepare("SELECT * FROM " . static::$table . " WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row === false ? null : $row;
    }

    public static function all(string $orderBy = 'id DESC'): array
    {
        $sql = "SELECT * FROM " . static::$table;
        if (!empty($orderBy)) {
            $sql .= " ORDER BY " . $orderBy;
        }
        $stmt = static::db()->query($sql);
        return $stmt->fetchAll();
    }

    public static function insert(array $data): int
    {
        $keys = array_keys($data);
        $fields = implode(', ', $keys);
        $placeholders = ':' . implode(', :', $keys);
        $sql = "INSERT INTO " . static::$table . " ($fields) VALUES ($placeholders)";
        $stmt = static::db()->prepare($sql);
        $stmt->execute($data);
        return (int) static::db()->lastInsertId();
    }

    public static function update(int $id, array $data): bool
    {
        $sets = [];
        $params = [':_id' => $id];
        foreach ($data as $key => $val) {
            $sets[] = "$key = :$key";
            $params[":$key"] = $val;
        }
        $sql = "UPDATE " . static::$table . " SET " . implode(', ', $sets) . " WHERE id = :_id";
        $stmt = static::db()->prepare($sql);
        return $stmt->execute($params);
    }

    public static function delete(int $id): bool
    {
        $stmt = static::db()->prepare("DELETE FROM " . static::$table . " WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
