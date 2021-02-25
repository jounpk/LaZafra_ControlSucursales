<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$motivo = (isset($_POST['motivoEspecial']) && $_POST['motivoEspecial'] != '') ? $_POST['motivoEspecial'] : 0 ;
$idCliente = (isset($_POST['idCliente']) && $_POST['idCliente'] != '') ? $_POST['idCliente'] : '' ;
$userReg = $_SESSION['LZFident'];
$idSucursal = $_SESSION['LZFidSuc'];

/*echo '<br>########################################################################<br>';
echo '<br>$_POST:<br>';
print_r($_POST);
echo '<br>';
echo '<br>$idCliente: '.$idCliente;
echo '<br>$motivo: '.$motivo;
echo '<br>$userReg: '.$userReg;
echo '<br>$idSucursal: '.$idSucursal;
echo '<br>';
echo '<br>########################################################################<br>';
exit(0);*/

if ($motivo == '') {
  errorBD('No se reconoció el motivo, vuelve a intentarlo, si persiste notifica a tu Administrador');
}

if ($idCliente == '') {
  errorBD('No se reconoció el Cliente, vuelve a intentarlo, si persiste notifica a tu Administrador');
}

  $sql = "INSERT INTO ventas(idCliente,total,idSucursal, fechaReg, idUserReg, estatus, idUserAut, ventaEspecial, motivoVtaEsp, fechaSolicita) VALUES('$idCliente','0','$idSucursal',NOW(),'$userReg','1','0','1','$motivo',NOW())";
  $res = mysqli_query($link,$sql) or die(errorBD('Problemas al capturar la Venta Especial, notifica a tu Administrador'));

  $_SESSION['LZFmsjSuccessInicioVentaEspecial'] = 'Salicitud cargada correctamente comunícate con la oficina para obtener una pronta respuesta.';
  header('location: ../ventaEspecial.php');


function errorBD($error){
#/*
  $_SESSION['autorizaVTA'] = 0;
  $_SESSION['LZFmsjInicioVentaEspecial'] = $error;
  header('location: ../ventaEspecial.php');
#*/
  exit(0);
}
 ?>
