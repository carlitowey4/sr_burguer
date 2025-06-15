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

    $stmt = $pdo->prepare("
        SELECT p.*, u.nombre AS nombre_usuario
        FROM pedidos p
        JOIN usuarios u ON p.usuario_id = u.id
        WHERE p.fecha >= NOW() - INTERVAL 24 HOUR
        ORDER BY p.fecha DESC
    ");
    $stmt->execute();
    $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($pedidos);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["message" => "Error: " . $e->getMessage()]);
}
