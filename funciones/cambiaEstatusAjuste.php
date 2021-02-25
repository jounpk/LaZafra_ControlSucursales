<?php
session_start();

define('INCLUDE_CHECK',1);
require_once('../include/connect.php');
//require_once('../assets/scripts/cadenas.php');

$idDetAjuste = (isset($_POST['ident'])) ? $_POST['ident'] : '' ;
$txt = '';
//print_r($_POST);

if ($idDetAjuste == '') {
  errorBD('Faltan datos Obligatorios, Notifica al Administrador.');

} else {

  $sql="SELECT tipo FROM detajustes WHERE id = $idDetAjuste ";
  $result=mysqli_query($link,$sql) or die('0|Problemas al guardar los Datos. '.mysqli_error($link));
  $cantRes = mysqli_num_rows($result);
 
  if ($cantRes >= 1) {
    $tipoBD= mysqli_fetch_array($result);
    $tipo=($tipoBD['tipo']=='1') ? '2':'1';
    $sql="UPDATE detajustes SET tipo=$tipo WHERE id = $idDetAjuste ";
    //echo $sql.'<br>';
    $result=mysqli_query($link,$sql) or die('0|Problemas al actualizar los Datos. '.mysqli_error($link));
    //$txt = ' y se Desasignan los Usuarios, asignalos de Nuevo';
  }
  else{
    errorBD('No se encontró el registro seleccionado, intentelo de nuevo o Notifique al Administrador');
  }

//  $sql="DELETE FROM asignaconveh WHERE id = '$idAsig' ";
  //echo $sql.'<br>';
//  $result=mysqli_query($link,$sql) or die('0|Problemas al guardar los Datos. '.mysqli_error($link));
  if($tipo==1){
    echo '1|Se agregó producto a Ajustes Entradas.'.$sql;
  }else{
    echo '1|Se agregó producto a Ajustes Salidas.'.$sql;

  }
}


function errorBD($error){
echo '0|'.$error;
exit(0);
}
?>
