<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$idDetCoti = (!empty($_POST['idDetCoti'])) ? $_POST['idDetCoti'] : 0 ;
/*
echo '<br> ############################################# <br>';
echo '<br>Print:<br>';
print_r($_POST);
echo '<br>';
echo '<br>$idDetCoti: '.$idDetCoti;
echo '<br>$tipoVenta: '.$tipoVenta;
echo '<br> ############################################# <br>';
echo '<br> Línea 26';
#exit(0);
#*/
if ($idDetCoti == 0) {
  errorBD('No se reconoció el producto, vuelve a intentarlo, si persiste notifica a tu Administrador');
}

$sqlCon = "SELECT c.estatus FROM cotizaciones c INNER JOIN detcotizaciones dc ON c.id = dc.idCotizacion WHERE dc.id = '$idDetCoti'";
$resCon = mysqli_query($link,$sqlCon) or die('Problemas al consultar la cotización, notifica a tu Administrador.');
$dt = mysqli_fetch_array($resCon);

if ($dt['estatus'] > '1') {
	errorBD('No se realizó ningún cambio porque la cotización ya fue cerrada o cancelada.');
}

$sql = "DELETE FROM detcotizaciones WHERE id = '$idDetCoti' LIMIT 1";
$res = mysqli_query($link,$sql) or die(errorBD('Problemas al actualizar el estatus de la Marca, notifica a tu Administrador'));

#se redirecciona al lugar donde se hizo la petición
#        echo '<br>Manda a cotizaciones, todo ok';
$_SESSION['LZFmsjSuccessAdminCotizaciones'] = 'Se ha eliminado el Producto correctamente';
header('location: ../Administrador/cotizaciones.php');
exit(0);

function errorBD($error){
#    echo '<br>Manda a cotizaciones, todo bad';
$_SESSION['LZFmsjAdminCotizaciones'] = $error;
header('location: ../Administrador/cotizaciones.php');
exit(0);
}
 ?>
