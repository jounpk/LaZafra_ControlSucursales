<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$idLote = (!empty($_POST['idLote'])) ? $_POST['idLote'] : 0 ;
$caducidad = (!empty($_POST['caducidad'])) ? $_POST['caducidad'] : 0 ;
/*
echo '<br>#####################################################<br>';
echo '<br>$_POST: <br>';
print_r($_POST);
echo '<br><br>$idLote: '.$idLote;
echo '<br>$caducidad: '.$caducidad;
echo '<br>#####################################################<br>';
exit(0);
#*/
if ($idLote < 1) {
  errorBD('No se reconoció el Lote, actualiza e inténtalo de nuevo, si persiste notifica a tu Administrador.');
}

$sql = "UPDATE lotestocks SET caducidad = '$caducidad' WHERE id = '$idLote' LIMIT 1";
$res = mysqli_query($link,$sql) or die(errorBD('Problemas al actualizar la Fecha de Caducidad del Lote, notifica a tu Administrador.'));

$_SESSION['LZFmsjSuccessLotesStock'] = 'La caducidad se ha modificado correctamente';
header('location: ../Corporativo/lotesProductos.php');
exit(0);

function errorBD($error){
  $_SESSION['LZFmsjLotesStock'] = $error;
  header('location: ../Corporativo/lotesProductos.php');
  exit(0);
}
 ?>
