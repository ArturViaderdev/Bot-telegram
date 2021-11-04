<?php

function leepalabras(){
  $c = conectar();
  $select = "select palabra,respuesta,imagen from bec_palabras";
  $resultado = mysqli_query($c, $select);
  desconectar($c);
  return $resultado;
}

function leecomandosbecaria()
{
  $c = conectar();
  $select = "select palabra,respuesta,documento from bec_combecaria";
  $resultado = mysqli_query($c, $select);
  desconectar($c);
  return $resultado;
}

function leerandom()
{
  $c = conectar();
  $select = "select palabra from bec_random";
  $resultado = mysqli_query($c, $select);
  desconectar($c);
  return $resultado;
}

// Función que conecta a la base de datos
function conectar() {
    $serverbd = "ip bd";
    $userbd = "ususario bd";
    $passbd = "pass bd";
    $base="nombre bd";
    $conexion = mysqli_connect($serverbd, $userbd, $passbd, $base);
    // Si no ha ido bien la conexión
    if (!$conexion) {
        die("No se ha podido establecer la conexión");
    }
    return $conexion;
}

// Función que cierra una conexión con la base de datos
function desconectar($conexion) {
    mysqli_close($conexion);
}

?>
