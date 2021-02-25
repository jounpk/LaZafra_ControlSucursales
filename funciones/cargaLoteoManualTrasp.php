<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');
$sucursal=$_SESSION['LZFidSuc'];
$userReg=$_SESSION['LZFident'];
$contenido='';
$ident = (!empty($_POST['ident'])) ? $_POST['ident'] : 0 ;
$idDetTrasp=(!empty($_POST['idDetTrasp'])) ? $_POST['idDetTrasp'] : 0 ;
$cantidadTotal = (!empty($_POST['cantidad'])) ? $_POST['cantidad'] : 0 ;

if ($ident < 1) {
    errorBD('No se reconoció el producto, vuelve a intentarlo, si persiste notifica a tu Administrador');
  }
  
$sqlSelectLote = 
"SELECT
lote.lote,
lote.id,
lote.cant,
prod.medios,
prod.id AS producto,
dt.cantidad AS cantAsignada
FROM
lotestocks lote
LEFT JOIN dettrasplote dt  ON lote.id=dt.idLoteSalida AND dt.idDetTraspaso='$idDetTrasp'
INNER JOIN productos prod ON lote.idProducto=prod.id 
INNER JOIN sucursales suc ON lote.idSucursal = suc.id 
AND lote.idSucursal = '$sucursal'
WHERE lote.idProducto='$ident'

";

$res = mysqli_query($link, $sqlSelectLote) or die($error = 'Problemas al listar los detalles de Ajustes, notifica a tu Administrador' . mysqli_error($link));
while($data = mysqli_fetch_array($res)){
     $descripcion=$data['medios']=='1'?'step="any"':'step="1"';
      $producto=$data["producto"];
      $colorlt='';
      $id=$data["id"];
      $lote=$data["lote"];
      $cantidad=$data["cant"];
      $cantAsignada=$data["cantAsignada"];
      if($data['medios']=='0'){
        $cantAsignada=intval($cantAsignada);

      }
      if ($cantidad<$cantAsignada){
        $colorlt="table-danger";
      }
      $contenido.="<tr class='$colorlt loteo-$ident'>
      <td colspan='2'><label>$lote<input type='hidden' name='id_lote'  value='$id' id='lote-$id'></label></td>
      <td colspan='2'>Disponible: <label class='text-info text-center'>$cantidad</label></td>
      <td colspan='1'> <input type='number' value='$cantAsignada' $descripcion data-total='$cantidadTotal' data-maxima='$cantidad'  value='0' min='0' data-dettrasp='$idDetTrasp' class='sum_prod_$ident cant_loteo_$producto'  max='$cantidad' id='lote_$id' onchange='revisarCant(\"$producto\", \"$id\", this.value)'></td>
      <td colspan='1'><div id='area_resultado_$id'></div></td>
      </tr>";

}
if($contenido==""){
$contenido="<tr class='loteo-$ident'><td colspan='5'><div class='alert alert-danger' role='alert'>
Por el momento, no hay lotes registrados.
</div></td></tr>";
}
//echo $sqlSelectLote;

 echo $contenido;
