<?php
require_once '../models/Role.php';
require_once '../models/User.php';

class UserController {
    private $db;
    private $roleModel;
    private $userModel;
    
    public function __construct($pdo) {
        $this->db = $pdo;
        $this->roleModel = new Role($pdo);
        $this->userModel = new User($pdo);
    }
    

    public function getUsers() {
        $stmt = $this->db->query("SELECT id, nombre, apellido, email, role_id FROM users");
        header('Content-Type: application/json');
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
    
    public function getRoles() {
        $roles = $this->roleModel->getAllRoles();
        header('Content-Type: application/json');
        echo json_encode(["roles" => $roles]);
    }
}
?>
