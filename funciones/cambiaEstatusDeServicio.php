<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$id = (!empty($_POST['idServicio'])) ? $_POST['idServicio'] : 0 ;
$estatus = (!empty($_POST['estatus'])) ? $_POST['estatus'] : 0 ;

if ($id == 0) {
  errorBD('No se reconoció el Servicio, vuelve a intentarlo, si persiste notifica a tu Administrador');
}

if ($estatus == 0) {
  errorBD('No se reconoció el estatus del Servicio, vuelve a intentarlo, si persiste notifica a tu Administrador');
}

$newEstatus = ($estatus == 1) ? 2 : 1 ;

$sql = "UPDATE catservicios SET estatus = '$newEstatus' WHERE id = '$id'";
$res = mysqli_query($link,$sql) or die(errorBD('Problemas al actualizar el estatus del Servicio, notifica a tu Administrador'));

$boton = ($newEstatus == 2) ? '<button class="btn btn-outline-warning" title="Habilitar" onclick="cambiaEstatus('.$id.','.$newEstatus.');" id="btnEstatus-'.$id.'"><i class="fas fa-exchange-alt"></i></button>' : '<button class="btn btn-outline-danger" title="Deshabilitar" onclick="cambiaEstatus('.$id.','.$newEstatus.');" id="btnEstatus-'.$id.'"><i class="fas fa-trash"></i></button>';

$botonEstatus = ($newEstatus == 1) ? '<a href="javascript:void(0);" class="text-success"><i class="fas fa-check"></i></a>' : '<a href="javascript:void(0);" class="text-danger"><i class="fas fa-times"></i></a>' ;

echo '1|El estatus se ha cambiado correctamente.|'.$boton.'|'.$botonEstatus.'|'.$newEstatus;

function errorBD($error){
  echo '0|'.$error;
}
 ?>
