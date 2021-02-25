<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$id = (!empty($_POST['id'])) ? $_POST['id'] : 0 ;

if ($id == 0) {
  errorBD('No se reconoció el producto, vuelve a intentarlo, si persiste notifica a tu Administrador');
}


$sql = "DELETE FROM dettraspasos WHERE id = $id LIMIT 1";
$res = mysqli_query($link,$sql) or die(errorBD('Problemas al actualizar el estatus de la Marca, notifica a tu Administrador'));

echo '1|Producto eliminado de la solicitud.';

function errorBD($error){
  echo '0|'.$error;
}
 ?>
