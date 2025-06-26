<?php
require_once __DIR__ . '/db.php';

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

$identificacion = $data['identificacion'] ?? '';
$nombre = $data['nombre'] ?? '';
$direccion = $data['direccion'] ?? '';
$telefono = $data['telefono'] ?? '';
$correo = $data['correo'] ?? '';
$facturaNumero = $data['numeroFactura'] ?? '';
$fecha = $data['fecha'] ?? '';
$items = $data['items'] ?? [];

// Enviar datos del cliente a Cliente.php (para verificar o insertar)
$clientePayload = json_encode([
    'identificacion' => $identificacion,
    'nombre' => $nombre,
    'direccion' => $direccion,
    'telefono' => $telefono,
    'correo' => $correo
]);

try {
    $ch = curl_init('../src/models/Cliente.php'); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $clientePayload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

    $clienteResponse = curl_exec($ch);
    curl_close($ch);

    // Puedes guardar la factura en otra tabla aquí

    echo json_encode([
        'mensaje' => 'Factura procesada. ' . $clienteResponse
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>