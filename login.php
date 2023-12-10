<!-- html del login -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOGIN YAIZA</title>
    <link rel="stylesheet" href="style_login.css" />
</head>

<body>
    <h1>LOGIN</h1>
    <!-- llama a comprobar login  -->
    <form action="comprobarLogin.php" method="post">
        <label>Usuario: <input type="text" name="usuario"></label><br />
        <label>Contraseña: <input type="password" name="password"></label><br />
        <label>Rol:</br>
            <select name="rol">
                <option value="administrador">administrador</option>
                <option value="limitado">limitado</option>
            </select>
        </label><br />
        <input type="submit" name="enviar" value="Entrar">
        <p id="errorMensaje">
        <!-- php para comprobar lo que llega del mensaje de error y que lo muestre -->
            <?php
            if (isset($_GET['error'])) {
                echo $_GET['error'];
            }
            ?>
        </p>
    </form>
</body>

</html>