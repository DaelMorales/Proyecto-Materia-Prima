<?php
header('Content-Type: application/json');

try {
    // Conexión a la base de datos
    $conexion = new mysqli('localhost', 'root', '', 'fepi');

    // Verificar si la conexión fue exitosa
    if ($conexion->connect_error) {
        throw new Exception("Conexión fallida: " . $conexion->connect_error);
    }

    // Obtener los datos enviados desde pago.html
    $idProd = $_GET['idProd'];
    $correoC = $_GET['correoC'];
    $total = $_GET['total'];

    // Obtener datos adicionales de la tabla productos
    $productoQuery = $conexion->prepare("SELECT correo, tipoProd, tipoSubProd, precio FROM productos WHERE idProd = ?");
    $productoQuery->bind_param("i", $idProd);
    $productoQuery->execute();
    $productoResult = $productoQuery->get_result();

    if ($productoResult->num_rows > 0) {
        $productoData = $productoResult->fetch_assoc();
        $correop = $productoData['correo'];
        $nombreProd = $productoData['tipoProd'];
        $nombreProducto = $productoData['tipoSubProd'];
        $precioProducto = $productoData['precio'];
    } else {
        throw new Exception("Producto no encontrado.");
    }

    // Obtener datos adicionales del proveedor
    $proveedorQuery = $conexion->prepare("SELECT nombre, apPat, apMat, telefono, alcaldia, correo FROM proveedores WHERE correo = ?");
    $proveedorQuery->bind_param("s", $correop);
    $proveedorQuery->execute();
    $proveedorResult = $proveedorQuery->get_result();

    if ($proveedorResult->num_rows > 0) {
        $proveedorData = $proveedorResult->fetch_assoc();
        $nombre = $proveedorData['nombre'];
        $apProveedor = $proveedorData['apPat'];
        $apMProveedor = $proveedorData['apMat'];
        $telProveedor = $proveedorData['telefono'];
        $alcProveedor = $proveedorData['alcaldia'];
        $correoProveedor = $proveedorData['correo'];
    } else {
        throw new Exception("Proveedor no encontrado.");
    }

    $cantidadComprada = $total / $precioProducto;
    $fechaCompra = date('Y-m-d H:i:s'); // Obtener la fecha y hora actual
    $datosComprador = $correoC; // Datos del comprador

    // Respuesta exitosa en JSON con detalles del ticket
    echo json_encode(array(
        'success' => true,
        'Nombreproveedor' => $nombre,
        'appProveedor' => $apProveedor,
        'apmProveedor' => $apMProveedor,
        'telProveedor' => $telProveedor,
        'alcProveedor' => $alcProveedor,
        'correoProveedor' => $correoProveedor,
        'nombreProd' => $nombreProd,
        'nombreProducto' => $nombreProducto,
        'cantidadComprada' => $cantidadComprada,
        'cantidadPagada' => $total,
        'fechaCompra' => $fechaCompra,
        'datosComprador' => $datosComprador
    ));
} catch (Exception $e) {
    // Registrar el error en el archivo de log y devolver un mensaje de error en JSON
    error_log($e->getMessage());
    echo json_encode(array('error' => $e->getMessage()));
}
?>
