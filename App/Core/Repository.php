<?php

// ============================================================================
// File:    Repository.php
// Author:  Recep Seymen Konuk <konukrecepseymen@gmail.com>
//
// Licensed under the terms of the LICENSE file in the project root directory.
// ============================================================================

require_once(CORE . "Database.php");

class Repository
{
    protected Database $database;
    protected PDO $pdo;
    protected string $privateSalt;

    public function __construct()
    {
        $this->database = Database::getInstance();
        $this->pdo = $this->database->getPDO();
        $this->privateSalt = $this->database->getPrivateSalt();
    }
}
