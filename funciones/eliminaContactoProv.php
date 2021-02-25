<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$id = (!empty($_POST['idContacto'])) ? $_POST['idContacto'] : 0 ;

if ($id == 0) {
  errorBD('No se reconoció el Contacto, vuelve a intentarlo, si persiste notifica a tu Administrador');
}

$sql="DELETE FROM contactosprov WHERE id = $id LIMIT 1";
$res = mysqli_query($link,$sql) or die(errorBD('Problemas al eliminar el Contacto, notifica a tu Administrador'));

echo '1|Se ha eliminado Correctamente.';

function errorBD($error){
  echo '0|'.$error;
}
 ?>
