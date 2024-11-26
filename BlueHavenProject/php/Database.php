<?php

class Database
{
    private static $instance = null;
    private $conn;

    private function __construct()
    {
        $this->conn = new mysqli("localhost", "root", "", "bluehaven_db");
        if ($this->conn->connect_error) {
            die("Error de conexión: " . $this->conn->connect_error);
        }
    }

    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection(): mysqli
    {
        return $this->conn;
    }
}

/*
Patrón Singleton
El patrón Singleton asegura que una clase tenga una única instancia y proporciona un punto global de acceso a ella. Esto es ideal para manejar la conexión a la base de datos.

Modificar la conexión a la base de datos con Singleton
Crea una clase Singleton para manejar la conexión a la base de datos:

Uso del patrón Singleton:
Ventaja: Se asegura de que siempre se use una sola conexión a la base de datos, evitando múltiples instancias innecesarias.
*/
