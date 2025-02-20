<?php
class Publication {
    private $db;
    
    public function __construct($pdo) {
        $this->db = $pdo;
    }
    
    public function create($user_id, $titulo, $descripcion) {
        $stmt = $this->db->prepare("INSERT INTO publicaciones (user_id, titulo, descripcion) VALUES (?, ?, ?)");
        return $stmt->execute([$user_id, $titulo, $descripcion]);
    }

    public function getAll() {
        $stmt = $this->db->prepare("
            SELECT p.id, p.titulo, p.descripcion, p.created_at, u.nombre AS user_name, r.nombre AS user_role
            FROM publicaciones p
            JOIN users u ON p.user_id = u.id
            JOIN roles r ON u.role_id = r.id
            WHERE p.deleted_at IS NULL
            ORDER BY p.created_at DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function delete($id) {
        $stmt = $this->db->prepare("UPDATE publicaciones SET deleted_at = NOW() WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>
