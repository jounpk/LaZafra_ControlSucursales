<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');
$sucursal=$_SESSION['LZFidSuc'];
$userReg=$_SESSION['LZFident'];
$estTabla='<div class="row"><div class="col-md-2"></div><div class"col-md-8"><table class="table"><thead>
<th>Producto</th>
<th>Cantidad Actual</th>
<th>Cantidad Enviar</th>


';
$ident = (!empty($_POST['ident'])) ? $_POST['ident'] : 0 ;
if ($ident < 1) {
    errorBD('No se reconoció el producto, vuelve a intentarlo, si persiste notifica a tu Administrador');
  }
  
$sqlDetallado = "SELECT prod.descripcion AS producto, stk.cantActual, det.cantEnvio FROM
dettraspasos det
INNER JOIN traspasos tras ON det.idTraspaso = tras.id AND tras.id='$ident'
INNER JOIN productos prod ON det.idProducto =prod.id
INNER JOIN stocks stk ON stk.idSucursal=tras.idSucSalida AND stk.idProducto=prod.id
ORDER BY producto ASC";
                                            //echo $sqlDetallado.'</br></br>';
$resdet = mysqli_query($link, $sqlDetallado) or die($error = 'Problemas al listar los Sucursales2, notifica a tu Administrador' . mysqli_error($link));
$estTabla.="</thead><tbody>";

while($res = mysqli_fetch_array($resdet)){
  $estTabla.= '<tr><td  class=""><b>' . $res["producto"] . '</b></td>';
  $estTabla.= '<td  class=""><b>' . $res["cantActual"] . '</b></td>';
  $estTabla.= '<td  class=""><b>' . $res["cantEnvio"] . '</b></td></tr>';

}


 
 $estTabla.="</tbody></table></div><div class='col-md-2'></div></div>";
 echo $estTabla;
