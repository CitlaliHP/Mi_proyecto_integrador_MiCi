<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Productos - Cafeteria</title>
    <link rel="stylesheet" href="reset.css">
    <link rel="stylesheet" href="style-home.css"> 
    <script>
        function redirectToLogin() {
            window.location.href = "inicio_sesion.html";
        }
    </script>
</head>
<body>
    <header>
        <div class= usuario>Usuario: </div>
        <div class= usuario id = 'id_usuario' >
        <?php
            print_r($_SESSION["matricula"]);
        ?>
        </div>
        <div>
            <button type="button" onclick="redirectToLogin()" class="botones-cerrarSesion">Cerrar sesión</button>
            </div> 
        <div class="caja">
            <h1><img src="imagenes/logo.png"></h1>
        </div>
    </header>
    <main>
        <h1 class="titulo-principal">Menú del día de hoy</h1>
        <!-- Se crea el listado donde se agregarán los productos disponibles -->
         <ul class="productos">
        
        </ul>

        <!-- Se crea otro listado para los productos seleccionados -->
        <ul class="productos-seleccionados">
            <li id="id_li_comida_total" onclick="cobrar()">
                <h2>TOTAL</h2>
                <img src="imagenes/total.jpg">
                <p class="producto-precio" id="id_precio_total">$0</p>
            </li>
        </ul>
    </main>
    <script src="telegram_bot.js"></script>
    <script>
        // Función para realizar una solicitud AJAX
        // Se define una función llamada "makeAjaxRequest()"
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

        // Función para actualizar los productos disponibles cada 6 segundos 
        function actualizarProductosDisponibles() {
            // Se llama a la función "makeAjaxRequest()" especificada antes, con dos argumentos "productos_disponibles.php"y una funcion "function(response)"
            makeAjaxRequest('productos_disponibles.php', function(response) {
                // Obtener la respuesta de la solicitud AJAX
                var disponibles = JSON.parse(response);
                var listaProductos = document.getElementsByClassName('productos')[0];
                /////////////////////////////////////////////////
                // Se crea la variable "listadoProductos_Cantidades" y se le asigma un valor de un objeto vacio "{};
                var listadoProductos_Cantidades = {};
                // 
                listaProductos.childNodes.forEach(function(child)
                {
                    try{
                        // Esta línea declara una variable llamada id_cantidad y le asigna un valor que se construye concatenando la cadena "id_cantidad_comida_" con el resultado de reemplazar la cadena "id_li_comida_"
                        var id_cantidad = 'id_cantidad_comida_' + child.id.replace('id_li_comida_', '');
                        // Obtieneun elemento con el id igual al valor de la variable "id_cantidad", obtiene su contenido interno, lo convierte en un número entero usando la función "parseInt()" y lo almacena en el objeto "listadoProductos_Cantidades" 
                        listadoProductos_Cantidades[id_cantidad] = parseInt(document.getElementById(id_cantidad).innerHTML);
                    }catch(ex){}
                });
            /////////////////////////////////////////////////
                // Vaciar la lista de productos disponibles
                listaProductos.innerHTML = '';

                var precio_total_recalculado = 0;

                // Recorrer los productos disponibles y agregarlos a la lista
                for (var i = 0; i < disponibles.length; i++) {
                    var producto = disponibles[i];
                    // Crea un nuevo "li" y agrega el contenido del "li"
                    var li = document.createElement('li');
                    // En el siguiente ejemplo, setAttribute() se utiliza para establecer atributos en un <li>
                    // Cada que se realiza el evento "onclick" se agrega un objeto y se concatena con su id 
                    li.setAttribute('onclick', 'agregar_objeto(' + producto.id_comida + ')');
                    li.setAttribute('id', 'id_li_comida_' + producto.id_comida);

                    var id_cantidad_comida = 'id_cantidad_comida_' + producto.id_comida;
                    var contenido = '<h2 id="id_nombre_comida_' + producto.id_comida + '">' + producto.nombre + '</h2>' +
                                    '<img src="imagenes/' + producto.imagen + '">' +
                                    '<p class="producto-descripcion">' + producto.descripcion + '</p>' +
                                    '<p class="producto-precio" id="id_precio_comida_' + producto.id_comida + '">$ ' + producto.precio + '</p>' +
                                    '<p class="producto-precio" id="' + id_cantidad_comida + '">' + (listadoProductos_Cantidades[id_cantidad_comida] || 0) +'</p>';

                    li.innerHTML = contenido;
                    listaProductos.appendChild(li);


                    precio_total_recalculado += ( (listadoProductos_Cantidades[id_cantidad_comida] || 0) * producto.precio)
                }
                document.getElementById("id_precio_total").innerHTML = '$ ' + precio_total_recalculado;
            });
        }

        function obtenerOrden()
        {
            var orden = {   productos: '' ,
                    matricula: document.getElementById('id_usuario').innerHTML,
                    precioTotal: ' PRECIO TOTAL: ' + document.getElementById("id_precio_total").innerHTML} ;
            var listaProductos = document.getElementsByClassName('productos')[0];
            listaProductos.childNodes.forEach(function(child)
            {
                try{
                    var id_nombre = 'id_nombre_comida_' + child.id.replace('id_li_comida_', '');
                    var id_cantidad = 'id_cantidad_comida_' + child.id.replace('id_li_comida_', '');
                    if(parseInt(document.getElementById(id_cantidad).innerHTML) > 0 )
                        orden.productos += parseInt(document.getElementById(id_cantidad).innerHTML) + ' ' + document.getElementById(id_nombre).innerHTML + '. \n';
                }catch(ex){}
            });

            return orden;
        }

        // Función para agregar productos al carrito
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

            // Llamar a la función "agregarProducto" para agregar el producto a la lista de productos seleccionados
             agregarProducto(origen, nombreProducto, precioProducto);
           
        } catch (error) {
            
        }
        }

        // Llamar a la función para actualizar los productos disponibles al cargar la página
        actualizarProductosDisponibles();

        // Actualizar los productos disponibles cada 5 segundos
        setInterval(actualizarProductosDisponibles, 5000);

 function cobrar() {
    var precioTotal = parseInt(document.getElementById("id_precio_total").innerHTML.replace('$', ''));
    
    if (precioTotal === 0) {
        alert('No ha seleccionado ningún producto');
    } else {
        var orden = obtenerOrden();

        if (confirm('Artículos seleccionados:\n' + orden.productos + '\nDesea realizar su compra?')) {
            console.log('Matrícula:', orden.matricula);
            sendTelegramMessage(orden); // Llama a sendTelegramMessage con los argumentos necesarios
            alert('Se le notificará a la cafetería, en breve puede pasar por su pedido, ¡Hasta luego!');
            //window.location.href = 'inicio_sesion.html';
        }
            // Enviar la orden al archivo guardar_orden.php
            var xhrOrden = new XMLHttpRequest();
            xhrOrden.open("POST", "guardar_orden.php", true);
            xhrOrden.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhrOrden.onreadystatechange = function () {
                if (xhrOrden.readyState === XMLHttpRequest.DONE) {
                    if (xhrOrden.status === 200) {
                        var idOrden = xhrOrden.responseText;

                        // Obtener los productos seleccionados
                        var listaProductos = document.getElementsByClassName('productos')[0];
                        var productosSeleccionados = [];
                        listaProductos.childNodes.forEach(function(child) {
                            try {
                                var id_cantidad = 'id_cantidad_comida_' + child.id.replace('id_li_comida_', '');
                                var id_nombre = 'id_nombre_comida_' + child.id.replace('id_li_comida_', '');
                                var cantidad = parseInt(document.getElementById(id_cantidad).innerHTML);
                                if (cantidad > 0) {
                                    var nombreProducto = document.getElementById(id_nombre).innerHTML;
                                    productosSeleccionados.push({
                                        nombre: nombreProducto,
                                        cantidad: cantidad
                                    });
                                }
                            } catch (ex) {}
                        });

                        // Enviar los detalles de la compra al archivo guardar_compra.php
                        var xhrCompra = new XMLHttpRequest();
                        xhrCompra.open("POST", "guardar_compra.php", true);
                        xhrCompra.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                        xhrCompra.onreadystatechange = function () {
                            if (xhrCompra.readyState === XMLHttpRequest.DONE) {
                                if (xhrCompra.status === 200) {
                                    console.log(xhrCompra.responseText);
                                    
                                } else {
                                    alert("Error al guardar la compra.");
                                }
                            }
                        };

                        var productosData = JSON.stringify(productosSeleccionados);
                        var paramsCompra = "id_orden=" + encodeURIComponent(idOrden) + "&productos=" + encodeURIComponent(productosData);
                        xhrCompra.send(paramsCompra);
                    } else {
                        alert("Error al guardar la orden.");
                    }
                }
                window.location.href = 'inicio_sesion.html';
                
            };

            xhrOrden.send("&matricula=" + encodeURIComponent(orden.matricula));
        }
    }


</script>
</body>
</html>