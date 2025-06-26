<?php
require '../db.php';

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $identificacion = $_GET['identificacion'] ?? '';

        if ($identificacion === '') {
            http_response_code(400);
            echo json_encode(['error' => 'Identificación requerida']);
            exit;
        }

        try {
            $stmt = $pdo->prepare("SELECT * FROM Cliente WHERE identificacion = ?");
            $stmt->execute([$identificacion]);

            if ($stmt->rowCount() > 0) {
                echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
            } else {
                echo json_encode(null);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);

        $identificacion = $data['identificacion'] ?? '';
        $nombres = $data['nombre'] ?? '';
        $direccion = $data['direccion'] ?? '';
        $telefono = $data['telefono'] ?? '';
        $correo = $data['correo'] ?? '';

        if ($identificacion === '') {
            http_response_code(400);
            echo json_encode(['error' => 'Identificación requerida']);
            exit;
        }

        try {
            $stmt = $pdo->prepare("SELECT id FROM Cliente WHERE identificacion = ?");
            $stmt->execute([$identificacion]);

            if ($stmt->rowCount() === 0) {
                $stmtInsert = $pdo->prepare("INSERT INTO Cliente (identificacion, nombres, direccion, telefono, correo)
                                             VALUES (?, ?, ?, ?, ?)");
                $stmtInsert->execute([$identificacion, $nombres, $direccion, $telefono, $correo]);

                echo json_encode(['mensaje' => 'Cliente insertado']);
            }

            $stmtSelect = $pdo->prepare("SELECT * FROM Cliente WHERE identificacion = ?");
            $stmtSelect->execute([$identificacion]);
            $cliente = $stmtSelect->fetch(PDO::FETCH_ASSOC);

            echo json_encode([
                'cliente' => $cliente
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Método no permitido']);
        break;
}