<?php
$servername = "127.0.0.1";
$username = "root";
$password = "12345";
$dbname = "cafeteria";
try
{
  // Conexión a la base de datos
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Verificar la conexión
  if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
  }

  // Obtener los datos del formulario
  $matricula = $_POST['matricula'];
  $nombre = $_POST['nombre'];
  $carrera = $_POST['carrera'];
  $grupo = $_POST['grupo'];
  $apellidoPaterno = $_POST['apellidoPaterno'];
  $apellidoMaterno = $_POST['apellidoMaterno'];
  $contraseña = $_POST['contraseña'];
  $periodo = $_POST['periodo'];

  // Insertar los datos en la base de datos
  $sql = "INSERT INTO registros (matricula, nombre, carrera, grupo, apellidoPaterno, apellidoMaterno, contraseña, periodo) VALUES ('$matricula', '$nombre', '$carrera', '$grupo', '$apellidoPaterno', '$apellidoMaterno', '$contraseña', '$periodo' )";echo 'sql: ',  $sql, "\n";

  if ($conn->query($sql) === TRUE) {
    echo "Registro exitoso";
    // Redirigir a otra página después del registro exitoso
    header("Location: productos.php");
    exit;
  } else {
    echo "Error al registrar: " . $conn->error;
  }

  // Cerrar la conexión a la base de datos
  $conn->close();

}
catch(Exception $e)
{
  echo 'Caught exception: ',  $e->getMessage(), "\n";
}
?>
