<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');
$sucursal=$_SESSION['LZFidSuc'];
$userReg=$_SESSION['LZFident'];
$contenido='';
$ident = (!empty($_POST['ident'])) ? $_POST['ident'] : 0 ;
$cantidadTotal = (!empty($_POST['cantidad'])) ? $_POST['cantidad'] : 0 ;
$tipo = (!empty($_POST['tipo'])) ? $_POST['tipo'] : 0 ;
if ($ident < 1) {
    errorBD('No se reconoció el producto, vuelve a intentarlo, si persiste notifica a tu Administrador');
  }
  
$sqlSelectLote = 
"SELECT
lote.lote,
lote.id,
lote.cant
FROM
lotestocks lote
INNER JOIN productos prod ON prod.id = lote.idProducto 
AND lote.idProducto = '$ident'
INNER JOIN sucursales suc ON lote.idSucursal = suc.id 
AND lote.idSucursal = '$sucursal'";

$res = mysqli_query($link, $sqlSelectLote) or die($error = 'Problemas al listar los detalles de Ajustes, notifica a tu Administrador' . mysqli_error($link));
$totales=0;
while($data = mysqli_fetch_array($res)){
      $totales+=$data["cant"];
      $id=$data["id"];
      $lote=$data["lote"];
      $cantidad=$data["cant"];
      $contenido.="<tr class='loteo-$ident'>
      <td colspan='2'><label>$lote<input type='hidden' name='id_lote'  value='$id' id='lote-$id'></label></td>
      <td colspan='1'>Disponible: <label class='text-info text-center'>$cantidad</label></td>
      <td colspan='2'> <input type='number'  value='0'  class='sum_prod_$ident'  max='$cantidad' onchange='revisarcantidad(this.value,$cantidad,\"cantlote-$id\", \"$lote\", \"sum_prod_$ident\", $cantidadTotal, $tipo)' id='cantlote-$id' name='cantlote-$id'></td>
      </tr>";

}
if($contenido==""){
$contenido="<tr class='loteo-$ident'><td colspan='5'><div class='alert alert-danger' role='alert'>
Por el momento, no hay lotes registrados.
</div></td></tr>";
}
//echo $sqlSelectLote;

 echo $contenido;
