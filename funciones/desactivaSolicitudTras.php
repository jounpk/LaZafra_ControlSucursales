<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');
require_once('../assets/scripts/cadenas.php');
$ident = (isset($_POST['ident']) AND $_POST['ident'] != '') ? $_POST['ident'] : '' ;
$sucursal=$_SESSION['LZFidSuc'];
$userReg=$_SESSION['LZFident'];
if ($ident=='') {
 errorBD('No se recibieron todos los datos necesarios para la cancelación, inténtalo de nuevo');
}
$sqlCon = "SELECT suc.nombre AS descripcion FROM traspasos tras INNER JOIN sucursales suc ON tras.idSucEntrada=suc.id WHERE
tras.id='$ident'";

$resCon = mysqli_query($link,$sqlCon) or die(errorBD('Problemas al consultar el Traspaso, notifica a tu Administrador'));

$cant = mysqli_num_rows($resCon);

if ($cant <= 0) {
  errorBD('No se encuentra el Traspaso emitido, notifica a tu Administrador');
}
$desc=mysqli_fetch_array($resCon);
$desc=$desc['descripcion'];
$sql = "UPDATE traspasos SET estatus='5' WHERE id='$ident'";
$res = mysqli_query($link,$sql) or die(errorBD('Problemas al Cancelar el Traspaso, notifica a tu Administrador'));
 echo'1|El Traspaso  <b>'.$desc.'</b> se ha cancelado correctamente.';


function errorBD($error){
 echo '0|'.$error;
   exit(0);
}
