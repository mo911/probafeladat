<?php

namespace Pamutlabor\Core;

class PDODatabase extends Database
{
    static private $pdo;

    public function __construct()
    {
        $config = include __DIR__ . '/../Config/config.php';
        $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4";
        try {
            $pdo = new \PDO($dsn, $config['user'], $config['password']);
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            self::$pdo = $pdo;
        } catch (\PDOException $e) {
            die("Adatbázis kapcsolódási hiba: " . $e->getMessage());
        }
    }

    public static function getConnection()
    {
        if(!isset(self::$pdo)){
            new PDODatabase()->getConnection();
        }
        return self::$pdo;
    }
}