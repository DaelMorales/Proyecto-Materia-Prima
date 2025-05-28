<?php
$conexion = new mysqli('localhost', 'root', '', 'fepi');

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

$id = $_GET['idProv'];

$stmt = $conexion->prepare("SELECT * FROM proveedores WHERE idProv = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

echo json_encode($usuario);

$stmt->close();
$conexion->close();
?>
