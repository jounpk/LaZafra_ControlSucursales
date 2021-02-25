<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');


$id = (!empty($_POST['id'])) ? $_POST['id'] : 0 ;
$precio = (!empty($_POST['precio'])) ? $_POST['precio'] : 0 ;
$costo = (!empty($_POST['costo'])) ? $_POST['costo'] : 0 ;
$tipo = (isset($_POST['tipo']) && $_POST['tipo'] > 0) ? $_POST['tipo'] : 1 ;
/*
echo '<br> ############################################# <br>';
echo '<br>Print:<br>';
print_r($_POST);
echo '<br>';
echo '<br>$id: '.$id;
echo '<br>$precio: '.$precio;
echo '<br>$tipo: '.$tipo;
echo '<br> ############################################# <br>';
echo '<br> Línea 26';
#exit(0);
#*/
if ($precio < 1) {
	errorBD('No se realizó ningún cambio porque el precio no debe ser negativo.',$tipo);
}

$sqlCon = "SELECT v.estatus FROM ventas v INNER JOIN detventas d ON v.id = d.idVenta WHERE d.id = '$id'";
$resCon = mysqli_query($link,$sqlCon) or die('Problemas al consultar la venta, notifica a tu Administrador.');
$dt = mysqli_fetch_array($resCon);

if ($dt['estatus'] > '1') {
	errorBD('No se realizó ningún cambio porque la venta ya fue cerrada o cancelada.',$tipo);
}
if ($id > 0) {
#	echo '<br> Línea 30';
	if ($tipo > 0) {
		if ($tipo == 2) {
			if ($precio < $costo) {
				errorBD('El precio no debe ser menor al costo del producto, debe ser mayor a $'.number_format($costo,2,'.',','),$tipo);
			}
		}
#echo '<br> Línea 32';
			$sql="UPDATE detventas SET precioVenta = '$precio' WHERE id = '$id'";
			$res=mysqli_query($link,$sql) or die (errorBD('Problemas al editar el precio, notifica a tu Administrador.',$tipo));

#echo '<br> Línea 38';
#			 se redirecciona al lugar donde se hizo la petición
			if ($tipo == 1) {
#				echo '<br> Línea 41';
				$_SESSION['LZFmsjSuccessInicioVenta'] = 'Se ha actualizado el precio del Producto';
				header('location: ../venta.php');
				exit(0);
			} else {
#				echo '<br> Línea 45';
				$_SESSION['LZFmsjSuccessInicioVentaEspecial'] = 'Se ha actualizado el precio del Producto';
				header('location: ../ventaEspecial.php');
				exit(0);
			}
		} else {
#			echo '<br> Línea 50';
			errorBD('No se reconoció la petición, actualiza y vuelve a intentarlo, si persiste notifica a tu Administrador',$tipo);
		}

} else {
#	echo '<br> Línea 55';
	errorBD('No se reconoció el producto, actualiza y vuelve a intentarlo, si persiste notifica a tu Administrador',$tipo);
}
exit(0);
function errorBD($error,$tipo){
	if ($tipo == 1) {
#		echo '<br> Línea 61';
		$_SESSION['LZFmsjInicioVenta'] = $error;
		header('location: ../venta.php');
		exit(0);
	} else {
#		echo '<br> Línea 66';
		$_SESSION['LZFmsjInicioVentaEspecial'] = $error;
		header('location: ../ventaEspecial.php');
		exit(0);
	}
}

 ?>
