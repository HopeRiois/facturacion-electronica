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
$factura = $data['factura'] ?? '';
$fecha = $data['fecha'] ?? '';
$total = $data['total'] ?? 0;
$subtotal = $data['subtotal'] ?? 0;
$impuestos = $data['impuesto'] ?? 0;
$items = $data['items'] ?? [];

try {
    // Iniciar transacción
    $pdo->beginTransaction();

    // Guardar venta
    $stmtVenta = $pdo->prepare("
        INSERT INTO Venta (numero_factura, subtotal, impuestos, total, fecha_compra, cliente_id)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmtVenta->execute([
        $factura,
        $subtotal,
        $impuestos,
        $total,
        $fecha,
        $idCliente
    ]);

    $idVenta = $pdo->lastInsertId();

    // Relacionar productos con la venta
    foreach ($items as $item) {
        
        $idProducto = $item['idProducto'];

        $stmtRelacion = $pdo->prepare("
            INSERT INTO Venta_has_Producto (Venta_id, Producto_id)
            VALUES (?, ?)
        ");
        $stmtRelacion->execute([$idVenta, $idProducto]);
    }

    //Finalizar transaccion
    $pdo->commit();

    echo json_encode([
        'mensaje' => 'Factura y productos guardados correctamente',
        'idVenta' => $idVenta
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>