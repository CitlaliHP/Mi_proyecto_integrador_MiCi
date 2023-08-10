<?php
$servername = "127.0.0.1";
$username = "root";
$password = "12345";
$dbname = "cafeteria";

try {
    $miConexion = new mysqli($servername, $username, $password, $dbname);

    if (isset($_POST["matricula"])) {
        
        $matricula = trim($_POST["matricula"]);


        // Insertar en la tabla 'orden'
        $query = "INSERT INTO orden (matricula) VALUES ('$matricula')";
        $miConexion->query($query);

        $idOrden = $miConexion->insert_id;
        echo $idOrden; // Devolver el ID de la orden recién guardada
    }
} catch (Exception $e) {
    echo "Error al guardar la orden: " . $e->getMessage();
}
?>
