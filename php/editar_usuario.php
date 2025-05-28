<?php
$conexion = new mysqli('localhost', 'root', '', 'fepi');

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

$id = $_POST['id'];
$nombre = $_POST['nombre'];
$apPat = $_POST['apellido1'];
$apMat = $_POST['apellido2'];
$telefono = $_POST['telefono'];
$alcaldia = $_POST['alcaldia'];
$gen = $_POST['genTutor'];
$correo = $_POST['correo'];
$contrasena = $_POST['password'];

$stmt = $conexion->prepare("UPDATE usuarios SET nombre = ?, apPat = ?, apMat = ?, telefono = ?, alcaldia = ?, gen = ?, correo = ?, contrasena = ? WHERE id = ?");
$stmt->bind_param("ssssssssi", $nombre, $apPat, $apMat, $telefono, $alcaldia, $gen, $correo, $contrasena, $id);
$stmt->execute();

$conexion->close();
?>
