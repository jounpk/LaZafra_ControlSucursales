<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$idCompra = (!empty($_POST['idCompra'])) ? $_POST['idCompra'] : 0 ;

$sqlProd = "SELECT dc.idProducto AS 'idProducto',dc.id AS 'idDetCompra',dc.descripcion AS nomProducto, dc.cantidad AS cantComprada,
            IF(SUM(dr1.cantidad) > 0, (dc.cantidad - SUM(dr1.cantidad)), dc.cantidad ) AS cantResta,
            IF(SUM(dr2.cantidad) >0,SUM(dr2.cantidad),0) AS cantRecibida, GROUP_CONCAT(dr2.lote SEPARATOR ',') AS lotes
            FROM compras c
            INNER JOIN (
              SELECT dc.id,dc.cantidad,dc.idCompra,IF(dc.idProducto > 0,dc.idProducto,0) AS idProducto, IF(dc.idProducto > 0,p.descripcion,dc.nombreProducto) AS descripcion
                  FROM detcompras dc
                  LEFT JOIN productos p ON dc.idProducto = p.id
                  WHERE dc.idCompra = '$idCompra'
            ) dc ON c.id = dc.idCompra
            LEFT JOIN (
            SELECT idDetCompra, IF(SUM(cantidad)>0,SUM(cantidad),0) AS 'cantidad',idProducto FROM detrecepciones WHERE estatus = '2' GROUP BY idDetCompra
            ) dr1 ON dc.id = dr1.idDetCompra AND dc.idProducto = dr1.idProducto
            LEFT JOIN (
            SELECT dr.idDetCompra, IF(SUM(dr.cantidad)>0,SUM(dr.cantidad),0) AS 'cantidad',lts.lote,dr.idProducto
            FROM detrecepciones dr
            LEFT JOIN lotestocks lts ON dr.idLote = lts.id
            WHERE dr.estatus = '1' GROUP BY dr.idDetCompra
            ) dr2 ON dc.id = dr2.idDetCompra AND dc.idProducto = dr2.idProducto
            WHERE c.id = '$idCompra'
            GROUP BY dc.id
            ORDER BY dc.descripcion ASC";
$resProd = mysqli_query($link,$sqlProd) or die('Problemas al consultar los productos, notifica a tu Administrador.');
$cont = 0;
while ($lst = mysqli_fetch_array($resProd)) {
  $lstLotes = ($lst['lotes'] != '') ? '<ul class="list-style-none"><li>'.$lst['lotes'].'</li></ul>' : '' ;
  $cantResta = $lst['cantResta'] - $lst['cantRecibida'];
  $bloquea = ($cantResta == 0) ? 'disabled' : '' ;
  echo '<tr>
          <td class="text-center">'.++$cont.'</td>
          <td>'.$lst['nomProducto'].'</td>
          <td class="text-right">'.number_format($lst['cantComprada'],2,'.',',').'</td>
          <td class="text-right">'.number_format($lst['cantResta'],2,'.',',').'</td>
          <td class="text-right">'.number_format($lst['cantRecibida'],2,'.',',').'</td>
          <td>'.$lstLotes.'</td>
          <td class="text-center">
            <button type="button" class="btn btn-outline-info btn-circle" data-toggle="modal" data-target="#modalRecibeProducto" onclick="agregaRecepcion('.$lst['idDetCompra'].','.$lst['idProducto'].')" '.$bloquea.'>
              <i class="fas fa-pencil-alt"></i>
            </button>
            <input type="hidden" id="cantProd-'.$lst['idProducto'].'" value="'.$cantResta.'">
          </td>
        </tr>';
}

 ?>
