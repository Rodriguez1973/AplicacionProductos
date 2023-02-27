<!DOCTYPE html>

<?php
session_start();
$mensaje = "";

require_once './ConexionBaseDatos.php';
//Si se ha establecido la conexión con la base de datos.
if (isset($conexionBD)) {
    $stmt = $conexionBD->stmt_init();
    $consulta = "select * from productos where id=?;";
    $stmt->prepare($consulta);
    $stmt->bind_param('i', $_SESSION['datos']['codigo']);
    if ($stmt->execute()) {
        $resultado = $stmt->get_result();
        $producto = $resultado->fetch_assoc();
        $stmt->close();
        $conexionBD->close();
    }
}
?>

<html>
    <head>
        <title>Detalle producto</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./css/EstilosDetalle.css"/>
    </head>
    <body>
        <form name="formulario_detalle" id="formulario_detalle" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
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
            <div class='cont_detalle'>
                <h1>Detalle de Producto</h1>
                <div class="detalle">
                    <p id="titulo"><?php echo $producto['nombre']; ?></p>
                    <p id="codigo">Codigo: <?php echo $producto['id']; ?>
                    <p id="nombre"><b>Nombre: </b><?php echo $producto['nombre']; ?></p>
                    <p id="nombre_corto"><b>Nombre Corto: </b><?php echo $producto['nombre_corto']; ?></p>
                    <p id="familia"><b>Codigo Familia: </b><?php echo $producto['familia']; ?></p>
                    <p id="pvp"><b>PVP (&euro;): </b><?php echo $producto['pvp']; ?></p>
                    <p id="descripcion"><b>Descripción: </b><?php echo $producto['descripcion']; ?></p> 
                </div>
                <a href="Listado.php">
                    <button type="button" name="btvolver" id="btvolver" value="">Volver</button>
                </a>
            </div>
        </form>
    </body>
</html>



