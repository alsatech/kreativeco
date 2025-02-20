<?php

define('SECRET_KEY', 'Kr3at1ve3');


function encodeJWT($data) {
    $header = json_encode(['alg' => 'HS256', 'typ' => 'JWT']);
    $payload = json_encode($data);
    
    
    $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
    $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
    

    $secretKey = 'Kr3at1ve3'; 
    $signature = hash_hmac('sha256', $base64UrlHeader . '.' . $base64UrlPayload, $secretKey, true);
    $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
    
    
    return $base64UrlHeader . '.' . $base64UrlPayload . '.' . $base64UrlSignature;
}


function decodeJWT($token) {
    $secretKey = 'Kr3at1ve3'; 

    
    list($base64UrlHeader, $base64UrlPayload, $base64UrlSignature) = explode('.', $token);
    
    
    $header = json_decode(base64_decode(str_replace(['-', '_'], ['+', '/'], $base64UrlHeader)), true);
    $payload = json_decode(base64_decode(str_replace(['-', '_'], ['+', '/'], $base64UrlPayload)), true);
    
    
    $signatureCheck = hash_hmac('sha256', $base64UrlHeader . '.' . $base64UrlPayload, $secretKey, true);
    $base64UrlSignatureCheck = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signatureCheck));

    if ($base64UrlSignature !== $base64UrlSignatureCheck) {
        return null; 
    }

    return $payload;
}

?>
