<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');
$sucursal=$_SESSION['LZFidSuc'];
$userReg=$_SESSION['LZFident'];
$tarjetaEntrada="
<div class='row'>
<div class='col-md-6'>
    <div class='card'>
        <div class='card-header bg-success'>
            <h4 class='m-b-0 text-white'><i class=' fas fa-arrow-down'></i> Ajuste de Entrada</h4>
        </div>
        <div class='card-body'>
        <table class='table'>
  <thead>
      <th>#</th>
      <th>Producto</th>
      <th>Cantidad</th> 
  </thead>
  <tbody>";
$tarjetaSalida="
<div class='col-md-6'>
    <div class='card'>
        <div class='card-header bg-danger'>
            <h4 class='m-b-0 text-white'><i class='fas fa-arrow-up'></i> Ajuste de Salida</h4>
        </div>
        <div class='card-body'>
        <table class='table'>
  <thead>
      <th>#</th>
      <th>Producto</th>
      <th>Cantidad</th> 
  </thead>
  <tbody>";

$ident = (!empty($_POST['ident'])) ? $_POST['ident'] : 0 ;
if ($ident < 1) {
    errorBD('No se reconoció el producto, vuelve a intentarlo, si persiste notifica a tu Administrador');
  }
  
$sqlDetallado = "
SELECT
prod.descripcion AS producto,
detajs.cantidad AS cantidad,
detajs.tipo 
FROM
ajustes ajs 
INNER JOIN detajustes detajs ON detajs.idAjuste=ajs.id 
INNER JOIN productos prod ON detajs.idProducto=prod.id
WHERE ajs.id='$ident'
";
$resdet = mysqli_query($link, $sqlDetallado) or die($error = 'Problemas al listar los detalles de Ajustes, notifica a tu Administrador' . mysqli_error($link));
$cant_rows_entrada=1;
$cant_rows_salida=1;
while($res = mysqli_fetch_array($resdet)){

  if ($res['tipo']==1) {
   $tarjetaEntrada.='<tr><td  class="">' . $cant_rows_entrada. '</td>';
   $tarjetaEntrada.='<td  class=""><b>' . $res["producto"] . '</b></td>';
   $tarjetaEntrada.= '<td  class=""><b>' . $res["cantidad"] . '</b></td></tr>';
   $cant_rows_entrada++;
  }
  else{
    $tarjetaSalida.='<tr><td  class="">' . $cant_rows_salida. '</td>';
    $tarjetaSalida.='<td  class=""><b>' . $res["producto"] . '</b></td>';
    $tarjetaSalida.= '<td  class=""><b>' . $res["cantidad"] . '</b></td></tr>';
    $cant_rows_salida++;
  }
  

}

 
 echo $tarjetaEntrada.'</tbody></table></div></div></div>'.$tarjetaSalida.'</tbody></table></div></div></div>';
