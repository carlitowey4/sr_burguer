<?php
require_once 'ConexionPdoEnv.php';

try {
    $pdo = ConexionPdoEnv::conectar();
    if (!$pdo) {
        throw new Exception("No se pudo conectar a la base de datos.");
    }

    //Aqui dentro se ponen los productos que se quieran ir agregando a la tabla. (Por si hay que ampliar la carta)
    $productos = [
    ['nombre' => 'Tequeños de queso', 'descripcion' => 'Queso mozzarella fundido con rebozado crujiente', 'precio' => 4.50, 'estado' => 'disponible', 'tipo' => 'entrante', 'imagen' => 'menu/entrantes/entrante3.jpg'],
    ['nombre' => 'Alitas BBQ', 'descripcion' => 'Alitas de pollo con salsa barbacoa', 'precio' => 6.00, 'estado' => 'disponible', 'tipo' => 'entrante', 'imagen' => 'menu/entrantes/entrante1.jpg'],
    ['nombre' => 'Fingers de pollo', 'descripcion' => 'Pechuga de pollo con crujienre rebozado', 'precio' => 4.50, 'estado' => 'disponible', 'tipo' => 'entrante', 'imagen' => 'menu/entrantes/entrante2.jpg'],

    ['nombre' => 'Patatas Fritas', 'descripcion' => 'Patatas fritas crujientes', 'precio' => 4.50, 'estado' => 'disponible', 'tipo' => 'patata', 'imagen' => 'menu/patatas/patatas1.jpg'],
    ['nombre' => 'Patatas Pull Pork', 'descripcion' => 'Patatas con pull pork y salsa de la casa', 'precio' => 7.00, 'estado' => 'disponible', 'tipo' => 'patata', 'imagen' => 'menu/patatas/patatas3.png'],
    ['nombre' => 'Patatas Bacon', 'descripcion' => 'Patatas con queso cheddar y bacon', 'precio' => 7.50, 'estado' => 'disponible', 'tipo' => 'patata', 'imagen' => 'menu/patatas/patatas2.png'],

    ['nombre' => 'Ensalada César', 'descripcion' => 'Lechuga, pollo, queso parmesano y salsa César', 'precio' => 5.00, 'estado' => 'disponible', 'tipo' => 'ensalada', 'imagen' => 'menu/ensaladas/ensalada1.jpg'],
    ['nombre' => 'Ensalada Mixta', 'descripcion' => 'Lechuga, tomate, cebolla y aceitunas', 'precio' => 4.50, 'estado' => 'disponible', 'tipo' => 'ensalada', 'imagen' => 'menu/ensaladas/ensalada2.jpg'],

    ['nombre' => 'Hamburguesa Clásica', 'descripcion' => 'Carne, queso, lechuga y tomate', 'precio' => 5.99, 'estado' => 'disponible', 'tipo' => 'hamburguesa', 'imagen' => 'menu/hamburguesas/hamburguesa1.jpg'],
    ['nombre' => 'Hamburguesa Epica del Oeste', 'descripcion' => 'Carne, bacon, queso cheddar y salsa BBQ', 'precio' => 7.50, 'estado' => 'disponible', 'tipo' => 'hamburguesa', 'imagen' => 'menu/hamburguesas/hamburguesa2.jpg'],
    ['nombre' => 'Hamburguesa Delirio', 'descripcion' => 'Doble carne, huevo frito, bacon y salsa de pepinillos', 'precio' => 6.50, 'estado' => 'disponible', 'tipo' => 'hamburguesa', 'imagen' => 'menu/hamburguesas/hamburguesa3.jpg'],
    ['nombre' => 'Hamburguesa Doble', 'descripcion' => 'Doble carne, queso, lechuga y tomate', 'precio' => 8.99, 'estado' => 'disponible', 'tipo' => 'hamburguesa', 'imagen' => 'menu/hamburguesas/hamburguesa4.jpg'],

    ['nombre' => 'Pizza Margarita', 'descripcion' => 'Tomate, mozzarella y albahaca', 'precio' => 7.00, 'estado' => 'disponible', 'tipo' => 'pizza', 'imagen' => 'menu/pizzas/pizza1.jpg'],
    ['nombre' => 'Pizza Pepperoni', 'descripcion' => 'Queso, pepperoni y tomate', 'precio' => 8.50, 'estado' => 'disponible', 'tipo' => 'pizza', 'imagen' => 'menu/pizzas/pizza2.jpg'],
    ['nombre' => 'Pizza Vegetal', 'descripcion' => 'Tomate, pimiento, cebolla y champiñones', 'precio' => 7.50, 'estado' => 'disponible', 'tipo' => 'pizza', 'imagen' => 'menu/pizzas/pizza3.jpg'],
    ['nombre' => 'Pizza Cuatro Quesos', 'descripcion' => 'Mozzarella, cheddar, gorgonzola y parmesano', 'precio' => 9.00, 'estado' => 'disponible', 'tipo' => 'pizza', 'imagen' => 'menu/pizzas/pizza4.jpg'],

    ['nombre' => 'Coca-Cola', 'descripcion' => 'Refresco clásico de cola', 'precio' => 1.80, 'estado' => 'disponible', 'tipo' => 'bebida', 'imagen' => 'menu/bebidas/bebida1.jpg'],
    ['nombre' => 'Fanta de Naranja', 'descripcion' => 'Refresco sabor naranja', 'precio' => 1.80, 'estado' => 'disponible', 'tipo' => 'bebida', 'imagen' => 'menu/bebidas/bebida2.jpg'],

    ['nombre' => 'Tarta de Queso', 'descripcion' => 'Tarta casera de queso con base de galleta', 'precio' => 3.50, 'estado' => 'disponible', 'tipo' => 'postre', 'imagen' => 'menu/postres/postre2.jpg'],
    ['nombre' => 'Tarta de Chocolate', 'descripcion' => 'Tarta casera de chocolate con base de galleta', 'precio' => 3.50, 'estado' => 'disponible', 'tipo' => 'postre', 'imagen' => 'menu/postres/postre1.jpg']
    ];

    $stmt = $pdo->prepare("INSERT INTO productos (nombre, descripcion, precio, estado, tipo, imagen) VALUES (:nombre, :descripcion, :precio, :estado, :tipo, :imagen)");

    foreach ($productos as $prod) {
        $stmt->execute([
            ':nombre' => $prod['nombre'],
            ':descripcion' => $prod['descripcion'],
            ':precio' => $prod['precio'],
            ':estado' => $prod['estado'],
            ':tipo' => $prod['tipo'],
            ':imagen' => $prod['imagen'],
        ]);
    }

    echo "[✓] Productos insertados correctamente.";
} catch (Exception $e) {
    echo "[x] Error: " . $e->getMessage();
}
