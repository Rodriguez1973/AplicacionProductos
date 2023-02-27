<!DOCTYPE html>

<?php
session_start();
$mensaje = "";
require_once './ConexionBaseDatos.php';
//Si se pulsa el botón "Login" o el botón "Salir".
if (isset($_POST['btlogin']) || isset($_POST['btsalir'])) {
    header('Location: login.php');
//Si se pulsa el botón "Detalle".
} else if (isset($_POST['btdetalle'])) {
    $_SESSION['datos'] = unserialize($_POST['btdetalle']);
    header('Location: detalle.php');
}
?>

<html>
    <head>
        <title>Listado productos</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./css/EstilosListado.css"/>
    </head>
    <body>
        <form name="formulario_productos" id="formulario_productos" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <div class="cont_usuario">
                <div class="icono">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor"
                         class="bi bi-person-fill" viewBox="0 0 16 16">
                    <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3Zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/>
                    </svg>
                </div>
                <input type="text" name="usuario" id="usuario" disabled value="<?php echo $_SESSION['usuario'] ?>">
                <?php if ($_SESSION['usuario'] == "Invitado") { ?>
                    <input type="submit" name="btlogin" id="btlogin" value="Login">
                <?php } else { ?>
                    <input type="submit" name="btsalir" id="btsalir" value="Salir">
                <?php } ?>    
            </div>
            <p><?php echo $mensaje; ?></p>
            <div class='cont_listado'>
                <h1>Gestión de Productos</h1>
                <div>
                    <?php echo '<p id="mensaje">' . $mensaje . '</p>'; ?>
                    <div class='btcrear'>
                        <button type='submit' name='btcrear' id='btcrear' value="" 
                        <?php
                        if ($_SESSION['usuario'] == "Invitado") {
                            echo ' disabled';
                        }
                        ?>
                                ><strong>+</strong>Crear</button>
                    </div>

                    <!--Genera la tabla de datos.-->
                    <table>
                        <thead>
                            <tr>
                                <th>Detalle</th>
                                <th>Código</th>
                                <th>Nombre</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            //Si se ha establecido la conexión con la base de datos.
                            if (isset($conexionBD)) {
                                $stmt = $conexionBD->stmt_init();
                                $consulta = "select id, nombre from productos;";
                                $stmt->prepare($consulta);
                                if ($stmt->execute()) {
                                    $stmt->bind_result($codigo, $nombre);
                                    while ($stmt->fetch()) {
                                        ?>
                                        <tr><td>
                                                <!--Botón Detalle-->
                                                <button type='submit' name='btdetalle' id='btdetalle' value='<?php echo serialize($datos = ["codigo" => $codigo]); ?>'>Detalle
                                            </td>
                                            <td>
                                                <!--Código-->
                                                <?php echo $codigo; ?>
                                            </td>
                                            <td>
                                                <!--Nombre-->
                                                <?php echo $nombre; ?>
                                            </td>
                                            <td>
                                                <!--Botón actualizar.-->
                                                <button type='submit' name='btactualizar' id='btactualizar' value='<?php echo serialize($datos = ["codigo" => $codigo]); ?>'
                                                <?php
                                                if ($_SESSION['usuario'] == "Invitado") {
                                                    echo ' disabled';
                                                }
                                                ?>
                                                        >Actualizar</button>
                                                <!--Botón borrar.-->
                                                <button type = 'submit' name = 'btborrar' id = 'btborrar' value = '<?php echo serialize($datos = ["codigo" => $codigo]); ?>'
                                                        <?php
                                                        if ($_SESSION['usuario'] == 'Invitado') {
                                                            echo ' hidden';
                                                        }
                                                        ?>
                                                        >Borrar</button>
                                            </td></tr>
                                        <?php
                                    }
                                    $stmt->close();
                                }
                                $conexionBD->close();
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </form>
    </body>
</html>
