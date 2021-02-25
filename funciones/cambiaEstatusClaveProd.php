<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$id = (!empty($_POST['idProd'])) ? $_POST['idProd'] : 0 ;
$estatus = (!empty($_POST['estatus'])) ? $_POST['estatus'] : 0 ;

if ($id == 0) {
  errorBD('No se reconoció la Clave del Producto, vuelve a intentarlo, si persiste notifica a tu Administrador');
}

if ($estatus == 0) {
  errorBD('No se reconoció el estatus de la Clave del Producto, vuelve a intentarlo, si persiste notifica a tu Administrador');
}

$newEstatus = ($estatus == 1) ? 2 : 1 ;

$sql = "UPDATE sat_claveproducto SET estatus = '$newEstatus' WHERE codigo = '$id'";
$res = mysqli_query($link,$sql) or die(errorBD('Problemas al actualizar el estatus de la Clave del Producto, notifica a tu Administrador'));

$iconProd = ($newEstatus == 1) ? '<a class="text-success"><i class="fas fa-check"></i></a>' : '<a class="text-danger"><i class="fas fa-times"></i></a>';

$btnProd = ($newEstatus == 1) ? '<button class="btn btn-outline-danger" title="Deshabilitar" onclick="cambiaEstatusProd('.$id.', '.$newEstatus.');"><i class="fas fa-trash"></i></button>' : '<button class="btn btn-outline-warning" title="Habilitar" onclick="cambiaEstatusProd('.$id.', '.$newEstatus.');"><i class="fas fa-exchange-alt"></i></button>' ;

echo '1|El estatus se ha cambiado correctamente.|'.$iconProd.'|'.$btnProd.'|'.$newEstatus;

function errorBD($error){
  echo '0|'.$error;
}
 ?>
