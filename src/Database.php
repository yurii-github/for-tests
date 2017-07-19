<?php

namespace Test;

class Database {
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @return PDO
     */
    public function getPDO() {
        return $this->pdo;
    }
}