<?php
require_once __DIR__ . '/../ConexionPdoEnv.php';
require_once __DIR__ . '/../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

header("Access-Control-Allow-Origin: http://localhost:4200");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$secret_key = "ClaveJWTSecreta";
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

$pdo = ConexionPdoEnv::conectar();
$stmt = $pdo->prepare("SELECT id, nombre, email, contraseña, direccion, fecha_registro FROM usuarios WHERE id = ?");
$stmt->execute([$usuario_id]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode($usuario);
