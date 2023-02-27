<!DOCTYPE html>

<?php
session_start();
session_destroy();
session_start();
$mensaje = "";

//Si se ha establecido el login.
if (isset($_POST['btlogin'])) {
    require_once 'ConexionBaseDatos.php';
    if (isset($conexionBD)) {
        $usuario = $_POST['usuario'];
        $password = $_POST['password'];
        $password = hash('sha256', $password); //Codifica la contraseña.

        $stmt = $conexionBD->stmt_init();
        $consulta = "select count(*) as cont from usuarios where usuario=? and pass=?;";
        $stmt->prepare($consulta);
        $stmt->bind_param('ss', $usuario, $password);
        if ($stmt->execute()) {
            $resultado = $stmt->get_result();
            if (isset($resultado)) {
                $registro = $resultado->fetch_assoc();
                if ($registro['cont'] == 1) {
                    $_SESSION['usuario'] = $usuario;
                    header("Location: Listado.php");
                } else {
                    $mensaje = "Los datos de login son incorrectos.";
                }
            } else {
                $mensaje = "Los datos de login son incorrectos.";
            }
        } else {
            $mensaje = "Se ha producido un error en la ejecución de la consulta.";
        }
    }
} else if (isset($_POST['btinvitado'])) {
    $_SESSION['usuario'] = "Invitado";
    header("Location: Listado.php");
}
?>

<html>
    <head>
        <title>Login aplicación productos</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./css/EstilosLogin.css"/>
    </head>
    <body>
        <form name="formulario_login" id="formulario_login" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <div id="contenedor_login">
                <div id="titulo">
                    <p>Login</p>
                </div>
                <!--Usuario-->
                <div class="input">
                    <div class="icono">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                             class="bi bi-person-fill" viewBox="0 0 16 16">
                        <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3Zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/>
                        </svg>
                    </div>
                    <input type="text" name="usuario" id="usuario" placeholder="usuario">
                </div>
                <!--Contraseña-->
                <div class="input">
                    <div class="icono">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" 
                             class="bi bi-key-fill" viewBox="0 0 16 16">
                        <path d="M3.5 11.5a3.5 3.5 0 1 1 3.163-5H14L15.5 8 14 9.5l-1-1-1 1-1-1-1 1-1-1-1 
                              1H6.663a3.5 3.5 0 0 1-3.163 2zM2.5 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
                        </svg>
                    </div>
                    <input type="password" name="password" id="password" placeholder="contraseña">
                </div>
                <!--Botones-->
                <div class="botones">
                    <button type="submit" name="btinvitado" id="btinvitado" value="">Acceso como Invitado</button>
                    <button type="submit" name="btlogin" id="btlogin" value="">Login</button>
                </div>
            </div>
            <?php echo '<p id="mensaje">' . $mensaje . '</p>'; ?>
        </form>
    </body>
</html>