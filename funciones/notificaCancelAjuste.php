<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$ident = (isset($_POST['ident'])) ? $_POST['ident'] : '' ;
$userReg=$_SESSION['LZFident'];
if ($ident == '') {
  errorBD('Faltan datos Obligatorios, Notifica al Administrador.');

} else {

  $sql="SELECT id FROM ajustes WHERE id=$ident";
  $result=mysqli_query($link,$sql) or die('0|Problemas al recuperar los Datos. ');
  $cantRes = mysqli_num_rows($result);

  if ($cantRes >= 1) {
    $dat= mysqli_fetch_array($result);
       
      $sql="UPDATE ajustes SET estatus='5', idUserAplica='$userReg', fechaAplica=NOW() WHERE id = $ident ";
    
    $result=mysqli_query($link,$sql) or die('0|Problemas al actualizar los Datos. ');

      echo '1|Haz notificado correctamente la cancelación de tu solicitud de Ajustes.';
  
    
  }else{
    errorBD('No se encontró el registro seleccionado, intentelo de nuevo o Notifique al Administrador');
  }
}

function errorBD($error){
  echo '0|'.$error;
  exit(0);
  }
?>
