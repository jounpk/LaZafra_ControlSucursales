<?php
session_start();
define('INCLUDE_CHECK', 1);
require_once('../include/connect.php');

$idCoti = (isset($_POST['idCot']) && $_POST['idCot'] != '') ? $_POST['idCot'] : '';
$idSucursal = $_SESSION['LZFidSuc'];
$idUser = $_SESSION['LZFident'];
$debug = 0;
//----------------devBug------------------------------
if ($debug == 1) {
	print_r($_POST);
	echo '<br><br>';
} else {
	error_reporting(0);
}  //-------------Finaliza devBug------------------------------
if ($idCoti == '') {
	errorBD('No se reconoció tu folio, inténtalo nuevamente, en caso de que el problema persista, notifica a tu Administrador.', 'Error de validación, no se reconoció el folio');
}

//$sql = "INSERT INTO ventas(idCliente,total,fechaReg,idUserReg,estatus,idSucursal,idUserAut,ventaEspecial,idCotizacion) VALUES('0','0',NOW(),'$idUser','1','$idSucursal','0','0','$idCoti')";
$sql = "INSERT INTO ventas(idCliente,total,fechaReg,idUserReg,estatus,idSucursal,idUserAut,ventaEspecial,idCotizacion) SELECT idCliente,'0', NOW(),'$idUser','1','$idSucursal','0','0','$idCoti' FROM
cotizaciones WHERE id='$idCoti'"; 
//----------------devBug------------------------------
if ($debug == 1) {
	$resultXQuery = mysqli_query($link, $sql) or die(errorBD('Problemas al Ver Ventas, notifica a tu Administrador', mysqli_error($link)));
	$canInsert = mysqli_affected_rows($link);
	echo '<br>SQL: ' . $sql . '<br>';
	echo '<br>Cant de Registros Cargados: ' . $canInsert;
} else {
	$resultXQuery = mysqli_query($link, $sql) or die(errorBD('Problemas al Ver Ventas, notifica a tu Administrador', mysqli_error($link)));
	$canInsert = mysqli_affected_rows($link);
} //-------------Finaliza devBug------------------------------
$idVenta = mysqli_insert_id($link);

$sql = "INSERT INTO detventas(idVenta,idProducto,cantidad,precioVenta,costo,cot,cantCotizada) SELECT '$idVenta',c.idProducto,c.cantidad,c.precioCoti,c.costo,'1',c.cantidad
			FROM detcotizaciones c
			INNER JOIN stocks s ON c.idProducto = s.idProducto AND s.idSucursal = '$idSucursal'
			WHERE c.idCotizacion = '$idCoti' AND s.idSucursal = '$idSucursal'";
//----------------devBug------------------------------
if ($debug == 1) {
	$resultXQuery = mysqli_query($link, $sql) or die(errorBD('Problemas al Ver Detalles de Venta, notifica a tu Administrador', mysqli_error($link)));
	$canInsert = mysqli_affected_rows($link);
	echo '<br>SQL: ' . $sql . '<br>';
	echo '<br>Cant de Registros Cargados: ' . $canInsert;
} else {
	$resultXQuery = mysqli_query($link, $sql) or die(errorBD('Problemas al Ver Detalles de Venta, notifica a tu Administrador', mysqli_error($link)));
	$canInsert = mysqli_affected_rows($link);
} //-------------Finaliza devBug------------------------------

$sql = "UPDATE cotizaciones SET estatus='5' WHERE id='$idCoti'";
//----------------devBug------------------------------
if ($debug == 1) {
	$resultXQuery = mysqli_query($link, $sql) or die(errorBD('Problemas al Actualizar Cotizacion, notifica a tu Administrador', mysqli_error($link)));
	$canInsert = mysqli_affected_rows($link);
	echo '<br>SQL: ' . $sql . '<br>';
	echo '<br>Cant de Registros Cargados: ' . $canInsert;
} else {
	$resultXQuery = mysqli_query($link, $sql) or die(errorBD('Problemas al Actualizar Cotizacion, notifica a tu Administrador', mysqli_error($link)));
	$canInsert = mysqli_affected_rows($link);
} //-------------Finaliza devBug------------------------------

$_SESSION['LZFmsjSuccessInicioVenta'] = 'Cotización cargada correctamente.';
echo '1|Cotización cargada correctamente.';
#echo '<br><br>------------------------ En caso de que exista el error se manda el mensaje de error y se redirige a la página ------------------------<br>';
function errorBD($error, $sql)
{
	//	$_SESSION['LZFmsjSuccessInicioVenta'] = $error;
	echo '0|' . $error;

	exit(0);
}
