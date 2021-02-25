<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$id = (!empty($_POST['idUsuario'])) ? $_POST['idUsuario'] : 0 ;
$estatus = (!empty($_POST['estatus'])) ? $_POST['estatus'] : 0 ;

if ($id == 0) {
  errorBD('No se reconoció el Usuario, vuelve a intentarlo, si persiste notifica a tu Administrador');
}

if ($estatus == 0) {
  errorBD('No se reconoció el estatus del Usuario, vuelve a intentarlo, si persiste notifica a tu Administrador');
}

$newEstatus = ($estatus == 1) ? 2 : 1 ;

$sql = "UPDATE segusuarios SET estatus = '$newEstatus' WHERE id = '$id'";
$res = mysqli_query($link,$sql) or die(errorBD('Problemas al actualizar el estatus del Usuario, notifica a tu Administrador'));

$iconEstatus = ($newEstatus == 1) ? '<a class="text-success"><i class="fas fa-check"></i></a>' : '<a class="text-danger"><i class="fas fa-times"></i></a>' ;

$btnCambiaEstatus = ($newEstatus == 1) ? '<button class="btn btn-outline-danger" title="Deshabilitar" onclick="cambiaEstatus('.$id.', '.$newEstatus.')"><i class="fas fa-trash"></i></button>' : '<button class="btn btn-outline-warning" title="Habilitar" onclick="cambiaEstatus('.$id.', '.$newEstatus.')"><i class="fas fa-exchange-alt"></i></button>' ;

echo '1|El estatus se ha cambiado correctamente.|'.$btnCambiaEstatus.'|'.$newEstatus.'|'.$iconEstatus;

function errorBD($error){
  echo '0|'.$error;
}
 ?>
