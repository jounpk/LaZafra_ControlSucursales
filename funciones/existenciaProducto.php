<?php
session_start();
define('INCLUDE_CHECK', 1);
require_once('../include/connect.php');
$idVenta = (isset($_POST['idVenta']) && $_POST['idVenta'] > 0) ? $_POST['idVenta'] : '';
$idProd = (isset($_POST['idprod']) && $_POST['idprod'] > 0) ? $_POST['idprod'] : '';
$tipoVenta = (isset($_POST['tipoVenta']) && $_POST['tipoVenta'] > 0) ? $_POST['tipoVenta'] : 1;
$idAutoriza = (!empty($_POST['autorizada'])) ? $_POST['autorizada'] : 0;
$idSucursal = $_SESSION['LZFidSuc'];
$idUser = $_SESSION['LZFident'];
$cantActual = '';

$debug = 0;
if ($debug != 1) {
	error_reporting(0);
} else {
	error_reporting(1);
	echo 'Contenido de POST:</br>';
	print_r($_POST);
	echo '</br></br>';
}
if ($idProd != '') {
	/****************************validar la cantidad en el stock***************************************/
	$sql = "SELECT IF(cantActual IS NULL OR cantActual=0, 'NO HAY', cantActual) stock FROM stocks s WHERE idProducto = '$idProd' AND idSucursal = '$idSucursal'";
	//----------------devBug------------------------------
	if ($debug == 1) {
		$resultXquery = mysqli_query($link, $sql) or die(errorBD('Problemas al Insertar Ventas, notifica a tu Administrador', 0));
		$canInsert = mysqli_affected_rows($link);
		echo '<br>SQL: ' . $sql . '<br>';
		echo '<br>Cant de Registros Cargados: ' . $canInsert;
	} else {
		$resultXquery = mysqli_query($link, $sql) or die(errorBD('Problemas al Insertar Ventas, notifica a tu Administrador', 0));
		$canInsert = mysqli_affected_rows($link);
	} //-------------Finaliza devBug------------------------------
	$var = mysqli_fetch_array($resultXquery);
	$numRecords = mysqli_num_rows($resultXquery);
	$cantActual = $var['stock'];
	if ($cantActual == 'NO HAY' or $numRecords == 0) {
		errorBD('No hay existencias en Stock.', 0);
	}
	/******************************************************************************************************/
	mysqli_autocommit($link, FALSE);
	mysqli_begin_transaction($link);
	if ($tipoVenta > 0) {
		$vntEsp = ($idAutoriza > 0) ? $idAutoriza : '0';
		/*************************************creación de nueva venta...***************************************/
		if ($idVenta == '') {
			$sql = "INSERT INTO ventas(total,fechaReg,idUserReg,estatus,idSucursal,idUserAut,ventaEspecial) VALUES('0',NOW(), $idUser, '1', $idSucursal,$idAutoriza,$vntEsp)";
			//----------------devBug------------------------------
			if ($debug == 1) {
				$resultXquery = mysqli_query($link, $sql) or die(errorBD('Problemas al Insertar Ventas, notifica a tu Administrador', 1));
				$canInsert = mysqli_affected_rows($link);
				echo '<br>SQL: ' . $sql . '<br>';
				echo '<br>Cant de Registros Cargados: ' . $canInsert;
			} else {
				$resultXquery = mysqli_query($link, $sql) or die(errorBD('Problemas al Insertar Ventas, notifica a tu Administrador', 1));
				$canInsert = mysqli_affected_rows($link);
			} //-------------Finaliza devBug------------------------------
			$idVenta = mysqli_insert_id($link);
		}
		/******************************************************************************************************/
		/*************************************verificar venta creada...***************************************/
		else  if ($idVenta != '') {

			$sql = "SELECT IF(COUNT(id)=0,'NO HAY',COUNT(id)) AS totalVentas FROM ventas WHERE id = '$idVenta'";
			//----------------devBug------------------------------
			if ($debug == 1) {
				$resultXquery = mysqli_query($link, $sql) or die(errorBD('Problemas al Consultar Ventas, notifica a tu Administrador', mysqli_error($link)));
				$canInsert = mysqli_affected_rows($link);
				echo '<br>SQL: ' . $sql . '<br>';
				echo '<br>Cant de Registros Cargados: ' . $canInsert;
			} else {
				$resultXquery = mysqli_query($link, $sql) or die(errorBD('Problemas al Consultar Ventas, notifica a tu Administrador', mysqli_error($link)));
				$canInsert = mysqli_affected_rows($link);
			} //-------------Finaliza devBug------------------------------
			$dat = mysqli_fetch_array($resultXquery);
			$afirmaVta = $dat['totalVentas'];
			if ($afirmaVta == 'NO HAY') {
				errorBD('No se ha identificado la venta con folio: #' . $idVenta, 0);
			}
		}
		/******************************************************************************************************/
		/************************************* Valida Producto en Venta ****************************************/
		$sql = "SELECT IF(COUNT(dtvta.id)>0,'YA ESTA', COUNT(dtvta.id)) AS prodReg FROM detventas dtvta WHERE dtvta.idProducto='$idProd' AND dtvta.idVenta='$idVenta'";
		//----------------devBug------------------------------
		if ($debug == 1) {
			$resultXquery = mysqli_query($link, $sql) or die(errorBD('Problemas al Consultar Registro del Producto en Venta, notifica a tu Administrador', 1));
			$canInsert = mysqli_affected_rows($link);
			echo '<br>SQL: ' . $sql . '<br>';
			echo '<br>Cant de Registros Cargados: ' . $canInsert;
		} else {
			$resultXquery = mysqli_query($link, $sql) or die(errorBD('Problemas al Consultar Registro del Producto en Venta, notifica a tu Administrador', 1));
			$canInsert = mysqli_affected_rows($link);
		} //-------------Finaliza devBug------------------------------
		$dat = mysqli_fetch_array($resultXquery);
		$VerProd = $dat['prodReg'];
		if ($VerProd == 'YA ESTA') {
			errorBD('El producto ya se encuentra en lista, actualiza cantidad', 0);
		}

		/******************************************************************************************************/
		/************************************* Ingresar Detalle Venta  ****************************************/
		$sql = "INSERT INTO detventas(idVenta, idProducto, cantidad, precioVenta, costo)
		 SELECT
			 '$idVenta' AS idVenta,
			 '$idProd' AS idProducto,
			 '1' AS cantidad,
		 IF
		 (suc.tipoExtra = '1',
			 MAX( pb.precio )+ suc.cantExtra,
			 MAX( pb.precio )*(1+suc.cantExtra )) AS precioConExtra, pr.costo
		 FROM
			 productos pr
			 INNER JOIN preciosbase pb ON pr.id = pb.idProducto
			 INNER JOIN sucursales suc ON suc.id = '1'
			 WHERE pr.id='$idProd'";
		//----------------devBug------------------------------
		if ($debug == 1) {
			$resultXquery = mysqli_query($link, $sql) or die(errorBD('Problemas al Guardar Producto en Venta, notifica a tu Administrador', 1));
			$canInsert = mysqli_affected_rows($link);
			echo '<br>SQL: ' . $sql . '<br>';
			echo '<br>Cant de Registros Cargados: ' . $canInsert;
		} else {
			$resultXquery = mysqli_query($link, $sql) or die(errorBD('Problemas al Guardar Producto en Venta, notifica a tu Administrador', 1));
			$canInsert = mysqli_affected_rows($link);
		} //-------------Finaliza devBug------------------------------

		/******************************************************************************************************/
		if (mysqli_commit($link)) {
			$_SESSION['LZFmsjSuccessInicioVenta'] = 'Se ha cargado correctamente el producto';
			echo '1|Producto Agregado a la Venta';
		}
	} else {
		errorBD('No se reconoció el producto, actualiza y vuelve a intentarlo, si persiste notifica a tu Administrador', 0);
	}
}


function errorBD($error, $NecesitaRollBack)
{
	$link = $GLOBALS["link"];
	if ($NecesitaRollBack == '1') {
		mysqli_rollback($link);
	}
	if ($GLOBALS['debug'] == 1) {
		echo '<br><br>Det Error: ' . $error;
		echo '<br><br>Error Report: ' . mysqli_error($link);
	} else {
		echo '0|' . $error;
	}
	exit(0);
}
