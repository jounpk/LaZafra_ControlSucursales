<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$folio = (isset($_POST['folio']) && $_POST['folio'] != '') ? $_POST['folio'] : '' ;
$metodoPago = (isset($_POST['metodoPago']) && $_POST['metodoPago'] != '') ? $_POST['metodoPago'] : '' ;

$sqlCon = "SELECT CONCAT(usu.nombre,' ',usu.appat) AS nomUsuario, scs.nameFact AS nomSucursal, vnt.fechaReg
            FROM pagosventas pvnt
            INNER JOIN ventas vnt ON pvnt.idVenta = vnt.id
            INNER JOIN segusuarios usu ON vnt.idUserReg = usu.id
            INNER JOIN sucursales scs ON vnt.idSucursal = scs.id
            WHERE pvnt.folio = '$folio' AND pvnt.idFormaPago = '$metodoPago' AND vnt.estatus < '3'";
$resCon = mysqli_query($link,$sqlCon) or die(errorBD('Problemas al consutlar los folios, notifica a tu Administrador.'));
$cant = mysqli_num_rows($resCon);

if ($cant > 0) {
  $dat = mysqli_fetch_array($resCon);
  errorBD('El folio '.$folio.' ya fue capturado en sucursal '.$dat['nomSucursal'].' por el usuario: '.$dat['nomUsuario'].' el día '.$dat['fecha']);
}

$sqlCon = "SELECT CONCAT(usu.nombre,' ',usu.appat) AS nomUsuario, scs.nameFact AS nomSucursal, pvnt.fechaReg
            FROM pagoscreditos pvnt
            INNER JOIN segusuarios usu ON pvnt.idUserReg = usu.id
            INNER JOIN sucursales scs ON pvnt.idSucursal = scs.id
            WHERE pvnt.folio = '$folio' AND pvnt.idFormaPago = '$metodoPago'";
$resCon = mysqli_query($link,$sqlCon) or die(errorBD('Problemas al consutlar los folios, notifica a tu Administrador.(2)'.mysqli_error($link)));
$cant = mysqli_num_rows($resCon);

if ($cant > 0) {
  $dat = mysqli_fetch_array($resCon);
  errorBD('El folio '.$folio.' ya fue capturado en sucursal '.$dat['nomSucursal'].' por el usuario: '.$dat['nomUsuario'].' el día '.$dat['fecha']);
}
echo '1|Folio válido, puede seguir sin problemas';
exit(0);

function errorBD($error){
  echo '0|'.$error;
  exit(0);
}
 ?>
