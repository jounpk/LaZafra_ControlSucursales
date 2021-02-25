<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$ids = (isset($_POST['cadena']) && $_POST['cadena'] != '') ? $_POST['cadena'] : '' ;
$clase = (isset($_POST['clase']) && $_POST['clase'] != '') ? $_POST['clase'] : '' ;
$idSuc = (!empty($_POST['idSucursal'])) ? $_POST['idSucursal'] : 0 ;
$idEntrega = (!empty($_POST['idEntrega'])) ? $_POST['idEntrega'] : 0 ;
$idUser = $_SESSION['LZFident'];
$fecha = date('Y-m-d');
if ($ids == '') {
  errorBD('No se reconoció la selección de los cortes, vuelve a intentarlo, si persiste notifica a tu Administrador');
}

if ($idSuc == '') {
  errorBD('No se reconoció la sucursal que se recolectó, vuelve a intentarlo, si persiste notifica a tu Administrador');
}
$valores = '';
$txt = explode(',',$ids);
$max = sizeof($txt);
for ($i=0; $i < $max ; $i++) {
  $valores .= "('$txt[$i]',NOW(),'$idUser','0'),";
}

$valores = trim($valores,',');

$sql = "INSERT INTO depositos(idCorte,fechaReg,idUserReg,estatus) VALUES $valores";
$res = mysqli_query($link,$sql) or die(errorBD('Problemas al registrar los cortes recolectados, notifica a tu Administrador'));

$sqlUp = "UPDATE depositos d INNER JOIN cortes c ON d.idCorte = c.id SET d.total = c.montoEfectivo, c.idRecoleccion = d.id, c.idEntregaCorte = '$idEntrega'
WHERE d.idUserReg = '$idUser' AND DATE_FORMAT(d.fechaReg,'%Y-%m-%d') = '$fecha' AND d.estatus = 0 AND d.idCorte IN ($ids)";
$resUp = mysqli_query($link,$sqlUp) or die(errorBD('Problemas al registrar los cortes recolectados, notifica a tu Administrador'));

$btn = '<a href="imprimeTicketRecolecta.php?fecha='.$fecha.'&suc='.$idSuc.'&tipo=2" target="_blank" class="btn btn-info btn-rounded">Imprimir Cortes Recolectados del Día</a href="javascript:void(0)">';

echo '1|Solicitud registrada correctamente|'.$btn.'|'.$sql;

function errorBD($error){
  echo '0|'.$error;
}
 ?>
