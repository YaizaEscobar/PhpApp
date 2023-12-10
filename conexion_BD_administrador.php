<!-- html con algunas partes de php para mostrar e insertar los datos en la tabla de BD  -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMINISTRADOR YAIZA</title>
    <link rel="stylesheet" href="style.css" />
</head>
<body>
    <?php
    //si la sesion ha sido creada la reanudara
    session_start();
    //si no se ha almacenado algo en la variable
    if (!isset($_SESSION["usuario"])) {
        header("Location: login.php");
    }
    ?>
    <div id="sesion-info">
        <?php echo "Has iniciado sesión como " . $_SESSION["usuario"]; ?>
        <a href="cerrarSesion.php" id="cerrar-sesion">Cerrar Sesión</a>
    </div>
    <div id="contenido">
        <h3>INSTRUMENTOS DEL LABORATORIO</h3>
        <?php
        //se llama a la funcion que obtiene los datos de la BD y se guarda en materiales
        $materiales = obtener_datos();
        //comprueba que haya datos en la matriz
        if (count($materiales) > 0) {
            ?>
            <!-- link que si lo pulsas se despliega el formulario para insertar datos en la tabla -->
            <a href="#" id="desplegarForm">DESPLEGAR FORMULARIO PARA INSERTAR MATERIAL</a>
            <div class="formularioMaterial">
                <form action="" method="post" enctype="multipart/form-data" onsubmit="return validarFormulario()">
                    <label>Nombre: </label><input type="text" name="nombre" id="nombre" required /><br />
                    <label>Descripción: </label><input type="text" name="descripcion" id="descripcion" required /><br />
                    <label>Precio: </label><input type="text" name="precio" id="precio" required /><br />
                    <label>Calidad: </label><input type="text" name="calidad" id="calidad" required /><br />
                    <label>Nombre Imagen: </label><input type="text" name="nombreImagen" id="nombreImagen" required /><br />
                    <label>Imagen: </label><input type="file" name="imagen" id="imagen" required /><br />
                    <input type="submit" name="enviar" value="Nuevo Material" required /><br />
                </form>
                
                <p id="errorMensaje" style="color: red;"></p>
            </div>
            <table>
                <tr>
                    <th>CODIGO</th>
                    <th>NOMBRE</th>
                    <th>DESCRIPCION</th>
                    <th>PRECIO</th>
                    <th>CALIDAD</th>
                    <th>NOMBRE IMAGEN</th>
                    <th>IMAGEN</th>
                    <th>DETALLE</th>
                    <th>VISUALIZA</th>
                    <th>DESCARGAR IMAGEN</th>
                </tr>
                <?php
                if (isset($_POST['enviar'])) {
                    insertar_datos($_POST, $_FILES);
                }
                $materiales = obtener_datos();
                foreach ($materiales as $material) {
                    echo "<tr>";
                    $imagen_base64 = base64_encode($material['imagen']);
                    echo "<td>" . $material['id'] . "</td>";
                    echo "<td>" . $material['nombre'] . "</td>";
                    echo "<td>" . $material['descripcion'] . "</td>";
                    echo "<td>" . $material['precio'] . "</td>";
                    echo "<td>" . $material['calidad'] . "</td>";
                    echo "<td>" . $material['nombreImagen'] . "</td>";
                    echo "<td><img src='img/". $material["nombreImagen"] ."' width='100' height='100'></td>";
                    echo "<td><a href='producto/". $material["id"] . "'>Detalle</a></td>";
                    echo "<td><a href='visualizaProducto/". $material["id"] . "'>Visualiza</a></td>";
                    echo "<td><a href='descargarImagen.php?id=" . $material['id'] ."'>Descargar Imagen</a></td>";
                    echo "</tr>";
                }
                ?>
            </table>
            <?php
            //sino hay datos salta un mensaje como que no hay datos
        } else {
            echo "No hay datos";
        }
        ?>
    </div>
    <script>
        //desplegar y ocultar el formulario de nuevos materiales
        let desplegarForm = document.getElementById('desplegarForm');
        let formularioMaterial = document.querySelector('.formularioMaterial');
        formularioMaterial.style.display = "none";
        desplegarForm.addEventListener('click', () => {
            if (formularioMaterial.style.display === 'none' || formularioMaterial.style.display === '') {
                formularioMaterial.style.display = 'block';
                desplegarForm.textContent = 'OCULTAR FORMULARIO';
            } else {
                formularioMaterial.style.display = 'none';
                desplegarForm.textContent = 'DESPLEGAR FORMULARIO INSERTAR MATERIAL';
            }
        });
    </script>
    <script src="validarFormulario.js"></script>
