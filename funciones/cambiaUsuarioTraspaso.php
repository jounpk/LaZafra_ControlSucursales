<?php
session_start();

define('INCLUDE_CHECK',1);
require_once('../include/connect.php');
//require_once('../assets/scripts/cadenas.php');

$ident = (isset($_POST['ident'])) ? $_POST['ident'] : '' ;
$userReg=$_SESSION['LZFident'];

$txt = '';
//print_r($_POST);

if ($ident == '') {
  errorBD('Faltan datos Obligatorios, Notifica al Administrador.');

} else {
  $sql="SELECT suc.nombre FROM traspasos INNER JOIN sucursales suc ON traspasos.idSucEntrada =suc.id WHERE traspasos.id = $ident ";
  //echo $sql;
  $result=mysqli_query($link,$sql) or die('0|Problemas al guardar los Datos. ');
  $cantRes = mysqli_num_rows($result);
  if ($cantRes >= 1) {
    $arrayCon=mysqli_fetch_array($result);
    $nameSuc=$arrayCon["nombre"];
   // echo $cantRes;
   // echo $nameSuc;
    $sql="UPDATE traspasos SET idUserEnvio='$userReg' WHERE id='$ident'";
    $result=mysqli_query($link,$sql) or die('0|Problemas al actualizar los Datos. ');
  }else{
    errorBD('No se encontró el registro seleccionado, intentelo de nuevo o Notifique al Administrador');
  }

  $_SESSION['LZmsjSuccessTraspasos'] = 'Solicitud de <b>'.$nameSuc.'</b> se ha aceptado.';
  header('location: ../Administrador/traspasos.php');
}


function errorBD($msj)
{
  //echo '<br>** Se dispara Error: '.$msj.' **<br>';
  $_SESSION['LZmsjInfoTraspasos'] = $msj;
  header('location: ../Administrador/traspasos.php');
  exit(0);
}
?>
