<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');
$sucursal=$_SESSION['LZFidSuc'];
$userReg=$_SESSION['LZFident'];
$pyme = $_SESSION['LZFpyme'];

$ident = (!empty($_POST['ident'])) ? $_POST['ident'] : 0 ;
if ($ident < 1) {
    echo ('No se reconoció la Venta, vuelve a intentarlo, si persiste notifica a tu Administrador');
  }
else{ 
?>

<div class='row'>
<div class='col-md-12'>
    <div class='card'>
        <div class='card-header bg-<?=$pyme?>'>
            <h4 class='m-b-0 text-white'> Detalles de Venta</h4>
        </div>
        <div class='card-body'>
        <table class='table'>
  <thead>
      <th>#</th>
      <th>Producto</th>
      <th>Cantidad</th> 
      <th>Subtotal</th> 
      <th>Importe</th>
  </thead>
  <tbody>
<?php

$sqlDetallado = "SELECT
prod.descripcion AS producto,
CONCAT('$',FORMAT(dtvta.cantidad,2)) AS cantidad,
CONCAT('$',FORMAT(dtvta.precioVenta,2)) AS precioVenta,
CONCAT('$',FORMAT((dtvta.cantidad*dtvta.precioVenta),2)) AS total
FROM
detventas dtvta 
INNER JOIN productos prod ON prod.id=dtvta.idProducto
WHERE dtvta.idVenta='$ident'
";
$resdet = mysqli_query($link, $sqlDetallado) or die($error = 'Problemas al listar los detalles de Ajustes, notifica a tu Administrador');
$contador=1;
while($res = mysqli_fetch_array($resdet)){
  echo '<tr>';
  echo '<td>'.$contador.'</td>';
  echo '<td>'.$res['producto'].'</td>';
  echo '<td>'.$res['cantidad'].'</td>';
  echo '<td>'.$res['precioVenta'].'</td>';

  echo '<td>'.$res['total'].'</td>';
  $contador++;


 

}
}
?>
 
</tbody></table></div></div></div>
