<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$idUser=$_SESSION['LZFident'];
$idSucursal=$_SESSION['LZFidSuc'];

$filtroProducto = (isset($_SESSION['filtroProducto']) && $_SESSION['filtroProducto'] != '') ? $_SESSION['filtroProducto'] : '' ;
unset($_SESSION['filtroProducto']);
$filtroProveedor = (isset($_SESSION['filtroProveedor']) && $_SESSION['filtroProveedor'] != '') ? $_SESSION['filtroProveedor']  : '' ;
unset($_SESSION['filtroProveedor']);

if ($filtroProducto != '' || $filtroProveedor != '') {
$sqlTabla = "SELECT c.id,p.nombre,p.meta,p.limiteCredito,AVG(dc.costoUnitario) AS promedio,dc.costoUnitario,SUM(dc.cantidad) AS cantidad,
							p.id AS idProveedor,dc.idProducto AS idProducto,c.fechaCompra
							FROM compras c
							INNER JOIN detcompras dc ON c.id = dc.idCompra
							INNER JOIN proveedores p ON c.idProveedor = p.id
							WHERE c.estatus = '2' $filtroProducto $filtroProveedor
							GROUP BY c.idProveedor
							ORDER BY dc.id DESC";
$resTabla = mysqli_query($link,$sqlTabla) or die('Problemas al consultar las compras, notifica a tu Administrador.');
$cont = 0;
while ($tab = mysqli_fetch_array($resTabla)) {
	$idProveedor = $tab['idProveedor'];
	$sqlCon = "SELECT c.fechaCompra,dc.*
							FROM compras c
							INNER JOIN detcompras dc ON c.id = dc.idCompra
							INNER JOIN proveedores p ON c.idProveedor = p.id
							WHERE c.estatus = '2' $filtroProducto AND p.id = '$idProveedor' AND DATE_FORMAT(c.fechaCompra,'%Y') = DATE_FORMAT(NOW(),'%Y')
							ORDER BY dc.id DESC";
	$resCon = mysqli_query($link,$sqlCon) or die('Problemas al consultar las compras, notifica a tu Administrador.');
	$cols = '';
	while ($dat = mysqli_fetch_array($resCon)) {
		$cols .= '<tr><td class="text-center">'.$dat['idCompra'].'</td><td class="text-center">'.$dat['fechaCompra'].'</td><td class="text-center">'.$dat['tipoUnidad'].'</td><td class="text-right">'.number_format($dat['cantidad'],2,'.',',').'</td><td class="text-right">$ '.number_format($dat['costoUnitario'],2,'.',',').'</td></tr>';
	}

	$descripcion = $tab['nombre'];
	$lista = ++$cont;
	$cant = number_format($tab['cantidad'],2,'.',',');
	$credito = '$ '.number_format($tab['limiteCredito'],2,'.',',');
	$meta = $tab['meta'];
	$costoPromedio = '$ '.number_format($tab['promedio'],2,'.',',');
	$costo = '$ '.number_format($tab['costoUnitario'],2,'.',',');
	$folio = $tab['id'];
	$fechaC = $tab['fechaCompra'];
$filas['data'][] = [
						'cont' =>$lista,
						'proveedor' => $descripcion,
						'cant' => $cant,
						'credito' => $credito,
						'meta' => $meta,
						'costoPromedio' => $costoPromedio,
						'costo' => $costo,
						'info' => $cols,
						'folio' => $folio,
						'fecha' => $fechaC
					];

				}

echo json_encode($filas);
} else {
	$filas['data'][] = [
							'cont' => "",
							'proveedor' => "",
							'cant' => "",
							'credito' => "",
							'meta' => "",
							'costoPromedio' => "",
							'costo' => "",
							'info' => "<td class=\"text-center\"></td><td class=\"text-center\"></td><td></td><td class=\"text-right\"></td><td class=\"text-right\"></td>",
							'folio' => "",
							'fecha' => ""
						];
						echo json_encode($filas);
}
 ?>
