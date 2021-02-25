<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$idDeposito = (!empty($_REQUEST['idDeposito'])) ? $_REQUEST['idDeposito'] : 0 ;
$valor = (!empty($_REQUEST['valor'])) ? $_REQUEST['valor'] : 0 ;
$motivo = (isset($_REQUEST['motivo']) && $_REQUEST['motivo'] != '') ? $_REQUEST['motivo'] : '' ;

$userReg = $_SESSION['LZFident'];
/*
echo '<br>#############################################################################<br>';
echo '<br>$_REQUEST: ';
print_r($_REQUEST);
echo '<br>$idDeposito: '.$idDeposito;
echo '<br>$valor: '.$valor;
echo '<br>$motivo: '.$motivo;
echo '<br>$userReg: '.$userReg;
echo '<br>#############################################################################<br>';
#*/

if ($idDeposito < 1) {
#  echo '<br>Entro en error, linea 20';
  errorBD('No se reconoció el depósito, actualiza e inténtalo nuevamente, si el problema persiste notifica a tu Administrador.');
}

if ($valor == 1 || $valor == 3) {

$sqlCon = "SELECT * FROM depositos WHERE id = $idDeposito LIMIT 1";
$resCon = mysqli_query($link,$sqlCon) or die('Problemas al consultar los depósitos, notifica a tu Administrador.');
$d = mysqli_fetch_array($resCon);
if ($d['estatus'] == 1) {
  errorBD('No se puede modificar el estatus del depósito porque ya fue autorizado, notifica a tu Administrador.');
}

if ($valor == 1) {
  $msn = 'Depósito autorizado satisfactoriamente.';
} else {
  $msn = 'Depósito rechazado satisfactoriamente.';
}

$sql = "UPDATE depositos d INNER JOIN detdepositos dd ON d.id = dd.idDepositoRecoleccion SET d.estatus = '$valor', d.motivo = '$motivo', dd.estatus = '$valor' WHERE d.id = '$idDeposito'";
#echo '<br>$sql: '.$sql;
$res = mysqli_query($link,$sql) or die(errorBD('Problemas al actualizar el registro, notifica a tu Administrador.'));

#echo '<br>$msn: '.$msn;
#echo '<br>Redirige a autorización de depósitos';
  $_SESSION['LZFmsjSuccessCorporativoDepositos'] = $msn;
  header ('location: ../Corporativo/depositos.php');
  exit(0);
} else {
#  echo '<br>Entro en error, linea 43';
  errorBD('No se reconoció la autorización del Depósito, actualiza e inténtalo nuevamente, si el problema persiste notifica a tu Administrador.');
}

exit(0);

function errorBD($error){
#    echo '<br>$error: '.$error;
    $_SESSION['LZFmsjCorporativoDepositos'] = $error;
    header ('location: ../Corporativo/depositos.php');
    exit(0);
}
 ?>
