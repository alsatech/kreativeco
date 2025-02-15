<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include '../routes/auth.php';
include '../routes/users.php';

$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'], '/'));

switch ($request[0]) {
    case 'auth':
        handleAuthRequest($method);
        break;
    case 'users':
        handleUserRequest($method);
        break;
    default:
        echo json_encode(["message" => "Ruta no encontrada"]);
}
?>
