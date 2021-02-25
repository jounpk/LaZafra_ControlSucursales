<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$idCorte = (!empty($_POST['idCorte'])) ? $_POST['idCorte'] : 0 ;
$idUser = (!empty($_POST['idUser'])) ? $_POST['idUser'] : 0 ;
$idSucursal = (!empty($_POST['idSucursal'])) ? $_POST['idSucursal'] : 0 ;

$sqlCon = "SELECT estatus FROM cortes WHERE id = $idCorte";
$resCon = mysqli_query($link,$sqlCon) or die(errorBD('Problemas al consultar los cortes, notifica a tu Administrador.'));
$c = mysqli_fetch_array($resCon);
$estatus = $c['estatus'];
if ($estatus > 1) {
  errorBD('No se realizó el recalcular porque el corte ya fue cerrado');
}
/*
echo '<br> ############################################# <br>';
echo '<br>Print:<br>';
print_r($_POST);
echo '<br>$idCorte: '.$idCorte;
echo '<br>$idUser: '.$idUser;
echo '<br>$idSucursal: '.$idSucursal;
echo '<br> ############################################# <br>';
exit(0);
*/
$sqlRecalcular = "CALL SP_recalcularCorte('$idCorte','$idUser','$idSucursal')";
$resCierraCorte = mysqli_query($link,$sqlRecalcular) or die(errorBD('Problemas al realizar el recalcular, notifica a tu Administrador.'));
$_SESSION['LZFmsjSuccessCierreDeCorte'] = 'Se ha realizado el recalculado correctamente.';
header('location: ../cierreDeCorte.php');
exit(0);

function errorBD($error){
#  echo '<br>Sucedió un error: '.$error;
  $_SESSION['LZFmsjCierreDeCorte'] = $error;
  header('location: ../cierreDeCorte.php');
}
 ?>
