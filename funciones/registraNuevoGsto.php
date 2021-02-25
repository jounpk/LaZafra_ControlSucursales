<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');
require_once('../assets/scripts/cadenas.php');

$desc = (isset($_POST['rdescGasto']) AND $_POST['rdescGasto'] != '') ? trim($_POST['rdescGasto']) : '' ;
$monto = (isset($_POST['rMonto']) AND $_POST['rMonto'] != '') ? $_POST['rMonto'] : '' ;
$sucursal=$_SESSION['LZFidSuc'];
$userReg=$_SESSION['LZFident'];
if ($desc == '' || $monto == '') {
 errorBD('No se recibieron todos los datos necesarios para el registro, inténtalo de nuevo');
}
$sql = "INSERT INTO gastos (idSucursal,monto, descripcion, idUserReg,fechaReg)
VALUES('$sucursal','$monto','$desc','$userReg', NOW())";
$res = mysqli_query($link,$sql) or die(errorBD('Problemas al Capturar el Gasto, notifica a tu Administrador'));
 echo'1|El Gasto  <b>'.$desc.'</b> se ha creado correctamente.';



function errorBD($error){
 echo '0|'.$error;
   exit(0);
}
