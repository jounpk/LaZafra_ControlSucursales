<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');
require_once('../assets/scripts/cadenas.php');

$identSuc = (isset($_POST['buscaSuc']) AND $_POST['buscaSuc'] != '') ? $_POST['buscaSuc'] : '' ;
$identPro = (isset($_POST['buscaPro']) AND $_POST['buscaPro'] != '') ? $_POST['buscaPro'] : '' ;
$monto = (isset($_POST['precio']) AND $_POST['precio'] != '') ? $_POST['precio'] : '' ;
$userReg=$_SESSION['LZFident'];
if ($identSuc == '' ||$identPro == '' || $monto == '') {
 errorBD('No se recibieron todos los datos necesarios para el registro, inténtalo de nuevo');
}
$sql = "INSERT INTO excepcionesprecio (idProducto,idSucursal,precio, fechaReg, idUserReg) 
VALUES('$identPro','$identSuc','$monto',NOW(),'$userReg')";
$res = mysqli_query($link,$sql) or die(errorBD('Problemas al Capturar el Precio de Excepción, notifica a tu Administrador'.mysqli_error($link)));
 echo'1|El Precio  de <b>'.$monto.'</b> se ha creado correctamente.';

  

function errorBD($error){
 echo '0|'.$error;
   exit(0);
}
