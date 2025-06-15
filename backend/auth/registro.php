<?php
require_once __DIR__ . '/../ConexionPdoEnv.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

try {
    $pdo = ConexionPdoEnv::conectar();
    if (!$pdo) {
        throw new Exception("No se pudo conectar a la base de datos.");
    }

    $data = json_decode(file_get_contents("php://input"));

    if (
        empty($data->nombre) ||
        empty($data->email) ||
        empty($data->contraseña)
    ) {
        http_response_code(400);
        echo json_encode(["message" => "Todos los campos obligatorios no fueron proporcionados."]);
        exit;
    }

    $nombre = htmlspecialchars(strip_tags($data->nombre));
    $email = filter_var($data->email, FILTER_VALIDATE_EMAIL);
    $contraseña = $data->contraseña;
    $direccion = isset($data->direccion) ? htmlspecialchars(strip_tags($data->direccion)) : null;

    if (!$email) {
        http_response_code(400);
        echo json_encode(["message" => "El correo electrónico no es válido."]);
        exit;
    }

    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->rowCount() > 0) {
        http_response_code(409);
        echo json_encode(["message" => "Este correo ya está registrado."]);
        exit;
    }

    $hash = password_hash($contraseña, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email, contraseña, direccion) VALUES (?, ?, ?, ?)");
    $stmt->execute([$nombre, $email, $hash, $direccion]);

    http_response_code(201);
    echo json_encode(["message" => "Usuario registrado con éxito."]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["message" => "Error interno: " . $e->getMessage()]);
}
