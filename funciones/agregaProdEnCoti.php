<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$idCoti = (!empty($_POST['idCot'])) ? $_POST['idCot'] : 0 ;
$idProd = (!empty($_POST['idprod'])) ? $_POST['idprod'] : 0 ;
$idSucursal = $_SESSION['LZFidSuc'];
$idUser = $_SESSION['LZFident'];
/*
echo '<br> ############################################# <br>';
echo '<br>Print:<br>';
print_r($_POST);
echo '<br>$idCoti: '.$idCoti;
echo '<br>$idProd: '.$idProd;
echo '<br>$idSucursal: '.$idSucursal;
echo '<br>$idUser: '.$idUser;
echo '<br> ############################################# <br>';
echo '<br> Línea 19';
#exit(0);
#*/
if ($idCoti == 0) {
	errorBD('No se reconoció la Cotización, inténtalo nuevamente, si persiste notifica a tu Administrador.');
}
if ($idProd == 0) {
	errorBD('No se reconoció el Producto, inténtalo nuevamente, si persiste notifica a tu Administrador.');
}

#echo '<br><br>------------------------ Se consulta el estatus de la cotización ------------------------<br>';
$sql = "SELECT estatus FROM cotizaciones WHERE id = '$idCoti' LIMIT 1";
#echo '<br>$sql: '.$sql;
$res = mysqli_query($link,$sql) or die(errorBD('Problemas al consultar la cotización, notifica a tu Administrador.'));
$d = mysqli_fetch_array($res);

if ($d['estatus'] > 1 ) {
	errorBD('La cotización ya fue cerrada o cancelada, verifica en tu historial.');
}

#echo '<br><br>------------------------ Se obtiene el precio base del producto ------------------------<br>';
$sqlConPd = "SELECT p.costo,pb.precio,scs.tipoExtra,scs.cantExtra,scs.aplicaExtra FROM productos p
						INNER JOIN preciosbase pb ON p.id = pb.idProducto
						INNER JOIN sucursales scs ON scs.id = '$idSucursal'
						WHERE p.id = '$idProd'
						ORDER BY pb.precio DESC
						LIMIT 1";
#echo '<br>$sqlConPd: '.$sqlConPd;
$resConPd = mysqli_query($link,$sqlConPd) or die(errorBD('Problemas al consultar el precio del producto, notifica a tu Administrador.'));
$rPd = mysqli_fetch_array($resConPd);
$costo = $rPd['costo'];
$precioBase = $rPd['precio'];
$aplicaExtra = $rPd['aplicaExtra'];
$cantExtra = $rPd['cantExtra'];
$tipoExtra = $rPd['tipoExtra'];
$precioProd = 0;
if ($aplicaExtra == 2) {
	$precioProd = $precioBase;
} else {
		if ($tipoExtra = 1) {
				if ($cantExtra > 0) {
					$precioProd = ($precioBase + $cantExtra);
				} else {
					$precioProd = $precioBase;
				}
		} else {
			if ($cantExtra > 0) {
				$precioProd = ($precioBase * (1 + $cantExtra));
			} else {
				$precioProd = $precioBase;
			}
		}
}
#echo '<br>$precioBase: '.$precioBase;
#echo '<br>$aplicaExtra: '.$aplicaExtra;
#echo '<br>$cantExtra: '.$cantExtra;
#echo '<br>$tipoExtra: '.$tipoExtra;
#echo '<br>$precioProd: '.$precioProd;
#echo '<br><br>------------------------ Se consulta si el producto ya existe en la cotización ------------------------<br>';
$sqlConDetCot = "SELECT * FROM detcotizaciones WHERE idCotizacion = '$idCoti' AND idProducto = '$idProd'";
#echo '<br>$sqlConDetCot: '.$sqlConDetCot;
$resConDetCot = mysqli_query($link,$sqlConDetCot) or die(errorBD('Problemas al consultar el producto en la cotización.'));
$cantP = mysqli_num_rows($resConDetCot);

#echo '<br><br>------------------------ Si existe se suma, si no se agrega ------------------------<br>';
if ($cantP >= 1) {
	$sqlCot = "UPDATE detcotizaciones SET cantidad = (cantidad + 1) WHERE idCotizacion = '$idCoti' AND idProducto = '$idProd'";
} else {
	$sqlCot = "INSERT INTO detcotizaciones(idCotizacion,idProducto,cantidad,precioVenta,costo) VALUES('$idCoti','$idProd','1','$precioProd','$costo')";
}
#echo '<br>$sqlCot: '.$sqlCot;
$resCot = mysqli_query($link,$sqlCot) or die(errorBD('Problemas al agregar el producto en la cotización, notifica a tu Administrador.'));

#echo '<br><br>------------------------ Se manda a el mensaje de éxito y se redirige a la página ------------------------<br>';
$_SESSION['LZFmsjSuccessAdminCotizaciones'] = 'Se ha cargado correctamente el producto';
header('location: ../Administrador/cotizaciones.php');
exit(0);

#echo '<br><br>------------------------ En caso de que exista el error se manda el mensaje de error y se redirige a la página ------------------------<br>';
function errorBD($error){
		$_SESSION['LZFmsjAdminCotizaciones'] = $error;
		header('location: ../Administrador/cotizaciones.php');
		exit(0);
#echo '<br>$error: '.$error;
	exit(0);
}

 ?>
