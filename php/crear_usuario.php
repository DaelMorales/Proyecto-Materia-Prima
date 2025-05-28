<?php
$conn = new mysqli('localhost', 'root', '', 'fepi');

 if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error);
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $nombre = $_POST['nombre'] ?? null;
    $apPat = $_POST['apPat'] ?? null;
    $apMat = $_POST['apMat'] ?? null;
    $telefono = $_POST['telefono'] ?? null;
    $alcaldia = $_POST['alcaldia'] ?? null;
    $gen = $_POST['gen'] ?? null;
    $correo = $_POST['correo'] ?? null;
    $contrasena = $_POST['contrasena'] ?? null;
    var_dump($nombre, $apPat, $apMat, $telefono, $alcaldia, $gen, $correo, $contrasena);
    // Verificar que todos los campos estén presentes
    if ($nombre && $apPat && $apMat && $telefono && $alcaldia && $gen && $correo && $contrasena) {
        $sql = "INSERT INTO usuarios (nombre, apPat, apMat, telefono, alcaldia, gen, correo, contrasena) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssss", $nombre, $apPat, $apMat, $telefono, $alcaldia, $gen, $correo, $contrasena);

        if ($stmt->execute()) {
            echo json_encode(["message" => "Usuario creado exitosamente"]);
        } else {
            echo json_encode(["error" => "Error al crear el usuario"]);
        }

        $stmt->close();
        $conn->close();
    } else {
        echo json_encode(["error" => "Todos los campos son obligatorios"]);
    }
}
?>
