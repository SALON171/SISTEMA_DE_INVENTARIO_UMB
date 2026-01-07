<?php
header('Content-Type: application/json');
include 'db.php'; // Archivo de conexión creado anteriormente

// Leer los datos enviados por JavaScript
$datos = json_decode(file_get_contents('php://input'), true);

if (!$datos || empty($datos['productos'])) {
    echo json_encode(['success' => false, 'error' => 'No hay productos']);
    exit;
}

$conn->begin_transaction(); // Iniciar transacción para seguridad

try {
    // 1. Crear la cabecera de la solicitud
    // Nota: El id_solicitante e id_area deberían venir de la sesión del usuario
    $id_usuario = 1; // Ejemplo temporal
    $id_area = 1;    // Ejemplo temporal
    
    $stmt = $conn->prepare("INSERT INTO solicitudes (fecha, id_solicitante, id_area, estado) VALUES (NOW(), ?, ?, 'PENDIENTE')");
    $stmt->bind_param("ii", $id_usuario, $id_area);
    $stmt->execute();
    $id_solicitud = $conn->insert_id;

    // 2. Insertar cada producto en detalles_solicitud
    foreach ($datos['productos'] as $prod) {
        // Primero buscamos el id_producto basado en el nombre que viene del HTML
        $stmtP = $conn->prepare("SELECT id_producto FROM productos WHERE nombre = ?");
        $stmtP->bind_param("s", $prod['nombre']);
        $stmtP->execute();
        $resP = $stmtP->get_result();
        $producto_db = $resP->fetch_assoc();

        if ($producto_db) {
            $id_p = $producto_db['id_producto'];
            $cant = $prod['cantidad'];
            $stmtD = $conn->prepare("INSERT INTO detalles_solicitud (id_solicitud, id_producto, cantidad_solicitada) VALUES (?, ?, ?)");
            $stmtD->bind_param("iii", $id_solicitud, $id_p, $cant);
            $stmtD->execute();
        }
    }

    $conn->commit();
    echo json_encode(['success' => true, 'id_solicitud' => $id_solicitud]);

} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>