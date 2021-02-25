<?php
session_start();
define('INCLUDE_CHECK', 1);
require_once('../include/connect.php');
$sucursal = $_SESSION['LZFidSuc'];
$userReg = $_SESSION['LZFident'];
$idCotizacion = (isset($_POST['idCotizacion']) and $_POST['idCotizacion'] != '') ? $_POST['idCotizacion'] : '';
$dias = (isset($_POST['dias']) and $_POST['dias'] != '') ? $_POST['dias'] : '';
$debug = 0;

  $sql = "UPDATE cotizaciones SET cantPeriodo='$dias' WHERE id='$idCotizacion'";

  //----------------devBug------------------------------
  if ($debug == 1) {
    $resultXquery = mysqli_query($link, $sql) or die(errorBD('Problemas al guardar Vigencias en Cotizaciones, notifica a tu Administrador', mysqli_error($link)));
    $canInsert = mysqli_affected_rows($link);
    echo '<br>SQL: ' . $sql . '<br>';
    echo '<br>Cant de Registros Cargados: ' . $canInsert;
  } else {
    $resultXquery = mysqli_query($link, $sql) or die(errorBD('Problemas al guardar Vigencias en Cotizaciones, notifica a tu Administrador', mysqli_error($link)));
    $canInsert = mysqli_affected_rows($link);
  } //-------------Finaliza devBug------------------------------


$_SESSION['LZmsjSuccessCotizacion'] = 'La Vigencia se ha Seleccionado.';

echo '1|La Vigencia se ha Seleccionado.';
function errorBD($error){
  echo '0|'.$error;
    exit(0);
 }
 ?>
