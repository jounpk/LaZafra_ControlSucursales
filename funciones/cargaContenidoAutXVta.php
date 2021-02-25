<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');
$sucursal=$_SESSION['LZFidSuc'];
$userReg=$_SESSION['LZFident'];
$pyme = $_SESSION['LZFpyme'];

?>
<style>
    .btn-circle-sm {
      width: 35px;
      height: 35px;
    /*  line-height: 35px;*/
     /* font-size: 0.9rem;*/
     /* background: #fff;*/
      box-shadow: 7px 10px 12px -4px rgba(0, 0, 0, 0.62);
    }

</style>
<div class='row'>
<div class='col-md-12'>
    <div class='card'>
        <div class='card-header bg-<?=$pyme?>'>
            <h4 class='m-b-0 text-white'><i class=' fas fa-shopping-basket'></i> Detalle de Venta</h4>
        </div>
        <div class='card-body'>
        <table class='table'>
  <thead>
      <th>#</th>
      <th>Descripción</th>
      <th>Precio</th>
      <th>Cantidad</th>
      <th>Subtotal</th> 
   
  </thead>
  <tbody>


 <?php   
$colorTexto='';
$ident = (!empty($_POST['ident'])) ? $_POST['ident'] : 0 ;
if ($ident < 1) {
    errorBD('No se reconoció el detalle de la Compra, vuelve a intentarlo, si persiste notifica a tu Administrador');
  }
  
$sqlDetallado = "SELECT
dc.*, pr.descripcion AS producto,
FORMAT (dc.costo,2) AS format_costo,
FORMAT (dc.subtotal,2) AS format_subtotal,
FORMAT(dc.cantidad,2) AS format_cantidad,
FORMAT(dc.precioCoti,2)  AS format_precio

FROM
cotizaciones ct
INNER JOIN detcotizaciones dc ON ct.id=dc.idCotizacion
INNER JOIN productos pr ON dc.idProducto= pr.id
WHERE dc.idCotizacion='$ident'";
//echo $sqlDetallado;
$resdet = mysqli_query($link, $sqlDetallado) or die($error = 'Problemas al listar los detalles de la Compra, notifica a tu Administrador' . mysqli_error($link));
$count=1;
if(mysqli_num_rows($resdet)<=0){
  echo "<tr><td class='text-center text-danger' colspan='6'>No hay Detalle de la Cotizacion por el momento</td></tr>";
}
else{


while($dat = mysqli_fetch_array($resdet)){
  $colorTexto='';
  if($dat['asignaPrecio']==2){
    $colorTexto='text-warning';
  }
  echo "
    <tr>
      <td>$count</td>
      <td>".$dat["producto"]."</td>
      <td class='".$colorTexto."'>$ ".$dat["format_precio"]."</td>
      <td>".$dat["format_cantidad"]."</td>
      <td>$ ".$dat["format_subtotal"]."</td>
    </tr>

  
  ";
  $count++;
}

}
?>
 
</tbody></table></div></div></div>
