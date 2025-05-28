<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conexion = new mysqli('localhost', 'root', '', 'fepi');

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Obtener el valor de la cookie 'correoc'
$correo = $_COOKIE['correoc'] ?? null;

// Obtener los valores del formulario
$comentario = $_POST['comentario'] ?? '';
$idProd = $_POST['idProd'] ?? '';

// Verificar que todos los datos estén presentes
if (empty($comentario) || empty($idProd) || empty($correo)) {
    die("Faltan datos. Cookie correoc: " . ($correo ?? 'No disponible') . ", Comentario: " . ($comentario ?? 'No disponible') . ", idProd: " . ($idProd ?? 'No disponible'));
}

echo "Cookie correoc: " . ($correo ?? 'No disponible') . "<br>";
echo "Comentario: " . ($comentario ?? 'No disponible') . "<br>";
echo "idProd: " . ($idProd ?? 'No disponible') . "<br>";

$stmt = $conexion->prepare("INSERT INTO comentarios (comentario, idProd) VALUES (?, ?)");
if (!$stmt) {
    die("Preparación fallida: " . $conexion->error);
}
$stmt->bind_param("si", $comentario, $idProd);

if ($stmt->execute()) {
    echo "Comentario agregado exitosamente.";
} else {
    echo "Error al agregar comentario: " . $stmt->error;
}

$stmt->close();
$conexion->close();
?>
