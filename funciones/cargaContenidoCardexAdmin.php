<?php
session_start();
define('INCLUDE_CHECK', 1);
require_once('../include/connect.php');
$sucursal = $_SESSION['LZFidSuc'];
$userReg = $_SESSION['LZFident'];
$estTabla = '<table class="table"><thead>
<th>#</th>
<th>Folio</th>
<th>Fecha</th>
<th>Cantidad</th>
<th>Descripción</th></thead><tbody>
';

$ident = (!empty($_POST['ident'])) ? $_POST['ident'] : 0;
if ($ident < 1) {
  errorBD('No se reconoció el producto, vuelve a intentarlo, si persiste notifica a tu Administrador');
}
$sqlDetallado = '(SELECT detc.id, detc.cantFinal AS cantFinal, detc.idProducto, "Compra" AS tipo, rec.fechaReg AS fecha, CONCAT("Compra para ",suc.nombre) AS detallado
                            FROM detcompras detc
                            INNER JOIN compras co ON detc.idCompra = co.id
                            INNER JOIN recepciones rec ON rec.idCompra = co.id

                            INNER JOIN sucursales suc ON co.idSucursal = suc.id
                            WHERE detc.idProducto="' . $ident . '" AND co.estatus=4)
                            
                            UNION

                            (SELECT dett.id, dett.cantFinalEnv AS cantFinal, dett.idProducto, "Traspaso" AS tipo, tra.fechaEnvio AS fecha, CONCAT("Transferencia de ",sucSa.nombre," a ",sucEn.nombre) AS detallado
                            FROM dettraspasos dett
                            INNER JOIN traspasos tra ON dett.idTraspaso = tra.id
                            INNER JOIN sucursales sucEn ON tra.idSucEntrada = sucEn.id
                            INNER JOIN sucursales sucSa ON tra.idSucSalida = sucSa.id
                            WHERE dett.idProducto="' . $ident . '" AND tra.estatus=3)
                            
                            UNION

(SELECT dett.id, dett.cantFinalRec AS cantFinal, dett.idProducto, "Traspaso" AS tipo, tra.fechaRecepcion AS fecha, CONCAT("Transferencia de ",sucSa.nombre," a ",sucEn.nombre) AS detallado
FROM dettraspasos dett
INNER JOIN traspasos tra ON dett.idTraspaso = tra.id
INNER JOIN sucursales sucEn ON tra.idSucEntrada = sucEn.id
INNER JOIN sucursales sucSa ON tra.idSucSalida = sucSa.id
WHERE dett.idProducto="' . $ident . '" AND tra.estatus=4)
                            
UNION

(SELECT da.id, da.cantFinal AS cantFinal, da.idProducto, "Ajuste" AS tipo, aj.fechaReg AS fecha, IF(aj.estatus="1","Ajuste Salida","Ajuste Entrada") AS detallado
FROM detajustes da
INNER JOIN ajustes aj ON da.idAjuste = aj.id
INNER JOIN sucursales suc ON aj.idSucursal = suc.id
WHERE da.idProducto=  "' . $ident . '") 

UNION

(SELECT vnta.id, dv.cantFinal AS cantFinal, dv.idProducto, "Ventas" AS tipo, vnta.fechaReg AS fecha, CONCAT("Venta de ",suc.nombre) AS detallado
FROM detventas dv
INNER JOIN ventas vnta ON dv.idVenta = vnta.id
INNER JOIN sucursales suc ON vnta.idSucursal = suc.id
WHERE dv.idProducto= "' . $ident . '" AND vnta.estatus=2) 




ORDER BY fecha ASC';
// echo $sqlDetallado.'</br></br>';
$resdet = mysqli_query($link, $sqlDetallado) or die($error = 'Problemas al listar los Sucursales2, notifica a tu Administrador' . mysqli_error($link));
$rowNueva="";
$iteracion=1;
$cantReg=mysqli_num_rows($resdet);
if($cantReg==0){
  $estTabla='
  <div class="alert alert-warning" role="alert">
Sin Movimientos Registrados por el momento.
</div>
  ';
}
while($data=mysqli_fetch_array($resdet)){
 $rowNueva='<tr><td id="detallado-' . $data['id'] . '" class=""><b>' . $iteracion. '</b></td>';
 $rowNueva.='<td id="detallado-' . $data['id'] . '" class=""><b>' . $data["id"] . '</b></td>';
 $rowNueva.='<td id="detallado-' . $data['id'] . '" class=""><b>' . $data["fecha"] . '</b></td>';
 $rowNueva.='<td id="detallado-' . $data['id'] . '" class=""><b>' . $data["cantFinal"] . '</b></td>';

 $rowNueva.='<td id="detallado-' . $data['id'] . '" class=""><b>' . $data["detallado"] . '</b></td></tr>';

  $estTabla .= $rowNueva;


  $iteracion++;

}


$estTabla .= "</tbody></table>";
echo $estTabla;
