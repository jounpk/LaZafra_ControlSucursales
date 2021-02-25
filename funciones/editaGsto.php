<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');
require_once('../assets/scripts/cadenas.php');
$ident = (isset($_POST['ident']) AND $_POST['ident'] != '') ? $_POST['ident'] : '' ;

$monto = (isset($_POST['monto']) AND $_POST['monto'] != '') ? $_POST['monto'] : '' ;
$sucursal=$_SESSION['LZFidSuc'];
$userReg=$_SESSION['LZFident'];
if ($monto == '' || $ident=='') {
 errorBD('No se recibieron todos los datos necesarios para el registro, inténtalo de nuevo');
}
$sqlCon = "SELECT gstos.descripcion FROM gastos gstos WHERE (id = '$ident') AND idSucursal='$sucursal' AND idUserReg='$userReg'";

$resCon = mysqli_query($link,$sqlCon) or die(errorBD('Problemas al consultar el Gasto, notifica a tu Administrador'));

$cant = mysqli_num_rows($resCon);

if ($cant <= 0) {
  errorBD('No se encuentra el Gasto emitido por la sucursal, notifica a tu Administrador');
}
$desc=mysqli_fetch_array($resCon);
$desc=$desc['descripcion'];
$sql = "UPDATE gastos SET monto='$monto' WHERE id='$ident'";
$res = mysqli_query($link,$sql) or die(errorBD('Problemas al Actualizar el Gasto, notifica a tu Administrador'));
 echo'1|El Gasto  <b>'.$desc.'</b> se ha actualizado correctamente.';


function errorBD($error){
 echo '0|'.$error;
   exit(0);
}
