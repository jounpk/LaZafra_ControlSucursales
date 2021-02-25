<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$id = (!empty($_POST['idBanco'])) ? $_POST['idBanco'] : 0 ;
$estatus = (!empty($_POST['estatus'])) ? $_POST['estatus'] : 0 ;

if ($id == 0) {
  errorBD('No se reconoció el Banco, vuelve a intentarlo, si persiste notifica a tu Administrador');
}

if ($estatus == 0) {
  errorBD('No se reconoció el estatus del Banco, vuelve a intentarlo, si persiste notifica a tu Administrador');
}

$newEstatus = ($estatus == 1) ? 2 : 1 ;

$sql = "UPDATE catbancos SET estatus = '$newEstatus' WHERE id = '$id'";
$res = mysqli_query($link,$sql) or die(errorBD('Problemas al actualizar el estatus del Banco, notifica a tu Administrador'));

$iEstat = '<input type="hidden" id="estBnk'.$id.'" value="'.$newEstatus.'">';
$iconEstatus = ($estatus == 2) ? '<a href="javascript:void(0);" class="text-success"><i class="fas fa-check"></i></a>'.$iEstat : '<a href="javascript:void(0);" class="text-danger"><i class="fas fa-times"></i></a>'.$iEstat ;
$boton = ($estatus == 2) ? '<button  title="Deshabilitar" class="btn btn-outline-danger" onclick="cambiaEstatus(\''.$id.'\');" id="btnEstatus-'.$id.'"><i class="fas fa-trash"></i></button>' : '<button  title="Habilitar" class="btn btn-outline-warning"  onclick="cambiaEstatus(\''.$id.'\');" id="btnEstatus-'.$id.'"><i class="fas fa-exchange-alt"></i></button>' ;

echo '1|El estatus se ha cambiado correctamente.|'.$boton.'|'.$iconEstatus.'|'.$newEstatus.'|'.$estatus.'|'.$sql;

function errorBD($error){
  echo '0|'.$error;
}
 ?>
