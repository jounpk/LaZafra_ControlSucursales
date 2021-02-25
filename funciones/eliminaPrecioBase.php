<?php
session_start();

define('INCLUDE_CHECK',1);
require_once('../include/connect.php');
//require_once('../assets/scripts/cadenas.php');

$idPrecio = (isset($_POST['ident'])) ? $_POST['ident'] : '' ;
//print_r($_POST);

if ($idPrecio == '') {
  errorBD('Faltan datos Obligatorios, Notifica al Administrador.');

} else {

  $sql="SELECT precio FROM preciosbase WHERE id = $idPrecio ";
  $result=mysqli_query($link,$sql) or die('0|Problemas al consultar los Datos. ');
  $cantRes = mysqli_num_rows($result);

  if ($cantRes >= 1) {
    $resarray=mysqli_fetch_array($result);
    $name=$resarray['precio'];
    $sql="DELETE FROM preciosbase WHERE id = $idPrecio";
   // echo $sql.'<br>';
    $result=mysqli_query($link,$sql) or die('0|Problemas al eliminar los Datos. ');
    echo '1|Se ha Eliminado Correctamente el Precio Base de <b>'.$name.'</b>.';

  }else{
    errorBD('No se encontró el registro seleccionado, intentelo de nuevo o Notifique al Administrador');
  }


  
}


function errorBD($error){
echo '0|'.$error;
exit(0);
}
?>
