<!DOCTYPE html>

<?php
session_start();
$_SESSION['mensaje'] = null;
//Realiza la conexión a la base de datos.
require_once './ConexionBaseDatos.php';

if (isset($_POST['btcrear'])) {
    if (isset($conexionBD)) {
        $stmt = $conexionBD->stmt_init();
        $consulta = "insert into productos(nombre,nombre_corto,descripcion,pvp,familia) values (?,?,?,?,?);";
        $stmt->prepare($consulta);
        $stmt->bind_param('sssis', $_POST['nombre'], $_POST['nombreCorto'], $_POST['descripcion'], $_POST['precio'], $_POST['familia']);
        try {
            $stmt->execute();
            $_SESSION['mensaje'] = "Producto creado correctamente.";
            header('Location: Listado.php');
        } catch (Exception $ex) {
            //Si la entrada está duplicada.
            if (strpos($ex->getMessage(), 'Duplicate entry')) {
                $_SESSION['mensaje'] = "Ya existe un producto con ese valor.";
            } else {
                $_SESSION['mensaje'] = "Error en la ejecución de la consulta.";
            }
        } finally {
            $stmt->close();
        }
    } else {
        $_SESSION['mensaje'] = $mensaje;
    }
}
?>

<html>
    <head>
        <title>Crear producto</title>
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
                <h1>Crear Producto</h1>
                <div class="crear">
                    <!-- Mensaje -->
                    <p id="mensaje"><?php
                        if (isset($_SESSION['mensaje'])) {
                            echo '! ' . $_SESSION['mensaje'];
                        }
                        ?></p>
                    <!--Nombre-->
                    <div class="cont">
                        <label for="nombre">Nombre</label><br>
                        <input type="text" name="nombre" id="nombre" required placeholder="Nombre" value="">
                    </div>
                    <!--Nombre corto-->
                    <div class="cont">
                        <label for="nombreCorto">Nombre Corto</label><br>
                        <input type="text" name="nombreCorto" id="nombreCorto" required placeholder="Nombre Corto" pattern="[a-zA-Z0-9]{4,20}" value="" title="Debe tener eentre 4 y 20 dígitos o caracteres. Caracter espacio no admitido.">
                    </div>
                    <!--Precio-->
                    <div class="cont">
                        <label for="precio">Precio (&euro;)</label><br>
                        <input type="number" name="precio" id="precio" required placeholder="Precio (&euro;)" min="0" step="0.01" value="">
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
                                        echo "<option value='" . $codFamilia . "'>" . $nombreFamilia . "</option>";
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
                <textarea id="descripcion" name="descripcion" rows="17" cols="80" required></textarea>

                <!--Botones-->
                <div class="botones">
                    <button type="submit" name="btcrear" id="btcrear" value="">Crear</button>
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