<?php


namespace Source\Database;
require __DIR__ . "/../Config/Config.php";

class Connect
{

    private $pdo;

    public function __construct()
    {
        try {
            if (!isset($this->pdo)) {
                $this->pdo = new \PDO("mysql:host=" . DATABASE['DB_HOST'] . ";dbname=" .
                    DATABASE["DB_NAME"], DATABASE["DB_USER"], DATABASE["DB_PASSWD"], [
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_CASE => \PDO::CASE_NATURAL
                ]);

            }
        } catch (\PDOException $exception) {
            echo $exception->getMessage();
        }
    }

    public function Connect()
    {
        return $this->pdo;
    }

}