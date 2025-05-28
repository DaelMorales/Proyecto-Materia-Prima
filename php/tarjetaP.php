<?php
$conexion = new mysqli('localhost', 'root', '', 'fepi');

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

$tipoProd = $_GET['tipoProd'] ?? null;

if (!$tipoProd) {
    die("Faltan parámetros tipoProd.");
}

$stmt = $conexion->prepare("SELECT idProd, tipo, contenido, precio, tipoSubProd, tipoProd, descrip, correo FROM productos WHERE tipoProd = ?");
if (!$stmt) {
    die("Preparación fallida: " . $conexion->error);
}
$stmt->bind_param("s", $tipoProd);
$stmt->execute();
$result = $stmt->get_result();

$productos = [];
while ($row = $result->fetch_assoc()) {
    // Se clona cada fila para evitar referencias no deseadas
    $productos[] = $row;
}
$stmt->close();

// Obtener comentarios
foreach ($productos as $key => $producto) {
    $producto_id = $producto['idProd'];
    $stmt = $conexion->prepare("SELECT comentario FROM comentarios WHERE idProd = ?");
    $stmt->bind_param("i", $producto_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $comentarios = [];
    while ($row = $result->fetch_assoc()) {
        $comentarios[] = $row['comentario'];
    }
    // Asignamos los comentarios al producto correcto
    $productos[$key]['comentarios'] = $comentarios;
    $stmt->close();
}

$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tarjeta del Producto</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .card-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            padding: 20px;
            gap: 10px;
        }
        .card {
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            overflow: hidden;
            margin-bottom: 20px;
        }
        .card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .card-body {
            padding: 15px;
            text-align: center;
        }
        .precio {
            color: green;
            font-size: 24px;
            font-weight: bold;
        }
        .comentarios {
            text-align: left;
            margin-top: 10px;
        }
        .comentarios p {
            font-size: 14px;
            color: #555;
            margin: 0;
        }
        .boton {
            display: inline-block;
            padding: 12px 24px;
            font-size: 12px;
            color: #fff;
            background-color: rgb(0, 133, 82);
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            transition: background-color 0.3s ease, transform 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .boton:hover {
            background-color: rgb(51, 107, 249);
            transform: translateY(-2px);
        }
        .boton:active {
            background-color: #004494;
            transform: translateY(0);
        }
    </style>
</head>
<body>
    <div class="card-container">
        <?php foreach ($productos as $producto): ?>
        <div class="card">
            <?php if ($producto['contenido']): ?>
                <img src="data:<?php echo $producto['tipo']; ?>;base64,<?php echo base64_encode($producto['contenido']); ?>" alt="Producto">
            <?php else: ?>
                <div>No se encontró la imagen.</div>
            <?php endif; ?>
            <div class="card-body">
                <h5><?php echo $producto['tipoProd']; ?></h5>
                <p><strong>Tipo:</strong> <?php echo $producto['tipoSubProd']; ?></p>
                <p><strong>Descripción:</strong> <?php echo $producto['descrip']; ?></p>
                <div class="stars" style="color: gold"> 
        <i class="fas fa-star"></i>
         <i class="fas fa-star"></i> 
         <i class="fas fa-star"></i> 
         <i class="fas fa-star"></i> 
         <i class="fas fa-star-half-alt"></i>
         </div>
                <div class="comentarios">
                    <h6>Comentarios:</h6>
                    <?php if (!empty($producto['comentarios'])): ?>
                        <?php foreach ($producto['comentarios'] as $comentario): ?>
                            <p>- <?php echo $comentario; ?></p>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No hay comentarios.</p>
                    <?php endif; ?>
                </div>
                <p class="precio">$<?php echo $producto['precio']; ?></p>
                <a href="/SUIMINISCAN/compra.html?idProd=<?php echo $producto['idProd']; ?>&correo=<?php echo $producto['correo']; ?>" class="boton">Comprar</a>

                <button class="boton" onclick="mostrarPanelComentario(<?php echo $producto['idProd']; ?>)">Comentar</button>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <div id="panelComentario" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%, -50%); background-color:white; padding:20px; border:1px solid #ccc; border-radius:10px; box-shadow:0 0 10px rgba(0, 0, 0, 0.1);">
        <h5>Agregar Comentario</h5>
        <textarea id="comentarioTexto" rows="4" cols="50" placeholder="Escribe tu comentario"></textarea><br>
        <button class="boton" onclick="agregarComentario()">Enviar</button>
        <button class="boton" onclick="cerrarPanelComentario()">Cerrar</button>
    </div>

    <script>
         function mostrarPanelComentario(idProd) { 
        document.cookie = "idProd=" + idProd; 
       // document.cookie = "correoc=" + correoc; 
        document.getElementById('panelComentario').style.display = 'block'; 
    } function cerrarPanelComentario() { 
        document.getElementById('panelComentario').style.display = 'none';
     } function agregarComentario() { 
        let comentario = document.getElementById('comentarioTexto').value; 
        let idProd = getCookie("idProd"); 
        let correoc = getCookie("correoc"); 
        if (comentario && idProd && correoc) { 
            let xhr = new XMLHttpRequest(); 
            xhr.open("POST", "./agregacomentario.php", true);
             xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
             xhr.onreadystatechange = function() { 
                if (this.readyState === XMLHttpRequest.DONE && this.status === 200) { 
                    alert("Comentario agregado exitosamente.");
                     cerrarPanelComentario(); location.reload();
                     } }
                      xhr.send("comentario=" + comentario + "&idProd=" + idProd + "&correoc=" + correoc); 
                      console.log("comentario=" + comentario + "&idProd=" + idProd + "&correoc=" + correoc); 
                    } else { 
                        alert("Falta comentario, idProd o correo."); 
                    } } function getCookie(name) { 
                        let cookieArr = document.cookie.split(";");
                         for (let i = 0; i < cookieArr.length; i++) { 
                            let cookiePair = cookieArr[i].split("=");
                             if (name == cookiePair[0].trim()) { 
                                return decodeURIComponent(cookiePair[1]);
                             } } 
                             return null;
                            }
    </script>
</body>
</html>