</body>
</html>

<?php

class ConectarBD
{
    //atributo para cerrar la conexion
    private $conexion;
    //crear conexion con la BD utilizando el PDO
    function crearConexionBD()
    {
        try {
            //instanciamos la clase PDO llamando al constructor
            $conexionBaseDatos = new PDO("mysql:host=localhost; dbname=instrumentos", "yaiza", "yaiza");
            //atributos para la instancia para facilitar el manejo de errores
            $conexionBaseDatos->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //se le especifica a la conexion: especificar el juego de caracteres con el metodo exec
            $conexionBaseDatos->exec("SET CHARACTER SET utf8");
        } catch (Exception $e) {
            //mata el programa y muestra un mensaje con la excepcion
            die("ERROR: " . $e->GetMessage());
        }
        //devuelve la conexion a la base de datos
        return $conexionBaseDatos;
    }

    //cerrar conexion con la base de datos
    function cerrarConexion()
    {
        $this->conexion = null;
    }

}

//funcion que obtiene los datos de la bd y los devuelve como matriz
function obtener_datos()
{
    //variable que crea una instancia llamando al constructor de la clase conectar BD
    $BaseDatos = new ConectarBD();
    //variable conexion que llama al metodo crear conexion de la base de datos
    $conexion = $BaseDatos->crearConexionBD();
    //prepara una consulta sql para obtener el nombre, descripcion y telefono de cada cliente
    $resultado = $conexion->prepare('SELECT * FROM materiales');
    //ejecuta la declaracion y obtiene el resultado en una matriz con el fetchAll 
    $resultado->execute();
    //guarda la matriz en la variable datos.
    $datos = $resultado->fetchAll();
    //cierra la conexion a base de datos
    $BaseDatos->cerrarConexion();
    //devuelve la matriz con los datos
    return $datos;
}

//funcion insertar datos en el formulario
function insertar_datos($datos, $fichero)
{
    if (isset($_POST['enviar'])) {
        // Procesar la imagen
        $nombreImagen = $_FILES['imagen']['name'];
        $imagenTemporal = $_FILES['imagen']['tmp_name'];
        $carpetaDestino = 'C:/xampp/htdocs/img/' . $nombreImagen; // Ruta en tu servidor
    
        if (move_uploaded_file($imagenTemporal, $carpetaDestino)) {
            // Guardar el nombre de la imagen en la base de datos
            $BaseDatos = new ConectarBD();
            $conexion = $BaseDatos->crearConexionBD();
            $resultado = $conexion->prepare('INSERT INTO materiales (nombre, descripcion, precio, calidad, nombreImagen, imagen)  VALUES (:nombre, :descripcion, :precio, :calidad, :nombreImagen, :imagen)');
            $imagenBinaria = file_get_contents($carpetaDestino);
            $resultado->bindParam(':nombre', $datos['nombre']);
            $resultado->bindParam(':descripcion', $datos['descripcion']);
            $resultado->bindParam(':precio', $datos['precio']);
            $resultado->bindParam(':calidad', $datos['calidad']);
            $resultado->bindParam(':nombreImagen', $nombreImagen);
            $resultado->bindParam(':imagen', $imagenBinaria, PDO::PARAM_LOB);
            $resultado->execute();
        } else {
            echo "Error al subir la imagen.";
        }
    }
}

?>