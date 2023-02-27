<?php
$nombreServidor='localhost';
$usuario='gestor';
$contraseña='secreto';
$baseDatos='proyecto';

try{
    $conexionBD=mysqli_connect($nombreServidor,$usuario , $contraseña, $baseDatos);
    //Establece la conexión.
    $conexionBD=mysqli_connect($nombreServidor,$usuario , $contraseña, $baseDatos);
    //Recoge la excepción si la conexión no se ha podido realizar.
}catch(Exception $exception){
    $_SESSION['mensaje']= "No es posible realizar la conexión con la base de datos.<br>".$exception->getMessage();
}