<head>

    <meta charset="utf-8">
</head>
<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');
$id = (isset($_REQUEST['idVenta'])) ? $_REQUEST['idVenta'] : '' ;
$tipoTicket=$_REQUEST['tipoTicket'];

if ($tipoTicket=='Venta') {
  $clienteOp='Publico en Gral.';
  require("imprimeTicketVenta.php");
}
if ($tipoTicket=='Venta2') {
  $clienteOp='Publico en Gral.';
  require("imprimeTicketVenta2.php");
}
if ($tipoTicket=='CorteCaja') {
  require("imprimeTicketCierreCaja.php");
}
if ($tipoTicket=='cierraTraspaso') {
  require("imprimeTicketCierraTraspaso.php");
}
if ($tipoTicket=='lanzaTraspaso') {
  require("imprimeTicketLanzaTraspaso.php");
}
if ($tipoTicket=='lanzaAjuste') {
  require("imprimeTicketLanzaAjuste.php");
}
if ($tipoTicket=='lanzaCompra') {
  require("imprimeTicketLanzaCompra.php");
}
if ($tipoTicket=='gastoPago') {
  require("imprimeTicketGastoPago.php");
}
if ($tipoTicket=='BodegaIniciaCarga') {
  $fecha=date("Y-m-d H:i:s");
  $user=$_SESSION['ident'];
  $sql="UPDATE traspasos SET estatusBodega='1', fechaBodega='$fecha', idUsuarioBodega='$user' WHERE id='$id'";
  mysqli_query($link,$sql) or die("Problemas con el registro de Bodega.");
  require("imprimeTicketBodegaInicia.php");
}
if ($tipoTicket=='reimprimeBodega') {
  require("imprimeTicketBodegaFin.php");
}
?>
