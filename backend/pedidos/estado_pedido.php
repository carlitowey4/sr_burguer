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
    if (!$pdo) throw new Exception("No se pudo conectar a la base de datos.");

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

    if ($usuario_id != 1) {
        http_response_code(403);
        echo json_encode(["message" => "Acceso denegado."]);
        exit;
    }

    $data = json_decode(file_get_contents("php://input"), true);
    if (!isset($data['pedido_id'], $data['nuevo_estado'])) {
        http_response_code(400);
        echo json_encode(["message" => "Faltan datos."]);
        exit;
    }

    $stmt = $pdo->prepare("UPDATE pedidos SET estado = ? WHERE id = ?");
    $stmt->execute([$data['nuevo_estado'], $data['pedido_id']]);

    echo json_encode(["message" => "Estado actualizado."]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["message" => "Error: " . $e->getMessage()]);
}
