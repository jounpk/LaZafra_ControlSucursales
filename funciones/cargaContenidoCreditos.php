<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');
$sucursal=$_SESSION['LZFidSuc'];
$userReg=$_SESSION['LZFident'];
$pyme = $_SESSION['LZFpyme'];

?>
<style>
    .btn-circle-sm {
      width: 35px;
      height: 35px;
    /*  line-height: 35px;*/
     /* font-size: 0.9rem;*/
     /* background: #fff;*/
      box-shadow: 7px 10px 12px -4px rgba(0, 0, 0, 0.62);
    }

</style>
<div class='row'>
<div class='col-md-12'>
    <div class='card'>
        <div class='card-header bg-<?=$pyme?>'>
            <h4 class='m-b-0 text-white'><i class='fas fa-handshake'></i> Detallado de Pagos de Crédito</h4>
        </div>
        <div class='card-body'>
        <table class='table'>
  <thead>
      <th>#</th>
      <th>Monto</th>
      <th>Saldo Residual</th>
      <th>Fecha de Pago</th> 
      <th>Usuario Rec.</th>
      <th>Complto.</th>
  </thead>
  <tbody>


 <?php   
$ident = (!empty($_POST['ident'])) ? $_POST['ident'] : 0 ;
if ($ident < 1) {
    errorBD('No se reconoció el crédito, vuelve a intentarlo, si persiste notifica a tu Administrador');
  }
  
$sqlDetallado = "SELECT
pc.id,
FORMAT( pc.monto, 2 ) AS monto,
DATE_FORMAT( pc.fechaReg, '%d-%m-%Y %H:%i:%s' ) AS fecha,
FORMAT( pc.residual, 2 ) AS adeudo,
CONCAT( usr.nombre, ' ', usr.appat, ' ', usr.apmat ) AS receptor,
comp.id AS idComplemento,
comp.doctoPDF,
comp.doctoXML,
cr.idVenta
FROM
pagoscreditos pc
INNER JOIN creditos cr ON cr.id=pc.idCredito
INNER JOIN segusuarios usr ON usr.id = pc.idUserReg
LEFT JOIN detcomplementos dc ON dc.idPagoCredito = pc.id
LEFT JOIN complementos comp ON comp.id = dc.idComplemento 
WHERE
pc.idCredito = '$ident' 
ORDER BY
pc.fechaReg ASC";
//echo $sqlDetallado;
$resdet = mysqli_query($link, $sqlDetallado) or die($error = 'Problemas al listar los detalles de Pagos Credito, notifica a tu Administrador' . mysqli_error($link));
$count=1;
if(mysqli_num_rows($resdet)<=0){
  echo "<tr><td class='text-center text-danger' colspan='6'>No hay Pagos Registrados por el momento</td></tr>";
}
else{


while($dat = mysqli_fetch_array($resdet)){
  $estatus=$dat["idComplemento"]!=""?"
  <a type='button' class='btn bg-success text-white' title='Documento XML' target='_blank' href='../" . $dat["doctoXML"] . "'><i class='far fa-file-code'></i> </a>
  <a type='button' class='btn bg-danger text-white' title='Documento PDF' target='_blank' href='../" . $dat["doctoPDF"] . "'><i class='far fa-file-pdf'></i> </a>":"
  <button type='button' class='btn btn-circle btn-success muestraSombra ' onclick='hacerComplemento(".$dat["id"].",".$dat["idVenta"].")' ><i class='fas fa-file-alt'></i></button>";
  echo "
    <tr>
      <td>$count</td>
      <td>$".$dat["monto"]."</td>
      <td>$".$dat["adeudo"]."</td>
      <td>".$dat["fecha"]."</td>
      <td>".$dat["receptor"]."</td>
      <td>".$estatus."</td>
    </tr>

  
  ";
  $count++;
}

}
?>
 
</tbody></table></div></div></div>
