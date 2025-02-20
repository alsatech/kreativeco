<?php
require_once '../models/User.php';
require_once '../config/jwt.php';

class AuthController {
    private $db;
    private $userModel;

    public function __construct($pdo) {
        $this->db = $pdo;
        $this->userModel = new User($pdo);
    }

    public function login() {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['email'], $data['password'])) {
            header('Content-Type: application/json');
            echo json_encode(["message" => "Faltan datos"]);
            return;
        }

        $user = $this->userModel->login($data['email'], $data['password']);
        if ($user) {

            $payload = [
                "id"       => $user['id'],
                "nombre"   => $user['nombre'],
                "apellido" => $user['apellido'],
                "email"    => $user['email'],
                "role"     => $user['role'],
                "exp"      => time() + 3600
            ];
            $token = encodeJWT($payload);
            header('Content-Type: application/json');
            echo json_encode([
                "token" => $token,
                "user"  => [
                    "id"       => $user['id'],
                    "nombre"   => $user['nombre'],
                    "apellido" => $user['apellido'],
                    "email"    => $user['email'],
                    "role"     => $user['role']
                ]
            ]);
        } else {
            header('HTTP/1.1 401 Unauthorized');
            echo json_encode(["message" => "Credenciales incorrectas"]);
        }
    }

    public function register() {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['nombre'], $data['apellido'], $data['email'], $data['password'], $data['rol'])) {
            header('Content-Type: application/json');
            echo json_encode(["message" => "Faltan datos obligatorios"]);
            return;
        }
        if ($this->userModel->emailExists($data['email'])) {
            echo json_encode(["message" => "El correo ya está registrado"]);
            return;
        }
        $role_id = $this->userModel->getRoleIdByName($data['rol']);
        if (!$role_id) {
            echo json_encode(["message" => "El rol ingresado no es válido"]);
            return;
        }
        $passwordHash = password_hash($data['password'], PASSWORD_DEFAULT);


        $result = $this->userModel->register($data['nombre'], $data['apellido'], $data['email'], $passwordHash, $role_id);

        if ($result) {
            echo json_encode(["message" => "Usuario registrado correctamente"]);
        } else {
            echo json_encode(["message" => "Error al registrar usuario"]);
        }
    }
    
    public function logout() {
        echo json_encode(["message" => "Logout exitoso, elimina el token en el frontend"]);
    }
    
}
?>
