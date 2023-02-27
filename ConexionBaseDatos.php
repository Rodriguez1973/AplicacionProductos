<?php
$nombreServidor='localhost';
$usuario='gestor';
$contraseña='secreto';
$baseDatos='proyecto';

$conexionBD=mysqli_connect($nombreServidor,$usuario , $contraseña, $baseDatos);

try{
    //Establece la conexión.
    $conexionBD=mysqli_connect($nombreServidor,$usuario , $contraseña, $baseDatos);
    //Recoge la excepción si la conexión no se ha podido realizar.
}catch(Exception $exception){
    $_SESSION['mensaje']= "<p class='mensaje'>No es posible realizar la conexión con la base de datos.<br>".
            $exception->getMessage()."</p>";
    die();
}