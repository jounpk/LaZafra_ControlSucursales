<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');
$sucursal=$_SESSION['LZFidSuc'];
$userReg=$_SESSION['LZFident'];
$contenido='';
$ident = (!empty($_POST['ident'])) ? $_POST['ident'] : 0 ;
$cantidadTotal = (!empty($_POST['cantidadTotal'])) ? $_POST['cantidadTotal'] : 0 ;
$idDetAjuste=(!empty($_POST['idDetAjuste'])) ? $_POST['idDetAjuste'] : 0 ;

$tipo = (!empty($_POST['tipo'])) ? $_POST['tipo'] : 0 ;
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
IF(da.cantidad IS NULL, '0', da.cantidad) AS cantAsignada
FROM
lotestocks lote
LEFT JOIN detajustelote da  ON lote.id=da.idLote AND da.idDetAjuste='$idDetAjuste'
INNER JOIN productos prod ON lote.idProducto=prod.id 
INNER JOIN sucursales suc ON lote.idSucursal = suc.id 
AND lote.idSucursal = '$sucursal'
WHERE lote.idProducto='$ident'
";

$res = mysqli_query($link, $sqlSelectLote) or die($error = 'Problemas al listar los detalles de Ajustes, notifica a tu Administrador' . mysqli_error($link));
if(mysqli_num_rows($res)==0){
 // echo "disabled";
}
else{
while($data = mysqli_fetch_array($res)){
      $id=$data["id"];
      $producto=$data["producto"];
      $descripcion=$data['medios']=='1'?'step="any"':'step="1"';

      $lote=$data["lote"];
      $cantidad=$data["cant"];
      $cantAsignada=$data["cantAsignada"];
      if($data['medios']=='0'){
        $cantAsignada=intval($cantAsignada);

      }

      $contenido.="<tr class='loteo-$ident'>
      <td colspan='2'><label>$lote<input type='hidden' name='id_lote'  value='$id' id='lote-$id'></label></td>
      <td colspan='1'>Disponible: <label class='text-info text-center'>$cantidad</label></td>
      <td colspan='1'> <input type='number' class='cant_loteo_$producto' $descripcion value='$cantAsignada' value='0' min='0' data-detajuste='$idDetAjuste' class='sum_prod_$ident'  max='$cantidad' data-maxima='$cantidad' data-total='$cantidadTotal'id='lote_$id' onchange='revisarCant(\"$producto\", \"$id\", this.value)' name='cantlote-$id'></td>
      <td colspan='1'><div id='area_resultado_$id'></div></td>

      </tr>";

}

//echo $sqlSelectLote;

 echo $contenido;
}
?>
