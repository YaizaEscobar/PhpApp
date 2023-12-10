<!-- php del codigo -->
<?php
//si la sesion ha sido creada la reanudara
session_start();
//verificar si se ha iniciado la sesion
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit; //salir del script una vez redirigida a la pagina
}

// Recoger el id y el rol de la URL
$id = $_GET['id'];

//guardar en una variable los datos del material segun el id que tiene
$detalle = obtener_detalle($id);

// devuelve todos los dattos del material segun el id que tenga
function obtener_detalle($id)
{
    //conexion con BD
    $conexionBaseDatos = new PDO("mysql:host=localhost; dbname=instrumentos", "yaiza", "yaiza");
    //Configura el modo de manejo de errores para la conexión 
    $conexionBaseDatos->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //consulta para seleccionar todos los datos segun el id que tiene
    $sql = 'SELECT * FROM materiales WHERE id = ?';
    //se prepara la consulta y se guarda enn una variable
    $resultado = $conexionBaseDatos->prepare($sql);
    //Se ejecuta la consulta y se pasa como parametro el valor del id en forma de array
    $resultado->execute([$id]);
    //se obtienen los resultados de una consulta de la base de datos
    $detalle = $resultado->fetch();
    //devuelve los datos de la consulta
    return $detalle;
}
?>
<!-- html del codigo donde se muestran la informacion de cada material -->
<!DOCTYPE html>
<html>

<head>
    <title>Detalle Material YAIZA</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style_detalle_material.css" />
</head>

<body>
    <!-- contenedor donde se muestra tu usuario y que has iniciado sesion -->
    <div id="sesion-info">
        <?php echo "Has iniciado sesión como " . $_SESSION["usuario"]; ?>
    </div>

    <!-- contenedor donde se muestran los datos de la BD  -->
    <div id="contenedor">
        <h3>Material con ID:
            <?php echo $id ?>
        </h3>
        <p><span class="detalle-label">Nombre:</span>
            <?php echo $detalle['nombre']; ?>
        </p>
        <p><span class="detalle-label">Descripción:</span>
            <?php echo $detalle['descripcion']; ?>
        </p>
        <p><span class="detalle-label">Precio:</span>
            <?php echo $detalle['precio']; ?>
        </p>
        <p><span class="detalle-label">Calidad:</span>
            <?php echo $detalle['calidad']; ?>
        </p>
        <p><span class="detalle-label">Nombre de la Imagen:</span>
            <?php echo $detalle['nombreImagen']; ?>
        </p>
        <!-- mostrar la imagen del material -->
        <img src="../img/<?php echo $detalle['nombreImagen']; ?>" alt="Imagen">
    </div>
    <!-- enlace para cerrar sesion  -->
    <p><a href="cerrarSesion.php">Cerrar Sesión</a></p>
</body>

</html>