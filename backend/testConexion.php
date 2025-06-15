<?php

require_once 'ConexionPdoEnv.php';

$conexion = ConexionPdoEnv::conectar();

if ($conexion instanceof PDO) {
    echo "[✓] Conexión realizada correctamente a la base de datos '" . Entorno::get('BD') . "'.";
} else {
    echo "[x] Error: No se pudo establecer la conexión.";
}
