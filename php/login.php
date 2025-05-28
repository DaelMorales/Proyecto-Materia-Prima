<?php
session_start();
header('Content-Type: application/json');

// Obtener datos del formulario
$correo = trim($_POST['correo']);
$password = trim($_POST['password']);

$_SESSION["correo"] = $correo;

// Conectar a la base de datos 
$servername = "localhost";
$username = "root";
$password_db = ""; // Cambia esto si tu contraseña de MySQL es diferente
$dbname = "fepi";

// Crear conexión
$conn = new mysqli($servername, $username, $password_db, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => "Conexión fallida: " . $conn->connect_error]);
    exit();
}

function setLoginCookies($nombre, $rol, $correo) {
    setcookie("correoc", $correo, time() + 36000, "/"); // 1 hora
    setcookie("nombre", $nombre, time() + 36000, "/"); // 1 hora
    setcookie("rol", $rol, time() + 36000, "/"); // 1 hora
}

try {
    // Consultar en la tabla usuarios
    $sql = "SELECT * FROM usuarios WHERE correo = ? AND contrasena = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $correo, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        setLoginCookies($row['nombre'], "USUARIO", $correo);
        echo json_encode(['success' => true, 'redirect' => './ofertas.html']);
        exit();
    }

    // Consultar en la tabla proveedores
    $sql = "SELECT * FROM proveedores WHERE correo = ? AND contrasena = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $correo, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        setLoginCookies($row['nombre'], "PROVEEDOR", $correo);
        echo json_encode(['success' => true, 'redirect' => './proveedores.html']);
        exit();
    }

    // Consultar en la tabla admin
    $sql = "SELECT * FROM admin WHERE correo = ? AND contrasena = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $correo, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION["acceso"] = "1";
        $row = $result->fetch_assoc();
        echo json_encode(['success' => true, 'redirect' => './admin.html']);
        exit();
    }

    // Si no coincide con ninguna tabla
    echo json_encode(['success' => false, 'message' => 'Inicio de sesión fallido. Correo o contraseña incorrectos.']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}

// Cerrar conexión
$stmt->close();
$conn->close();
?>
