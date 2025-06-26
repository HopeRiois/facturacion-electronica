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

$idCliente = $data['idCliente'] ?? '';
$identificacion = $data['identificacion'] ?? '';
$nombre = $data['nombre'] ?? '';
$direccion = $data['direccion'] ?? '';
$telefono = $data['telefono'] ?? '';
$correo = $data['correo'] ?? '';
$facturaNumero = $data['numeroFactura'] ?? '';
$fecha = $data['fecha'] ?? '';
$items = $data['items'] ?? [];

try {
    // Puedes guardar la factura en otra tabla aquí

    echo json_encode([
        'mensaje' => 'Factura procesada. ' . $clienteResponse
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>