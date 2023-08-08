<?php
// Parametros de conexión a la base de datos
$servername = "127.0.0.1";
$username = "root";
$password = "12345";
$dbname = "cafeteria";

//la conexión y consulta a la bd esta dentro de un try catch que contendra posibles errores 
try {
    // Conexión a la base de datos
    $miConexion = new mysqli($servername, $username, $password, $dbname);

    // Verificar la conexión
    if ($miConexion->connect_error) {
        // Función de salida, te saca del código en caso de error de conexión
        die("Error de conexión: " . $miConexion->connect_error);
    }

    // Instrucción a ejecutar 
    $miConsulta = "SELECT * FROM menu WHERE disponibilidad = true";

    // Se manda como parametro la variable "$miConsulta" a la función "query"
    // La función "query" ejecuta una instrucción a base de datos 
    // la función "query" se ejecuta en la conexión guardada en la variable "$miConexión"
    // El resultado obtenido se guarda en la variable "$result"
    $result = $miConexion->query($miConsulta);

    // Arreglo para almacenar los productos disponibles
    $productos = array();

    // Valida si la variable $result tiene un valor
    if ($result) {
        // La función "fetch_assoc" lee una fila del arreglo "$result" obtenido de la base de datos
        // La fila obtenida se guarda en la variable "$row"
        // se inicia ciclo "while", leyéndose "mientras '$row' tenga valor entra al segmento de código" 
        while ($row = $result->fetch_assoc()) {
            // Se agrega el producto a la lista de productos disponibles
            $productos[] = $row;
        }
    }

    // Devolver los productos disponibles en formato JSON
    // Con json_encode se puede traducir cualquier cosa codificada de PHP a un string JSON
    // Los objetos se inspeccionan y sus atributos públicos se convierten. Esto ocurre de forma recursiva, por lo que los atributos del objeto disponibilidad también se traducen en JSON. Esta es una forma de transmitir fácilmente los productos en JSON, el lado del cliente podrá tomar el tiempo real y el timezone.
    echo json_encode($productos);
} catch (Exception $e) {
    // En caso de presentarse un error, se mostrará un mensaje de error
    echo json_encode(array("error" => "Se presentó un error al cargar los productos: " . $e->getMessage()));
}
?>
