<?php
require_once '../models/Publication.php';
require_once '../models/User.php';
require_once '../config/jwt.php';

class PublicationController {
    private $db;
    private $publicationModel;
    private $userModel;
    
    public function __construct($pdo) {
        $this->db = $pdo;
        $this->publicationModel = new Publication($pdo);
        $this->userModel = new User($pdo);
    }
    
    public function createPublication() {

        $headers = getallheaders();
        if (!isset($headers['Authorization'])) {
            header('Content-Type: application/json');
            echo json_encode(["message" => "Token no proporcionado"]);
            return;
        }
        $token = str_replace('Bearer ', '', $headers['Authorization']);
        $userData = decodeJWT($token);
        if ($userData === null) {
            header('Content-Type: application/json');
            echo json_encode(["message" => "Token inválido o expirado"]);
            return;
        }
        
        //roles
        $allowedRolesForCreation = ["Medio Alto", "Alto Medio", "Alto"];
        if (!in_array($userData['role'], $allowedRolesForCreation)) {
            header('Content-Type: application/json');
            echo json_encode(["message" => "No tienes permisos para crear publicaciones"]);
            return;
        }
        
        $data = json_decode(file_get_contents("php://input"), true);
        if (!isset($data['titulo'], $data['descripcion'])) {
            header('Content-Type: application/json');
            echo json_encode(["message" => "Faltan datos"]);
            return;
        }
        
        $user_id = intval($userData['id']);
        
        $result = $this->publicationModel->create($user_id, $data['titulo'], $data['descripcion']);
        header('Content-Type: application/json');
        if ($result) {
            echo json_encode(["message" => "Publicación creada con éxito"]);
        } else {
            $error = $this->db->errorInfo();
            echo json_encode([
                "message" => "Error al crear la publicación",
                "error" => $error
            ]);
        }
    }
    public function getAll() {
        $publications = $this->publicationModel->getAll();
        echo json_encode(["publications" => $publications]);
    }
    
    public function deletePublication() {
        
        $headers = getallheaders();
        if (!isset($headers['Authorization'])) {
            header('Content-Type: application/json');
            echo json_encode(["message" => "Token no proporcionado"]);
            return;
        }
        $token = str_replace('Bearer ', '', $headers['Authorization']);
        $userData = decodeJWT($token);
        if ($userData === null) {
            header('Content-Type: application/json');
            echo json_encode(["message" => "Token inválido o expirado"]);
            return;
        }

        if ($userData['role'] !== "Alto") {
            header('Content-Type: application/json');
            echo json_encode(["message" => "No tienes permisos para eliminar publicaciones"]);
            return;
        }
        
        $data = json_decode(file_get_contents("php://input"), true);
        if (!isset($data['id'])) {
            header('Content-Type: application/json');
            echo json_encode(["message" => "Falta el ID de la publicación"]);
            return;
        }
        
        $result = $this->publicationModel->delete($data['id']);
        header('Content-Type: application/json');
        if ($result) {
            echo json_encode(["message" => "Publicación eliminada lógicamente con éxito"]);
        } else {
            $error = $this->db->errorInfo();
            echo json_encode([
                "message" => "Error al eliminar la publicación",
                "error" => $error
            ]);
        }
    }
}
?>
