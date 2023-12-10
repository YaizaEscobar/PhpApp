<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>USUARIOS YAIZA</title>
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

                $materiales = obtener_datos();
                foreach ($materiales as $material) {
                    echo "<tr>";
                    echo "<td>" . $material['id'] . "</td>";
                    echo "<td>" . $material['nombre'] . "</td>";
                    echo "<td>" . $material['descripcion'] . "</td>";
                    echo "<td>" . $material['precio'] . "</td>";
                    echo "<td>" . $material['calidad'] . "</td>";
                    echo "<td>" . $material['nombreImagen'] . "</td>"; // Nombre de la imagen
                    echo "<td><img src='img/" . $material["nombreImagen"] . "' width='100' height='100'></td>";
                    echo "<td><a href='producto/" . $material["id"] . "'>Detalle</a></td>";
                    echo "<td><a href='visualizaProducto/" . $material["id"] . "'>Visualiza</a></td>";
                    echo "<td><a href='descargarImagen.php?id=" . $material['id'] . "'>Descargar Imagen</a></td>";
                    echo "</tr>";
                }
                ?>

            </table>
            <?php
            //sino hay datos salta un mensaje como que no hay datosSS
        } else {
            echo "No hay datos";
        }
        ?>
    </div>
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
?>