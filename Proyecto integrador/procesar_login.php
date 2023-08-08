<?php

session_unset();
session_destroy();

session_start();

// Parametros de conexión a la base de datos
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

    // Obtener los valores de matrícula y contraseña del formulario
    $matriculaForm = $_POST['matricula'];
    $contraseñaForm = $_POST['contraseña'];

    $miConsulta = "SELECT * FROM registros WHERE matricula='$matriculaForm'";

    // Ejecutar la consulta
    $result = $miConexion->query($miConsulta);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashedPassword = $row['contraseña'];

        // Verificar si la contraseña ingresada coincide con la contraseña almacenada (usando password_verify)
        if (password_verify($contraseñaForm, $hashedPassword)) {
            // Inicio de sesión exitoso
            echo "Inicio de sesión exitoso";
            $_SESSION["matricula"] = $matriculaForm;
            // Redirigir a otra página después del inicio de sesión exitoso
            header("Location: productos.php");
            exit;
        } else {
            // Contraseña incorrecta
            echo "Error al iniciar sesión: contraseña incorrecta";
        }
    } else {
        // Matrícula incorrecta
        echo "Error al iniciar sesión: matrícula no encontrada";
    }

    // Cerrar la conexión a la base de datos
    $miConexion->close();
} catch (Exception $e) {
    echo 'Caught exception: ', $e->getMessage(), "\n";
}
?>
