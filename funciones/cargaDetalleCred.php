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
            <h4 class='m-b-0 text-white'><i class='far fa-money-bill-alt'></i> Crédito a Cobrar por Sucursal</h4>
        </div>
        <div class='card-body'>
        <table class='table'>
  <thead>
      <th>#</th>
      <th>Folio Venta</th> 
      <th>Sucursal</th>
      <th>Monto Total</th>
      <th>Debe</th>

  </thead>
  <tbody>


 <?php   
$ident = (!empty($_POST['ident'])) ? $_POST['ident'] : 0 ;
if ($ident < 1) {
    errorBD('No se reconoció el crédito, vuelve a intentarlo, si persiste notifica a tu Administrador');
  }
  
$sqlDetallado = "SELECT
cr.idVenta,
FORMAT(cr.totalDeuda,2) AS totalDeuda,
FORMAT(cr.montoDeudor,2) AS montoDeudor,
suc.nombre AS sucursal
FROM
creditos cr 
INNER JOIN ventas vta ON cr.idVenta= vta.id 
INNER JOIN sucursales suc ON vta.idSucursal=suc.id
WHERE
cr.idCliente = '$ident' 
ORDER BY
cr.totalDeuda DESC";
//echo $sqlDetallado;
$resdet = mysqli_query($link, $sqlDetallado) or die($error = 'Problemas al listar los detalles de Credito, notifica a tu Administrador' . mysqli_error($link));
$count=1;
if(mysqli_num_rows($resdet)<=0){
  echo "<tr><td class='text-center text-danger' colspan='6'>No hay Pagos Registrados por el momento</td></tr>";
}
else{


while($dat = mysqli_fetch_array($resdet)){
  
  echo "
    <tr>
      <td>$count</td>
      <td>#".$dat["idVenta"]."</td>
      <td>".$dat["sucursal"]."</td>
      <td>$".$dat["totalDeuda"]."</td>
      <td>$".$dat["montoDeudor"]."</td>
    </tr>

  
  ";
  $count++;
}

}
function errorBD($error){
  #    echo '<br>Manda a venta, todo bad';
     echo $error;
  }
?>
 
</tbody></table></div></div></div>
