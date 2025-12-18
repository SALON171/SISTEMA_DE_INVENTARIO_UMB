<?php
include("conexion.php");

$usuario = $_POST['usuario'];
$password = $_POST['password'];

$sql = "SELECT * FROM USUARIO WHERE correo_institucional = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $usuario);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows == 1) {
    $fila = $resultado->fetch_assoc();

    if ($password === $fila['password']) {
        echo "<h2>✅ Bienvenido ".$fila['nombre_completo']."</h2>";
    } else {
        echo "❌ Contraseña incorrecta";
    }
} else {
    echo "❌ Usuario no encontrado";
}

$stmt->close();
$conn->close();
?>