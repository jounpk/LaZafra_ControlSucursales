<?php
session_start();

define('INCLUDE_CHECK',1);
require_once('../include/connect.php');
//require_once('../assets/scripts/cadenas.php');

$idPro = (isset($_POST['ident'])) ? $_POST['ident'] : '' ;
$cant = (isset($_POST['precio'])) ? $_POST['precio'] : '' ;
$txt = '';
//print_r($_POST);

if ($idPro == '') {
  errorBD('Faltan datos Obligatorios, Notifica al Administrador.');

} elseif ($cant == '' OR $cant == 0) {
  errorBD('Debes ingresar un precio.');

} else {

  $sql="SELECT * FROM preciosbase WHERE id = $idPro ";
  $result=mysqli_query($link,$sql) or die('0|No encontramos el Producto. '.mysqli_error($link));
  $cantRes = mysqli_num_rows($result);

  if ($cantRes >= 1) {
    $sql="UPDATE preciosbase SET precio=$cant WHERE id = $idPro ";
    //echo $sql.'<br>';
    $result=mysqli_query($link,$sql) or die('0|Problemas al actualizar los Datos. '.mysqli_error($link));
    //$txt = ' y se Desasignan los Usuarios, asignalos de Nuevo';
    $pr = number_format($cant,2,'.',',');
    echo '1|'.$pr;
  }
  else{
    errorBD('No se encontró el registro seleccionado, intentelo de nuevo o Notifique al Administrador');
  }

}


function errorBD($error){
echo '0|'.$error;
exit(0);
}
?>
