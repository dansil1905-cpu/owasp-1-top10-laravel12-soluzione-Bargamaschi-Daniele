<?php

// $allowedOrigin = 'http://internal.user:8000';
// $allowedIp = ['127.0.0.1','::1'];
// // var_dump($_SERVER['HTTP_REFERER'] !== $allowedOrigin);die;
// // var_dump(in_array($_SERVER['REMOTE_ADDR'],$allowedIp));die;
// if (!isset($_SERVER['HTTP_REFERER']) || $_SERVER['HTTP_REFERER'] !== $allowedOrigin || !in_array($_SERVER['REMOTE_ADDR'],$allowedIp)) {
//     http_response_code(403);
//     echo json_encode(['error' => 'Unauthorized']);
//     exit;
// }

// header('Content-Type: application/json');

// // Percorso del file JSON
// $filePath = './data.json';

// // Controlla se il file esiste
// if (!file_exists($filePath)) {
//     http_response_code(404);
//     echo json_encode(["error" => "File not found"]);
//     exit();
// }

// // Leggi il contenuto del file
// $json = file_get_contents($filePath);

// // Decodifica il JSON in un array associativo
// $data = json_decode($json, true);

// // Controlla se la decodifica è riuscita
// if (json_last_error() !== JSON_ERROR_NONE) {
//     http_response_code(500);
//     echo json_encode(["error" => "Error decoding JSON"]);
//     exit();
// }

// // Restituisci i dati come risposta JSON
// echo json_encode($data);

$allowedIp =  ['127.0.0.1','localhost','::1'];
$clinetIp = $_SERVER['REMOTE_ADDR'] ?? '';
$userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';

if (!in_array($clientIp, allowedIp)){
    http_response_code(403);
    echo json_encode(['error' => 'Access denied - IP not allowed']);
    exit;
}

if (!str_contains($userAgent, 'GuzzleHttp')){
    http_response_code(403);
    echo json_encode(['error' => 'Access denied - Server-to-server request only']);
    exit;
}

header ('Content-Type: application/json');

$filePath = './data.json';

if (!file_exists($filePath)){
    http_response_code(404);
    echo json_encode(["error" => "File not found"]);
    exit();
}

$json = file_get_contents($filePath);

$data = json_decode($json, true);

if (json_last_error() !== JSON_ERRORNONE){
    http_response_code(500);
    echo json_encode(["error" => "Error decoding JSON"]);
    exit();
}

echo json_encode($data);

?>