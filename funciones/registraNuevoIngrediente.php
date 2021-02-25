<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$nombre = (isset($_POST['nombre']) AND $_POST['nombre'] != '') ? trim($_POST['nombre']) : '' ;
$desc = (isset($_POST['desc']) && $_POST['desc'] != '') ? trim($_POST['desc']) : '' ;

if ($nombre == '') {
 errorBD('No se recibió un nombre, debes escribir un nombre, inténtalo de nuevo');
}

$sqlCon = "SELECT * FROM catingact WHERE nombre = '$nombre'";
$resCon = mysqli_query($link,$sqlCon) or die(errorBD('Problemas al consultar los Ingredientes, notifica a tu Administrador'));
$cant = mysqli_num_rows($resCon);

if ($cant > 0) {
  errorBD('Ya se encuentra un Ingrediente con ese nombre, notifica a tu Administrador');
}

$sql = "INSERT INTO catingact (nombre,descripcion,estatus) VALUES('$nombre', '$desc', 1)";
$res = mysqli_query($link,$sql) or die(errorBD('Problemas al Capturar el Ingrediente, notifica a tu Administrador'));


echo '1|El Ingrediente '.$nombre.' se ha creado correctamente.';
exit(0);

function errorBD($error){
 echo '0|'.$error;
 exit(0);
}
 ?>
