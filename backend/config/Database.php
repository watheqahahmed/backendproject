<?php
header("Access-Control-Allow-Origin: http://localhost:5173"); // دومين Vue
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

class Database {
    private static $instance = null;
    private $conn;

    private $host = "localhost";
    private $db = "portal_db";
    private $user = "root";
    private $pass = "";

    private function __construct() {
        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->db};charset=utf8",
                $this->user,
                $this->pass
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            die(json_encode(["success"=>false,"message"=>"DB Connection Failed: ".$e->getMessage()]));
        }
    }

    public static function getInstance(){
        if(!self::$instance) self::$instance = new Database();
        return self::$instance;
    }

    public function getConnection(){
        return $this->conn;
    }
}
