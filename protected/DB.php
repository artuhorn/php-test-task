<?php

namespace App;

use PDO;

/**
 * Class DB
 * @package App
 */
class DB
{
    /** @var string */
    public $lastId = null;

    protected static $instance = null;

    /** @var PDO */
    protected $dbh;

    /**
     * @return static
     */
    public static function instance()
    {
        if (static::$instance == null) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    protected function __construct()
    {
        $json = file_get_contents(__DIR__ . './config.json');
        $config = json_decode($json, true);

        $dsn = 'mysql:dbname=' . $config['db']['dbname'] .
            ';host=' . $config['db']['host'] . ':' . $config['db']['port'];

        $dbh = new PDO($dsn, $config['db']['login'], $config['db']['password']);

        $this->dbh = $dbh;
    }

    /**
     * @param string $query
     * @param array $params
     * @return bool
     */
    public function execute(string $query, array $params = []): bool
    {
        $sth = $this->dbh->prepare($query);
        $result = $sth->execute($params);
        $this->lastId = $this->dbh->lastInsertId();

        return $result;
    }

    /**
     * @param string $query
     * @param array $params
     * @param string $class
     * @return array
     */
    public function query(string $query, array $params = [], string $class = 'stdClass'): array
    {
        $sth = $this->dbh->prepare($query);
        $success = $sth->execute($params);
        $this->lastId = $this->dbh->lastInsertId();

        if (!$success) {
            return [];
        }

        return $sth->fetchAll(PDO::FETCH_CLASS, $class);
    }
}
