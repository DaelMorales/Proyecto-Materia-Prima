<?php
 $servername = "localhost"; $username = "root"; $password = ""; $dbname = "fepi"; // Crear la conexión
 $conn = new mysqli($servername, $username, $password, $dbname); // Verificar la conexión 
 if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error);
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents("php://input");
    $data = json_decode($input, true);

    var_dump($data); // Para depuración, imprimir el contenido de $data
    $id = $data['id'] ?? null;

    if ($id !== null) {
        $sql = "DELETE FROM usuarios WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo json_encode(["message" => "Usuario eliminado exitosamente"]);
        } else {
            echo json_encode(["error" => "Error al eliminar el usuario"]);
        }

        $stmt->close();
        $conn->close();
    } else {
        echo json_encode(["error" => "Id no proporcionado"]);
    }
}
?>
