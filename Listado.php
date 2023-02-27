<!DOCTYPE html>

<?php
session_start();
require_once './ConexionBaseDatos.php';

//Se ha establecido la conexión con la base de datos.
if (isset($conexionBD)) {
    //Si se pulsa el botón "Detalle".
    if (isset($_POST['btdetalle'])) {
        $_SESSION['datos'] = unserialize($_POST['btdetalle']);
        header('Location: Detalle.php');
    //Si se pulsa el botón "Crear".
    } else if (isset($_POST['btcrear'])) {
        header('Location: Crear.php');
    //Si se pulsa el botón "Modificar".
    } else if (isset($_POST['btmodificar'])) {
        $_SESSION['datos'] = unserialize($_POST['btmodificar']);
        header('Location: Modificar.php');
    //Si se pulsa el botón "Borrar".
    } else if (isset($_POST['btborrar'])) {
        $_SESSION['datos'] = unserialize($_POST['btborrar']);
        if (isset($conexionBD)) {
            $stmt = $conexionBD->stmt_init();
            $consulta = "delete from productos where id=?";
            $stmt->prepare($consulta);
            $stmt->bind_param('i', $_SESSION['datos']['codigo']);
            $stmt->execute();
            $stmt->close();
        }
    }
} else {
    $_SESSION['mensaje'] = $mensaje;
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
                    <a href="Login.php">
                        <button type="button" name="btlogin" id="btlogin" value="">Login</button>
                    </a>
                <?php } else { ?>
                    <a href="Login.php">
                        <button type="button" name="btsalir" id="btsalir" value="">Salir</button>
                    </a>
                <?php } ?>    
            </div>

            <div class='cont_listado'>
                <h1>Gestión de Productos</h1>

                <div>
                    <!-- Mensaje -->
                    <p id="mensaje"><?php
                        if (isset($_SESSION['mensaje'])) {
                            echo $_SESSION['mensaje'];
                            $_SESSION['mensaje']=null;
                        }
                        ?></p>
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