<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$idCorte = (!empty($_POST['idCorte'])) ? $_POST['idCorte'] : 0 ;
$idUser = $_SESSION['LZFident'];
$idSucursal = $_SESSION['LZFidSuc'];

$sqlCon = "SELECT estatus FROM cortes WHERE idSucursal = '$idSucursal' AND idUserReg = '$idUser'";
$resCon = mysqli_query($link,$sqlCon) or die(errorBD('Problemas al consultar los cortes, notifica a tu Administrador.'));
$c = mysqli_fetch_array($resCon);
$estatus = $c['estatus'];
if ($idCorte < 0) {
  errorBD('El corte ya fue cerrado, no se puede hacer cambios a un corte cerrado');
}
/*
echo '<br> ############################################# <br>';
echo '<br>Print:<br>';
print_r($_POST);
echo '<br>$idCorte: '.$idCorte;
echo '<br>$idUser: '.$idUser;
echo '<br>$idSucursal: '.$idSucursal;
$sqlCierraCorte = "CALL SP_cierraCorte('$idCorte','$idUser','$idSucursal')";
echo '<br>$sqlCierraCorte: '.$sqlCierraCorte;
echo '<br> ############################################# <br>';
exit(0);
#*/
$sqlCierraCorte = "CALL SP_cierraCorte('$idCorte','$idUser','$idSucursal')";
$resCierraCorte = mysqli_query($link,$sqlCierraCorte) or die(errorBD(mysqli_error($link)));

header('location: ../cierreDeCorte.php');
exit(0);

function errorBD($error){
#  echo '<br>Sucedió un error: '.$error;
  $_SESSION['LZFmsjInicioCaja'] = $error;
  header('location: ../home.php');
}
 ?>
