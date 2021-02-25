<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$id = (!empty($_POST['idDepto'])) ? $_POST['idDepto'] : 0 ;
$nombre = (isset($_POST['nombre']) AND $_POST['nombre'] != '') ? trim($_POST['nombre']) : '';
$desc = (isset($_POST['desc']) AND $_POST['desc'] != '') ? trim($_POST['desc']) : '';

if ($id < 1) {
  errorBD('No se reconoció el Departamento a editar, vuelve a intentarlo, si persiste notifica a tu Administrador');
}

if ($nombre == '') {
  errorBD('Debes ingresar un nombre');
}

$sqlCon = "SELECT * FROM departamentos WHERE nombre = '$nombre' AND id != '$id'";
$resCon = mysqli_query($link,$sqlCon) or die(errorBD('Problemas al consultar el Departamento, notifica a tu Administrador'));
$cant = mysqli_num_rows($resCon);

if ($cant > 0) {
  errorBD('Lo sentimos, ya se encuentra un Departamento con ese nombre');
}

$sql = "UPDATE departamentos SET nombre = '$nombre',descripcion = '$desc' WHERE id = '$id'";
$res = mysqli_query($link,$sql) or die(errorBD('Problemas al actualizar el Departamento, notifica a tu Administrador'));
 echo '1|El Departamento fue editado exitosamente.';

exit(0);

function errorBD($error){
echo '0|'.$error;
exit(0);
}
 ?>
