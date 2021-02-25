<?php
//print_r($_POST);
session_start();

define('INCLUDE_CHECK',1);
require_once('../include/connect.php');


$id = (isset($_POST['ident']) AND $_POST['ident'] != '') ? $_POST['ident'] : '' ;

echo '<p aling="justify">Detallado del Traspaso con <strong>FOLIO: '.$id.'</strong>, al finalizar el traspaso indicas que toda la mercancia llego correctamente.<p>';
echo '<br> ';
echo '<form action="" id="formRecepcion" method="post">
<input type="hidden" id="ident" name="idVenta" value="'.$id.'">
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Producto</th>
                <th>Enviado</th>
            </tr>
        </thead>
        <tbody>';
$sql="SELECT dett.*, prod.descripcion as producto
      FROM dettraspasos dett
      INNER JOIN productos prod ON dett.idProducto=prod.id
      WHERE idTraspaso='$id'";
$res=mysqli_query($link,$sql) or die('<tr><td colspan="5" aling="center">Error de Consulta.'.$sql.'</td></tr>');
$cont=0;
$identificadores="";
$productos="";
$cantEnviada="";
$cantRecibida="";
$notas="";

while($datos=mysqli_fetch_array($res)){
  $cont++;
  $identificadores.=','.$datos['id'];
  $productos.=",'".$datos['producto']."'";
  $cantEnviada.=",".$datos['cantEnvio'];
  $cantRecibida.=",".$datos['cantEnvio'];
  ?>
  <tr>
      <td><?=$cont;?></td>
      <td><?=$datos['producto'];?></td>
      <td><?=$datos['cantEnvio'];?></td>
  </tr>
  <?php
}
echo'
        </tbody>
  </table>
</div>
<div class="row">
<div class="col-md-4">
</div>
<div class="col-md-8 text-right">
<button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
<input class="btn btn-success" type="button" onclick="hacerRecepcion(\''.$id.'\')" value="Recibir Traspaso">
</div>
</div>
</form>';
echo '<p aling="center">&nbsp;<p>';

?>
