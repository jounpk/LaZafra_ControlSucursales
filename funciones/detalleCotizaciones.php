<?php
session_start();
define('INCLUDE_CHECK', 1);
require_once('../include/connect.php');
$sucursal = $_SESSION['LZFidSuc'];
$userReg = $_SESSION['LZFident'];
$idCotizacion = (isset($_GET['idCotizacion']) and $_GET['idCotizacion'] != '') ? $_GET['idCotizacion'] : '';
$producto = (isset($_GET['producto']) and $_GET['producto'] != '') ? $_GET['producto'] : '';
$debug = 0;

$sql = "SELECT cantidad FROM detcotizaciones WHERE idProducto='$producto' AND idCotizacion='$idCotizacion'";
//----------------devBug------------------------------
if ($debug == 1) {
  $resultXquery = mysqli_query($link, $sql) or die(errorBD('Problemas al consultar Productos en Cotizaciones, notifica a tu Administrador', mysqli_error($link)));
  $canInsert = mysqli_affected_rows($link);
  echo '<br>SQL: ' . $sql . '<br>';
  echo '<br>Cant de Registros Cargados: ' . $canInsert;
} else {
  $resultXquery = mysqli_query($link, $sql) or die(errorBD('Problemas al consultar Productos en Cotizaciones, notifica a tu Administrador', mysqli_error($link)));
  $canInsert = mysqli_affected_rows($link);
} //-------------Finaliza devBug------------------------------
$cantrow = mysqli_num_rows($resultXquery);
if ($debug == 1) {
  echo ('Num. de Filas ' . $cant_row);
}
if ($cantrow <= 0) {

  $sql = "SELECT * FROM productos WHERE id='$producto'";
  //----------------devBug------------------------------
  if ($debug == 1) {
    $resultXquery = mysqli_query($link, $sql) or die(errorBD('Problemas al consultar Productos, notifica a tu Administrador', mysqli_error($link)));
    $canInsert = mysqli_affected_rows($link);
    echo '<br>SQL: ' . $sql . '<br>';
    echo '<br>Cant de Registros Cargados: ' . $canInsert;
  } else {
    $resultXquery = mysqli_query($link, $sql) or die(errorBD('Problemas al consultar Productos, notifica a tu Administrador', mysqli_error($link)));
    $canInsert = mysqli_affected_rows($link);
  } //-------------Finaliza devBug------------------------------

  $arrayProd=mysqli_fetch_array($resultXquery);
  $costo=$arrayProd['costo'];

  $sql = "INSERT INTO detcotizaciones(idCotizacion,idProducto,cantidad, costo,precioCoti, subtotal) VALUES('$idCotizacion','$producto',1, '$costo',0.0,0.0)";

  //----------------devBug------------------------------
  if ($debug == 1) {
    $resultXquery = mysqli_query($link, $sql) or die(errorBD('Problemas al guardar Productos en Cotizaciones, notifica a tu Administrador', mysqli_error($link)));
    $canInsert = mysqli_affected_rows($link);
    echo '<br>SQL: ' . $sql . '<br>';
    echo '<br>Cant de Registros Cargados: ' . $canInsert;
  } else {
    $resultXquery = mysqli_query($link, $sql) or die(errorBD('Problemas al guardar Productos en Cotizaciones, notifica a tu Administrador', mysqli_error($link)));
    $canInsert = mysqli_affected_rows($link);
  } //-------------Finaliza devBug------------------------------
}

$_SESSION['LZmsjSuccessCotizacion'] = 'El Producto  se a seleccionado.';

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
