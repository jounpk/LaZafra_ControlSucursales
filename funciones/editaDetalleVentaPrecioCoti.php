-<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');


$id = (!empty($_POST['id'])) ? $_POST['id'] : 0 ;
$precio = (!empty($_POST['precio'])) ? $_POST['precio'] : 0 ;
$costo = (!empty($_POST['costo'])) ? $_POST['costo'] : 0 ;
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
	errorBD('No se realizó ningún cambio porque el precio no debe ser negativo.');
}
$costo += 2;
if ($precio < $costo) {
	errorBD('El precio no debe ser menor al costo del producto, debe ser mayor a $'.number_format($costo,2,'.',','));
}
$sqlCon = "SELECT c.estatus FROM cotizaciones c INNER JOIN detcotizaciones dc ON c.id = dc.idCotizacion WHERE dc.id = '$id'";
$resCon = mysqli_query($link,$sqlCon) or die('Problemas al consultar la cotización, notifica a tu Administrador.');
$dt = mysqli_fetch_array($resCon);

if ($dt['estatus'] > '1') {
	errorBD('No se realizó ningún cambio porque la cotización ya fue cerrada o cancelada.');
}
if ($id > 0) {
#	echo '<br> Línea 30';
			$sql="UPDATE detcotizaciones SET precioVenta = '$precio' WHERE id = '$id'";
			$res=mysqli_query($link,$sql) or die (errorBD('Problemas al editar la cantidad, notifica a tu Administrador.'));

#echo '<br> Línea 38';
#			 se redirecciona al lugar donde se hizo la petición
				$_SESSION['LZFmsjSuccessAdminCotizaciones'] = 'Se ha actualizado el Precio del Producto';
				header('location: ../Administrador/cotizaciones.php');
				exit(0);


} else {
	#echo '<br> Línea 55';
	errorBD('No se reconoció el producto, actualiza y vuelve a intentarlo, si persiste notifica a tu Administrador');
}

exit(0);
function errorBD($error){
	#	echo '<br> Línea 66';
		$_SESSION['LZFmsjAdminCotizaciones'] = $error;
		header('location: ../Administrador/cotizaciones.php');
		exit(0);
}

 ?>
