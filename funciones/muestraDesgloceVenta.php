<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$idVenta = (!empty($_POST['idVenta'])) ? $_POST['idVenta'] : 0 ;
$pyme = $_SESSION['LZFpyme'];
if ($idVenta <  1) {
  errorBD('No se reconoció la venta, actualiza e intenta otra vez, si persiste notifica a tu Administrador');
}
$sql = "SELECT p.descripcion AS nomProducto, dv.precioVenta, IF(dv.cantCancel > 0,(dv.cantidad - dv.cantCancel), dv.cantidad) AS cant,
	(dv.precioVenta * IF(dv.cantCancel > 0,(dv.cantidad - dv.cantCancel), dv.cantidad)) AS subtotal
FROM detventas dv
INNER JOIN productos p ON dv.idProducto = p.id
WHERE dv.idVenta = '$idVenta'";
$res = mysqli_query($link,$sql) or die(errorBD('Problemas al consultar los productos, notifica a tu Administrador.'));
$cont = $total = 0;
$tabla = '';
$tabla .= '<div class="table-responsive">
          <table class="table">
              <thead class="bg-'.$pyme.' text-white">
                  <tr>
                      <th scope="col">#</th>
                      <th scope="col">Descripción</th>
                      <th scope="col">Precio</th>
                      <th scope="col">Cantidad</th>
                      <th scope="col">Subtotal</th>
                  </tr>
              </thead>
              <tbody>';
        while ($pd = mysqli_fetch_array($res)) {
          ++$cont;
          $total += $pd['subtotal'];
      $tabla .= '
                  <tr>
                    <td scope="col">'.$cont.'</td>
                    <td scope="col">'.$pd['nomProducto'].'</td>
                    <td scope="col" class="text-right">$ '.number_format($pd['precioVenta'],2,'.',',').'</td>
                    <td scope="col" class="text-right">'.number_format($pd['cant'],2,'.',',').'</td>
                    <td scope="col" class="text-right">$ '.number_format($pd['subtotal'],2,'.',',').'</td>
                  </tr>';
                }
      $tabla .= '
                  <tr>
                    <td colspan="4" class="text-right"><b>TOTAL</b></td>
                    <td scope="col" class="text-right"><b>$ '.number_format($total,2,'.',',').'</b></td>
                  </tr>
              </tbody>
          </table>
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-success" onClick="imprimeTicketVenta('.$idVenta.');">Imprimir</button>
      </div>';
echo '1|'.$tabla;

function errorBD($error){
  echo '0|'.$error;
}
 ?>
