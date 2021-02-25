<?php
require_once('../include/connect.php');
$idAjuste='';
$tipo_num='';
$estTabla='<div class="row">
<div class="col-md-12">
<table class="table">
  <thead>
      <th>#</th>
      <th>Producto</th>
      <th>Cantidad</th>
      <th>Cantidad Actual</th>
      <th>Loteo Manual</th>

  </thead>
  <tbody>

';

$sqlDetallado = "SELECT
ajs.id AS ajuste,
det.id,
prod.id AS idProducto,
prod.descripcion AS producto,
det.cantidad,
IF(tipo='1','table-success','table-danger') AS tipo,
stk.cantActual,
det.tipo AS tipo_num,
det.cantidad AS cantidadsola,
IF(lotes.cantLotes IS NULL, '0',lotes.cantLotes) AS verificarlote,
IF(dtla.cantLotes IS NULL, '0',dtla.cantLotes) AS cantidadLotes


FROM
ajustes ajs
INNER JOIN detajustes det ON det.idAjuste=ajs.id AND ajs.idSucursal='$sucursal' AND ajs.id='$id'
INNER JOIN productos prod ON det.idProducto=prod.id
INNER JOIN stocks stk ON stk.idProducto=prod.id AND stk.idSucursal='$sucursal'
LEFT JOIN (SELECT idStock, COUNT(id) AS cantLotes FROM lotestocks GROUP BY(idStock)) lotes ON lotes.idStock =stk.id
LEFT JOIN (SELECT idDetAjuste AS id, COUNT(id) AS cantLotes FROM detajustelote GROUP BY(idDetAjuste)) dtla ON det.id=dtla.id

ORDER BY det.tipo
";
//echo $sqlDetallado;
$resdet = mysqli_query($link, $sqlDetallado) or die($error = 'Problemas al listar los detalles de Ajustes, notifica a tu Administrador' . mysqli_error($link));
$estTabla.="<tbody>";
$it=1;
$encender='';
while($res = mysqli_fetch_array($resdet)){
 $checked='';
  $idAjuste=$res['ajuste'];
  if($res['cantActual']<=0){
    $estTabla.= '<tr id="tr'.$res["idProducto"].'>';
    $color='bg-light';
    $check='';

  
  }
  else{
    $estTabla.= '<tr id="tr'.$res["idProducto"].'" >';
    $color='';
    $btn_estatus='';
    if($res['cantidadLotes']>0){
      $encender='cargalotes('.$res["idProducto"].', '.$res['cantidadsola'].','.$res['id'].');
      ';

      $checked=" checked ";
    $check='<input type="hidden" name="id_ajuste" id="id_ajuste" value="'.$idAjuste.'"><input type="checkbox" value="1" class="loteoCheck" name="chck_auto_'.$res["id"].'" id="loteo_manual-'.$res["idProducto"].'" '.$checked.' onclick="cargalotes('.$res["idProducto"].', '.$res['cantidadsola'].','. $res['id'] . ') ">';




    }
  }
  $idAjuste=$res['ajuste'];
  $tipo_num=$res['tipo_num'];
  $estTabla.= '<td  class="'.$color.'">' . $it . '</td>';
  $estTabla.= '<td  class="'.$color.'">' . $res["producto"] . '</td>';
  $estTabla.= '<td  class="'.$color.'">' . $res["cantidad"] . '</td>';
  $estTabla.= '<td  class="'.$color.'">' . $res["cantActual"] . '</td>';
  $estTabla.= '<td  class="'.$color.' text-center">'.$check.'</td></tr>';
  $estTabla.='<script>'.$encender.'</script>';
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
 $estTabla.="</tbody></table></div></div> 
 
 <div class=\"modal-footer\"><button type=\"button\" class=\"btn btn-danger\" data-dismiss=\"modal\">Salir</button>
 <button type='button' onclick=\"ajustar();\" $btn_estatus class=' btn btn-success'>Ajustar</button></div>

 ";

