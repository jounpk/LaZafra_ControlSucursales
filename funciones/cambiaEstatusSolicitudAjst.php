<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$ident = (isset($_POST['ident'])) ? $_POST['ident'] : '' ;
$estatus = (isset($_POST['estatus'])) ? $_POST['estatus'] : '' ;
$txt = '';
$userReg=$_SESSION['LZFident'];

//print_r($_POST);
if ($ident == '' && $estatus == '') {
  errorBD('Faltan datos Obligatorios, Notifica al Administrador.');

} else {

  $sql="SELECT ajst.descripcion FROM ajustes ajst
   WHERE ajst.id = $ident ";
  $result=mysqli_query($link,$sql) or die('0|Problemas al recuperar los Datos. ');
  $cantRes = mysqli_num_rows($result);

  if ($cantRes >= 1) {
    $dat= mysqli_fetch_array($result);
    $suc=$dat["descripcion"];
    
      $sql="UPDATE ajustes SET estatus=$estatus, fechaDecide=NOW(), idUsuarioDecide=$userReg WHERE id = $ident ";
    
    $result=mysqli_query($link,$sql) or die('0|Problemas al actualizar los Datos. ');
    if($estatus==3){

      echo '1|Se ha Aceptado Correctamente el Ajuste de '.$suc .'.';
    }else{
      echo '1|Se ha Negado el Ajuste de  '.$suc .'.';
  
    }
  }else{
    errorBD('No se encontró el registro seleccionado, intentelo de nuevo o Notifique al Administrador');
  }
}

function errorBD($error){
  echo '0|'.$error;
  exit(0);
  }
?>
