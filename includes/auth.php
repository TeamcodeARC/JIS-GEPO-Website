<?php
// includes/auth.php

class Auth {
    private $db;
    private $session;

    public function __construct($db) {
        $this->db = $db;
        session_start();
    }

    public function register($data) {
        $email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
        $password = password_hash($data['password'], PASSWORD_DEFAULT);
        $role = $data['role'] ?? 'student';
        
        $stmt = $this->db->prepare("
            INSERT INTO users (email, password, role, first_name, last_name, institution) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        
        return $stmt->execute([
            $email, 
            $password, 
            $role,
            $data['first_name'],
            $data['last_name'],
            $data['institution']
        ]);
    }

    public function login($email, $password) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['email'] = $user['email'];
            return true;
        }
        return false;
    }

    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    public function isAdmin() {
        return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
    }

    public function getCurrentUser() {
        if (!$this->isLoggedIn()) return null;
        
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function logout() {
        session_destroy();
        return true;
    }
}