<?php
require '../db.php';

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET");

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $codigo = $_GET['codigo'] ?? '';

    if ($codigo === '') {
        http_response_code(400);
        echo json_encode(['error' => 'Código requerido']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("SELECT * FROM Producto WHERE codigo = ?");
        $stmt->execute([$codigo]);

        if ($stmt->rowCount() > 0) {
            echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
        } else {
            echo json_encode(null);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
}