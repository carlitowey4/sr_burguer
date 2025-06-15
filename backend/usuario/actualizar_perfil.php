<?php
require_once __DIR__ . '/../ConexionPdoEnv.php';
require_once __DIR__ . '/../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Allow-Headers: Authorization, Content-Type");
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

$data = json_decode(file_get_contents("php://input"), true);

$pdo = ConexionPdoEnv::conectar();

$campos = [];
$valores = [];

if (!empty($data['nombre'])) {
    $campos[] = 'nombre = ?';
    $valores[] = $data['nombre'];
}

if (!empty($data['direccion'])) {
    $campos[] = 'direccion = ?';
    $valores[] = $data['direccion'];
}

if (!empty($data['password'])) {
    $campos[] = 'contraseña = ?';
    $valores[] = password_hash($data['password'], PASSWORD_DEFAULT);
}

if (!empty($campos)) {
    $valores[] = $usuario_id;
    $stmt = $pdo->prepare("UPDATE usuarios SET " . implode(", ", $campos) . " WHERE id = ?");
    $stmt->execute($valores);
}

echo json_encode(["message" => "Perfil actualizado con éxito."]);
