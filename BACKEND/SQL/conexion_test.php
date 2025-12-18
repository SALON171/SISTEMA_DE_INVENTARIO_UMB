<?php
header('Content-Type: text/plain');

try {
    include 'conexion.php';
    
    echo "✅ CONEXIÓN EXITOSA A LA BASE DE DATOS\n\n";
    echo "Servidor: " . $conn->host_info . "\n";
    echo "Base de datos: sistema_umb\n";
    echo "Charset: " . $conn->character_set_name() . "\n\n";
    
    // Probar consulta de materiales
    $result = $conn->query("SELECT COUNT(*) as total FROM material");
    if ($result) {
        $row = $result->fetch_assoc();
        echo "📦 Total materiales en BD: " . $row['total'] . "\n";
    }
    
    // Probar consulta de solicitudes
    $result = $conn->query("SELECT COUNT(*) as total FROM solicitud");
    if ($result) {
        $row = $result->fetch_assoc();
        echo "📋 Total solicitudes en BD: " . $row['total'] . "\n";
    }
    
    $conn->close();
    
} catch (Exception $e) {
    echo "❌ ERROR DE CONEXIÓN:\n";
    echo $e->getMessage() . "\n";
    echo "Verifique:\n";
    echo "1. Servidor MySQL activo\n";
    echo "2. Base de datos 'sistema_umb' existe\n";
    echo "3. Usuario y contraseña correctos\n";
    echo "4. Archivo conexion.php configurado\n";
}
?>