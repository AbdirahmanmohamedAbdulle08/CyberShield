<?php
namespace App\Core;

use PDO;
use PDOException;

/**
 * CyberShield Database Manager
 * Implements Singleton Pattern for PDO Connection
 */
class Database {
    private static ?PDO $instance = null;

    private function __construct() {}
    private function __clone() {}

    public static function getInstance(): PDO {
        if (self::$instance === null) {
            $config = require __DIR__ . '/../../config/database.php';
            $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['dbname']};charset={$config['charset']}";
            
            try {
                self::$instance = new PDO($dsn, $config['username'], $config['password'], $config['options']);
            } catch (PDOException $e) {
                // In production, avoid leaking DSN details
                error_log("CyberShield Database Connection Error: " . $e->getMessage());
                die("<div style='background:#11192e;color:#f87171;padding:30px;font-family:sans-serif;border:1px solid #ef4444;border-radius:8px;max-width:650px;margin:50px auto;'>"
                    . "<h2 style='margin-top:0;'>⚠️ Database Connection Failed</h2>"
                    . "<p>Unable to connect to MySQL database <strong>{$config['dbname']}</strong> at {$config['host']}:{$config['port']}.</p>"
                    . "<p>Please ensure Apache and MySQL are running in XAMPP, and that the database schema is imported.</p>"
                    . "<p><a href='setup.php' style='color:#38bdf8;font-weight:bold;'>Launch CyberShield Setup Wizard &rarr;</a></p>"
                    . "</div>");
            }
        }
        return self::$instance;
    }

    /**
     * Helper to run prepared queries easily
     */
    public static function query(string $sql, array $params = []): \PDOStatement {
        $stmt = self::getInstance()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public static function fetchAll(string $sql, array $params = []): array {
        return self::query($sql, $params)->fetchAll();
    }

    public static function fetchOne(string $sql, array $params = []): ?array {
        $result = self::query($sql, $params)->fetch();
        return $result ?: null;
    }

    public static function fetchColumn(string $sql, array $params = []) {
        return self::query($sql, $params)->fetchColumn();
    }

    public static function lastInsertId(): string {
        return self::getInstance()->lastInsertId();
    }
}
