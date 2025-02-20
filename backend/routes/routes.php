<?php
require_once '../config/db.php';
require_once '../config/jwt.php';
require_once '../controllers/AuthController.php';
require_once '../controllers/PublicationController.php';

$method = $_SERVER['REQUEST_METHOD'];
$requestUri = trim($_SERVER['REQUEST_URI']);
$requestUri = preg_replace('/[\r\n%0A]+/', '', $requestUri);
$basePath = '/kreative/backend/public/';
$request = str_replace($basePath, '', $requestUri);
$request = explode('/', trim($request, '/'));


switch ($request[0]) {
    case 'login':
        if ($method === 'POST') {
            $controller = new AuthController($pdo);
            $controller->login();
        } else {
            echo json_encode(["message" => "Método $method no soportado en login"]);
        }
        break;
        
    case 'register':
        if ($method === 'POST') {
            $controller = new AuthController($pdo);
            $controller->register();
        } else {
            echo json_encode(["message" => "Método $method no soportado en register"]);
        }
        break;

    case 'publications':
        handlePublicationRequest($method, $request);
        break;
    case 'logout':
        if ($method === 'POST') {
            $controller = new AuthController($pdo);
            $controller->logout();
        } else {
            echo json_encode(["message" => "Método $method no soportado en logout"]);
        }
        break;

    default:
        header("HTTP/1.1 404 Not Found");
        echo json_encode(["message" => "Ruta no encontrada"]);
        break;
}


function handleAuthRequest($method, $request) {
    global $pdo;
    $controller = new AuthController($pdo);

    if ($method === 'POST') {
        if (isset($request[1])) {
            switch ($request[1]) {
                case 'login':
                    $controller->login();
                    break;
                case 'register':
                    $controller->register();
                    break;
                default:
                    echo json_encode(["message" => "Acción no soportada en auth"]);
            }
        } else {
            echo json_encode(["message" => "Ruta de auth incompleta"]);
        }
    } else {
        echo json_encode(["message" => "Método $method no soportado en auth"]);
    }
}

function handlePublicationRequest($method, $request) {
    global $pdo;
    $controller = new PublicationController($pdo);

    if ($method === 'POST') {
        if (isset($request[1]) && $request[1] === 'create') {
            $controller->createPublication(); 
        } else {
            echo json_encode(["message" => "Ruta no encontrada en publications"]);
        }
    } elseif ($method === 'GET') {
        $controller->getAll();
    } elseif ($method === 'DELETE') {
        if (isset($request[1])) {
            $controller->delete($request[1]);
        } else {
            echo json_encode(["message" => "ID de publicación faltante"]);
        }
    } else {
        echo json_encode(["message" => "Método $method no soportado en publications"]);
    }
}
?>
