<?php
// Parametros de conexión a la base de datos
$servername = "127.0.0.1";
$username = "root";
$password = "12345";
$dbname = "cafeteria";

// Conexión a la base de datos
$miConexion = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($miConexion->connect_error) {
    die("Error de conexión: " . $miConexion->connect_error);
}

// Obtener los valores de matrícula y contraseña del formulario
$matriculaForm = $_POST['matricula'];
$contraseñaForm = $_POST['contraseña'];

$miConsulta = "SELECT * FROM registros WHERE matricula='$matriculaForm' AND contraseña='$contraseñaForm'";

// Valida si la variable $result tiene un valor
$result = $miConexion->query($miConsulta);

if ($result->num_rows > 0) {
    // Si hay un resultado, significa que la matrícula y la contraseña son correctas
    echo "Inicio de sesión exitoso";
    // Redirigir a otra página después del inicio de sesión exitoso
    header("Location: productos.php");
    exit;
} else {
    // Si no hay resultados, significa que la matrícula o la contraseña son incorrectas
    echo "Error al iniciar sesión: matrícula o contraseña incorrectas";
}

// Cerrar la conexión a la base de datos
$miConexion->close();
?>
