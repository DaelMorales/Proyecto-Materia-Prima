<?php
// Obtener datos del formulario

session_start();
header('Content-Type: application/json');

$nombre = $_POST['nombre'];
$apellido1 = $_POST['apellido1'];
$apellido2 = $_POST['apellido2'];
$telefono = $_POST['telefono'];
$alcaldia = $_POST['alcaldia'];
$gen = $_POST['gen'];
$correo = $_POST['correo'];
$password = $_POST['password']; // Nota: Esto no está encriptado en esta versión básica

// Conectar a la base de datos (reemplaza estos valores con los de tu propia base de datos)
$servername = "localhost";
$username = "root";
$password_db = ""; // Cambia esto si tu contraseña de MySQL es diferente
$dbname = "fepi";

// Crear conexión
$conn = new mysqli($servername, $username, $password_db, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Ejecutar consulta SQL para insertar datos en la tabla
$sql = "INSERT INTO proveedores (nombre, apPat, apMat, telefono, alcaldia, gen, correo, contrasena) 
        VALUES ('$nombre', '$apellido1', '$apellido2', '$telefono', '$alcaldia', '$gen', '$correo', '$password')";

if ($conn->query($sql) === TRUE) {
    $response = array('success' => true, 'message' => 'Registro exitoso');
} else {
    $response = array('success' => false, 'message' => 'Error al registrar: ' . $conn->error);
}

echo json_encode($response);

// Cerrar conexión
$conn->close();
?>
