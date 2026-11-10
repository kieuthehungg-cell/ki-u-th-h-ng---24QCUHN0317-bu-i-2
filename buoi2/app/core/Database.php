<?php
// app/core/Database.php

class Database
{
    private static ?PDO $instance = null;

    private function __construct() {}

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $config = require __DIR__ . '/../../config/database.php';
            $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['dbname']};charset={$config['charset']}";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            try {
                self::$instance = new PDO($dsn, $config['username'], $config['password'], $options);
            } catch (PDOException $e) {
                // Tu dong tao database va nạp schema.sql neu chua ton tai
                try {
                    $initDsn = "mysql:host={$config['host']};port={$config['port']};charset={$config['charset']}";
                    $initPdo = new PDO($initDsn, $config['username'], $config['password'], $options);
                    $initPdo->exec("CREATE DATABASE IF NOT EXISTS `{$config['dbname']}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                    
                    self::$instance = new PDO($dsn, $config['username'], $config['password'], $options);
                    
                    $schemaFile = __DIR__ . '/../../database/schema.sql';
                    if (file_exists($schemaFile)) {
                        $sql = file_get_contents($schemaFile);
                        self::$instance->exec($sql);
                    }
                } catch (PDOException $ex) {
                    die("Lỗi kết nối cơ sở dữ liệu: " . $ex->getMessage());
                }
            }
        }

        return self::$instance;
    }
}
