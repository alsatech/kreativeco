<?php


header("Access-Control-Allow-Origin: http://localhost:8080");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}


include '../config/db.php';
include '../config/jwt.php';

$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'], '/'));

switch ($method) {
    case 'POST':
        if ($request[0] === 'login') {
            login();
        } elseif ($request[0] === 'register') {
            register();
        } elseif ($request[0] === 'create-publication') {  
            createPublication();
        }
        break;
    case 'GET':
            if ($request[0] === 'users') {
                getUsers();
            } elseif ($request[0] === 'publications') { 
                getPublications();
            }
        break; 
    case 'PUT':
        if ($request[0] === 'users') {
            updateUser();
        }
        break;
    case 'DELETE':
            if ($request[0] === 'users') {
                deleteUser();
            } elseif ($request[0] === 'publications') { 
                deletePublication();
            }
        break;
        
    default:
        echo json_encode(["message" => "Método no permitido"]);
        break;
}

function getUsers() {
    global $pdo;
    $stmt = $pdo->query("SELECT id, username, email, role_id FROM users");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}
// endpoint 1
function register() {
    global $pdo;
    $data = json_decode(file_get_contents("php://input"), true);


    if (!isset($data['nombre'], $data['apellido'], $data['email'], $data['password'], $data['rol'])) {
        echo json_encode(["message" => "Faltan datos obligatorios"]);
        return;
    }

    
    $passwordHash = password_hash($data['password'], PASSWORD_DEFAULT);

    
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$data['email']]);
    if ($stmt->fetch()) {
        echo json_encode(["message" => "El correo ya está registrado"]);
        return;
    }

    
    $stmt = $pdo->prepare("SELECT id FROM roles WHERE nombre = ?");
    $stmt->execute([$data['rol']]);
    $role = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$role) {
        echo json_encode(["message" => "El rol ingresado no es válido"]);
        return;
    }

    
    $stmt = $pdo->prepare("INSERT INTO users (nombre, apellido, email, password, role_id) VALUES (?, ?, ?, ?, ?)");
    
    if ($stmt->execute([$data['nombre'], $data['apellido'], $data['email'], $passwordHash, $role['id']])) {
        echo json_encode(["message" => "Usuario registrado correctamente"]);
    } else {
        echo json_encode(["message" => "Error al registrar usuario"]);
    }
}

// endpoint 2
function login() {
    global $pdo;
    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data['email'], $data['password'])) {
        echo json_encode(["message" => "Faltan datos"]);
        return;
    }

    
    $stmt = $pdo->prepare("SELECT u.id, u.nombre, u.apellido, u.email, u.password, r.nombre as role 
                           FROM users u 
                           JOIN roles r ON u.role_id = r.id 
                           WHERE u.email = ?");
    $stmt->execute([$data['email']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && md5($data['password']) === $user['password']) {

        
        $payload = [
            "id" => $user['id'],
            "nombre" => $user['nombre'],
            "apellido" => $user['apellido'],
            "email" => $user['email'],
            "role" => $user['role'],
            "exp" => time() + 3600 
        ];

        
        $token = encodeJWT($payload);

        
        echo json_encode([
            "token" => $token,
            "user" => [
                "id" => $user['id'],
                "nombre" => $user['nombre'],
                "apellido" => $user['apellido'],
                "email" => $user['email'],
                "role" => $user['role']
            ]
        ]);
    } else {
        echo json_encode(["message" => "Credenciales incorrectas"]);
    }
}

function createPublication() {
    global $pdo;

    
    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data['user_id'], $data['titulo'], $data['descripcion'])) {
        echo json_encode(["message" => "Faltan datos"]);
        exit;
    }

    
    $data['user_id'] = intval($data['user_id']);

    
    $stmt = $pdo->prepare("INSERT INTO publicaciones (user_id, titulo, descripcion) VALUES (?, ?, ?)");
    $result = $stmt->execute([$data['user_id'], $data['titulo'], $data['descripcion']]);

    if ($result) {
        echo json_encode(["message" => "✅ Publicación creada con éxito"]);
    } else {
        
        $error = $stmt->errorInfo();
        echo json_encode([
            "message" => "❌ Error al crear la publicación",
            "error" => $error
        ]);
    }
}



// endpoint 4
function deletePublication() {
    global $pdo;
    
    
    
    if ($userData['role'] !== 'alto' && $userData['role'] !== 'alto medio') {
        echo json_encode(["message" => "No tienes permisos para eliminar publicaciones"]);
        return;
    }
    
   
    $data = json_decode(file_get_contents("php://input"), true);
    
    if (!isset($data['id'])) {
        echo json_encode(["message" => "Falta el ID de la publicación"]);
        return;
    }
    
    
    $stmt = $pdo->prepare("SELECT * FROM publicaciones WHERE id = ?");
    $stmt->execute([$data['id']]);
    $publication = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$publication) {
        echo json_encode(["message" => "La publicación no existe"]);
        return;
    }
    
    
    $stmt = $pdo->prepare("UPDATE publicaciones SET deleted_at = NOW() WHERE id = ?");
    $stmt->execute([$data['id']]);
    
    
    echo json_encode(["message" => "Publicación eliminada lógicamente con éxito"]);
}


//endpoint 5
function getPublications() {
    global $pdo;

    
    $stmt = $pdo->prepare("
        SELECT p.id, p.titulo, p.descripcion, p.created_at, u.nombre AS user_name, r.nombre AS user_role
        FROM publicaciones p
        JOIN users u ON p.user_id = u.id
        JOIN roles r ON u.role_id = r.id
        ORDER BY p.created_at DESC
    ");
    $stmt->execute();
    $publications = $stmt->fetchAll(PDO::FETCH_ASSOC);

    
    echo json_encode(["publications" => $publications]);
}



function deleteUser() {
    global $pdo;
    $data = json_decode(file_get_contents("php://input"), true);
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    
    if ($stmt->execute([$data['id']])) {
        echo json_encode(["message" => "Usuario eliminado correctamente"]);
    } else {
        echo json_encode(["message" => "Error al eliminar usuario"]);
    }
}
?>
