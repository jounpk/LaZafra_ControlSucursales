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

  $sql="SELECT * FROM traspasos WHERE id= $ident ";
  $result=mysqli_query($link,$sql) or die('0|Problemas al recuperar los Datos. ');
  $cantRes = mysqli_num_rows($result);

  if ($cantRes >= 1) {
    $dat= mysqli_fetch_array($result);
   
      $sql="UPDATE traspasos SET estatus='4' WHERE id = $ident ";
    
    $result=mysqli_query($link,$sql) or die('0|Problemas al actualizar los Datos. ');
    echo '1|'."Se canceló el traspaso seleccionado.";
  }else{
    errorBD('No se encontró el registro seleccionado, intentelo de nuevo o Notifique al Administrador');
  }
}

function errorBD($error){
  echo '0|'.$error;
  exit(0);
  }
?>