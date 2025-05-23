<?php
class Database {
    private $host = "localhost";
    private $db = "passwords_db";
    private $user = "root";
    private $pass = "";
    public $conn;

    public function connect() {
        $this->conn = new PDO("mysql:host={$this->host};dbname={$this->db}", $this->user, $this->pass);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $this->conn;
    }
}
