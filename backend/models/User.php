<?php
class User {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    public function login($email, $password) {
        $stmt = $this->db->prepare("SELECT u.id, u.nombre, u.apellido, u.email, u.password, r.nombre as role 
                                    FROM users u 
                                    JOIN roles r ON u.role_id = r.id 
                                    WHERE u.email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && md5($password) === $user['password']) {
            return $user;
        }
        return false;
    }

    public function emailExists($email) {
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ? true : false;
    }

    public function getRoleIdByName($roleName) {
        $stmt = $this->db->prepare("SELECT id FROM roles WHERE nombre = ?");
        $stmt->execute([$roleName]);
        $role = $stmt->fetch(PDO::FETCH_ASSOC);
        return $role ? $role['id'] : false;
    }

    public function register($nombre, $apellido, $email, $passwordHash, $role_id) {
        $stmt = $this->db->prepare("INSERT INTO users (nombre, apellido, email, password, role_id) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$nombre, $apellido, $email, $passwordHash, $role_id]);
    }
}
?>
