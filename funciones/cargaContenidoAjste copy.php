<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');
$sucursal=$_SESSION['LZFidSuc'];
$userReg=$_SESSION['LZFident'];
$estTabla='<div class="row"><div class="col-md-1"></div>
<div class="col-md-10">
<table class="table">
  <thead>
  <tr>
    <th colspan="2">Entradas</th>
    <th colspan="2">Salidas</th>
  </tr>
  <tr>
      <th>Producto</th>
      <th>Cantidad</th>
      <th>Producto</th>
      <th>Cantidad</th>
  </tr>
  </thead>
  <tbody>

';
$ident = (!empty($_POST['ident'])) ? $_POST['ident'] : 0 ;
if ($ident < 1) {
    errorBD('No se reconoció el producto, vuelve a intentarlo, si persiste notifica a tu Administrador');
  }
  
$sqlDetallado = "SELECT
prodEntrada.descripcion AS productoEntrada,
detajsEntrada.cantidad AS cantidadEntrada,
prodSalida.descripcion AS productoSalida,
detajsSalida.cantidad AS cantidadSalida
FROM
ajustes ajs 
INNER JOIN detajustes detajsEntrada ON detajsEntrada.idAjuste=ajs.id AND detajsEntrada.tipo='1'
INNER JOIN detajustes detajsSalida ON detajsSalida.idAjuste=ajs.id AND detajsSalida.tipo='2'
INNER JOIN productos prodEntrada ON detajsEntrada.idProducto=prodEntrada.id
INNER JOIN productos prodSalida ON detajsSalida.idProducto=prodSalida.id
WHERE ajs.id='$ident'
";
$resdet = mysqli_query($link, $sqlDetallado) or die($error = 'Problemas al listar los detalles de Ajustes, notifica a tu Administrador' . mysqli_error($link));
$estTabla.="<tbody>";
$cantidadEntrada=0;
$cantidadSalida=0;
while($res = mysqli_fetch_array($resdet)){
  $cantidadSalida=$cantidadSalida+$res["cantidadSalida"];
  $cantidadEntrada=$cantidadEntrada+$res["cantidadEntrada"];
  $estTabla.= '<tr><td  class=""><b>' . $res["productoEntrada"] . '</b></td>';
  $estTabla.= '<td  class=""><b>' . $res["cantidadEntrada"] . '</b></td>';
  $estTabla.= '<td  class=""><b>' . $res["productoSalida"] . '</b></td>';
  $estTabla.= '<td  class=""><b>' . $res["cantidadSalida"] . '</b></td></tr>';

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
 $estTabla.="</tbody></table></div><div class='col-md-1'></div></div>";
 echo $estTabla;
