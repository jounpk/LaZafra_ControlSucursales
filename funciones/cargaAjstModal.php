<?php
require_once('../include/connect.php');
$idAjuste = '';
$tipo_num = '';
$estTabla='';
$estTabla = '<div class="row">
<div class="col-md-12">
<table class="table ">
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
	detajs.id AS idAjuste,
	prod.id AS idProducto,
	prod.descripcion AS producto,
	detajs.cantidad AS cantAjustar,
	detajs.tipo AS tipoAjs,
	stk.cantActual AS cantStock,
  IF(dtla.cantLotes IS NULL, '0',dtla.cantLotes) AS cantidadLotes

FROM
	ajustes ajs
	INNER JOIN detajustes detajs ON ajs.id=detajs.idAjuste
	INNER JOIN productos prod ON detajs.idProducto=prod.id
	INNER JOIN stocks stk ON prod.id=stk.idProducto AND stk.idSucursal='$sucursal'
  LEFT JOIN (SELECT idDetAjuste AS id, COUNT(id) AS cantLotes FROM detajustelote GROUP BY(idDetAjuste)) dtla ON detajs.id=dtla.id
  WHERE ajs.idSucursal='$sucursal' AND ajs.id='$id'
";
//echo $sqlDetallado;
$resdet = mysqli_query($link, $sqlDetallado) or die($error = 'Problemas al listar los detalles de Ajustes, notifica a tu Administrador' . mysqli_error($link));
$estTabla .= "<tbody>";
$it = 1;
$encender = '';
$errores = 0;

while ($res = mysqli_fetch_array($resdet)) {
  $checked = '';
  $it ++;

  $idAjuste = $res['ajuste'];
  if ($res['cantStock'] <= 0 and  $res['tipoAjs'] == '2') {
    $estTabla = '<div class="row">
    <div class="col-md-12"><div class="alert alert-secondary" role="alert">
                  No Hay Producto De Salida.<br>
                  <b>' . $res['producto'] . '</b>  con <b>' . $res['cantStock'] . '</b> en Stock
                </div></div></div>   <div class="modal-footer"><button type="button" class="btn btn-danger" data-dismiss="modal">Salir</button>
               </div>';
    $errores = 1;
    break;
  } else if ($res['cantStock'] < $res['cantAjustar'] and  $res['tipoAjs'] == '2') {

    $estTabla = '<div class="row">
      <div class="col-md-12"><div class="alert alert-secondary" role="alert">
      La Cantidad de Producto Es Menor A la Requerida.
      <b>' . $res['producto'] . '</b>  con ' . $res['cantStock'] . ' en Stock
    </div></div></div><div class="modal-footer"><button type="button" class="btn btn-danger" data-dismiss="modal">Salir</button></div>';
    $errores = 1;
    break;
  } else {

    if ($res['cantidadLotes'] > 0) {
      $encender = 'cargalotes(' . $res["idProducto"] . ', ' . $res['cantAjustar'] . ',' . $res['idAjuste'] . ');
      ';

      $checked=" checked ";
    }



    $check = '<input type="hidden" name="id_ajuste" id="id_ajuste" value="' . $idAjuste . '"><input type="checkbox" data-total="'.$res['cantAjustar'].'" data-producto="'.$res['idProducto'].'" value="1" class="loteoCheck" name="chck_auto_' . $res["idAjuste"] . '" id="loteo_manual-' . $res["idProducto"] . '" ' . $checked . ' onclick="cargalotes(' . $res["idProducto"] . ', ' . $res['cantAjustar'] . ',' . $res['idAjuste'] . ') ">';

    $tipo = $res["tipoAjs"] == '1' ? '<p class="text-success">Entrada</p>' : '<p class="text-danger">Salida</p>';
    $estTabla .= '<tr id="tr' . $res["idProducto"] . '" >';
    $estTabla .= '<td  class="' . $color . '">' . $it . '</td>';
    $estTabla .= '<td  class="' . $color . '">' . $res["producto"] . $tipo . '</td>';
    $estTabla .= '<td  class="' . $color . '">' . $res["cantAjustar"] . '</td>';
    $estTabla .= '<td  class="' . $color . '">' . $res["cantStock"] . '</td>';
    $estTabla .= '<td  class="' . $color . ' text-center">' . $check . '</td></tr>';
    $estTabla .= '<script>' . $encender . '</script>';
    $estTabla .= '</tr>';
  }
}
if ($errores == 0) {
  $estTabla .= "</tbody></table></div></div> <div class=\"modal-footer\"><button type=\"button\" class=\"btn btn-danger\" data-dismiss=\"modal\">Salir</button><button type='button' onclick=\"ajustar('$id');\" class=' btn btn-success'>Ajustar</button></div>";
}
?>
