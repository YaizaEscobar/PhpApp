<!-- se encarga de cerrar la sesion y redirigirte al login una vez este cerrada la sesion -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cerrar Sesion Yaiza</title>
</head>

<body>
    <?php
    //reanudar la sesion que estasba abierta
    session_start();
    //cerrar la sesion
    session_destroy();
    //redirigir al login
    header("Location: login.php");
    ?>
</body>

</html>