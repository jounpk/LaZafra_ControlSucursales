<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$folio = (isset($_POST['idCot']) && $_POST['idCot'] != '') ? $_POST['idCot'] : '' ;
$idSucursal=$_SESSION['LZFidSuc'];
$idUser = $_SESSION['LZFident'];

if ($folio == '') {
	errorBD('No se reconoció tu folio, inténtalo nuevamente, en caso de que el problema persista, notifica a tu Administrador.', 'Error de validación, no se reconoció el folio');
}
$sqlCot = "SELECT * FROM cotizaciones WHERE folio = '$folio'";
$resCot = mysqli_query($link,$sqlCot) or die(errorBD('Problemas al consultar la autorización de la cotización, notifica a tu Administrador.',$sqlCot));
$r = mysqli_fetch_array($resCot);

if ($r['estatus'] != 3) {
	errorBD('Lo sentimos, la cotización no está autorizada para ser cargada en venta, por favor verifica el estatus de la cotización.','Error de validación de estatus != 3');
}
$idCoti = $r['id'];
$sqlCon ="SELECT v.id, s.nombre as nomSucursal, CONCAT(u.nombre,' ',u.appat, ' ',u.apmat) AS nomUsuario
FROM ventas v
INNER JOIN sucursales s ON v.idSucursal = s.id
INNER JOIN segusuarios u ON v.idUserReg = u.id
WHERE v.idCotizacion = '$idCoti'";
$resCon = mysqli_query($link,$sqlCon) or die(errorBD('Problemas al consultar las cotizaciones, notifica a tu Administrador.',$sqlCon));
$d = mysqli_fetch_array($resCon);
if ($d['id'] > 0) {
	errorBD('Lo sentimos, ya se encuentra registrada la cotización con la venta #'.$d['id'].' en la sucursal '.$d['nomSucursal'].', registrado por '.$d['nomUsuario'],'Error de validación, ya fue capturada la cotización');
}

$sql = "SELECT d.*, p.descripcion AS nomProducto, IF(s.cantActual > 0,s.cantActual,0) AS cantStock
				FROM detcotizaciones d
				INNER JOIN productos p ON d.idProducto = p.id
				LEFT JOIN stocks s ON d.idProducto = s.idProducto AND s.idSucursal = '$idSucursal'
				WHERE d.idCotizacion = '$idCoti'";
$res = mysqli_query($link,$sql) or die(errorBD('Problemas al consultar los productos registrados en la cotización, notifica a tu Administrador.',$sql));
$tabla = $colorFila = '';
$contador = $no = 0;
$tabla .=  '<div class="col-md-12 text-center">
							<label class="control-label"><big class="text-danger">Nota:</big> Sólo te permitirá cargar los productos de la cotización si tienes suficiente producto en tu sucursal</label>
						</div>
						<div class="table-responsive">
							<table class="table product-overview table-striped">
								<thead>
									<tr>
										<th>#</th>
										<th>Producto</th>
										<th class="text-right">Precio Cotizado</th>
										<th class="text-right">Cantidad Cotizada</th>
										<th class="text-right">Stock</th>
									</tr>
								</thead>
								<tbody>';
								while ($ct = mysqli_fetch_array($res)) {
								if ($ct['cantidad'] > $ct['cantStock']) {
									$contador++;
									$colorFila = 'class="table-danger"';
								}	else {
									$colorFila = '';
								}
				$tabla .= '<tr '.$colorFila.'>
										<td>'.++$no.'</td>
										<td>'.$ct['nomProducto'].'</td>
										<td class="text-right">$'.number_format($ct['precioVenta'],2,'.',',').'</td>
										<td class="text-right">'.number_format($ct['cantidad'],2,'.',',').'</td>
										<td class="text-right">'.number_format($ct['cantStock'],2,'.',',').'</td>
									</tr>';
								}
								if ($contador > 0) {
									$disabled = 'disabled = "disabled"';
									$form = '';
								} else {
									$disabled = '';
									$form = 'role="form" method="post" action="funciones/cargaCotizacionEnVenta.php"';
								}
		$tabla .= '</tbody>
							</table>
						</div>
						<form '.$form.'>
							<div class="modal-footer">
								<input type="hidden" name="idCoti" value="'.$idCoti.'">
								<button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
								<button type="submit" class="btn btn-info" '.$disabled.'>Vender</button>
							</div>
						</form>';

echo '1|Cotización Válida|'.$tabla;

#echo '<br><br>------------------------ En caso de que exista el error se manda el mensaje de error y se redirige a la página ------------------------<br>';
function errorBD($error,$sql){
	echo '0|'.$error.'|'.$sql;
	exit(0);
}

 ?>
