<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Productos - Cafeteria</title>
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
        <h1 class="titulo-principal">Menú del día de hoy</h1>
        <!-- Se crea el listado donde se agregarán los productos que se obtengan de la base de datos  -->
        <ul class="productos">
        <?php
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
                
                // Instrucción a ejecutar para obtener los productos
                $miConsulta = "SELECT * FROM menu";
                $result = $miConexion->query($miConsulta);
                
                // Valida si la consulta devuelve resultados
                if ($result) {
                    // Ciclo para recorrer los productos obtenidos
                    while ($row = $result->fetch_assoc()) {
                        $id_comida = $row["id_comida"];
                        $nombre = $row["nombre"];
                        $precio = $row["precio"];
                        $descripcion = $row["descripcion"];
                        $imagen = $row["imagen"];
                        $disponibilidad = $row["disponibilidad"];
                        
                        // Definimos el texto del botón según la disponibilidad del producto
                        $texto_boton = $disponibilidad ? "Cambiar a no disponible" : "Cambiar a disponible";
                        
                        echo '<li>
                                <h2>'. $nombre . '</h2>
                                <img src="imagenes/' . $imagen . '">
                                <p class="producto-descripcion">' . $descripcion . '</p>
                                <p class="producto-precio">$ ' . $precio . '</p>
                                <button class="enviar" onclick="cambiarDisponibilidad(' . $id_comida . ')" data-id="' . $id_comida . '">' . $texto_boton . '</button>
                            </li>';
                    }
                }
            } catch(Exception $e) {
                echo '<li><h3>Se presentó un error al cargar los productos: ',  $e->getMessage(), "</h3></li>";
            }
        ?>
        </ul>
    </main>
    <footer>
        <br>
        <br>
        <br>
        <br>
    </footer>

    <script>
        // Función que se ejecuta cuando se presiona sobre un botón de producto
        function cambiarDisponibilidad(id_comida) {
            // Crear una solicitud AJAX para cambiar la disponibilidad en el servidor
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "cambiar_disponibilidad.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        // Si la solicitud se completó correctamente, actualizamos el texto del botón
                        var boton = document.querySelector("li button[data-id='" + id_comida + "']");
                        var nuevoTexto = xhr.responseText === "1" ? " Cambiar a no disponible" : "Cambiar a disponible";
                        boton.textContent = nuevoTexto;
                    } else {
                        alert("Se presentó un error al cambiar la disponibilidad.");
                    }
                }
            };
            xhr.send("id_comida=" + id_comida);
        }
    </script>
</body>
</html>
