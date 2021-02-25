<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$idUser=$_SESSION['LZFident'];
$idSucursal=$_SESSION['LZFidSuc'];

$filtroFecha = (isset($_SESSION['filtroFecha']) && $_SESSION['filtroFecha'] != '') ? $_SESSION['filtroFecha'] : '' ;
unset($_SESSION['filtroFecha']);
$filtroProveedor = (isset($_SESSION['proveedor']) && $_SESSION['proveedor'] != '') ? $_SESSION['proveedor']  : '' ;
unset($_SESSION['proveedor']);

if ($filtroFecha != '') {

$sqlTabla = "SELECT c.id,c.fechaCompra,c.total, p.nombre AS nomProveedor, CONCAT(u.nombre, ' ',u.appat, ' ',u.apmat) AS comprador
FROM compras c
INNER JOIN proveedores p ON c.idProveedor = p.id
INNER JOIN segusuarios u ON c.idUserReg = u.id
WHERE c.estatus = '2' $filtroFecha $filtroProveedor
ORDER BY c.fechaCompra DESC";
$resTabla = mysqli_query($link,$sqlTabla) or die('Problemas al consultar las compras, notifica a tu Administrador.');
$cont = 0;
while ($tab = mysqli_fetch_array($resTabla)) {
$idCompra = $tab['id'];
$fechaCompra = $tab['fechaCompra'];
	$sqlDet = "SELECT dc.*, IF(dc.idProducto > 0, p.descripcion, dc.nombreProducto) AS nomProducto
							FROM detcompras dc
							LEFT JOIN productos p ON dc.idProducto = p.id
							WHERE idCompra = '$idCompra'";
	$resDet = mysqli_query($link,$sqlDet) or die('Problemas al consultar el detalle de compras, notifica a tu Administrador.');
$inf = '';
$sTotal =  0;
$boton = '<button type="button" class="btn btn-info btn-circle muestraSombra" onClick="imprimeTicketCompra('.$idCompra.');"><i class="fas fa-print"></i></button>';
while ($det = mysqli_fetch_array($resDet)) {
$sTotal = $det['cantidad'] * $det['costoUnitario'] ;
$inf .= '<tr>
            <td>'.$det['nomProducto'].'</td>
            <td class="text-right">'.number_format($det['cantidad'] , 2 , '.' , ',' ).'</td>
            <td class="text-right">$ '.number_format($det['costoUnitario'] , 2 , '.' , ',' ).'</td>
            <td class="text-right">$ '.number_format($sTotal, 2 , '.' , ',' ).'</td>
        </tr>';
				}
  $lista = ++$cont;
	$folio = $idCompra;
	$nomProveedor = $tab['nomProveedor'];
	$comprador = $tab['comprador'];
	$fechaC = $fechaCompra;
	$totalC = '$ '.number_format($tab['total'],2,'.',',');
	$ticket = $boton;
$filas['data'][] = [
            'cont' =>$lista,
						'folio' =>$folio,
						'proveedor' => $nomProveedor,
						'comprador' => $comprador,
            'info' => $inf,
						'fecha' => $fechaC,
						'totalCompra' => $totalC,
						'ticketCompra' => $ticket

					];

				}

echo json_encode($filas);
} else {
	$filas['data'][] = [
          'cont' => "",
					'folio' => "",
					'proveedor' => "",
					'comprador' => "",
          'info' => "",
					'fecha' => "",
					'totalCompra' => ""
						];
						echo json_encode($filas);
}
 ?>
