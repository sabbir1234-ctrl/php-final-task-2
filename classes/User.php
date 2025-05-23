<?php
class User {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function register($username, $password) {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $key = openssl_encrypt('KEY', 'AES-128-ECB', $password);
        $stmt = $this->conn->prepare("INSERT INTO users (username, password_hash, encryption_key) VALUES (?, ?, ?)");
        return $stmt->execute([$username, $hash, $key]);
    }

    public function login($username, $password) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password_hash'])) {
            return $user;
        }
        return false;
    }
}
