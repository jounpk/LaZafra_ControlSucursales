<?php
session_start();
define('INCLUDE_CHECK', 1);
require_once('../include/connect.php');

$idCotizacion = (isset($_POST['ident'])) ? $_POST['ident'] : '';
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
if ($idCotizacion == '') {
  errorBD('Faltan datos Obligatorios, Notifica al Administrador.');
} else {
  $sql = "DELETE cot, dcot, correo
    FROM cotizaciones cot
    INNER JOIN detcotizaciones dcot ON cot.id=dcot.idCotizacion
    LEFT JOIN detcotcorreos correo ON cot.id=correo.idCotizacion
    WHERE cot.id='$idCotizacion'";
  //----------------devBug------------------------------
  if ($debug == 1) {
    $resultXquery = mysqli_query($link, $sql) or die(errorBD('Problemas al Cancelar Cotizaciones, notifica a tu Administrador', mysqli_error($link)));
    $canInsert = mysqli_affected_rows($link);
    echo '<br>SQL: ' . $sql . '<br>';
    echo '<br>Cant de Registros Cargados: ' . $canInsert;
  } else {
    $resultXquery = mysqli_query($link, $sql) or die(errorBD('Problemas al Cancelar Cotizaciones, notifica a tu Administrador', mysqli_error($link)));
    $canInsert = mysqli_affected_rows($link);
  } //-------------Finaliza devBug------------------------------
  $_SESSION['LZmsjSuccessCotizacion'] = 'Se ha eliminado la Cotización';

  echo '1|Se ha eliminado la Cotización.';
}

function errorBD($error)
{
  echo '0|' . $error;
  exit(0);
}
