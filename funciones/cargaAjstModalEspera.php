<?php
require_once('../include/connect.php');
$estTabla='<div class="row">
<div class="col-md-12">
<table class="table">
  <thead>
      <th>#</th>
      <th>Producto</th>
      <th>Cantidad</th>
      <th>Cantidad Actual</th>

  </thead>
  <tbody>

';

$sqlDetallado = "
SELECT
prod.id AS idProducto,
prod.descripcion AS producto,
CASE det.tipo
WHEN '1' THEN
  CONCAT(\"+\", det.cantidad)
WHEN '2' THEN
  CONCAT(\"-\", det.cantidad)
END AS cantidad,
IF(tipo='1',\"table-success\",\"table-danger\") AS tipo,
stk.cantActual
FROM
ajustes ajs
INNER JOIN detajustes det ON det.idAjuste=ajs.id AND ajs.idSucursal='$sucursal' AND ajs.id='$id'
INNER JOIN productos prod ON det.idProducto=prod.id
INNER JOIN stocks stk ON stk.idProducto=prod.id AND stk.idSucursal='$sucursal'
ORDER BY det.tipo
";
$resdet = mysqli_query($link, $sqlDetallado) or die($error = 'Problemas al listar los detalles de Ajustes, notifica a tu Administrador' . mysqli_error($link));
$estTabla.="<tbody>";
$it=1;
while($res = mysqli_fetch_array($resdet)){
 
  $estTabla.= '<tr id="producto-'.$res["idProducto"].'" class="'.$res["tipo"].'"><td  class="">' . $it . '</td>';
  $estTabla.= '<td  class="">' . $res["producto"] . '</td>';
  $estTabla.= '<td  class="">' . $res["cantidad"] . '</td>';
  $estTabla.= '<td  class="">' . $res["cantActual"] . '</td>';
  $it++;
}

 /*$estFooter=
 "<tfoot>
 <tr>
      <th>Balance</th>
      <td >$cantidadEntrada</td>
      <th>Balance</th>
      <td >$cantidadSalida</td>
  </tr>
 </tfoot>
 ";*/
 $estTabla.="</tbody></table></div></div>";

