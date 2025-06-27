<?php
require_once __DIR__ . '/db.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

try {
    $stmt = $pdo->query("SELECT MAX(numero_factura) as ultimo FROM Venta");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $ultimo = $row['ultimo'] ?? 0;
    
    $siguiente = str_pad((intval($ultimo) + 1), 8, "0", STR_PAD_LEFT);

    echo json_encode(['numeroFactura' => $siguiente]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}