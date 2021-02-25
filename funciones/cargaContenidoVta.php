<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');
$sucursal=$_SESSION['LZFidSuc'];
$userReg=$_SESSION['LZFident'];
$estTabla='<div class="row"><div class="col-md-2"></div><div class="col-md-8"><table class="table"><thead>';

$ident = (!empty($_POST['ident'])) ? $_POST['ident'] : 0 ;
if ($ident < 1) {
    errorBD('No se reconoció el producto, vuelve a intentarlo, si persiste notifica a tu Administrador');
  }
 
      $estTabla.= 
      '<th class="text-left" >Lote</th>
      <th>Cantidad</th><tbody>';
  
  $sqlQuery = "SELECT lote, cant FROM lotestocks WHERE lotestocks.idProducto='$ident' AND lotestocks.idSucursal='$sucursal' AND lotestocks.cant>0 ORDER BY caducidad ASC";
  $resPro = mysqli_query($link, $sqlQuery) or die('Problemas al listar los Productos, notifica a tu Administrador'.mysqli_error($link));
  if(mysqli_num_rows($resPro)>0){
    while ($data = mysqli_fetch_array($resPro)) {
      $estTabla.="
      <tr>
      <td class='text-left'>".$data["lote"]."</td>
      <td >".$data["cant"]."</td>
       <tr>
      
      ";
   
    }
  }
  else{
    $estTabla.="<tr>
    <td colspan='2'>
    <div class='alert alert-warning alert-dismissible fade show' role='alert'>
    <strong>Alerta</strong> No hay lotes registrados en la Sucursal.
    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
      <span aria-hidden='true'>&times;</span>
    </button>
  </div>
    
    </td>
    
    </tr>";
  }
  
                                           
 $estTabla.="</tbody></table></div><div class='col-md-2'></div>";
 echo $estTabla;
 function errorBD($error){
  echo ''.$error;
  exit(0);
}
  ?>
