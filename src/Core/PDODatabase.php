<?php

namespace Pamutlabor\Core;

class PDODatabase extends Database
{
    private $pdo;

    public function __construct()
    {
        $config = include __DIR__ . '/../Config/config.php';
        $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4";
        try {
            $this->pdo = new \PDO($dsn, $config['user'], $config['password']);
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            die("Adatbázis kapcsolódási hiba: " . $e->getMessage());
        }
    }

    public function getConnection()
    {
        return $this->pdo;
    }
}