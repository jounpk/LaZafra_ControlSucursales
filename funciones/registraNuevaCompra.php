<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$idEmp = (!empty($_POST['empresaCompra'])) ? $_POST['empresaCompra'] : 0 ;
$idProv = (!empty($_POST['proveedorCompra'])) ? $_POST['proveedorCompra'] : 0;
$fecha = (isset($_POST['fechaCompra']) && $_POST['fechaCompra'] != '') ? $_POST['fechaCompra'] : '' ;
$idUser = $_SESSION['LZFident'];
$idSuc = $_SESSION['LZFidSuc'];

/*
echo '<br> ########################################################################### <br>';
echo '<br>$_POST:<br>';
print_r($_POST);
echo '<br>$idEmp: '.$idEmp;
echo '<br>$idProv: '.$idProv;
echo '<br>$fecha: '.$fecha;
echo '<br>$idUser: '.$idUser;
echo '<br>$idSuc: '.$idSuc;
echo '<br> ########################################################################### <br>';
exit(0);
#*/

if ($idEmp == 0) {
  errorBD('No se reconoció la empresa que realiza la compra, inténtalo nuevamente, si el problema persiste notifica a tu Administrador.');
}
if ($idProv == 0) {
  errorBD('No se reconoció el proveedor que realiza la venta, inténtalo nuevamente, si el problema persiste notifica a tu Administrador.');
}
if ($fecha == '') {
  errorBD('No se reconoció la fecha de compra, inténtalo nuevamente, si el problema persiste notifica a tu Administrador.');
}

$sql = "INSERT INTO compras(total,fechaReg,idUserReg,idSucursal,estatus,idProveedor,subtotal,iva, fechaCompra) VALUES('0',NOW(),'$idUser','$idSuc','1','$idProv','0','0','$fecha')";
#echo '<br>$sql: '.$sql;
$res = mysqli_query($link,$sql) or die(errorBD('Problemas al capturar la compra, notifica a tu Administrador. '.mysqli_error($link)));

#echo '<br>Listo, se guardó correctamente';
$_SESSION['LZFmsjSuccessCorporativoCompras'] = 'Listo, puede comenzar a cargar los productos de la compra';
header('location: ../Corporativo/compras.php');
exit(0);

function errorBD($error){
#  echo '<br>$error: '.$error;
  $_SESSION['LZFmsjCorporativoCompras'] = $error;
  header('location: ../Corporativo/compras.php');
exit(0);
}
 ?>
