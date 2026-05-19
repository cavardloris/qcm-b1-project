<?php

abstract class AbstractManager # cette classe permet d'établir une connexion avec la bdd
{
    protected PDO $db;

    public function __construct()
    {
        $dbHost = $_ENV['DB_HOST'] ?? 'localhost';
        $dbUser = $_ENV['DB_USER'] ?? 'root';
        $dbPass = $_ENV['DB_PASS'] ?? '';
        $dbName = $_ENV['DB_NAME'] ?? 'qcm_project_database';
        $dbPort = $_ENV['DB_PORT'] ?? '3306';

        $connexion = "mysql:host=".$dbHost.";port=".$dbPort.";charset=utf8;dbname=".$dbName;
        $this->db = new PDO(
            $connexion,
            $dbUser,
            $dbPass
        );
    }
}