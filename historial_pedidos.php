<?php
// Incluir la conexión a la base de datos
$servername = "127.0.0.1";
$username = "root";
$password = "12345";
$dbname = "cafeteria";

$miConexion = new mysqli($servername, $username, $password, $dbname);

if ($miConexion->connect_error) {
    die("Error de conexión: " . $miConexion->connect_error);
}

// Obtener el historial de pedidos desde la base de datos
$consultaHistorial = "SELECT orden.id_orden, orden.matricula, orden.fecha, compra.id_comida, menu.nombre AS nombre_comida, compra.precio, compra.cantidad, compra.precio_total 
                     FROM orden
                     INNER JOIN compra ON orden.id_orden = compra.id_orden
                     INNER JOIN menu ON compra.id_comida = menu.id_comida
                     ORDER BY orden.id_orden DESC";
$resultadoHistorial = $miConexion->query($consultaHistorial);
$historialPedidos = [];

if ($resultadoHistorial->num_rows > 0) {
    while ($row = $resultadoHistorial->fetch_assoc()) {
        $historialPedidos[] = $row;
    }
}
$miConexion->close();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Historial de Pedidos - Cafetería</title>
    <link rel="stylesheet" href="reset.css">
    <link rel="stylesheet" href="style-home.css"> 
</head>
<body>
    <header>
        <div class="caja">
            <h1><img src="imagenes/logo.png"></h1>
        </div>
    </header>
    <main>
        <h1 class="titulo-principal">Historial de Pedidos</h1>
            <div class="container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID de Orden</th>
                                <th>Matrícula</th>
                                <th>Fecha</th>
                                <th>Comida</th>
                                <th>Precio</th>
                                <th>Cantidad</th>
                                <th>Total</th>
                                <th>Pedido Entregado</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            $lastOrderId = null;
                            $colorMap = array(); // Para asignar colores a IDs de orden
                            foreach ($historialPedidos as $pedido) {
                                if ($pedido["id_orden"] !== $lastOrderId) {
                                    // Si es una nueva orden, cierra la fila anterior y abre una nueva fila
                                    if ($lastOrderId !== null) {
                                        echo '</tr>';
                                    }
                                    
                                    // Genera un color para esta orden si aún no se asignó uno
                                    if (!isset($colorMap[$pedido["id_orden"]])) {
                                        $colorMap[$pedido["id_orden"]] = 'color-' . (count($colorMap) % 3 + 1); 
                                    }
                                    
                                    // Inicia una nueva fila con el estilo correspondiente si el ID de orden cambió
                                    echo '<tr class="orden-row ' . $colorMap[$pedido["id_orden"]] . '">';
                                }
                                
                                echo '<td>' . $pedido["id_orden"] . '</td>
                                    <td>' . $pedido["matricula"] . '</td>
                                    <td>' . $pedido["fecha"] . '</td>
                                    <td>' . $pedido["nombre_comida"] . '</td>
                                    <td>' . $pedido["precio"] . '</td>
                                    <td>' . $pedido["cantidad"] . '</td>
                                    <td>' . $pedido["precio_total"] . '</td>
                                    <td><input type="radio" name="entregado_' . $pedido["id_orden"] . '"/></td></tr>';
                                
                                $lastOrderId = $pedido["id_orden"];
                            }
                            
                            // Cierra la última fila
                            if ($lastOrderId !== null) {
                                echo '</tr>';
                            }
                            ?>
                        </tbody>   
                    </table>
            </div>
    
    </main>
</body>
</html>
