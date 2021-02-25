<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$idTraspaso = (!empty($_POST['idTraspaso'])) ? $_POST['idTraspaso'] : 0 ;
$tipo = (!empty($_POST['tipo'])) ? $_POST['tipo'] : 1 ;

if ($idTraspaso < 1 || $tipo < 1) {
  errorBD('No se reconoció el Traspaso, vuelve a intentarlo, si persiste notifica a tu Administrador');
}
# tipo == 1  ===> cierra la solicitud para que se pueda ver
# tipo == 2  ===> Cancela la solicitud y todo lo relacionado a ella

$sqlCon = "SELECT estatus FROM traspasos WHERE id = '$idTraspaso' LIMIT 1";
$resCon = mysqli_query($link,$sqlCon) or die(errorBD('Problemas al consultar los traspasos, notifica a tu Administrador'));
$dat = mysqli_fetch_array($resCon);

if ($dat['estatus'] > 1) {
  errorBD('El taspaso ya fue enviado o cancelado, verifica en la vista de traspasos.');
}

if ($tipo == 1) {
  #comienza con el cierre de la solicitud
  $sql = "UPDATE traspasos tp
          INNER JOIN solicitudestrasp stp ON tp.idSolicitud = stp.id
          SET stp.estatus = 2
          WHERE tp.id = '$idTraspaso'";
  $res = mysqli_query($link,$sql) or die(errorBD('Problemas al enviar la solicitud del Traspaso, notifica a tu Administrador'.$sql));
  echo '1|Solicitud cerrada y enviada.';
} else {
  #comienza con la cancelación de la solicitud
  $sql = "UPDATE traspasos tp
          INNER JOIN solicitudestrasp stp ON tp.idSolicitud = stp.id
          SET tp.estatus = 4, stp.estatus = 3
          WHERE tp.id = '$idTraspaso'";
  $res = mysqli_query($link,$sql) or die(errorBD('Problemas al cancelar la solicitud del Traspaso, notifica a tu Administrador'.$sql));
  echo '1|Solicitud cancelada.';
}

exit(0);
function errorBD($error){
  echo '0|'.$error;
  exit(0);
}
 ?>
