<?php
session_start();
define('INCLUDE_CHECK', 1);
require_once('../include/connect.php');
$debug = 0;
$ident = (isset($_GET['id']) and $_GET['id'] != '') ? $_GET['id'] : '';
//----------------devBug------------------------------
if ($debug == 1) {
  print_r($_GET);
  echo '<br><br>';
} else {
  error_reporting(0);
}  //-------------Finaliza devBug------------------------------
if ($ident == "") {
  //echo "Compra Null";
  errorCarga("Selecciona un producto para continuar. Inténtalo de Nuevo.");
}

$sql = "DELETE FROM detcotizaciones WHERE id = '$ident'";
//----------------devBug------------------------------
if ($debug == 1) {
  $resultXquery = mysqli_query($link, $sql) or die(errorBD('Problemas al Borrar Producto de la Cotizacion, notifica a tu Administrador', mysqli_error($link)));
  $canInsert = mysqli_affected_rows($link);
  echo '<br>SQL: ' . $sql . '<br>';
  echo '<br>Cant de Registros Cargados: ' . $canInsert;
} else {
  $resultXquery = mysqli_query($link, $sql) or die(errorBD('Problemas al Borrar Producto de la Cotizacion, notifica a tu Administrador', mysqli_error($link)));
  $canInsert = mysqli_affected_rows($link);
} //-------------Finaliza devBug------------------------------
$_SESSION['LZmsjSuccessCotizacion'] = 'Se ha eliminado el Producto Seleccionado de la Cotización';

header('location: ../Administrador/cotizaciones.php');
function errorCarga($error)
{
  $_SESSION['LZmsjInfoCotizacion'] = $error;
  // echo 'cayo: '.$error;
  header('location: ../Administrador/cotizaciones.php');
  exit(0);
}
function errorBD($msj)
{
  //echo '<br>** Se dispara Error: '.$msj.' **<br>';
  $_SESSION['LZmsjInfoCotizacion'] = $msj;
  header('location: ../Administrador/cotizaciones.php');
  exit(0);
}
