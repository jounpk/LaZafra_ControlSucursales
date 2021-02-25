<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$motivo = (isset($_POST['rmotivo']) AND $_POST['rmotivo'] != '') ? $_POST['rmotivo'] : '' ;
$sucursal=$_SESSION['LZFidSuc'];
$userReg=$_SESSION['LZFident'];

if ($motivo == '') {
 errorBD('No se recibieron todos los datos necesarios para el registro, inténtalo de nuevo');
}

$sqlCon = "SELECT COUNT(id) AS solPend FROM activaredicionstock WHERE idUserSolicita='$userReg' AND idSucSolicita='$sucursal' AND estatus=1";

$resCon = mysqli_query($link,$sqlCon) or die(errorBD('Problemas al consultar las Solicitudes de Edición, notifica a tu Administrador'));

$cant = mysqli_num_rows($resCon);

if ($cant==0){
    errorBD('Problemas al consultar las Solicitudes de Edición, notifica a tu Administrador');
}
$totalSol=mysqli_fetch_array($resCon);
if ($totalSol['solPend'] > 0) {
  errorBD('Ya se encuentra una solicitud pendiente, espera la deliberación.');
}

$sql = "INSERT INTO activaredicionstock (idUserSolicita,fechaSolicita, idSucSolicita, descripcion, estatus) VALUES('$userReg',NOW(),'$sucursal','$motivo', 1)";
$res = mysqli_query($link,$sql) or die(errorBD('Problemas al Capturar la Solicitud, notifica a tu Administrador'));
echo'1|La solicitud de Edición de Stock se ha creado correctamente.';



function errorBD($error){
  echo '0|'.$error;
    exit(0);
 }
 ?>
