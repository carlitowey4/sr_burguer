<?php
require_once 'ConexionPdoEnv.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

try {
    $pdo = ConexionPdoEnv::conectar();
    if (!$pdo) {
        throw new Exception("No se pudo conectar a la base de datos.");
    }

    $stmt = $pdo->query("SELECT id, nombre, descripcion, precio, estado, tipo, imagen FROM productos WHERE estado = 'disponible'");
    $productos = $stmt->fetchAll();

    echo json_encode($productos);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
