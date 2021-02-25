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
  print_r($_GET);
  echo '<br><br>';
} else {
  error_reporting(0);
}  //-------------Finaliza devBug------------------------------

$sql = "UPDATE detcotizaciones dc
    INNER JOIN productos pr ON dc.idProducto = pr.id 
    SET dc.asignaPrecio='2', dc.idPrecioBase='', dc.precioCoti='$precio', dc.subtotal=$precio*dc.cantidad WHERE dc.id='$idDetCotizacion'
    AND pr.costo<$precio";

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
if ($canInsert <= 0) {
  $sql = "UPDATE detcotizaciones dc
  INNER JOIN productos pr ON dc.idProducto = pr.id 
  SET dc.asignaPrecio='2', dc.idPrecioBase='', dc.precioCoti=pr.costo+2, dc.subtotal=pr.costo*dc.cantidad WHERE dc.id='$idDetCotizacion'
  ";
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

  errorCarga("El precio Personalizado Siempre debe ser MAYOR al costo");
}

$_SESSION['LZmsjSuccessCotizacion'] = 'Precio de Cotizacion Seleccionado.';

header('location: ../Administrador/cotizaciones.php');

function errorCarga($error)
{
  $_SESSION['LZmsjInfoCotizacion'] = $error;
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
