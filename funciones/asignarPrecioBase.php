<?php
session_start();
define('INCLUDE_CHECK', 1);
require_once('../include/connect.php');
$sucursal = $_SESSION['LZFidSuc'];
$userReg = $_SESSION['LZFident'];
$idDetCotizacion = (isset($_GET['idDetCotizacion']) and $_GET['idDetCotizacion'] != '') ? $_GET['idDetCotizacion'] : '';
$precio = (isset($_GET['precio']) and $_GET['precio'] != '') ? $_GET['precio'] : '';
$debug = 0;
//----------------devBug------------------------------
if ($debug == 1) {
  print_r($_POST);
  echo '<br><br>';
} else {
  error_reporting(0);
}  //-------------Finaliza devBug------------------------------
$sql = "SELECT precio FROM preciosbase WHERE id='$precio'";
//----------------devBug------------------------------
if ($debug == 1) {
  $resultXquery = mysqli_query($link, $sql) or die(errorBD('Problemas al editar Productos en Cotizaciones, notifica a tu Administrador', mysqli_error($link)));
  $canInsert = mysqli_affected_rows($link);
  echo '<br>SQL: ' . $sql . '<br>';
  echo '<br>Cant de Registros Cargados: ' . $canInsert;
} else {
  $resultXquery = mysqli_query($link, $sql) or die(errorBD('Problemas al editar Productos en Cotizaciones, notifica a tu Administrador', mysqli_error($link)));
  $canInsert = mysqli_affected_rows($link);
} //-------------Finaliza devBug------------------------------
$arrayDePrecio=mysqli_fetch_array($resultXquery);
$precioCotizador=$arrayDePrecio['precio'];

$sql = "UPDATE detcotizaciones SET asignaPrecio='1', precioCoti='$precioCotizador', subtotal=cantidad*(SELECT precio FROM preciosbase WHERE id='$precio'), idPrecioBase='$precio' WHERE id='$idDetCotizacion'";

//----------------devBug------------------------------
if ($debug == 1) {
  $resultXquery = mysqli_query($link, $sql) or die(errorBD('Problemas al editar Productos en Cotizaciones, notifica a tu Administrador', mysqli_error($link)));
  $canInsert = mysqli_affected_rows($link);
  echo '<br>SQL: ' . $sql . '<br>';
  echo '<br>Cant de Registros Cargados: ' . $canInsert;
} else {
  $resultXquery = mysqli_query($link, $sql) or die(errorBD('Problemas al editar Productos en Cotizaciones, notifica a tu Administrador', mysqli_error($link)));
  $canInsert = mysqli_affected_rows($link);
} //-------------Finaliza devBug------------------------------


$_SESSION['LZmsjSuccessCotizacion'] = 'Precio de Cotizacion Seleccionado.';

header('location: ../Administrador/cotizaciones.php');

function errorCarga($error)
{
  $_SESSION['LZmsjInfoCotizaciones'] = $error;
  //echo 'cayo: '.$error;
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
