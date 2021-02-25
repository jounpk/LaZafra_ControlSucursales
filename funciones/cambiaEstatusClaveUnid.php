<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$idUni = (isset($_POST['idUnid']) && $_POST['idUnid'] != '') ? $_POST['idUnid'] : '' ;
$estatus = (!empty($_POST['estatus'])) ? $_POST['estatus'] : 0 ;

if ($idUni == '') {
  errorBD('No se reconoció la Clave de la Unidad, vuelve a intentarlo, si persiste notifica a tu Administrador');
}

if ($estatus == 0) {
  errorBD('No se reconoció el estatus de la Clave de la Unidad, vuelve a intentarlo, si persiste notifica a tu Administrador');
}

$newEstatus = ($estatus == 1) ? 2 : 1 ;

$sql = "UPDATE sat_claveunidad SET estatus = '$newEstatus' WHERE id = '$idUni'";
$res = mysqli_query($link,$sql) or die(errorBD('Problemas al actualizar el estatus de la Clave de la Unidad, notifica a tu Administrador'));

$iconUni = ($newEstatus == 1) ? '<a class="text-success"><i class="fas fa-check"></i></a>' : '<a class="text-danger"><i class="fas fa-times"></i></a>';

$btnUni = ($newEstatus == 1) ? '<button class="btn btn-outline-danger" title="Deshabilitar" onclick="cambiaEstatusUni(\''.$idUni.'\', '.$newEstatus.');"><i class="fas fa-trash"></i></button>' : '<button class="btn btn-outline-warning" title="Habilitar" onclick="cambiaEstatusUni('.$idUni.', '.$newEstatus.');"><i class="fas fa-exchange-alt"></i></button>' ;

echo '1|El estatus se ha cambiado correctamente.|'.$iconUni.'|'.$btnUni.'|'.$newEstatus;

function errorBD($error){
  echo '0|'.$error;
}
 ?>
