<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$idCotizacion=$_SESSION['idCotizacion'];
$newEmail=(isset($_POST['email']) && $_POST['email'] != '') ? $_POST['email'] : '' ;


if(!ctype_digit($idCotizacion)) {
  $idCotizacion = 0;
}

  if($newEmail == '') {
     errorBD('No se recibió un email, debes escribir un email, inténtalo de nuevo');
  }

$sqlCon = "SELECT * FROM detcotcorreos WHERE correo = '$newEmail' AND idCotizacion = '$idCotizacion'";
$resCon = mysqli_query($link,$sqlCon) or die(errorBD('Problemas al consultar las Marcas, notifica a tu Administrador'));
$cant = mysqli_num_rows($resCon);

  if($cant > 0) {
    errorBD('Ya existe esa dirección de correo');
  }

$sql = "INSERT INTO detcotcorreos (idCotizacion,correo) VALUES('$idCotizacion', '$newEmail')";
$res = mysqli_query($link,$sql) or die(errorBD('Problemas al Capturar la Marca, notifica a tu Administrador | Sql: '.$sql));

echo '1|El correo '.$newEmail.' se ha agregado correctamente';
exit(0);

function errorBD($error){
 echo '0|'.$error;
 exit(0);
}
 ?>