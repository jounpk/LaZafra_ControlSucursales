<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$idCompra = (!empty($_REQUEST['idCompra'])) ? $_REQUEST['idCompra'] : 0 ;
$totalCompra = (!empty($_REQUEST['totalCompra'])) ? $_REQUEST['totalCompra'] : 0 ;
/*
echo '<br> ############################################# <br>';
echo '<br>Print:<br>';
print_r($_REQUEST);
echo '<br>';
echo '<br>$idCompra: '.$idCompra;
echo '<br>$totalCompra: '.$totalCompra;
echo '<br> ############################################# <br>';
exit(0);
#*/

if ($idCompra == 0) {
  errorBD('No se reconoció la compra, inténtalo nuevamente, si el problema persiste notifica a tu Administrador.');
}

$sqlCon = "SELECT * FROM compras WHERE id = $idCompra";
$resCon = mysqli_query($link,$sqlCon) or die(errorBD('Problemas al consultar las compras, notifica a tu Administrador.'));
$c = mysqli_fetch_array($resCon);

if ($c['estatus'] > 1) {
  errorBD('No se realizó ningún cambio a la compra porque ya está cerrada o fue cancelada.');
}

$sql="UPDATE compras SET estatus = '3', total = '$totalCompra' WHERE id = '$idCompra'";
$res = mysqli_query($link,$sql) or die(errorBD('Problemas al cerrar la compra, notifica a tu Administrador.'));

		$_SESSION['LZFmsjSuccessCorporativoCompras'] = 'La compra ha sido cancelada.';
		header('location: ../Corporativo/compras.php');
		exit(0);

function errorBD($error){
#    echo '<br>Manda a venta, todo bad';
    $_SESSION['LZFmsjCorporativoCompras'] = $error;
    header('location: ../Corporativo/compras.php');
    exit(0);
}
 ?>
