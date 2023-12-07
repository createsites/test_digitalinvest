<?php


namespace App;


use Exception;

class DB
{
    private static $instance;
    private $dbHandle;

    /**
     * Singleton
     * @return DB
     */
    public static function getInstance()
    {
        if (empty(self::$instance)) self::$instance = new self();
        return self::$instance;
    }

    /**
     * DB connection
     * @throws Exception
     */
    public function init()
    {
        try {
            $this->dbHandle = new \PDO(
                'mysql:dbname=' . Config::DB_NAME . ';host=' . Config::DB_HOST,
                Config::DB_USER,
                Config::DB_PASS,
                [\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'cp1251'"]
            );
        } catch (\PDOException $e) {
            throw new Exception('DB connection failed: ' . $e->getMessage());
        }
    }

    /**
     * Prepare string for SQL request
     * @param $string string sql request
     * @return \PDOStatement|false
     */
    public function prepare($string)
    {
        return $this->dbHandle->prepare($string);
    }
}