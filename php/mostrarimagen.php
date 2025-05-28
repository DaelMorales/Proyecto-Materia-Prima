<?php
$conexion = new mysqli('localhost', 'root', '', 'fepi');

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Obtener los parámetros de la URL
$tipoProd = $_GET['tipoProd'];
$tipoSubProd = $_GET['tipoSubProd'];

if (empty($tipoProd) || empty($tipoSubProd)) {
    die("Faltan parámetros tipoProd o tipoSubProd.");
}

// Preparar la consulta para buscar en la base de datos con los nuevos parámetros
$stmt = $conexion->prepare("SELECT tipo, contenido FROM productos WHERE tipoProd = ? AND tipoSubProd = ?");
if(!$stmt){
    die("Preparación fallida: " . $conexion->error);
}
$stmt->bind_param("ss", $tipoProd, $tipoSubProd);
$stmt->execute();
$stmt->bind_result($tipo, $contenido);
$stmt->fetch();

if($contenido){
    header("Content-type: $tipo");
    echo $contenido;
} else {
    echo "No se encontró la imagen.";
}

$stmt->close();
$conexion->close();
?>
