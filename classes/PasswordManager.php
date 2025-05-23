<?php
class PasswordManager {
    private $conn;
    private $encryption_key;

    public function __construct($db, $user_key) {
        $this->conn = $db;
        $this->encryption_key = $user_key;
    }

    public function savePassword($user_id, $site_name, $plain_password) {
        $encrypted = openssl_encrypt($plain_password, 'AES-128-ECB', $this->encryption_key);
        $stmt = $this->conn->prepare("INSERT INTO passwords (user_id, site_name, password) VALUES (?, ?, ?)");
        return $stmt->execute([$user_id, $site_name, $encrypted]);
    }

    public function getPasswords($user_id) {
        $stmt = $this->conn->prepare("SELECT * FROM passwords WHERE user_id = ?");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll();
    }

    public function decrypt($ciphertext) {
        return openssl_decrypt($ciphertext, 'AES-128-ECB', $this->encryption_key);
    }
}
