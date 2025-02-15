<?php

define('SECRET_KEY', 'mi_clave_secreta');


function encodeJWT($data) {
    $header = json_encode(['alg' => 'HS256', 'typ' => 'JWT']);
    $payload = json_encode($data);
    
    // Codificar el header y payload en base64url
    $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
    $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
    

    $secretKey = 'Kr3at1ve3'; 
    $signature = hash_hmac('sha256', $base64UrlHeader . '.' . $base64UrlPayload, $secretKey, true);
    $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
    
    // Unir el header, payload y signature
    return $base64UrlHeader . '.' . $base64UrlPayload . '.' . $base64UrlSignature;
}

// Función para decodificar un JWT
function decodeJWT($token) {
    $secretKey = 'clave_secreta'; // Debe coincidir con la clave usada para firmar el JWT

    // Separar el token en las tres partes: header, payload y signature
    list($base64UrlHeader, $base64UrlPayload, $base64UrlSignature) = explode('.', $token);
    
    // Decodificar el header y payload
    $header = json_decode(base64_decode(str_replace(['-', '_'], ['+', '/'], $base64UrlHeader)), true);
    $payload = json_decode(base64_decode(str_replace(['-', '_'], ['+', '/'], $base64UrlPayload)), true);
    
    // Verificar la firma
    $signatureCheck = hash_hmac('sha256', $base64UrlHeader . '.' . $base64UrlPayload, $secretKey, true);
    $base64UrlSignatureCheck = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signatureCheck));

    if ($base64UrlSignature !== $base64UrlSignatureCheck) {
        return null; // Si la firma no es válida, retornar null
    }

    return $payload; // Si la firma es válida, retornar el payload
}

?>
