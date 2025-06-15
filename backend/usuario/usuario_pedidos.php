<?php
require_once __DIR__ . '/../ConexionPdoEnv.php';
require_once __DIR__ . '/../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
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

    $stmt = $pdo->prepare("SELECT * FROM pedidos WHERE usuario_id = ? ORDER BY fecha DESC");
    $stmt->execute([$usuario_id]);
    $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($pedidos as &$pedido) {
        $stmtDetalle = $pdo->prepare("SELECT dp.producto_id, p.nombre, dp.cantidad, dp.subtotal
                                      FROM detalles_pedido dp
                                      JOIN productos p ON dp.producto_id = p.id
                                      WHERE dp.pedido_id = ?");
        $stmtDetalle->execute([$pedido['id']]);
        $pedido['productos'] = $stmtDetalle->fetchAll(PDO::FETCH_ASSOC);
    }

    echo json_encode($pedidos);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["message" => "Error al obtener pedidos", "error" => $e->getMessage()]);
}
