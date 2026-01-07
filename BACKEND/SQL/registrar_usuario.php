<?php
include 'db.php';

// Recibir datos del formulario HTML
$nombre = $_POST['nombre'];
$correo = $_POST['correo'];
$rol = $_POST['rol']; // Asegúrate que el <select> en HTML tenga name="rol"
$num_empleado = $_POST['num_empleado'];
$ues = $_POST['ues'];
$pass = password_hash($_POST['password'], PASSWORD_DEFAULT); // Encriptar contraseña

// Preparar la consulta SQL
$sql = "INSERT INTO usuarios (nombre, num_empleado, ues_adscripcion, correo, contrasena, rol)
        VALUES ('$nombre', '$num_empleado', '$ues', '$correo', '$pass', '$rol')";

if ($conn->query($sql) === TRUE) {
    // Redirigir al usuario con éxito
    echo "<script>
            alert('Usuario registrado correctamente');
            window.location.href='PRINCIPAL.html';
          </script>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>