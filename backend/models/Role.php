<?php
class Role {
    private $db;
    
    public function __construct($pdo) {
        $this->db = $pdo;
    }
    
    public function getAllRoles() {
        $stmt = $this->db->prepare("SELECT * FROM roles ORDER BY id ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getRoleByName($name) {
        $stmt = $this->db->prepare("SELECT * FROM roles WHERE nombre = ?");
        $stmt->execute([$name]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
