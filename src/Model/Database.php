<?php
namespace Simoncdt\Api\Model;

use Simoncdt\Api\Model\Config;

// Classe pour connexion avec la BDD
class Database
{
    private static ?Database $instance = null;


    private \PDO $pdo;

    public static function getDB(): Database
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }

        return self::$instance;
    }
    private function __construct()
    {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8";
        $opt = [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_EMULATE_PREPARES => false
        ];

        $this->pdo = new \PDO($dsn, DB_USER, DB_PASSWORD, $opt);
    }

    public function run($query, $param = [])
    {
        $statement = $this->pdo->prepare($query);
        $statement->execute($param);
        return $statement;
    }

    public function lastInsertId(): string|false
    {
        return $this->pdo->lastInsertId();
    }
}
