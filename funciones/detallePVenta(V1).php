<?php
session_start();
define('INCLUDE_CHECK', 1);
require_once('../include/connect.php');

$idVenta = (!empty($_POST['idVenta'])) ? $_POST['idVenta'] : 0;
$idProd = (!empty($_POST['idprod'])) ? $_POST['idprod'] : 0;
$tipoVenta = (isset($_POST['tipoVenta']) && $_POST['tipoVenta'] > 0) ? $_POST['tipoVenta'] : 1;
$idAutoriza = (!empty($_POST['autorizada'])) ? $_POST['autorizada'] : 0;
$idSucursal = $_SESSION['LZFidSuc'];
$idUser = $_SESSION['LZFident'];
$cantActual = '';
$debug = '0';
if ($idProd > 0) {

	if ($tipoVenta > 0) {
		$vntEsp = ($idAutoriza > 0) ? $idAutoriza : '0';
		if ($idVenta < 1) {
			$sqlIn = "INSERT INTO ventas(total,fechaReg,idUserReg,estatus,idSucursal,idUserAut,ventaEspecial) VALUES('0',NOW(), $idUser, '1', $idSucursal,$idAutoriza,$vntEsp)";
			$resIn = mysqli_query($link, $sqlIn) or die(errorBD('Problemas al crear la venta, notifica a tu Administrador.', $tipoVenta));
			$newID = mysqli_insert_id($link);
		}

		if ($idVenta > 0) {
			$venta = $idVenta;
		} elseif ($newID > 0) {
			$venta = $newID;
		} else {
			errorBD('No se reconoció la venta, actualiza y vuelve a intentarlo, si persiste por favor notifica a tu Administrador.', $tipoVenta);
		}

		$sqlConVnt = "SELECT * FROM ventas WHERE id = '$venta' LIMIT 1";
		$resConVnt = mysqli_query($link, $sqlConVnt) or die(errorBD('Problemas al consultar la venta, notifica a tu Administrador.'));
		$dat = mysqli_fetch_array($resConVnt);
		$idCliente = $dat['idCliente'];

		$sqlConPd = "SELECT pb.precio,scs.tipoExtra,scs.cantExtra,scs.aplicaExtra FROM productos p
						INNER JOIN preciosbase pb ON p.id = pb.idProducto
						INNER JOIN sucursales scs ON scs.id = '$idSucursal'
						WHERE p.id = '$idProd'
						ORDER BY pb.precio ASC
						LIMIT 1";
		$resConPd = mysqli_query($link, $sqlConPd) or die(errorBD('Problemas al consultar el precio del producto, notifica a tu Administrador.'));
		$rPd = mysqli_fetch_array($resConPd);
		$aplicaExtra = $rPd['aplicaExtra'];
		$cantExtra = $rPd['cantExtra'];
		$tipoExtra = $rPd['tipoExtra'];

		$sql = "CALL SP_agregaProductoEnVenta('$venta','$idProd','$idCliente','$idAutoriza','$idSucursal','$idUser','$aplicaExtra','$cantExtra','$tipoExtra')";
		
		$res = mysqli_query($link, $sql) or die(errorBD(mysqli_error($link), $tipoVenta));
		$var = mysqli_fetch_array($res);
		$mensaje = $var['mensaje'];
		$estatus = $var['estatus'];

		if ($tipoVenta == 1) {
			$_SESSION['LZFmsjSuccessInicioVenta'] = 'Se ha cargado correctamente el producto';
			header('location: ../venta.php');
			exit(0);
		} else {
			$_SESSION['LZFmsjSuccessInicioVentaEspecial'] = 'Se ha cargado correctamente el producto';
			header('location: ../ventaEspecial.php');
			exit(0);
		}
	} else {
		errorBD('No se reconoció la petición, actualiza y vuelve a intentarlo, si persiste notifica a tu Administrador', $tipoVenta);
	}
} else {
	errorBD('No se reconoció el producto, actualiza y vuelve a intentarlo, si persiste notifica a tu Administrador', $tipoVenta);
}
exit(0);
function errorBD($error, $tipoVenta)
{
	if ($tipoVenta == 1) {
		$_SESSION['LZFmsjInicioVenta'] = $error;
		header('location: ../venta.php');
		exit(0);
	} else {
		$_SESSION['LZFmsjInicioVentaEspecial'] = $error;
		header('location: ../ventaEspecial.php');
		exit(0);
	}

	exit(0);
}
