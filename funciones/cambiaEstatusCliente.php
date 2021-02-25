<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$id = (!empty($_POST['idCliente'])) ? $_POST['idCliente'] : 0 ;
$estatus = (!empty($_POST['estatus'])) ? $_POST['estatus'] : 0 ;

if ($id == 0) {
  errorBD('No se reconoció el Cliente, vuelve a intentarlo, si persiste notifica a tu Administrador');
}

if ($estatus == 0) {
  errorBD('No se reconoció el estatus del Cliente, vuelve a intentarlo, si persiste notifica a tu Administrador');
}

$newEstatus = ($estatus == 1) ? 2 : 1 ;

$sql = "UPDATE clientes SET estatus = '$newEstatus' WHERE id = '$id'";
$res = mysqli_query($link,$sql) or die(errorBD('Problemas al actualizar el estatus del Cliente, notifica a tu Administrador'));

$iconEstatus = ($newEstatus == 1) ? '<a class="text-success"><i class="fas fa-check"></i></a>' : '<a class="text-danger"><i class="fas fa-times"></i></a>' ;

$boton = ($newEstatus == 1) ? '<button class="btn btn-outline-danger" title="Deshabilitar" title="Deshabilitar" onclick="cambiaEstatus('.$id.', '.$newEstatus.')"><i class="fas fa-trash"></i></button>' : '<button class="btn btn-outline-warning" title="Habilitar" title="Habilitar" onclick="cambiaEstatus('.$id.', '.$newEstatus.')"><i class="fas fa-exchange-alt"></i></button>' ;

echo '1|El estatus se ha cambiado correctamente.|'.$boton.'|'.$newEstatus.'|'.$iconEstatus;

function errorBD($error){
  echo '0|'.$error;
}
 ?>
