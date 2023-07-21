<?php
// Verificar si se recibió el parámetro "id_comida" en la solicitud POST
if (isset($_POST['id_comida'])) {
    // Parámetros de conexión a la base de datos
    $servername = "127.0.0.1";
    $username = "root";
    $password = "12345";
    $dbname = "cafeteria";

    try {
        // Conexión a la base de datos
        $miConexion = new mysqli($servername, $username, $password, $dbname);

        // Verificar la conexión
        if ($miConexion->connect_error) {
            die("Error de conexión: " . $miConexion->connect_error);
        }

        // Obtener el valor actual de disponibilidad del producto
        $id_comida = $_POST['id_comida'];
        $consulta = "SELECT disponibilidad FROM menu WHERE id_comida = $id_comida";
        $resultado = $miConexion->query($consulta);
        
        // Verificar si la consulta se realizó correctamente
        if ($resultado && $resultado->num_rows > 0) {
            $fila = $resultado->fetch_assoc();
            $disponibilidad_actual = $fila['disponibilidad'];

            // Cambiar el valor de disponibilidad en la base de datos
            $nuevo_estado = $disponibilidad_actual ? 0 : 1;
            $actualizarConsulta = "UPDATE menu SET disponibilidad = $nuevo_estado WHERE id_comida = $id_comida";
            $miConexion->query($actualizarConsulta);

            // Enviar el nuevo estado de disponibilidad como respuesta a la solicitud AJAX
            echo $nuevo_estado;
        } else {
            echo "Error: Producto no encontrado.";
        }
    } catch(Exception $e) {
        echo "Error: " . $e->getMessage();
    }

    // Cerrar la conexión a la base de datos
    $miConexion->close();
} else {
    echo "Error: No se proporcionó el parámetro 'id_comida'.";
}
?>
