<?php
//iniciar sesion
session_start();

//verificar si el usuario ha iniciado sesion, sino se le redirige al login
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
}

//recoger el id de la imagen a descargar
$id = $_GET['id'];

//se guarda en una variable la informacion de la imagen con el metodo obtener detalle
$detalle = obtener_detalle($id);

//ruta al archivo de la imagen en el servidor y le añadimos el nombre de la imagen
$ruta_imagen = "img/" . $detalle['nombreImagen'];

//verificar si el archivo existe en el servidor
if (file_exists($ruta_imagen)) {

    //establecer un tipo de contenido genérico(archivo binario)
    $tipoContenido = "application/octet-stream";

    //definir las cabeceras para forzar la descarga de la imagen
    //establece el tipo de contenido del archivo que se está enviando.
    header("Content-Type: $tipoContenido");
    //indica al navegador que debe manejar el archivo como una descarga y no mostrarlo directamente.
    //"filename=" especifica el nombre del archivo que se descarga 
    header("Content-Disposition: attachment; filename=" . $detalle['nombreImagen']);
    // indica que el contenido se envía en formato binario
    header("Content-Transfer-Encoding: binary");

    //funcion para borrar cualquier búfer de salida de PHP existente
    ob_clean();
    //función para forzar la escritura de cualquier contenido que aún no se haya enviado al navegador
    flush();
    //función para leer y enviar el contenido de la imagen al navegador. 
    //la variable $ruta_imagen contiene la ubicación de la imagen en el servidor. 
    readfile($ruta_imagen);
    exit;
} else {
    //Si el archivo no existe, mostrar un mensaje de error
    header("HTTP/1.0 404 Not Found");
    echo 'La imagen no existe.';
}

//función para obtener detalles de la imagen a través del ID
function obtener_detalle($id)
{
    $conexionBaseDatos = new PDO("mysql:host=localhost; dbname=instrumentos", "yaiza", "yaiza");
    $conexionBaseDatos->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = 'SELECT nombreImagen FROM materiales WHERE id = ?';
    $resultado = $conexionBaseDatos->prepare($sql);
    $resultado->execute([$id]);
    //obtener los detalles de la imagen
    $detalle = $resultado->fetch(PDO::FETCH_ASSOC);
    return $detalle;
}
?>