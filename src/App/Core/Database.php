<?php
// ============================================================================
// File:    Database.php
// Author:  Recep Seymen Konuk <konukrecepseymen@gmail.com>
//
// Licensed under the terms of the LICENSE file in the project root directory.
// ============================================================================

class Database
{
    private $pdo = NULL;
    private $privateSalt;
    private static Database|NULL $instance = NULL;

    public static function getInstance()
    {
        if (self::$instance === NULL) {
            self::$instance = new Database();
        }

        return self::$instance;
    }

    public static function generateToken($length)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    private function __construct()
    {
        $host    = getenv('MYSQL_HOST');
        $port    = getenv('MYSQL_PORT');
        $dbname  = getenv('MYSQL_DATABASE');
        $user    = getenv('MYSQL_USER');
        $pass    = getenv('MYSQL_PASSWORD');
        $charset = getenv('MYSQL_CHARSET');
        $this->privateSalt = getenv('PRIVATESALT');

        try {
            $this->pdo = new PDO("mysql:dbname=$dbname;host=$host;port:$port;charset=$charset", $user, $pass);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
        } catch (PDOException $e) {
            http_response_code(500);
            exit;
        }
    }

    public function __destruct()
    {
        $this->pdo = NULL;
    }

    public function getPDO()
    {
        return $this->pdo;
    }

    public function getPrivateSalt()
    {
        return $this->privateSalt;
    }
}
