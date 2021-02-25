<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$id = (!empty($_POST['idProv'])) ? $_POST['idProv'] : 0 ;
$estatus = (!empty($_POST['estatus'])) ? $_POST['estatus'] : 0 ;

if ($id == 0) {
  errorBD('No se reconoció el Proveedor, vuelve a intentarlo, si persiste notifica a tu Administrador');
}

if ($estatus == 0) {
  errorBD('No se reconoció el estatus del Proveedor, vuelve a intentarlo, si persiste notifica a tu Administrador');
}

$newEstatus = ($estatus == 1) ? 2 : 1 ;

$sql = "UPDATE proveedores SET estatus = '$newEstatus' WHERE id = '$id'";
$res = mysqli_query($link,$sql) or die(errorBD('Problemas al actualizar el estatus del Proveedor, notifica a tu Administrador'));

$botonEstatus = ($newEstatus == 1) ? '<a class="text-success"><i class="fas fa-check"></i></a>' : '<a class="text-danger"><i class="fas fa-times"></i></a>';

$boton = ($newEstatus == 1) ? '<a href="javascript:void(0);" onclick="cambiaEstatus(\'activar-btn'.$id.'\', '.$id.','.$newEstatus.');" class="text-danger"><i class="fas fa-trash"></i></a>&nbsp;&nbsp;' : '<a href="javascript:void(0);" onclick="cambiaEstatus(\'activar-btn'.$id.'\', '.$id.','.$newEstatus.');" class="text-warning"><i class="fas fa-exchange-alt"></i></a>&nbsp;&nbsp;' ;

echo '1|El estatus se ha cambiado correctamente.|'.$botonEstatus.'|'.$boton;

function errorBD($error){
  echo '0|'.$error;
}
 ?>
