<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');
$ident=(isset($_POST['ident']) AND $_POST['ident'] != '') ? $_POST['ident'] : '' ;
$desc = (isset($_POST['eNombre']) AND $_POST['eNombre'] != '') ? trim($_POST['eNombre']) : '' ;
$clave = (isset($_POST['eClave']) AND $_POST['eClave'] != '') ? $_POST['eClave'] : '' ;
$banco = (isset($_POST['eBanco']) AND $_POST['eBanco'] != '') ? $_POST['eBanco'] : '' ;
$cierre = (isset($_POST['eCierre']) AND $_POST['eCierre'] != '') ? $_POST['eCierre'] : '' ;

if ($desc == '' || $clave == '' || $cierre == '' || $ident=='') {
 errorBD('No se recibieron todos los datos necesarios para el registro, inténtalo de nuevo'.$ident.'|'.$desc.'|'.$clave.'|'.$banco.'|'.$cierre);
}

$sqlCon = "SELECT * FROM sat_formapago WHERE (id = '$ident')";

$resCon = mysqli_query($link,$sqlCon) or die(errorBD('Problemas al consultar los Métodos de Pago, notifica a tu Administrador'));

$cant = mysqli_num_rows($resCon);

if ($cant <= 0) {
  errorBD('No se encuentra una Método de Pago con ese nombre y Banco, notifica a tu Administrador');
}

$sql = "UPDATE sat_formapago SET nombre='$desc',clave='$clave', cierraPago='$cierre', idBanco='$banco' WHERE id = '$ident'";
$res = mysqli_query($link,$sql) or die(errorBD('Problemas al Capturar el Método, notifica a tu Administrador'));


$_SESSION['LZFmsjSuccessCatalogoMet'] = 'El Método de Pago  <b>'.$desc.'</b> se ha modificado correctamente.';
header('location: ../Corporativo/catalogoPago.php');


function errorBD($error){
  $_SESSION['LZFmsjSuccessCatalogoMet'] = $error;


header('location: ../Corporativo/catalogoPago.php');
  exit(0);
}
 ?>
