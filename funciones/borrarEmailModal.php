<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$idEmail = (!empty($_REQUEST['idEmail'])) ? $_REQUEST['idEmail'] : 0 ;

  if(!ctype_digit($idEmail)) {
      $idEmail = 0;
  }

$sqlCon="SELECT id FROM detcotcorreos WHERE id=".$idEmail;
$resCon = mysqli_query($link,$sqlCon) or die(errorBD('Problemas al consultar los Detalles de Traspasos, notifica a tu Administrador'));
$cant = mysqli_num_rows($resCon);
    
  if($cant < 0) {
      errorCarga('No se encuentra el email, notifica a tu Administrador');
  }

$sql="DELETE FROM detcotcorreos WHERE id = '$idEmail'";
mysqli_query($link,$sql) or die (errorCarga('Error al borrar el producto, notifica a tu Administrador'));

echo '1|el correo se ha eliminado';
exit(0);

function errorBD($error){
    echo '0|'.$error;
      exit(0);
   }
?>
