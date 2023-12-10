<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprobar Login Yaiza</title>
</head>

<body>
    <!-- //comprobar el login, si existe un usuario en la BD -->
    <?php
    $error = ""; // Inicializa la variable de error
    try {
        //instanciamos la clase PDO llamando al constructor
        $conexionBaseDatos = new PDO("mysql:host=localhost; dbname=instrumentos", "yaiza", "yaiza");
        $conexionBaseDatos->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //consulta de la base de datos
        $sql = "SELECT * FROM usuarios WHERE usuario= :usuario AND password= :password AND rol= :rol";
        $resultado = $conexionBaseDatos->prepare($sql);
        //convertir cualquier simbolo en html: la funcion (htmlentities)
        //La funcion addslashes hace escapar cualquier caracter especial para que no se tenga en cuenta
        $usuario = htmlentities(addslashes($_POST["usuario"]));
        $password = htmlentities(addslashes($_POST["password"]));
        $rol = htmlentities(addslashes($_POST["rol"]));
        //identificar los marcadores con lo que el usuario a introducido para ello se utiliza la funcion bindValue
        $resultado->bindValue(":usuario", $usuario);
        $resultado->bindValue(":password", $password);
        $resultado->bindValue(":rol", $rol);
        $resultado->execute();
        //saber si el usuario esta dentro de la base de datos o no
        //con la funcion rowCount nos dice el nº de registros que devuelve una consulta
        $numeroRegistro = $resultado->rowCount();

        //si el usuario existe
        if ($numeroRegistro != 0) {
            //redirigirle a la zona de usuarios registrados
            if ($rol === "administrador") {
                //crear sesion para el usuario admin
                session_start();
                //almacenar en la sesion el login del usuario
                $_SESSION["usuario"] = $_POST["usuario"];
                header("location: conexion_BD_administrador.php");
            } else {
                //crear sesion para el usuario normal
                session_start();
                //almacenar en la sesion el login del usuario
                $_SESSION["usuario"] = $_POST["usuario"];
                header("location: conexion_BD_usuarios.php");
            }
            //si el usuario NO existe
        } else {
            //redirigirle al login 
            $error = "Usuario no es correcto"; // Actualiza el mensaje de error
            header("location: login.php?error=Usuario no es correcto");
            exit; //Salir del script después de redirigir
        }

    } catch (Exception $e) {
        // Si ocurre una excepción, configura la variable $error con un mensaje de error
        $error = "Ha ocurrido un error en el servidor: " . $e->getMessage();
        // Redirige de vuelta a la página de inicio de sesión con el mensaje de error
        header("Location: login.php?error=" . urlencode($error)); // Usar urlencode para evitar problemas con caracteres en la URL
        exit; // Salir del script después de redirigir
    }
    ?>
</body>

</html>