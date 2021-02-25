<?php
session_start();
define('INCLUDE_CHECK', 1);
require_once('../include/connect.php');
$sucursal = $_SESSION['LZFidSuc'];
$ident = (isset($_POST['ident'])) ? $_POST['ident'] : '';
$estatus = (isset($_POST['estatus'])) ? $_POST['estatus'] : '';
$dias= (isset($_POST['dias'])) ? $_POST['dias'] : '';
$idSucursalEmite=(isset($_POST['idSucursalEmite'])) ? $_POST['idSucursalEmite'] : '';

$txt = '';
$userReg = $_SESSION['LZFident'];
$debug = 0;
//----------------devBug------------------------------
if ($debug == 1) {
  print_r($_POST);
  echo '<br><br>';
} else {
  error_reporting(0);
}  //-------------Finaliza devBug------------------------------

//print_r($_POST);
if ($ident == '' OR $estatus == '' OR $dias=='') {
  errorBD('Faltan datos Obligatorios, Notifica al Administrador.');
} else {
  if ($estatus == '3') {
    $sql = "SELECT
    LPAD(COUNT( ct.id )+ 1,8,'0') AS folio,
    suc.serieFact 
    FROM
    cotizaciones ct,
		sucursales suc
    WHERE suc.id='$idSucursalEmite' AND
    (ct.estatus='3' OR ct.estatus='5') ";
    if($debug==1){
      print_r('Consulta Para Folios: '.$sql);
    }
    //----------------devBug------------------------------
    if ($debug == 1) {
      $resultXquery = mysqli_query($link, $sql) or die(errorBD('Problemas al obtener Serie y folio, notifica a tu Administrador', mysqli_error($link)));
      $canInsert = mysqli_affected_rows($link);
      echo '<br>SQL: ' . $sql . '<br>';
      echo '<br>Cant de Registros Cargados: ' . $canInsert;
    } else {
      $resultXquery = mysqli_query($link, $sql) or die(errorBD('Problemas al obtener Serie y folio, notifica a tu Administrador', mysqli_error($link)));
      $canInsert = mysqli_affected_rows($link);
    } //-------------Finaliza devBug------------------------------
    $arrayFolios = mysqli_fetch_array($resultXquery);
    
    $folio = "COT-" . $arrayFolios['serieFact'] . '-' . $arrayFolios['folio'];

    $sql = "UPDATE cotizaciones SET estatus=$estatus, folio='" . $folio . "', incSerie='" . $arrayFolios['folio'] . "', fechaAut=NOW(), idUserAut=$userReg, cantPeriodo='$dias'  WHERE id = $ident ";
    
  } else {
    $sql = "UPDATE cotizaciones SET estatus=$estatus, fechaAut=NOW(), idUserAut=$userReg, cantPeriodo='$dias' WHERE id = $ident ";
  }

  //----------------devBug------------------------------
  if ($debug == 1) {
    $resultXquery = mysqli_query($link, $sql) or die(errorBD('Problemas al autorizar Cotizaciones, notifica a tu Administrador', mysqli_error($link)));
    $canInsert = mysqli_affected_rows($link);
    echo '<br>SQL: ' . $sql . '<br>';
    echo '<br>Cant de Registros Cargados: ' . $canInsert;
  } else {
    $resultXquery = mysqli_query($link, $sql) or die(errorBD('Problemas al autorizar Cotizaciones, notifica a tu Administrador', mysqli_error($link)));
    $canInsert = mysqli_affected_rows($link);
  } //-------------Finaliza devBug------------------------------


  if ($estatus == 3) {

    echo '1|Se ha Aceptado Correctamente la Cotización.';
  } else {
    echo '1|Se ha Negado la Cotización.';
  }
}

function errorBD($error)
{
  echo '0|' . $error;
  exit(0);
}
