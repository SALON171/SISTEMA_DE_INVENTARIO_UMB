<?php
header('Content-Type: application/json');
require 'conexion.php';

$id_solicitud = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_solicitud <= 0) {
    echo json_encode(["success" => false, "message" => "ID de solicitud inválido"]);
    exit;
}

try {
    $sql = "SELECT 
                ds.cantidad,
                ds.tipo_cantidad,
                m.nombre_material
            FROM detalle_solicitud ds
            JOIN material m ON ds.material_id = m.idMATERIAL
            WHERE ds.solicitud_id = ?
            ORDER BY ds.id_detalle";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_solicitud);
    $stmt->execute();
    
    $result = $stmt->get_result();
    $detalles = [];
    
    while ($row = $result->fetch_assoc()) {
        $detalles[] = $row;
    }
    
    echo json_encode([
        "success" => true,
        "detalles" => $detalles,
        "total" => count($detalles)
    ]);
    
    $stmt->close();
    
} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "Error al obtener detalles: " . $e->getMessage()
    ]);
}

if (isset($conn)) {
    $conn->close();
}
?>