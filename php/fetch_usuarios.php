<?php
$conexion = new mysqli('localhost', 'root', '', 'fepi');

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

$result = $conexion->query("SELECT * FROM usuarios");
$usuarios = $result->fetch_all(MYSQLI_ASSOC);

echo json_encode($usuarios);

$conexion->close();
?>
