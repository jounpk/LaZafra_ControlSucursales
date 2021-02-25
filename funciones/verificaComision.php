<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$banco = (isset($_POST['banco']) && $_POST['banco'] != '') ? $_POST['banco'] : '' ;
$formaPago = (isset($_POST['formaPago']) && $_POST['formaPago'] != '') ? $_POST['formaPago'] : '' ;

$sqlCon = "SELECT IF(comisionCheque > 0, comisionCheque,0) AS comCheque, IF(comisionTarjeta > 0, comisionTarjeta,0) AS comTarjeta
FROM catbancos bnk
WHERE bnk.id = '$banco' AND estatus = 1";
$resCon = mysqli_query($link,$sqlCon) or die(errorBD('Problemas al consutlar los folios, notifica a tu Administrador.'));
$d = mysqli_fetch_array($resCon);



echo '1|'.$d['comCheque'].'|'.$d['comTarjeta'];
exit(0);

function errorBD($error){
  echo '0|'.$error;
  exit(0);
}
 ?>
