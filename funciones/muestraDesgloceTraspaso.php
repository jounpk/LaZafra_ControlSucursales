<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$idTraspaso = (!empty($_POST['idTraspaso'])) ? $_POST['idTraspaso'] : 0 ;
$pyme = $_SESSION['LZFpyme'];
if ($idTraspaso <  1) {
  errorBD('No se reconoció el Traspaso, actualiza e intenta otra vez, si persiste notifica a tu Administrador');
}
$sql = "SELECT pro.descripcion,det.*
          FROM dettraspasos det
          LEFT JOIN productos pro ON det.idProducto = pro.id
					WHERE idTraspaso = '$idTraspaso'";
$res = mysqli_query($link,$sql) or die(errorBD('Problemas al consultar los productos, notifica a tu Administrador.',$sql));
$cont = $total = 0;
$tabla = '';
$tabla .= '<div class="table-responsive">
          <table class="table">
              <thead class="bg-'.$pyme.' text-white">
                  <tr>
                      <th scope="col">#</th>
                      <th scope="col">Producto</th>
                      <th scope="col">Enviado</th>
                      <th scope="col">Recibido</th>
                      <th scope="col">Nota</th>
                  </tr>
              </thead>
              <tbody>';
        while ($pd = mysqli_fetch_array($res)) {
          ++$cont;
      $tabla .= '
                  <tr>
                    <td scope="col">'.$cont.'</td>
                    <td scope="col">'.$pd['descripcion'].'</td>
                    <td scope="col">'.$pd['cantEnvio'].'</td>
                    <td scope="col">'.$pd['cantRecepcion'].'</td>
                    <td scope="col">'.$pd['nota'].'</td>
                  </tr>';
                }
      $tabla .= '
              </tbody>
          </table>
      </div>
      <br>
      <a href="../funciones/cambiaEstausBodega.php?idTraspaso='.$idTraspaso.'&tipo=1" class="btn btn-rounded btn-success btn-block text-center">Comenzar a Cargar</a>
      <br>
      <div class="modal-footer">
          <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Cerrar</button>
      </div>';
echo '1|'.$tabla;

function errorBD($error,$sql){
  echo '0|'.$error.'|'.$sql;
}
 ?>
