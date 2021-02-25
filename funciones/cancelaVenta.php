<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');
$debug=0;

$idVenta = (!empty($_POST['idVenta'])) ? $_POST['idVenta'] : 0 ;
$tipoVenta = (!empty($_POST['tipoVenta'])) ? $_POST['tipoVenta'] : 0 ;
/*
echo '<br>########################################################################<br>';
echo '<br>$_POST:<br>';
print_r($_POST);
echo '<br>';
echo '<br>$idVenta: '.$idVenta;
echo '<br>$tipoVenta: '.$tipoVenta;
echo '<br>';
echo '<br>########################################################################<br>';
exit(0);
# */
if ($idVenta < 1) {
  errorBD('No se reconoció la venta, vuelve a intentarlo, si persiste notifica a tu Administrador.',$tipoVenta);
}
$sqlCon ="SELECT estatus FROM ventas WHERE id = '$idVenta'";
$resCon = mysqli_query($link,$sqlCon) or die(errorBD('Problemas al consultar la venta, notifica a tu Administrador.',$tipoVenta));
$dat = mysqli_fetch_array($resCon);
if ($dat['estatus'] > 1) {
  errorBD('La venta no se encuentra disponible para su cierre, verifica con tu Administrador de Sucursal.',$tipoVenta);
}

$sql = "UPDATE ventas SET estatus = '5', fechaReg = NOW() WHERE id = '$idVenta'";
$res = mysqli_query($link,$sql) or die(errorBD('Tuvimos problemas al cancelar la venta, notifica a tu Administrador.',$tipoVenta));
$sql = "UPDATE cotizaciones c
  INNER JOIN ventas v ON c.id=v.idCotizacion
  SET c.estatus='3' WHERE v.id='$idVenta'";
  //----------------devBug------------------------------
  if ($debug == 1) {
    $resultXQuery = mysqli_query($link, $sql) or die(errorBD('Problemas al Regresar Cotización, notifica a tu Administrador', mysqli_error($link)));
    $canInsert = mysqli_affected_rows($link);
    echo '<br>SQL: ' . $sql . '<br>';
    echo '<br>Cant de Registros Cargados: ' . $canInsert;
  } else {
    $resultXQuery = mysqli_query($link, $sql) or die(errorBD('Problemas al Regresar Cotización, notifica a tu Administrador', mysqli_error($link)));
    $canInsert = mysqli_affected_rows($link);
  } //-------------Finaliza devBug------------------------------
if ($tipoVenta == 1) {
#		echo '<br> Línea 61';
  $_SESSION['LZFmsjSuccessInicioVenta'] = 'Venta cancelada correctamente.';
  header('location: ../venta.php');
  exit(0);
} else {
#		echo '<br> Línea 66';
  $_SESSION['LZFmsjSuccessInicioVentaEspecial'] = 'Venta cancelada correctamente.';
  header('location: ../ventaEspecial.php');
  exit(0);
}

function errorBD($error,$tipoVenta){
  if ($tipoVenta == 1) {
  #		echo '<br> Línea 61';
    $_SESSION['LZFmsjInicioVenta'] = $error;
    header('location: ../venta.php');
    exit(0);
  } else {
  #		echo '<br> Línea 66';
    $_SESSION['LZFmsjInicioVentaEspecial'] = $error;
    header('location: ../ventaEspecial.php');
    exit(0);
  }
}
 ?>
