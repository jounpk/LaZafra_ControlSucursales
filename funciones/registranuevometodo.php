<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$desc = (isset($_POST['rNombre']) AND $_POST['rNombre'] != '') ? trim($_POST['rNombre']) : '' ;
$clave = (isset($_POST['rClave']) AND $_POST['rClave'] != '') ? $_POST['rClave'] : '' ;
$banco = (isset($_POST['rBanco']) AND $_POST['rBanco'] != '') ? $_POST['rBanco'] : '' ;
$cierre = (isset($_POST['rCierre']) AND $_POST['rCierre'] != '') ? $_POST['rCierre'] : '' ;

if ($desc == '' || $clave == '' || $cierre == '') {
 errorBD('No se recibieron todos los datos necesarios para el registro, inténtalo de nuevo');
}

$sqlCon = "SELECT * FROM sat_formapago WHERE (nombre = '$desc' AND idBanco = '$banco') OR clave='$clave'";

$resCon = mysqli_query($link,$sqlCon) or die(errorBD('Problemas al consultar los Métodos de Pago, notifica a tu Administrador'));

$cant = mysqli_num_rows($resCon);

if ($cant > 0) {
  errorBD('Ya se encuentra una Método de Pago con ese nombre y Banco, notifica a tu Administrador');
}

$sql = "INSERT INTO sat_formapago (nombre,clave, cierraPago, idBanco, estatus) VALUES('$desc','$clave','$cierre','$banco', 1)";
$res = mysqli_query($link,$sql) or die(errorBD('Problemas al Capturar el Método, notifica a tu Administrador'));


$_SESSION['LZFmsjSuccessCatalogoMet'] = 'El Método de Pago  <b>'.$desc.'</b> se ha creado correctamente.';
header('location: ../Corporativo/catalogoPago.php');


function errorBD($error){
  $_SESSION['LZFmsjSuccessCatalogoMet'] = $error;


header('location: ../Corporativo/catalogoPago.php');
  exit(0);
}
 ?>
