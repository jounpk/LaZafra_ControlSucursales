<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$idProd = (!empty($_POST['idProd'])) ? $_POST['idProd'] : 0 ;
$idVenta = (!empty($_POST['idVenta'])) ? $_POST['idVenta'] : 0 ;

if ($idProd == 0 || $idVenta == 0) {
  errorBD('No se reconoció el producto, actualiza e inténtalo nuevamente, si persiste notifica a tu Administrador.');
}
$contenido = '';
$sql = "SELECT dls.idLote,ls.lote, IF(dv.cantCancel > 0,(dls.cant - dv.cantCancel),dls.cant) AS cantEnLote,dv.id AS idDetVenta
FROM detventas dv
INNER JOIN detvtalotes dls ON dv.id = dls.idDetVenta
INNER JOIN lotestocks ls ON dls.idLote = ls.id
WHERE dv.idVenta = '$idVenta' AND dv.idProducto = '$idProd'";
$res = mysqli_query($link,$sql) or die(errorBD('Problemas al consultar los lotes, notifica a tu Administrador.'));
$cant = mysqli_num_rows($res);
if ($cant > 0) {
  $contenido .= '<select class="form-control" id="lotestocks" onChange="habilitaCant(this.value);">';
  $contenido .= '<option value="">Selecciona un lote</option>';
    while ($list = mysqli_fetch_array($res)) {
      $contenido .= '<option value="'.$list['idLote'].'">'.$list['lote'].' ('.$list['cantEnLote'].' Productos)</option>
                      <input id="lote-'.$list['idLote'].'" type="hidden" value="'.$list['cantEnLote'].'">
                      <input id="idDetVenta-'.$list['idLote'].'" type="hidden" value="'.$list['idDetVenta'].'">';
    }
}

$contenido .= '</select>';
echo '1|'.$contenido;

exit(0);

function errorBD($error){
  echo '0|'.$error;
  exit(0);
}
 ?>
