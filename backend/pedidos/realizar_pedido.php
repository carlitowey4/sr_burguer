<?php
require_once __DIR__ . '/../ConexionPdoEnv.php';
require_once __DIR__ . '/../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$secret_key = "ClaveJWTSecreta";

try {
    $pdo = ConexionPdoEnv::conectar();
    if (!$pdo) {
        throw new Exception("No se pudo conectar a la base de datos.");
    }

    $headers = apache_request_headers();
    $authHeader = $headers['Authorization'] ?? '';

    if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
        http_response_code(401);
        echo json_encode(["message" => "Token no proporcionado."]);
        exit;
    }

    $jwt = trim(str_replace('Bearer', '', $authHeader));
    $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));

    $usuario_id = $decoded->data->id;

    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data['direccion_envio'], $data['metodo_pago'], $data['total'], $data['productos'])) {
        http_response_code(400);
        echo json_encode(["message" => "Faltan datos para procesar el pedido."]);
        exit;
    }

    $direccion_envio = $data['direccion_envio'];
    $metodo_pago = $data['metodo_pago'];
    $total = $data['total'];
    $productos = $data['productos'];

    $pdo->beginTransaction();

    $stmt = $pdo->prepare("INSERT INTO pedidos (usuario_id, total, direccion_envio, metodo_pago) VALUES (?, ?, ?, ?)");
    $stmt->execute([$usuario_id, $total, $direccion_envio, $metodo_pago]);

    $pedido_id = $pdo->lastInsertId();

    $stmtDetalle = $pdo->prepare("INSERT INTO detalles_pedido (pedido_id, producto_id, cantidad, subtotal) VALUES (?, ?, ?, ?)");

    foreach ($productos as $producto) {
        $producto_id = $producto['id'];
        $cantidad = $producto['cantidad'];
        $subtotal = $producto['precio'] * $cantidad;

        $stmtDetalle->execute([$pedido_id, $producto_id, $cantidad, $subtotal]);
    }

    $pdo->commit();

    echo json_encode(["message" => "Pedido realizado con éxito."]);

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    http_response_code(500);
    echo json_encode(["message" => "Error al procesar el pedido.", "error" => $e->getMessage()]);
}
