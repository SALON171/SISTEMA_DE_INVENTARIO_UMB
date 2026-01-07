<?php
$servername = "localhost";
$username = "root"; // Por defecto en XAMPP
$password = "12345678";     // Por defecto en XAMPP vacío
$dbname = "Inventario_UMB"; // Asegúrate que tu BD se llame así

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>