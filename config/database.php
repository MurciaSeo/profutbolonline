<?php

if(isset($_GET['debug']) && $_GET['debug'] == 'true'){
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

class Database {
    private static $instance = null;
    private $connection;
    
    private function __construct() {
        $host = 'localhost';
        $dbname = 'profutbolonline_qwb';
        $username = 'profutbolonline_asa';
        $password = '&}p^lgTkK9d~';
        
        try {
            $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            
            $this->connection = new PDO($dsn, $username, $password, $options);
        } catch (PDOException $e) {
            die("Error de conexión a la base de datos: " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->connection;
    }
    
    // Prevenir la clonación del objeto
    private function __clone() {}
    
    // Prevenir la deserialización
    public function __wakeup() {
        throw new Exception("No se puede deserializar una instancia singleton.");
    }
} 