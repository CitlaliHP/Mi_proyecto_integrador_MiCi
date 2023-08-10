<?php
$servername = "127.0.0.1";
$username = "root";
$password = "12345";
$dbname = "cafeteria";

try {
    $miConexion = new mysqli($servername, $username, $password, $dbname);

    if (isset($_POST["id_orden"]) && isset($_POST["productos"])) {
        $id_orden = $_POST["id_orden"];
        $productosData = $_POST["productos"];
        $productosSeleccionados = json_decode($productosData, true);

        foreach ($productosSeleccionados as $producto) {
            $nombreProducto = $miConexion->real_escape_string($producto["nombre"]);
            $cantidad = intval($producto["cantidad"]);

            // Obtener información del producto desde la tabla 'menu'
            $miConsultaMenu = "SELECT * FROM menu WHERE nombre = '$nombreProducto'";
            $result = $miConexion->query($miConsultaMenu);

            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $id_comida = $row["id_comida"];
                $precio = $row["precio"];

                // Calcular el precio total del producto en esta compra
                $precioTotalProducto = $precio * $cantidad;

                // Guardar los detalles de la compra en la tabla 'compra'
                $miConsultaCompra = "INSERT INTO compra (id_orden, id_comida, precio, cantidad, precio_total) VALUES ($id_orden, $id_comida, $precio, $cantidad, $precioTotalProducto)";
                $miConexion->query($miConsultaCompra);
            } else {
                echo "Error: Producto no encontrado en el menú.";
                exit;
            }
        }

        echo "Compra registrada correctamente.";
    } else {
        echo "Error: Datos insuficientes para registrar la compra.";
    }
} catch (Exception $e) {
    echo "Error al registrar la compra: " . $e->getMessage();
}
?>
