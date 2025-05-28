<?php
header('Content-Type: application/json');

// Conexión a la base de datos
$conexion = new mysqli('localhost', 'root', '', 'fepi');

// Verificar si la conexión fue exitosa
if ($conexion->connect_error) {
    echo json_encode(array('error' => "Conexión fallida: " . $conexion->connect_error));
    exit();
}

// Verificar si la cookie del correo está disponible
if (isset($_COOKIE['correoc'])) {
    $correoComprador = $_COOKIE['correoc'];
} else {
    echo json_encode(array('error' => "No se encontró el correo del comprador."));
    exit();
}

// Obtener los datos del formulario
$cardNumber = $_POST['cardNumber'];
$expiryDate = $_POST['expiryDate'];
$cvv = $_POST['cvv'];
$billingAddress = $_POST['billingAddress'];
$total = $_POST['total'];  // Obtener el total a pagar
$idProd = $_POST['idProd'];

// Validar y sanitizar datos del formulario (opcional, pero recomendado)
$cardNumber = $conexion->real_escape_string($cardNumber);
$expiryDate = $conexion->real_escape_string($expiryDate);
$cvv = $conexion->real_escape_string($cvv);
$billingAddress = $conexion->real_escape_string($billingAddress);
$total = $conexion->real_escape_string($total);
$idProd = $conexion->real_escape_string($idProd);

// Preparar la consulta para insertar los datos del pago
$stmt = $conexion->prepare("INSERT INTO pagos (correoComprador, cardNumber, expiryDate, cvv, billingAddress, total, idProd) VALUES (?, ?, ?, ?, ?, ?, ?)");
if (!$stmt) {
    echo json_encode(array('error' => "Error en la preparación de la consulta: " . $conexion->error));
    exit();
}

// Vincular los parámetros
$stmt->bind_param("ssssssi", $correoComprador, $cardNumber, $expiryDate, $cvv, $billingAddress, $total, $idProd);

// Ejecutar la consulta
if ($stmt->execute()) {
    // Respuesta exitosa en JSON
    echo json_encode(array('success' => true, 'message' => 'Pago exitoso'));
} else {
    echo json_encode(array('error' => "Error al procesar el pago: " . $stmt->error));
}

// Cerrar la conexión
$stmt->close();
$conexion->close();
?>
