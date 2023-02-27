<!DOCTYPE html>

<?php
session_start();
//Realiza la conexión a la base de datos.
require_once './ConexionBaseDatos.php';
$cambio = false;

if (isset($conexionBD)) {
    //Si se ha pulsado el botón modificar.
    if (isset($_POST['btmodificar'])) {
        $nombre = $_POST['nombre'];
        $nombreCorto = $_POST['nombreCorto'];
        $descripcion = $_POST['descripcion'];
        $precio = $_POST['precio'];
        $familia = $_POST['familia'];
        try {
            $stmt = $conexionBD->stmt_init();
            $consulta = "update productos set nombre=?, nombre_corto=?, descripcion=?, pvp=?, familia=? where id=?;";
            $stmt->prepare($consulta);
            $stmt->bind_param('sssisi', $nombre, $nombreCorto, $descripcion, $precio, $familia, $_SESSION['datos']['codigo']);
            $stmt->execute();
            $_SESSION['mensaje'] = "! Producto modificado correctamente.";
            header('Location: Listado.php');
            $cambio = true;
        } catch (Exception $ex) {
            //Si la entrada está duplicada.
            $error = $ex->getMessage();
            if (strpos($error, "Duplicate entry") !== false) {
                $_SESSION['mensaje'] = "Ya existe un producto con ese valor.";
            } else {
                $_SESSION['mensaje'] = "Error en la ejecución de la consulta.";
            }
        } finally {
            $stmt->close();
        }
        //Viene de la página Listado.php.
    } else {
        try {
            $stmt = $conexionBD->stmt_init();
            $consulta = "select nombre, nombre_corto, descripcion, pvp, familia from productos where id=?;";
            $stmt->prepare($consulta);
            $stmt->bind_param('i', $_SESSION['datos']['codigo']);
            $stmt->execute();
            $resultado = $stmt->get_result();
            $producto = $resultado->fetch_assoc();
            $nombre = $producto['nombre'];
            $nombreCorto = $producto['nombre_corto'];
            $descripcion = $producto['descripcion'];
            $precio = $producto['pvp'];
            $familia = $producto['familia'];
        } catch (Exception $ex) {
            //Si la entrada está duplicada.
            $error = $ex->getMessage();
            if (strpos($error, "Duplicate entry") !== false) {
                $_SESSION['mensaje'] = "Ya existe un producto con ese valor.";
            } else {
                $_SESSION['mensaje'] = "Error en la ejecución de la consulta.";
            }
        } finally {
            $stmt->close();
        }
    }
} else {
    $_SESSION['mensaje'] = $mensaje;
}
?>

<html>
    <head>
        <title>Modificar producto</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./css/EstilosCrearModificar.css"/>
    </head>
    <body>
        <form name="formulario_crear" id="formulario_crear" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <div class="cont_usuario">
                <div class="icono">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor"
                         class="bi bi-person-fill" viewBox="0 0 16 16">
                    <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3Zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/>
                    </svg>
                </div>
                <input type="text" name="usuario" id="usuario" disabled value="<?php echo $_SESSION['usuario'] ?>">
                <a href="Login.php">
                    <button type="button" name="btsalir" id="btsalir" value="">Salir</button>
                </a>
            </div>

            <div class='cont_crearmodificar'>
                <h1>Modificar Producto</h1>
                <div class="crear">

                    <!-- Mensaje -->
                    <p id="mensaje"><?php
                        //Si hay mensaje y no cambia de página.
                        if (isset($_SESSION['mensaje']) && !$cambio) {
                            echo $_SESSION['mensaje'];
                            echo $_SESSION['mensaje'] = null;
                        }
                        ?></p>

                    <!--Nombre-->
                    <div class="cont">
                        <label for="nombre">Nombre</label><br>
                        <input type="text" name="nombre" id="nombre" required placeholder="Nombre" value="<?php
                        echo $nombre;
                        ?>">
                    </div>

                    <!--Nombre corto-->
                    <div class="cont">
                        <label for="nombreCorto">Nombre Corto</label><br>
                        <input type="text" name="nombreCorto" id="nombreCorto" required placeholder="Nombre Corto" pattern="[a-zA-Z0-9]{4,20}" value="<?php
                        echo $nombreCorto;
                        ?>" title="Debe tener eentre 4 y 20 dígitos o caracteres. Caracter espacio no admitido.">
                    </div>

                    <!--Precio-->
                    <div class="cont">
                        <label for="precio">Precio (&euro;)</label><br>
                        <input type="number" name="precio" id="precio" required placeholder="Precio (&euro;)" min="0" step="0.01" value="<?php
                        echo $precio;
                        ?>">
                    </div>

                    <!--Familia-->
                    <div class="cont">
                        <label for="familia">Familia</label><br>
                        <select id='familia' name='familia' required>";
                            <?php
                            //Si ha sido posible la conexión.
                            if (isset($conexionBD)) {
                                $stmt = $conexionBD->stmt_init();
                                $consulta = "select * from familias;";
                                $stmt->prepare($consulta);
                                if ($stmt->execute()) {
                                    $stmt->bind_result($codFamilia, $nombreFamilia);
                                    while ($stmt->fetch()) {
                                        echo "<option value='" . $codFamilia . "'";
                                        //Si $familia y coincide con $codFamilia la selecciona.
                                        if ($familia === $codFamilia) {
                                            echo "selected";
                                        }
                                        echo ">" . $nombreFamilia . "</option>";
                                    }
                                    $stmt->close();
                                }
                                $conexionBD->close();
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <!--Descripción-->
                <label for="descripción">Descripción</label><br>
                <textarea id="descripcion" name="descripcion" rows="17" cols="80" required><?php
                    echo $descripcion;
                    ?></textarea>

                <!--Botones-->
                <div class="botones">
                    <button type="submit" name="btmodificar" id="btmodificar" value="">Modificar</button>
                    <button type="reset" name="btlimpiar" id="btlimpiar" value="">Limpiar</button>
                    <a href="Listado.php">
                        <button type="button" name="btvolver" id="btvolver" value="">Volver</button>
                    </a> 
                </div>
            </div>
        </div>
    </form>
</body>
</html>