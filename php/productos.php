<?php
session_start();
header('Content-Type: application/json');

// Obtener datos del formulario

$servername = "localhost";
$username = "root";
$password_db = ""; // Cambia esto si tu contraseña de MySQL es diferente
$dbname = "fepi";

$conn = new mysqli($servername, $username, $password_db, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener datos del formulario
$correo = $_POST['correo'];
$producto = $_POST['producto'];
$subproducto = $_POST['subproducto'];
$descripcion = $_POST['descripcion'];+
$precio = $_POST['precio'];
$nombre = $_FILES['imagen']['name'];
$tipo = $_FILES['imagen']['type'];
$tamanio = $_FILES['imagen']['size'];
$contenido = file_get_contents($_FILES['imagen']['tmp_name']);

// Guardar datos en la base de datos
$stmt = $conn->prepare("INSERT INTO productos (correo, tipoProd, tipoSubProd, descrip, precio, nombre, tipo, tam, contenido) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssissis", $correo, $producto, $subproducto, $descripcion, $precio, $nombre, $tipo, $tamanio, $contenido);

if ($stmt->execute()) {
    echo "La imagen y los datos se han subido correctamente.";
    header('Location: ../proveedores.html');
     exit();
} else {
    echo "Error al subir los datos: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
