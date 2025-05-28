<?php
header('Content-Type: application/json');

$conexion = new mysqli('localhost', 'root', '', 'fepi');

if ($conexion->connect_error) {
    echo json_encode(['error' => "Conexión fallida: " . $conexion->connect_error]);
    exit();
}

$idProd = $_GET['idProd'];
$correop = $_GET['correo'];

$stmt = $conexion->prepare("
    SELECT p.tipo, p.contenido, p.precio, p.tipoSubProd, p.tipoProd, p.descrip, 
           pr.nombre, pr.apPat, pr.apMat, pr.telefono, pr.alcaldia, pr.gen, pr.correo
    FROM productos p
    JOIN proveedores pr ON p.correo = pr.correo
    WHERE p.idProd = ? AND pr.correo = ?
");
if (!$stmt) {
    echo json_encode(['error' => "Preparación fallida: " . $conexion->error]);
    exit();
}
$stmt->bind_param("is", $idProd, $correop);
$stmt->execute();
$stmt->bind_result($tipo, $contenido, $precio, $tipoSubProd, $tipoProd, $descrip, $nombre, $apPat, $apMat, $telefono, $alcaldia, $gen, $correo);
$stmt->fetch();

if ($tipo) {
    echo json_encode([
        'tipo' => $tipo,
        'contenido' => base64_encode($contenido),
        'precio' => $precio,
        'tipoSubProd' => $tipoSubProd,
        'tipoProd' => $tipoProd,
        'descrip' => $descrip,
        'proveedorNombre' => $nombre,
        'apPat' => $apPat,
        'apMat' => $apMat,
        'telefono' => $telefono,
        'alcaldia' => $alcaldia,
        'correo' => $correo
    ]);
} else {
    echo json_encode(['error' => "No se encontró el producto."]);
}

$stmt->close();
$conexion->close();
?>
