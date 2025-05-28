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

// Consultar productos por correo
$sql = "SELECT idProd, tipoSubProd, tipoProd FROM productos WHERE correo = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $correo);
$stmt->execute();
$result = $stmt->get_result();

$productos = [];
while ($row = $result->fetch_assoc()) {
    $productos[$row['idProd']] = [
        'tipoSubProd' => $row['tipoSubProd'],
        'tipoProd' => $row['tipoProd']
    ];
}

$totalVendido = 0;

if (!empty($productos)) {
    // Convertir array de IDs en una cadena separada por comas
    $productosIds = implode(',', array_keys($productos));

    // Consultar pagos por idProd
    $sqlPagos = "SELECT idPago, correoComprador, fechaPago, total, idProd FROM pagos WHERE idProd IN ($productosIds)";
    $resultPagos = $conn->query($sqlPagos);

    // Mostrar resultados en una tabla HTML
    echo "<table border='1'>";
    echo "<tr><th>ID Pago</th><th>Correo Comprador</th><th>Fecha Compra</th><th>Monto</th><th>Tipo Sub Materia Prima</th><th>Materia Prima</th><th>Fecha Pago</th></tr>";
    while ($rowPagos = $resultPagos->fetch_assoc()) {
        $tipoSubProd = $productos[$rowPagos["idProd"]]['tipoSubProd'];
        $tipoProd = $productos[$rowPagos["idProd"]]['tipoProd'];
        echo "<tr><td>" . $rowPagos["idPago"] . "</td><td>" . $rowPagos["correoComprador"] . "</td><td>" . $rowPagos["fechaPago"] . "</td><td>" . $rowPagos["total"] . "</td><td>" . $tipoSubProd . "</td><td>" . $tipoProd . "</td><td>" . $rowPagos["fechaPago"] . "</td></tr>";
        $totalVendido += $rowPagos["total"];
    }
    echo "<tr><td colspan='3'><strong>Total Vendido</strong></td><td colspan='4'><strong>" . $totalVendido . "</strong></td></tr>";
    echo "</table>";
} else {
    echo "No se encontraron productos para el correo proporcionado.";
}
echo "<br><button onclick='window.location.href=\"/SUIMINISCAN/index.html\"'>Regresar</button>";// Cerrar conexión


// Cerrar conexión
$stmt->close();
$conn->close();
?>
