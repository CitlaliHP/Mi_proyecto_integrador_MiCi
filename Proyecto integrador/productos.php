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
        <!-- Se crea el listado donde se agregaran los productos que se obtengan de la base de datos  -->
        <ul class="productos">
        <?php
        
            // Parametros de conexión a la base de datos
            $servername = "127.0.0.1";
            $username = "root";
            $password = "12345";
            $dbname = "cafeteria";
            //la conexión y consulta a la bd esta dentro de un try catch que contendra posibles errores 

            try
            {
                // Conexión a la base de datos
                $miConexion = new mysqli($servername, $username, $password, $dbname);

                // Verificar la conexión
                if ($miConexion->connect_error) {
                    // Función de salida, te saca del codigo en caso de error de conexión
                    die("Error de conexión: " . $miConexion->connect_error);
                }
               // Instruccion a ejecutar 
                $miConsulta = "SELECT * FROM menu";
                // Se manda como parametro la variable "$miConsulta" a la función "query"
                // La función "query" ejecuta una instrucción a base de datos 
                // la función "query" se ejecuta en la conexión guardada en la variable "$miConexión"
                // El resultado obtenido se guarda en la variable "$result"
                $result = $miConexion->query($miConsulta);
                // Valida si la variable $result tiene un valor
                if ($result) {
                    // La función "fetch_assoc" lee una fila del arreglo "$result" obtenido de la base de datos
                    // La fila obtenida se guarda en la variable "$row"
                    // se inicia ciclo "while", leyendose "mientras '$row' tenga valor entra al segmento de código" 
                    while ($row = $result->fetch_assoc()) {
                        // Se guarda en la variable "$id_ comida" el valor de la columna "id_comida" de la fila que se esta leyendo almacenada en "$row"
                        $id_comida = $row["id_comida"];
                        $nombre = $row["nombre"];
                        $precio = $row["precio"];
                        $descripcion = $row["descripcion"];
                        $imagen = $row["imagen"];
                        $disponibilidad = $row["disponibilidad"];
                        // Valida que la variable "$disponibilidad" sea verdadera y ejecuta el segmento de código 
                        if($disponibilidad == true)
                        {
                            // Se manda el "id" del producto ($id_comida) como parametro a la función "agregar_objeto"
                            // Se asigna la función "agregar_objeto()" al evento "onclick" 
                            // Se le asigna el "id" "id_li_comida_'. $id_comida" al objeto
                            // Se le asigna el "id" "id_precio_comida_' . $id_comida" al parrafo donde se muestra el precio
                            // Se le asigna el "id" "id_cantidad_comida_' . $id_comida" al parrafo donde se muestra la cantidad
                            // Se construye un objeto perteneciente al listado de productos, concatenando las estiquetas de la estructura "li"con las variables que contiene los valores obtenidos de la base de datos
                            //la función "echo" escribira a nivel documento el objeto que se construyo, haciendolo parte del listado de productos 
                            
                            echo '<li onclick="agregar_objeto(' . $id_comida . ')" id="id_li_comida_' . $id_comida . '">
                                <h2>'. $nombre . '</h2>
                                <img src="imagenes/' . $imagen . '">
                                <p class="producto-descripcion">' . $descripcion . '</p>
                                <p class="producto-precio" id="id_precio_comida_' . $id_comida . '">$ ' . $precio . '</p>
                                <p class="producto-precio" id="id_cantidad_comida_' . $id_comida . '">0</p>
                            </li>';
                        }
                    }
                }
            }
            //En caso de presentarse un error el "catch" contiene el error y lo muestra en pantalla, como un elemento más del listado de productos 
            catch(Exception $e)
            {
                echo '<li><h3>Se presentó un error al cargar los productos: ',  $e->getMessage(), "</h3></li>";
            }
        ?>
