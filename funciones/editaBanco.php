<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$id = (!empty($_POST['idBanco'])) ? $_POST['idBanco'] : 0 ;
$tarjeta = (!empty($_POST['tarjeta'])) ? $_POST['tarjeta'] : 0 ;
$cheque = (!empty($_POST['cheque'])) ? $_POST['cheque'] : 0 ;

if ($id < 1) {
  errorBD('No se reconoció el Banco a editar, vuelve a intentarlo, si persiste notifica a tu Administrador');
}

$sql = "UPDATE catbancos SET comisionCheque = '$cheque', comisionTarjeta = '$tarjeta' WHERE id = '$id'";
$res = mysqli_query($link,$sql) or die(errorBD('Problemas al actualizar el Banco, notifica a tu Administrador'));
 echo '1|El Banco fue editado exitosamente.';

exit(0);

function errorBD($error){
echo '0|'.$error;
exit(0);
}
 ?>
