-<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');


$id = (!empty($_POST['id'])) ? $_POST['id'] : 0 ;
$cant = (!empty($_POST['cant'])) ? $_POST['cant'] : 0 ;
$tipoVenta = (isset($_POST['tipo']) && $_POST['tipo'] > 0) ? $_POST['tipo'] : 1 ;
$idSucursal=$_SESSION['LZFidSuc'];
$idUser = $_SESSION['LZFident'];
/*
echo '<br> ############################################# <br>';
echo '<br>Print:<br>';
print_r($_POST);
echo '<br>$id: '.$id;
echo '<br>$cant: '.$cant;
echo '<br>$tipoVenta: '.$tipoVenta;
echo '<br> ############################################# <br>';
echo '<br> Línea 26';
exit(0);
#*/

if ($cant <= 0) {
	errorBD('No se realizó ningún cambio porque la cantidad es menor a uno o es cero.',$tipoVenta);
}

$sqlCon = "SELECT v.estatus,d.idProducto,d.precioVenta,d.cot,d.cantCotizada FROM ventas v INNER JOIN detventas d ON v.id = d.idVenta WHERE d.id = '$id'";
$resCon = mysqli_query($link,$sqlCon) or die('Problemas al consultar la venta, notifica a tu Administrador.');
$dt = mysqli_fetch_array($resCon);
$idPd = $dt['idProducto'];
$pVnt = $dt['precioVenta'];
if ($dt['estatus'] > '1') {
	errorBD('No se realizó ningún cambio porque la venta ya fue cerrada o cancelada.',$tipoVenta);
}
if ($dt['cot'] == 1) {
	if ($dt['cantCotizada'] > $cant) {
		errorBD('No se permite que la cantidad sea menor a '.$dt['cantCotizada'].' ya que este producto es de una cotización.',$tipoVenta);
	}
}
if ($id > 0) {
#	echo '<br> Línea 30';
	if ($tipoVenta > 0) {
#echo '<br> Línea 32';
			if ($tipoVenta == 1) {
				$sqlStk = "SELECT id FROM stocks WHERE idProducto = '$idPd' AND idSucursal = '$idSucursal' LIMIT 1";
				$resStk = mysqli_query($link,$sqlStk) or die(errorBD('Problemas al consultar los precios base del producto',$tipoVenta));
				$stk = mysqli_fetch_array($resStk);
				$idStock = $stk['id'];
				$sqlDv = "SELECT DISTINCT(IF(scs.aplicaExtra = 1 AND a.tipoPrecio = 1,IF(scs.tipoExtra = 1,(a.precio + scs.cantExtra),(a.precio *(1 + (scs.cantExtra / 100)))),a.precio)) AS precioProd, a.cantLibera
											FROM stocks s
											INNER JOIN sucursales scs ON s.idSucursal = scs.id
											INNER JOIN productos p ON s.idProducto = p.id
											INNER JOIN (
											SELECT '1' AS tipoPrecio, precio, idProducto, cantLibera FROM preciosbase
											UNION
											SELECT '2' AS tipoPrecio, precio, idProducto, '1' AS cantLibera FROM excepcionesprecio WHERE idSucursal = '$idSucursal'
											) a ON a.idProducto = p.id
											WHERE s.id = '$idStock'
											ORDER BY precioProd DESC";
				$resDv = mysqli_query($link,$sqlDv) or die(errorBD('Problemas al consultar los precios base del producto',$tipoVenta));
				$precio = $pPrecio = 0;
				while ($p =mysqli_fetch_array($resDv)) {
					if ($cant >= $p['cantLibera']) {
						$precio = $p['precioProd'];
					}
				}
				if ($pVnt <= $precio && $dt['cot'] == 0) {
					$mns = 'Se ha actualizado la cantidad del Producto y su precio al más bajo posible, porque su precio no está permitido con la cantidad seleccionada';
					$pPrecio = $precio;
				} else {
					$mns = 'Se ha actualizado la cantidad del Producto';
					$pPrecio = $pVnt;
				}
				$sql="UPDATE detventas SET cantidad = '$cant', precioVenta = ('$cant'*'$pPrecio') WHERE id = '$id'";
				$res=mysqli_query($link,$sql) or die (errorBD('Problemas al editar la cantidad, notifica a tu Administrador.',$tipoVenta));
			} else {
				$sql="UPDATE detventas SET cantidad = '$cant' WHERE id = '$id'";
				$res=mysqli_query($link,$sql) or die (errorBD('Problemas al editar la cantidad, notifica a tu Administrador.',$tipoVenta));
			}

#echo '<br> Línea 38';
#			 se redirecciona al lugar donde se hizo la petición
			if ($tipoVenta == 1) {
			#	echo '<br> Línea 41';
				$_SESSION['LZFmsjSuccessInicioVenta'] = $mns;
				header('location: ../venta.php');
				exit(0);
			} else {
#				echo '<br> Línea 45';
				$_SESSION['LZFmsjSuccessInicioVentaEspecial'] = 'Se ha actualizado la cantidad del Producto';
				header('location: ../ventaEspecial.php');
				exit(0);
			}
		} else {
			#echo '<br> Línea 50';
			errorBD('No se reconoció la petición, actualiza y vuelve a intentarlo, si persiste notifica a tu Administrador',$tipoVenta);
		}

} else {
	#echo '<br> Línea 55';
	errorBD('No se reconoció el producto, actualiza y vuelve a intentarlo, si persiste notifica a tu Administrador',$tipoVenta);
}
exit(0);
function errorBD($error,$tipoVenta){
	if ($tipoVenta == 1) {
	#	echo '<br> Línea 61';
		$_SESSION['LZFmsjInicioVenta'] = $error;
		header('location: ../venta.php');
		exit(0);
	} else {
	#	echo '<br> Línea 66';
		$_SESSION['LZFmsjInicioVentaEspecial'] = $error;
		header('location: ../ventaEspecial.php');
		exit(0);
	}
}

 ?>
