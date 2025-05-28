<?php
// Configuración de la conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "fepi";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener el correo del usuario (suponiendo que se pasa como parámetro en la URL)
$correo = $_GET['correoc'];

// Consultar pagos por correo
$sqlPagos = "SELECT idPago, correoComprador, fechaPago, total, idProd FROM pagos WHERE correoComprador = ?";
$stmtPagos = $conn->prepare($sqlPagos);
$stmtPagos->bind_param("s", $correo);
$stmtPagos->execute();
$resultPagos = $stmtPagos->get_result();

$totalComprado = 0;

// Mostrar resultados en una tabla HTML
echo "<table border='1'>";
echo "<tr><th>ID Pago</th><th>Correo Proveedor</th><th>Materia Prima</th><th>Sub Materia Prima</th><th>Monto</th><th>Fecha Pago</th></tr>";
while ($rowPagos = $resultPagos->fetch_assoc()) {
    // Consultar correo del proveedor y detalles del producto
    $sqlProducto = "SELECT correo, tipoProd, tipoSubProd FROM productos WHERE idProd = ?";
    $stmtProducto = $conn->prepare($sqlProducto);
    $stmtProducto->bind_param("i", $rowPagos['idProd']);
    $stmtProducto->execute();
    $resultProducto = $stmtProducto->get_result();
    $producto = $resultProducto->fetch_assoc();
    $correoProveedor = $producto['correo'];
    $tipoProd = $producto['tipoProd'];
    $tipoSubProd = $producto['tipoSubProd'];

    echo "<tr><td>" . $rowPagos["idPago"] . "</td><td>" . $correoProveedor . "</td><td>" . $tipoProd . "</td><td>" . $tipoSubProd . "</td><td>" . $rowPagos["total"] . "</td><td>" . $rowPagos["fechaPago"] . "</td></tr>";
    $totalComprado += $rowPagos["total"];
}
echo "<tr><td colspan='3'><strong>Total Comprado</strong></td><td colspan='3'><strong>" . $totalComprado . "</strong></td></tr>";
echo "</table>";
echo "<br><button onclick='window.location.href=\"/SUIMINISCAN/index.html\"'>Regresar</button>";// Cerrar conexión
$stmtPagos->close();
$conn->close();
?>
