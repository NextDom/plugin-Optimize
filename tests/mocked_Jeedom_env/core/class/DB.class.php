<?php

class MockedStatement
{
    private $query;
    private $data;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function execute($data = null)
    {
        $this->data = $data;
        MockedActions::add('query_execute', array('query' => $this->query, 'data' => $this->data));
    }

    /**
     * Obtenir les résultats d'une requête
     *
     * @param integer $fetchMethod Méthode de récupération (inutilisé)
     *
     * @return mixed Données définies par DB::setAnswer au format PDO
     */
    public function fetchAll($fetchMethod)
    {
        return DB::$answer;
    }
}

class MockedPDO
{
    public function prepare($query)
    {
        return new MockedStatement($query);
    }
}

class DB
{
    private static $connection = null;

    public static $answer;


    public static function init()
    {
        static::$connection = new MockedPDO();
    }

    public static function getConnection()
    {
        return static::$connection;
    }

    public static function setAnswer($answer)
    {
        if ($answer !== null) {
            static::$answer = array($answer);
        } else {
            static::$answer = array();
        }
    }
}
