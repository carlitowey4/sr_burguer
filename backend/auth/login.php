<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

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

    $data = json_decode(file_get_contents("php://input"));

    if (empty($data->email) || empty($data->contraseña)) {
        http_response_code(400);
        echo json_encode(["message" => "Email y contraseña son requeridos."]);
        exit;
    }

    $email = $data->email;
    $password = $data->contraseña;

    $stmt = $pdo->prepare("SELECT id, nombre, email, contraseña, direccion FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->rowCount() === 0) {
        http_response_code(401);
        echo json_encode(["message" => "Credenciales inválidas o el usuario no existe."]);
        exit;
    }

    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!password_verify($password, $usuario['contraseña'])) {
        http_response_code(401);
        echo json_encode(["message" => "Contraseña incorrecta."]);
        exit;
    }

    // Generar JWT
    $payload = [
        "iat" => time(),
        "exp" => time() + (60 * 60), // Expira en 1h
        "data" => [
            "id" => $usuario['id'],
            "nombre" => $usuario['nombre'],
            "email" => $usuario['email'],
            "direccion" => $usuario['direccion']
        ]
    ];

    $jwt = JWT::encode($payload, $secret_key, 'HS256');

    echo json_encode([
        "message" => "Inicio de sesión exitoso.",
        "token" => $jwt,
        "usuario" => [
            "id" => $usuario['id'],
            "nombre" => $usuario['nombre'],
            "email" => $usuario['email'],
            "direccion" => $usuario['direccion']
        ]
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["message" => "Error interno: " . $e->getMessage()]);
}
