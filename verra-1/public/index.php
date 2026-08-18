<?php

require_once __DIR__ . '/../vendor/autoload.php';

header('Content-Type: application/json');

// Validar que solo acepte peticiones POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed. Use POST.']);
    exit;
}

// Leer el JSON recibido en el cuerpo de la petición
$jsonContent = file_get_contents('php://input');
$data = json_decode($jsonContent, true);

if (empty($jsonContent) || json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode(['error' => 'Malformed or empty JSON payload.']);
    exit;
}

// Punto de entrada listo para mapear a DTOs y llamar al Domain Service mañana
http_response_code(200);
echo json_encode([
    'status' => 'success',
    'message' => 'Native PHP API Ready',
    'received_items_count' => count($data)
]);