<script>
    // Se crea la función "agregar_objeto()" que recibe como parametro "origen" el cual indica que elemento llamo la función 
    //La función "agregar_objeto()" es llamada por los elementos del listado productos 
    // toda la función esta dentro de un try catch que contendra posibles errores 
    function agregar_objeto(origen) {
        try {
            // Se genera el "id" del elemento donde se encuentra la cantidad seleccionada del producto seleccionado, concatenando la siguiente cadena con la variable "origen"
            var id_cantidad = 'id_cantidad_comida_' + origen;
            // Se genera el "id" del elemento donde se encuentra el precio del producto seleccionado, concatenando la siguiente cadena con la variable "origen"
            var id_precio = 'id_precio_comida_' + origen;
            // la función "document.getElementById()" obtiene un elemento del documento utilizando como referencia el "id"
            // La propiedad "innerHTML" hace referencia al contenido de la etiqueta obtenida como elemento por la función getElementById
            // La función "parseInt()" parsea [convierte] texto a entero
            // Se obtiene la cantidad seleccionada del producto seleccionado y se incrementa en una unidad [esto representa que por cada click en un elemento del listado de productos la cantidad aumenta en 1]
            // Se almacena en la variable "cantidad"
            var cantidad = parseInt(document.getElementById(id_cantidad).innerHTML) + 1;
            // la función "document.getElementById()" obtiene un elemento del documento utilizando como referencia el "id"
            // La propiedad "innerHTML" hace referencia al contenido de la etiqueta obtenida como elemento por la función getElementById
            // La función ".replace()" [exclusiva de cadenas] recibe dos parametros, donde el primero es el elemento a remplazar en la cadena; el segundo elemento es el que se utilizará como remplazo [en este caso se remplaza con '', es decir se eliminará el caracter "$"]
            // La función "parseInt()" parsea [convierte] texto a entero
            // Se obtiene el precio del producto seleccionado
            // Se almacena en la variable "precio"
            var precio = parseInt(document.getElementById(id_precio).innerHTML.replace('$', ''));
            // la función "document.getElementById()" obtiene un elemento del documento utilizando como referencia el "id"
            // La propiedad "innerHTML" hace referencia al contenido de la etiqueta obtenida como elemento por la función getElementById
            // La función ".replace()" [exclusiva de cadenas] recibe dos parametros, donde el primero es el elemento a remplazar en la cadena; el segundo elemento es el que se utilizará como remplazo [en este caso se remplaza con '', es decir se eliminará el caracter "$"]
            // La función "parseInt()" parsea [convierte] texto a entero
            // Se obtiene el precio total del producto seleccionado
            // Se almacena en la variable "precio_total"
            var precio_total = parseInt(document.getElementById("id_precio_total").innerHTML.replace('$', ''));
            // Se actualiza el elemento del documento con el id "id_cantidad" cambiando el contenido de la etiqueta con el valor guardado en la variable "cantidad"
            document.getElementById(id_cantidad).innerHTML = cantidad;
            // Se calcula el nuevo costo total sumando el precio del elemento seleccionado más el costo total actual 
            // Se actualiza el elemento del documento con el id "id_precio_total" cambiando el contenido de la etiqueta con el valor calculado previamente y concatenandolo al signo "$" 
            document.getElementById("id_precio_total").innerHTML = '$ ' + (precio_total + precio);
           
        } catch (error) {
            
        }
    }
    // Se crea la función "cobrar()" que confirma si se desea realizar la compra 
    // Esta función es llamada por el elemento con id "id_li_comida_total" del listado de productos en el evento "onclick" 
    function cobrar()
    {
        //Se confirma si la orden ha terminado, en caso de ser así se inicia el procesamiento del pedido y se redirecciona  a la pagina ...
        
        if(parseInt(document.getElementById("id_precio_total").innerHTML.replace('$', '')) == 0)
        {
            alert('No ha seleccionado ningún producto');
        }
        else{
            if(confirm('Desea realizar su compra?'))
            {
                alert('Procesando pedido.');
                window.location.href = 'productos.php';
            }
        }  
    }
</script>
    <!--Se construye el objeto perteneciente al listado de productos, el cual contendrá el precio total, con el id "id_li_comida_total" 
    // Se le asigna el "id" "id_precio_total" al parrafo donde se muestra el precio     
-->  
            <li id="id_li_comida_total" onclick="cobrar()">
                <h2>TOTAL</h2>
                <img src="imagenes/total.jpg">
                <p class="producto-precio" id="id_precio_total">$0</p>
            </li>
        </ul>

    </main>
    <script>
    // Función para realizar una solicitud AJAX
    function makeAjaxRequest(url, callback) {
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                callback(xhr.responseText);
            }
        };
        xhr.open("GET", url, true);
        xhr.send();
    }

    // Función para actualizar los productos disponibles en tiempo real
    function actualizarProductosDisponibles() {
        makeAjaxRequest('productos_disponibles.php', function(response) {
            // Obtener la respuesta de la solicitud AJAX
            var disponibles = JSON.parse(response);
            var listaProductos = document.getElementsByClassName('productos')[0];

            // Vaciar la lista de productos
            listaProductos.innerHTML = '';

            // Recorrer los productos disponibles y agregarlos a la lista
            for (var i = 0; i < disponibles.length; i++) {
                var producto = disponibles[i];
                var li = document.createElement('li');
                li.setAttribute('onclick', 'agregar_objeto(' + producto.id_comida + ')');
                li.setAttribute('id', 'id_li_comida_' + producto.id_comida);

                var contenido = '<h2>' + producto.nombre + '</h2>' +
                                '<img src="imagenes/' + producto.imagen + '">' +
                                '<p class="producto-descripcion">' + producto.descripcion + '</p>' +
                                '<p class="producto-precio" id="id_precio_comida_' + producto.id_comida + '">$ ' + producto.precio + '</p>' +
                                '<p class="producto-precio" id="id_cantidad_comida_' + producto.id_comida + '">0</p>';

                li.innerHTML = contenido;
                listaProductos.appendChild(li);
            }
        });
    }

    // Función para agregar productos al carrito
    function agregar_objeto(origen) {
        // ... Tu código para agregar productos al carrito ...

        // Ejemplo: Incrementar la cantidad del producto seleccionado en 1
        var cantidadElemento = document.getElementById('id_cantidad_comida_' + origen);
        var cantidad = parseInt(cantidadElemento.innerHTML) + 1;
        cantidadElemento.innerHTML = cantidad;
    }

    // Llamar a la función para actualizar los productos disponibles al cargar la página
    actualizarProductosDisponibles();

    // Actualizar los productos disponibles cada 5 segundos
    setInterval(actualizarProductosDisponibles, 5000);
</script>
</body>
</html>
