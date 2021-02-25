<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$id = (!empty($_REQUEST['id'])) ? $_REQUEST['id'] : 0 ;
$idDet = (!empty($_REQUEST['idDet'])) ? $_REQUEST['idDet'] : 0 ;
/*
echo '<br> ############################################# <br>';
echo '<br>Print:<br>';
print_r($_REQUEST);
echo '<br>';
echo '<br>$idDet: '.$idDet;
echo '<br>$id: '.$id;
echo '<br> ############################################# <br>';
exit(0);
#*/
$sqlCon = "SELECT * FROM compras WHERE id = $id";
$resCon = mysqli_query($link,$sqlCon) or die(errorBD('Problemas al consultar los productos en compras, notifica a tu Administrador.'));
$c = mysqli_fetch_array($resCon);

if ($c['estatus'] > 1) {
  errorBD('No se realizó ningún cambio a la compra porque ya está cerrada o fue cancelada.');
}

$sql="DELETE FROM detcompras WHERE id = '$idDet' LIMIT 1";
$res = mysqli_query($link,$sql) or die(errorBD('Problemas al eliminar el producto, notifica a tu Administrador.'));

		$_SESSION['LZFmsjSuccessCorporativoCompras'] = 'El producto ha sido eliminado correctamente.';
		header('location: ../Corporativo/compras.php');
		exit(0);

function errorBD($error){
#    echo '<br>Manda a venta, todo bad';
    $_SESSION['LZFmsjCorporativoCompras'] = $error;
    header('location: ../Corporativo/compras.php');
    exit(0);
}
 ?>
