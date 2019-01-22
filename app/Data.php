<?php


namespace Journbers;


class Data
{
    private $database = null;
    private $connectionParams = [];

    public function __construct($connectionParams)
    {
        $this->connectionParams = $connectionParams;
    }

    protected function db()
    {
        if ($this->database !== null) {
            return $this->database;
        }

        $this->database = new \PDO(
            sprintf(
                'mysql:host=%s;port=%s;dbname=%s',
                $this->connectionParams['host'],
                $this->connectionParams['port'],
                $this->connectionParams['dbname']
            ),
            $this->connectionParams['user'],
            $this->connectionParams['password'],
            [
                \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
            ]
        );
        return $this->database;
    }
}
