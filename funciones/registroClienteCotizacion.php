<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');
$nameCliente= (isset($_POST['nameCliente'])) ? trim($_POST['nameCliente']) : '' ;
$userReg=$_SESSION['LZFident'];
$debug = 0;
$sucursal = $_SESSION['LZFidSuc'];
//----------------devBug------------------------------
if ($debug == 1) {
  print_r($_POST);
  echo '<br><br>';
} else {
  error_reporting(0);
}  //-------------Finaliza devBug------------------------------

if ($nameCliente == '') {
    errorBD('No se reconoció  el nombre del Cliente, actualiza e intenta otra vez, si persiste notifica a tu Administrador', '');
  }


$sql="INSERT INTO cotizaciones (idUserReg, fechaReg, estatus, nameCliente, tipo, idSucursal) VALUES('$userReg',NOW(),'1','$nameCliente','2', '$sucursal')";
//----------------devBug------------------------------
if ($debug == 1) {
  $resultXquery=mysqli_query($link,$sql) or die(errorBD('Problemas al Iniciar Cotizaciones, notifica a tu Administrador', mysqli_error($link)));
  $canInsert = mysqli_affected_rows($link);
  echo '<br>SQL: '.$sql.'<br>';
  echo '<br>Cant de Registros Cargados: '.$canInsert;
} else {
  $resultXquery=mysqli_query($link,$sql) or die(errorBD('Problemas al Iniciar Cotizaciones, notifica a tu Administrador', mysqli_error($link)));
  $canInsert = mysqli_affected_rows($link);
} //-------------Finaliza devBug------------------------------
echo "1|Registro de Nueva Cotización Exitosa";
$_SESSION['LZmsjSuccessCotizacion']="Registro de Nueva Cotización Exitosa";
function errorBD($error, $sql_error){
  if ($GLOBALS['debug'] == 1) {
    echo '<br><br>Det Error: '.$error;
    echo '<br><br>Error Report: '.$sql_error;

  } else {
    echo '0|'.$error;
  }
  exit(0);
  }

?>