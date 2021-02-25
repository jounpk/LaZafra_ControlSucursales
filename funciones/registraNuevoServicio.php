<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$nombre = (isset($_POST['nombre']) AND $_POST['nombre'] != '') ? trim($_POST['nombre']) : '' ;

if ($nombre == '') {
 errorBD('No se recibió un nombre, debes escribir un nombre, inténtalo de nuevo');
}

$sqlCon = "SELECT * FROM catservicios WHERE nombre = '$nombre'";
$resCon = mysqli_query($link,$sqlCon) or die(errorBD('Problemas al consultar los Servicios, notifica a tu Administrador'));
$cant = mysqli_num_rows($resCon);

if ($cant > 0) {
  errorBD('Ya se encuentra un Servicio con ese nombre, notifica a tu Administrador');
}

$sql = "INSERT INTO catservicios (nombre,estatus) VALUES('$nombre', 1)";
$res = mysqli_query($link,$sql) or die(errorBD('Problemas al Capturar el Servicio, notifica a tu Administrador'));


echo '1|El Servicio '.$nombre.' se ha creado correctamente.';
exit(0);

function errorBD($error){
 echo '0|'.$error;
 exit(0);
}
 ?>
