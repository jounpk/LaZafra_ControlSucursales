<?php
session_start();
define('INCLUDE_CHECK', 1);
require_once('../include/connect.php');
$sucursal = $_SESSION['LZFidSuc'];
$userReg = $_SESSION['LZFident'];
$idCotizacion = (isset($_POST['idCotizacion']) and $_POST['idCotizacion'] != '') ? $_POST['idCotizacion'] : '';
$email = (isset($_POST['email']) and $_POST['email'] != '') ? $_POST['email'] : '';
$debug = 0;
//----------------devBug------------------------------
if ($debug == 1) {
  print_r($_POST);
  echo '<br><br>';
} else {
  error_reporting(0);
}  //-------------Finaliza devBug------------------------------

$sql = "INSERT INTO detcotcorreos (idCotizacion, correo) VALUES('$idCotizacion', '$email') ";

//----------------devBug------------------------------
if ($debug == 1) {
  $resultXquery = mysqli_query($link, $sql) or die(errorBD('Problemas al guardar Email en Cotizaciones, notifica a tu Administrador', mysqli_error($link)));
  $canInsert = mysqli_affected_rows($link);
  echo '<br>SQL: ' . $sql . '<br>';
  echo '<br>Cant de Registros Cargados: ' . $canInsert;
} else {
  $resultXquery = mysqli_query($link, $sql) or die(errorBD('Problemas al guardar Email en Cotizaciones, notifica a tu Administrador', mysqli_error($link)));
  $canInsert = mysqli_affected_rows($link);
} //-------------Finaliza devBug------------------------------


$_SESSION['LZmsjSuccessCotizacion'] = 'Email de Cotizacion Seleccionado.';
echo '1|Email de Cotizacion Seleccionado.';

function errorCarga($error)
{
  $_SESSION['LZmsjInfoCotizaciones'] = $error;
  //echo 'cayo: '.$error;
  //header('location: ../Administrador/cotizaciones.php');
  echo '0|' . $error;
  exit(0);
}
function errorBD($msj)
{
  //echo '<br>** Se dispara Error: '.$msj.' **<br>';
  //$_SESSION['LZmsjInfoCotizacion'] = $msj;
  //header('location: ../Administrador/cotizaciones.php');
  echo '0|' . $msj;
  exit(0);
}
