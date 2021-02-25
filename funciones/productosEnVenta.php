<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$idUser=$_SESSION['LZFident'];
$idSucursal=$_SESSION['LZFidSuc'];

$sqlProd = "SELECT d.*, p.costo,p.descripcion,p.id AS idProd, s.cantActual, s.id AS idStock
								FROM ventas v
								INNER JOIN detventas d ON v.id = d.idVenta
								INNER JOIN productos p ON d.idProducto = p.id
								INNER JOIN stocks s ON d.idProducto = s.idProducto AND s.idSucursal = '$idSucursal'
								WHERE  v.estatus = '1' AND v.idSucursal = '$idSucursal' AND v.idUserReg = '$idUser' AND v.ventaEspecial = '0'
								ORDER BY d.id ASC";
$resProd = mysqli_query($link,$sqlProd) or die('Problemas al consultar los Productos, notifica a tu Administrador');
$cont = 1; $tot = 0;

$cantRows = mysqli_num_rows($resProd);
	if ($cantRows > 0) {
		while ($pd = mysqli_fetch_array($resProd)) {
			$cot = $pd['cot'];
			$idStock = $pd['idStock'];
			$prod=$pd['idProd'];
			$sqlLt = "SELECT idLote1,cant1,lote1,caducidad1,idLote2,cant2,lote2,caducidad2,idLote3,cant3,lote3,caducidad3
								FROM stocks s
								LEFT JOIN (SELECT id AS idLote1, cant AS cant1, lote AS lote1, caducidad AS caducidad1,idStock AS stock1 FROM lotestocks WHERE idStock = $idStock AND cant > 0 ORDER BY caducidad ASC LIMIT 1) a ON a.stock1 = s.id
								LEFT JOIN (SELECT id AS idLote2, cant AS cant2, lote AS lote2, caducidad AS caducidad2,idStock AS stock2 FROM lotestocks WHERE idStock = $idStock AND cant > 0 ORDER BY caducidad ASC LIMIT 1 OFFSET 1) b ON b.stock2 = s.id
								LEFT JOIN (SELECT id AS idLote3, cant AS cant3, lote AS lote3, caducidad AS caducidad3,idStock AS stock3 FROM lotestocks WHERE idStock = $idStock AND cant > 0 ORDER BY caducidad ASC LIMIT 1 OFFSET 2) c ON c.stock3 = s.id
								WHERE s.id = '$idStock'";
			$resLt = mysqli_query($link,$sqlLt) or die('Problemas al consultar los lotes, notifica a tu Administrador.');
			$lt = mysqli_fetch_array($resLt);
			$precio = $lista = $descripcion = $cantidad = $acciones = $lote1 = $lote2 = $lote3 = $cantidad1 = $cantidad2 = $cantidad3 = $caducidad1 = $caducidad2 = $caducidad3 = $tsubtotal = '';
					$subtotal = $precioPd = 0;

					$descripcion = $pd['descripcion'];
					$precio .= '<select id="precios" class="form-control" onChange="cambiaPrecioProd('.$pd['id'].',this.value);">';
				$precio .= '<optgroup label="Precios">';
				if ($cot == 0) {
				# comienza la búsqueda de los precios del producto en el precio base
				$sqlPrecios = "SELECT DISTINCT(IF(scs.aplicaExtra = 1 AND a.tipoPrecio = 1,IF(scs.tipoExtra = 1,(a.precio + scs.cantExtra),(a.precio *(1 + (scs.cantExtra / 100)))),a.precio)) AS precioProd, a.cantLibera
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
				$resPrecios = mysqli_query($link,$sqlPrecios) or die('Problemas al consultar los precios, notifica a tu Administrador');
				while ($pr = mysqli_fetch_array($resPrecios)) {
						$desactiva = ($pd['cantidad'] >= $pr['cantLibera']) ? '' : 'title="Disponible con cantidades de '.$pr['cantLibera'].' en adelante" disabled' ;
						$precioPd = $pr['precioProd'];
						$p1 = ($precioPd == $pd['precioVenta']) ? 'selected' : '' ;
					if ($precioPd > 0) {
						$prcio = ($pd['cantidad'] >= $pr['cantLibera']) ? '$ '.number_format($precioPd,2,'.',',') : '$ '.number_format($precioPd,2,'.',',');
						$precio .= '<option value="'.$precioPd.'" '.$p1.' class="text-right" '.$desactiva.'>'.$prcio.'</option>';
					}
				}
			} else {
				$precio .= '<option value="'.$pd['precioVenta'].'" class="text-right">'.number_format($pd['precioVenta'],2,'.',',').'</option>';
			}

			$precio .= '</optgroup>
				</select>';
				/*
				$precio .= '</optgroup>
						<optgroup label="Costo">
						<option value="'.$pd['costo'].'" class="text-right" disabled>$ '.number_format($pd['costo'],2,'.',',').'</option>
						</optgroup>
					</select>';
					#*/
					$lista = $cont;
					if ($cot == 0) {
						$cantidad = '<input type="number" min="0" step="any" max="'.$pd['cantActual'].'" class="form-control text-right" value="'.$pd['cantidad'].'" id="cant'.$pd['idVenta'].'" onChange="cambiaCantProd('.$pd['id'].',this.value,'.$pd['cantActual'].','.$pd['cantActual'].');">';
					} else {
						$cantidad = '<input type="number" step="any" min="'.$pd['cantCotizada'].'" max="'.$pd['cantActual'].'" class="form-control text-right" value="'.$pd['cantidad'].'" id="cant'.$pd['idVenta'].'" onChange="cambiaCantProd('.$pd['id'].',this.value,'.$pd['cantActual'].','.$pd['cantActual'].');">';
					}
					$subtotal = $pd['precioVenta'] * $pd['cantidad'];
					$acciones = '<div class="btn btn-outline-danger" onClick="eliminaProductoEnVenta('.$pd['id'].');"><i class="fas fa-trash"></i></div>';
					$tot += $subtotal;
					$cantActual = $pd['cantActual'];
					$lote1 = ($lt['lote1'] != null) ? $lt['lote1'] : '';
					$lote2 = ($lt['lote2'] != null) ? $lt['lote2'] : '';
					$lote3 = ($lt['lote3'] != null) ? $lt['lote3'] : '';
					$cantidad1 = ($lt['cant1'] != null) ? $lt['cant1'] : '';
					$cantidad2 = ($lt['cant2'] != null) ? $lt['cant2'] : '';
					$cantidad3 = ($lt['cant3'] != null) ? $lt['cant3'] : '';
					$caducidad1 = ($lt['caducidad1'] != null) ? $lt['caducidad1'] : '';
					$caducidad2 = ($lt['caducidad2'] != null) ? $lt['caducidad2'] : '';
					$caducidad3 = ($lt['caducidad3'] != null) ? $lt['caducidad3'] : '';
					$tsubtotal = '$ '.number_format($subtotal,2,'.',',').'<input type="hidden" class="subtotalVenta" id="subt-'.$pd['id'].'" value="'.$subtotal.'">';
					$filas['data'][] = [
											'cont' =>$lista,
											'descripcion' => $descripcion,
											'precio' => $precio,
											'cantidad' => $cantidad,
											'cantActual' => $cantActual,
											'subtotal' => $tsubtotal,
											'acciones' => $acciones,
											'nomLote' => $lote1,
											'cantLote' => $cantidad1,
											'caducaLote' => $caducidad1,
											'nomLote2' => $lote2,
											'cantLote2' => $cantidad2,
											'caducaLote2' => $caducidad2,
											'nomLote3' => $lote3,
											'cantLote3' => $cantidad3,
											'caducaLote3' => $caducidad3,
											'id'=>$prod
										];

								$cont++;
		}
	} else {
		$filas['data'][] = [
								'cont' =>'',
								'descripcion' => '',
								'precio' => '',
								'cantidad' => '',
								'cantActual' => '',
								'subtotal' => '',
								'acciones' => '',
								'nomLote' => '',
								'cantLote' => '',
								'caducaLote' => '',
								'nomLote2' => '',
								'cantLote2' => '',
								'caducaLote2' => '',
								'nomLote3' => '',
								'cantLote3' => '',
								'caducaLote3' => ''
							];
	}

echo json_encode($filas);

 ?>
