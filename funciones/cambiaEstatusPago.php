<?php
session_start();

define('INCLUDE_CHECK',1);
require_once('../include/connect.php');
//require_once('../assets/scripts/cadenas.php');

$idPro = (isset($_POST['ident'])) ? $_POST['ident'] : '' ;
$estatus = (isset($_POST['estatus'])) ? $_POST['estatus'] : '' ;
$txt = '';
//print_r($_POST);

if ($idPro == '' && $estatus == '') {
  errorBD('Faltan datos Obligatorios, Notifica al Administrador.');

} else {

  $sql="SELECT * FROM sat_formapago WHERE id = $idPro ";
  $result=mysqli_query($link,$sql) or die('0|Problemas al guardar los Datos. '.mysqli_error($link));
  $cantRes = mysqli_num_rows($result);

  if ($cantRes >= 1) {
    $sql="UPDATE sat_formapago SET estatus=$estatus WHERE id = $idPro ";
    //echo $sql.'<br>';
    $result=mysqli_query($link,$sql) or die('0|Problemas al actualizar los Datos. '.mysqli_error($link));
    //$txt = ' y se Desasignan los Usuarios, asignalos de Nuevo';
  }

//  $sql="DELETE FROM asignaconveh WHERE id = '$idAsig' ";
  //echo $sql.'<br>';
//  $result=mysqli_query($link,$sql) or die('0|Problemas al guardar los Datos. '.mysqli_error($link));
  if($estatus==0){
    echo '1|Se ha Deshabilitado Correctamente el Método de Pago '.$txt.'.';
  }else{
    echo '1|Se ha Habilitado Correctamente el Método de Pago '.$txt.'.';

  }
}


function errorBD($error){
echo '0|'.$error;
exit(0);
}
?>
