<?php
session_start();
define('INCLUDE_CHECK', 1);
require_once('../include/connect.php');
$sucursal = $_SESSION['LZFidSuc'];
$userReg = $_SESSION['LZFident'];
$pyme = $_SESSION['LZFpyme'];
?>
<!-- Nav tabs -->
<div class="row">
<div class="col-md-12">
<table class="table-responsive" id="detalladoVentas">
  <thead>
    <tr>
      <th>#</th>
      <th>Folio Venta</th>
      <th>Sucursal</th>
      <th>Monto Total</th>
      <th>Monto Esp. Pag.</th>
      <th>Facturas</th>
    </tr>
  
  </thead>

<tbody>
  <?php
  $idsVentas = (!empty($_POST['idsVentas'])) ? $_POST['idsVentas'] : '';
  $formaPago = (!empty($_POST['idFormaPago'])) ? $_POST['idFormaPago'] : '';
  //echo "ids Ventas".$idsVentas;
  if ($idsVentas == '') {
    errorBD('No se reconoció la folios del Corte, vuelve a intentarlo, si persiste notifica a tu Administrador');
  }
  $totalGastos=0;
  $sqlDetallado = "SELECT vta.*,
  FORMAT(vta.total, 2) AS total,
  fact.uid,
  fact.doctoPDF,
  fact.doctoXML,
  suc.nombre AS sucursal,
  FORMAT(pv.monto,2) AS montoPago
  FROM ventas vta
  LEFT JOIN vtasfact vf ON vf.idVenta = vta.id
  LEFT JOIN facturasgeneradas fg ON fg.id= vf.idFactgen
  LEFT JOIN facturas fact ON fact.uuid = fg.uuidFact
  INNER JOIN sucursales suc ON suc.id = vta.idSucursal
  INNER JOIN pagosventas pv ON vta.id=pv.idVenta AND pv.idFormaPago='$formaPago'
  WHERE vta.id IN ($idsVentas)

";
  /*AND cte.estatus='2'*/
//echo $sqlDetallado;
  $resdet = mysqli_query($link, $sqlDetallado) or die($error = 'Problemas al listar las ventas, notifica a tu Administrador');
  if (mysqli_num_rows($resdet) == 0) {
    echo '<td colspan="3" class="text-danger">No hay Ventas Registradas para el Corte</td>';
  }
 $vuelta=1;
  while ($res = mysqli_fetch_array($resdet)) {
    $idventa=$res["id"];
    
    $facturas= $res['uid']=='' ? '<small class="text-danger">No Facturada</small>':' <a type="button" class="btn bg-success text-white" title="Documento XML" target="_blank" href="../' . $res["doctoXML"] . '"><i class="far fa-file-code"></i> </a>
    <a type="button" class="btn bg-danger text-white" title="Documento PDF" target="_blank" href="../' . $res["doctoPDF"] . '"><i class="far fa-file-pdf"></i> </a>';
    echo '
    <tr>
      <td>'.$vuelta.'</td>
      <td>#'.$res['id'].'</td>
      <td>'.$res["sucursal"].'</td>
      <td>$'.$res['total'].'</td>
      <td>$'.$res['montoPago'].'</td>
      <td>'.$facturas.'</td>
    <tr>
    
    ';
    $vuelta++;
   
  }
  function errorBD($error)
  {
    echo $error;
    exit(0);
  }
  ?>
</tbody>
</table>
</div>
</div>