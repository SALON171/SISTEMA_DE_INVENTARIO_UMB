<?php
// conexion.php - Configuración corregida
$host = "localhost";
$user = "umb_user";      
$pass = "umb_password123"; 
$db   = "sistema_umb";

// Crear conexión
$conn = new mysqli($host, $user, $pass, $db);

// Verificar conexión
if ($conn->connect_error) {
    // Mensaje detallado para depuración
    $error_msg = "Error de conexión MySQL: " . $conn->connect_error . "\n";
    $error_msg .= "Host: $host\n";
    $error_msg .= "Usuario: $user\n";
    $error_msg .= "Base de datos: $db\n";
    
    // En producción, mostrar mensaje genérico
    if (php_sapi_name() === 'cli') {
        die($error_msg);
    } else {
        error_log($error_msg);
        die(json_encode([
            "success" => false,
            "message" => "Error de conexión con la base de datos"
        ]));
    }
}

// Configurar charset
$conn->set_charset("utf8mb4");

// Opcional: para verificar en desarrollo
if (isset($_GET['debug'])) {
    echo "✅ Conexión exitosa a MySQL";
}
?>