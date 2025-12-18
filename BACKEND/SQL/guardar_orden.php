<?php
session_start();
header('Content-Type: application/json');
require 'conexion.php';

// Obtener los datos enviados por el fetch de JS
$input = json_decode(file_get_contents('php://input'), true);

if (!$input || empty($input)) {
    echo json_encode(["success" => false, "message" => "No se recibieron datos."]);
    exit;
}

// ID de usuario (en un sistema real, de $_SESSION)
$usuario_id = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : 1;

try {
    // 1. Iniciar transacción
    $conn->begin_transaction();

    // 2. Insertar la solicitud (Cabecera)
    $sqlSolicitud = "INSERT INTO solicitud (usuario_id, estado) VALUES (?, 'Pendiente')";
    $stmtSolicitud = $conn->prepare($sqlSolicitud);
    $stmtSolicitud->bind_param("i", $usuario_id);
    $stmtSolicitud->execute();
    
    // Obtener el ID de la solicitud recién creada
    $idSolicitud = $conn->insert_id;
    $stmtSolicitud->close();

    // 3. Insertar los detalles
    $sqlDetalle = "INSERT INTO detalle_solicitud (solicitud_id, material_id, cantidad, tipo_cantidad) 
                   VALUES (?, ?, ?, ?)";
    $stmtDetalle = $conn->prepare($sqlDetalle);

    // Preparar consulta para buscar ID del material por nombre
    $sqlBuscarMaterial = "SELECT idMATERIAL FROM material WHERE nombre_material = ? LIMIT 1";
    $stmtBuscar = $conn->prepare($sqlBuscarMaterial);

    foreach ($input as $item) {
        // Buscar el ID del material
        $materialNombre = trim($item['material']);
        $stmtBuscar->bind_param("s", $materialNombre);
        $stmtBuscar->execute();
        $resultadoMaterial = $stmtBuscar->get_result();
        
        if ($resultadoMaterial->num_rows > 0) {
            $row = $resultadoMaterial->fetch_assoc();
            $idMaterial = $row['idMATERIAL'];
            
            // Insertar detalle
            $stmtDetalle->bind_param("iids", $idSolicitud, $idMaterial, $item['cantidad'], $item['tipo']);
            $stmtDetalle->execute();
        } else {
            // Si el material no existe, insertarlo primero
            $sqlInsertMaterial = "INSERT INTO material (nombre_material, unidad_medida) VALUES (?, ?)";
            $stmtInsertMat = $conn->prepare($sqlInsertMaterial);
            $stmtInsertMat->bind_param("ss", $materialNombre, $item['tipo']);
            $stmtInsertMat->execute();
            $idMaterial = $conn->insert_id;
            $stmtInsertMat->close();
            
            // Ahora insertar el detalle
            $stmtDetalle->bind_param("iids", $idSolicitud, $idMaterial, $item['cantidad'], $item['tipo']);
            $stmtDetalle->execute();
        }
    }

    // 4. Confirmar transacción
    $conn->commit();
    
    // Obtener datos completos de la solicitud para el resumen
    $sqlInfoSolicitud = "SELECT s.*, u.nombre_completo 
                         FROM solicitud s 
                         JOIN USUARIO u ON s.usuario_id = u.id_usuario 
                         WHERE s.id_solicitud = ?";
    $stmtInfo = $conn->prepare($sqlInfoSolicitud);
    $stmtInfo->bind_param("i", $idSolicitud);
    $stmtInfo->execute();
    $infoSolicitud = $stmtInfo->get_result()->fetch_assoc();
    $stmtInfo->close();
    
    echo json_encode([
        "success" => true, 
        "message" => "Orden guardada correctamente.",
        "id_solicitud" => $idSolicitud,
        "fecha_solicitud" => $infoSolicitud['fecha_solicitud'],
        "solicitante" => $infoSolicitud['nombre_completo']
    ]);

} catch (Exception $e) {
    // Si algo falla, revertir cambios
    $conn->rollback();
    
    // Log del error (en producción)
    error_log("Error al guardar orden: " . $e->getMessage());
    
    echo json_encode([
        "success" => false, 
        "message" => "Error al guardar la orden: " . $e->getMessage()
    ]);
} finally {
    if (isset($stmtBuscar)) $stmtBuscar->close();
    if (isset($stmtDetalle)) $stmtDetalle->close();
    $conn->close();
}
?>