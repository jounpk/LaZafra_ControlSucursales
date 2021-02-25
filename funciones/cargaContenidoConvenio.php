
<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');
$sucursal=$_SESSION['LZFidSuc'];
$userReg=$_SESSION['LZFident'];
$pyme = $_SESSION['LZFpyme'];

$tarjetaConvenios="
<div class='row'>
<div class='col-md-6'>
    <div class='card'>
        <div class='card-header bg-$pyme'>
            <h4 class='m-b-0 text-white'><i class='fas money-bill-alt'></i> Listas de Convenios</h4>
        </div>
        <div class='card-body'>
        <table class='table'>
  
  <tbody>";
  $tarjetaLista="
<div class='col-md-6'>
    <div class='card'>
        <div class='card-header bg-$pyme'>
            <h4 class='m-b-0 text-white'><i class='fas money-bill-alt'></i> Listas de Precios Asignadas</h4>
        </div>
        <div class='card-body'>
        <table class='table'>
  
  <tbody>";

$identProveedor = (!empty($_POST['ident'])) ? $_POST['ident'] : 0 ;
if ($identProveedor < 1) {
    errorBD('No se reconoció el proveedor, vuelve a intentarlo, si persiste notifica a tu Administrador');
  }
  else{
$sqlDetallado = "SELECT
*
FROM doctosprov 
WHERE idProveedor='$identProveedor' ORDER BY fechaReg DESC
";
$resdet = mysqli_query($link, $sqlDetallado) or die($error = 'Problemas al listar los detalles de Ajustes, notifica a tu Administrador' . mysqli_error($link));
$cant_rows_Lista=1;
$cant_rows_Convenio=1;
if(mysqli_num_rows($resdet)==0){
  echo '<h6 class="text-center text-danger">No hay Documentos Relacionados</h6>';
}
else{
while($res = mysqli_fetch_array($resdet)){
  if($res['tipo']=='1'){
    $anotaciones = $res["anotaciones"]==''?"<small class='text-danger'>No hay  anotaciones Previas</small>":$res["anotaciones"];
    $tarjetaConvenios.= '<tr><td  class="">' . $cant_rows_Convenio. '</td>';
    $tarjetaConvenios.='<td  class=""><b>' . $anotaciones . '</b></td>';
    $tarjetaConvenios.=  '<td  class=""> <button type="button" onclick="verIMG(\'Detalle de Listas de Precios\', \''.$res['docto'].'\')" class="btn btn-danger btn-circle-tablita" id=""><i class=" fas fa-file-pdf"></i> </button></td></tr>';
    $cant_rows_Convenio++;
  }
  if($res['tipo']=='2'){
    $anotaciones = $res["anotaciones"]==''?"<small class='text-danger'>No hay  anotaciones Previas</small>":$res["anotaciones"];
    $tarjetaLista.='<tr><td  class="">' . $cant_rows_Lista. '</td>';
    $tarjetaLista.='<td  class=""><b>' . $anotaciones . '</b></td>';
    $tarjetaLista.= '<td  class=""> <button type="button" onclick="verIMG(\'Detalle de Listas de Precios\', \''.$res['docto'].'\')" class="btn btn-danger btn-circle-tablita" id=""><i class=" fas fa-file-pdf"></i> </button></td></tr>';
    $cant_rows_Lista++;
  }
  
 




}

$tarjetaConvenios.='</tbody></table></div></div>';

$tarjetaLista.='</tbody></table></div></div>';
echo $tarjetaConvenios.'</div>';
echo $tarjetaLista.'</div>';
  }


  }

function errorBD($error){
  echo '0|'.$error;
    exit(0);
 }?>