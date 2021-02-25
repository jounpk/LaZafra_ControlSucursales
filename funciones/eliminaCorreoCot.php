<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$id = (!empty($_POST['ident'])) ? $_POST['ident'] : 0 ;
$idCot = (!empty($_POST['idCot'])) ? $_POST['idCot'] : 0 ;

#echo '<br><br>------------------------ se valida que el id sea mayor a 0 ------------------------<br><br>';
if ($id < 1) {
  errorBD('No se reconoció el correo a eliminar, vuelve a intentarlo, si persiste notifica a tu Administrador');
}

#echo '<br><br>------------------------ se valida que el idCot sea mayor a 0 ------------------------<br><br>';
if ($idCot < 1) {
  errorBD('No se reconoció el correo a eliminar, vuelve a intentarlo, si persiste notifica a tu Administrador');
}

#echo '<br><br>------------------------ se consulta que la cotización esté en estatus 1 para proseguir con el ingreso de los correos ------------------------<br><br>';
$sqlCon = "SELECT * FROM cotizaciones WHERE id = '$idCot'";
$resCon = mysqli_query($link,$sqlCon) or die(errorBD('Problemas al consultar las cotizaciones.'));
$edo = mysqli_fetch_array($resCon);

#echo '<br><br>------------------------ Se valida que su estatus no sea mayor a 1 ------------------------<br><br>';
if ($edo['estatus'] > 1) {
  errorBD('No se eliminó el correo porque la cotización ya fue cerrada o cancelada.');
}

$sql="DELETE FROM detcotcorreos WHERE id = $id LIMIT 1";
$res = mysqli_query($link,$sql) or die(errorBD('Problemas al eliminar el Contacto, notifica a tu Administrador'));

echo '1|Se ha eliminado Correctamente.';

function errorBD($error){
  echo '0|'.$error;
}
 ?>
