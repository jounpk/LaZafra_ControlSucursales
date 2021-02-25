<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$idTraspaso = (!empty($_REQUEST['idTraspaso'])) ? $_REQUEST['idTraspaso'] : 0 ;
$tipo = (!empty($_REQUEST['tipo'])) ? $_REQUEST['tipo'] : 0 ;
$userReg = $_SESSION['LZFident'];
/*
echo '<br>#############################################################################<br>';
echo '<br>$_REQUEST: ';
print_r($_REQUEST);
echo '<br>$idTraspaso: '.$idTraspaso;
echo '<br>$tipo: '.$tipo;
echo '<br>#############################################################################<br>';
#*/
if ($idTraspaso == 0) {
  errorBD('No se reconoció el traspaso, vuelve a intentarlo, si persiste notifica a tu Administrador',$tipo);
}

if ($tipo == 0) {
  errorBD('No se reconoció el tipo, vuelve a intentarlo, si persiste notifica a tu Administrador',$tipo);
}
# el tipo es para cambiar el estatus del envío del producto por el bodeguero
if ($tipo == 1) {
  $estatus = 1;
  $msn = 'Se ha capturado la carga del traspaso correctamente.';
} else {
  $estatus = 2;
  $msn = 'Se ha capturado el envío del traspaso correctamente';
}

$sql ="UPDATE traspasos SET estatusBodega = '$estatus', fechaBodega = NOW(), idUserBodega = '$userReg' WHERE id = '$idTraspaso' LIMIT 1";
$res = mysqli_query($link,$sql) or die(errorBD('Problemas al actualizar la carga del traspaso, notifica a tu Administrador.',$tipo));

if ($tipo == 2) {
  $btn = '<a href="imprimeTicketTraspaso.php?idTraspaso='.$idTraspaso.'&cat=1" target="_blank" class="btn btn-circle-small muestraSombra btn-info"><i class="fas fa-print"></i></a>';
  echo '1|Se ha capturado el envío del producto|'.$btn;
  exit(0);
} else {
  $_SESSION['LZFmsjSuccessBodeguero'] = $msn;
  header ('location: ../funciones/ticketLanzaTraspaso.php?idTraspaso='.$idTraspaso.'&cat=1');
  exit(0);
}

exit(0);

function errorBD($error,$tipo){
  if ($tipo == 2) {
    echo '0|'.$error;
    exit(0);
  } else {
#    echo '<br>$error: '.$error;
    $_SESSION['LZFmsjBodeguero'] = $error;
    header ('location: ../Bodega/bodega.php');
    exit(0);
  }
}
 ?>
