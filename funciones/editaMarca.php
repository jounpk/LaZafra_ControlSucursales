<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$id = (!empty($_POST['idMarca'])) ? $_POST['idMarca'] : 0 ;
$nombre = (isset($_POST['nombre']) AND $_POST['nombre'] != '') ? trim($_POST['nombre']) : '';

if ($id < 1) {
  errorBD('No se reconoció la marca a editar, vuelve a intentarlo, si persiste notifica a tu Administrador');
}

if ($nombre == '') {
  errorBD('Debes ingresar un nombre');
}

$sqlCon = "SELECT * FROM catmarcas WHERE nombre = '$nombre' AND id != '$id'";
$resCon = mysqli_query($link,$sqlCon) or die(errorBD('Problemas al consultar las marcas, notifica a tu Administrador'));
$cant = mysqli_num_rows($resCon);

if ($cant > 0) {
  errorBD('Lo sentimos, ya se encuentra una marca con ese nombre');
}

$sql = "UPDATE catmarcas SET nombre = '$nombre' WHERE id = '$id'";
$res = mysqli_query($link,$sql) or die(errorBD('Problemas al actualizar las Marcas, notifica a tu Administrador'));
 echo '1|La Marca fue editada exitosamente.';

exit(0);

function errorBD($error){
echo '0|'.$error;
exit(0);
}
 ?>
