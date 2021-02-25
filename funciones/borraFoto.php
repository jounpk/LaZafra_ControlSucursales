<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');
$ident=(isset($_POST['idProducto'])) ? trim($_POST['idProducto']) : '' ;
$sucursal=$_SESSION['LZFidSuc'];
$userReg=$_SESSION['LZFident'];
$debug = 0;

//----------------devBug------------------------------
if ($debug == 1) {
  print_r($_POST);
  echo '<br><br>';
} else {
  error_reporting(0);
}  //-------------Finaliza devBug------------------------------

if ($ident=='') {
  errorBD('No se Reconoció Al Producto, actualiza e intenta otra vez, si persiste notifica a tu Administrador');
}
$sql="UPDATE productos SET foto='' WHERE id='$ident'";


//----------------devBug------------------------------
if ($debug == 1) {
  $resultXquery=mysqli_query($link,$sql) or die(errorBD('Problemas al Eliminar Fotos, notifica a tu Administrador', mysqli_error($link)));
  $canInsert = mysqli_affected_rows($link);
  echo '<br>SQL: '.$sql.'<br>';
  echo '<br>Cant de Registros Cargados: '.$canInsert;
} else {
  $resultXquery=mysqli_query($link,$sql) or die(errorBD('Problemas al Eliminar Fotos, notifica a tu Administrador', mysqli_error($link)));
  $canInsert = mysqli_affected_rows($link);
} //-------------Finaliza devBug------------------------------
echo "1|Eliminación Correcto";
$_SESSION['LZmsjSuccessProducto']="Eliminación Correcto";

//$_SESSION['ADCmsjSuccessAsig']="Eliminación de Asignación Correcto";
//$_SESSION['ADCmsjSuccessAsig']="Eliminación de Asignación Correcto";
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