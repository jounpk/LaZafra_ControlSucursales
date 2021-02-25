<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$ident = (isset($_POST['ident'])) ? $_POST['ident'] : '' ;
$txt = '';
$userReg=$_SESSION['LZFident'];

//print_r($_POST);
if ($ident == '') {
  errorBD('Faltan datos Obligatorios, Notifica al Administrador.');

} else {

  $sql="SELECT * FROM ajustes WHERE id= $ident ";
  $result=mysqli_query($link,$sql) or die('0|Problemas al recuperar los Datos. ');
  $cantRes = mysqli_num_rows($result);

  if ($cantRes >= 1) {
    $dat= mysqli_fetch_array($result);
   
      $sql="
      DELETE aj, det
      FROM ajustes aj
      JOIN detajustes det ON aj.id = det.idAjuste
      WHERE aj.id=$ident";
    
    $result=mysqli_query($link,$sql) or die('0|Problemas al borrar los Datos. ');
    echo '1|'."Se canceló el ajuste seleccionado.";
  }else{
    errorBD('No se encontró el registro seleccionado, intentelo de nuevo o Notifique al Administrador');
  }
}

function errorBD($error){
  echo '0|'.$error;
  exit(0);
  }
?>