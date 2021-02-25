<?php
session_start();

define('INCLUDE_CHECK',1);
require_once('../include/connect.php');
//require_once('../assets/scripts/cadenas.php');

$idGsto = (isset($_POST['ident'])) ? $_POST['ident'] : '' ;
//print_r($_POST);

if ($idGsto == '') {
  errorBD('Faltan datos Obligatorios, Notifica al Administrador.');

} else {

  $sql="SELECT id FROM pagos WHERE id = $idGsto ";
  $result=mysqli_query($link,$sql) or die('0|Problemas al consultar los Datos. ');
  $cantRes = mysqli_num_rows($result);

  if ($cantRes >= 1) {
    $resarray=mysqli_fetch_array($result);
    $name=$resarray['descripcion'];
    $sql="DELETE FROM pagos WHERE id = $idGsto";
   // echo $sql.'<br>';
    $result=mysqli_query($link,$sql) or die('0|Problemas al eliminar los Datos. ');
    echo '1|Se ha Eliminado Correctamente el Pago.';

  }else{
    errorBD('No se encontró el registro seleccionado, intentelo de nuevo o Notifique al Administrador');
  }


  
}


function errorBD($error){
echo '0|'.$error;
exit(0);
}
?>
