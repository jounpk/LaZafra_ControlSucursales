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
      <th>Cantidad</th>
      <th>Modificación</th>
      
      
      <tbody>';
  $sql="SELECT idProducto FROM stocks WHERE idProducto='$ident' AND idSucursal=$sucursal";
  $resStk= mysqli_query($link, $sql) or die('Problemas al consultar producto, notifica a tu Administrador');
  $idProd=mysqli_fetch_array($resStk)['idProducto'];
  $sqlQuery = "SELECT id,lote, cant FROM lotestocks WHERE lotestocks.idProducto='$idProd' AND lotestocks.idSucursal='$sucursal'";
  $resPro = mysqli_query($link, $sqlQuery) or die('Problemas al listar los Productos, notifica a tu Administrador');
  if(mysqli_num_rows($resPro)>0){
    while ($data = mysqli_fetch_array($resPro)) {
      $idlote=$data['id'];
      $estTabla.="
      <tr>
      <td class='text-left'>".$data["lote"]."</td>
      <td ><input type='number' id='cantAct$idlote' value='".$data["cant"]."' min='0'/></td>
      <td class='text-center' id='modPro-$idlote'>
      <button id='btnSave-$idlote' data-toggle='tooltip' data-placement='top' title='' data-original-title='Guardar'
       onclick='guardastock($idlote);' type='button' style=' box-shadow: 7px 10px 12px -4px rgba(0,0,0,0.62); color:#fff' class='btn btn-circle btn-success btn-circle-tablita'>
        <i class='fas fa-check'></i>
      </button></td>


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
  ?>
