<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$idUser=$_SESSION['LZFident'];
$idSucursal=$_SESSION['LZFidSuc'];
$idVenta = (!empty($_POST['idVenta'])) ? $_POST['idVenta'] : 0 ;
$tipoVenta = (!empty($_POST['tipoVenta'])) ? $_POST['tipoVenta'] : 1 ;
$idCliente = (!empty($_POST['idCliente'])) ? $_POST['idCliente'] : 1 ;
$autorizada = (!empty($_POST['autorizada'])) ? $_POST['autorizada'] : 0 ;

/*
echo '<br>########################################################################<br>';
echo '<br>$_POST:<br>';
print_r($_POST);
echo '<br>';
echo '<br>$idVenta: '.$idVenta;
echo '<br>$tipoVenta: '.$tipoVenta;
echo '<br>$idUser: '.$idUser;
echo '<br>$idSucursal: '.$idSucursal;
echo '<br>$idCliente: '.$idCliente;
echo '<br>$autorizada: '.$autorizada;
echo '<br>';
echo '<br>########################################################################<br>';
#exit(0);
# */
#echo '<br>Línea 28';
//Detallado del Cliente
if($idCliente==''){
	errorBD('No se recibió el cliente',$tipoVenta);
}
#echo '<br>Línea 33';
//Definicion de Venta, si es apertura o continuacion de una abierta
if ($tipoVenta == 1) {
	if ($idVenta==0){
		$sql="INSERT INTO ventas(idCliente,total,fechaReg,idUserReg,estatus,idSucursal) VALUES('$idCliente',0,NOW(),'$idUser',1,'$idSucursal')";
		mysqli_query($link,$sql) or die (errorBD('Tuvimos un problema al capturar al Cliente, prueba otra vez, si persiste notifica a tu Administrador.',$tipoVenta));
#echo '<br>Línea 39';
	}
	else{
		$sql="UPDATE ventas SET idCliente='$idCliente' WHERE id='$idVenta'";
		mysqli_query($link,$sql) or die (errorBD('Error al Guardar.',$tipoVenta));
		#echo '<br>Línea 44';
	}
#echo '<br>Línea 46';
header('location: ../venta.php');
} else {
	if ($idVenta==0){
		$sql="INSERT INTO ventas(idCliente,total,fechaReg,idUserReg,estatus,idSucursal) VALUES('$idCliente',0,NOW(),'$idUser',1,'$idSucursal')";
		mysqli_query($link,$sql) or die (errorBD('Tuvimos un problema al capturar al Cliente, prueba otra vez, si persiste notifica a tu Administrador.',$tipoVenta));
#echo '<br>Línea 52';
	}
	else{
		$sql="UPDATE ventas SET idCliente='$idCliente' WHERE id='$idVenta'";
		mysqli_query($link,$sql) or die (errorBD('Error al Guardar.',$tipoVenta));
	}
#echo '<br>Línea 58';
header('location: ../ventaEspecial.php');
}



exit(0);
function errorBD($error,$tipoVenta){
	#echo '<br>Línea 65';
#/*
	if ($tipoVenta == 1) {
		$_SESSION['LZFmsjInicioVenta'] = $error;
		header('location: ../venta.php');
  exit(0);
	} else {
		$_SESSION['LZFmsjInicioVentaEspecial'] = $error;
		header('location: ../ventaEspecial.php');
  exit(0);
	}
	#*/
  exit(0);
}
 ?>
