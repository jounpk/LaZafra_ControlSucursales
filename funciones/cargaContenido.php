<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');
$sucursal=$_SESSION['LZFidSuc'];
$userReg=$_SESSION['LZFident'];
$estTabla='<table class="table"><thead>';

$ident = (!empty($_POST['ident'])) ? $_POST['ident'] : 0 ;
if ($ident < 1) {
    errorBD('No se reconoció el producto, vuelve a intentarlo, si persiste notifica a tu Administrador');
  }
  $sqlSuc = "SELECT nameCorto AS nombre FROM sucursales ORDER BY id";
  $ressuc = mysqli_query($link, $sqlSuc) or die('Problemas al listar los Sucursales1, notifica a tu Administrador');
  $totalSuc=0;
  while ($suc = mysqli_fetch_array($ressuc)) {
      $estTabla.= '<th data-breakpoints="all" data-title="' . $suc['nombre'] . '">' . $suc['nombre'] . '</th>';
  }
  $estTabla.= '<th data-breakpoints="all" data-title="Total En Sucursales">Total</th>';

  $sqlQuery = "SELECT
  GROUP_CONCAT(DISTINCT
  CONCAT(
  'SUM(CASE WHEN idSucursal = \"',
  scs.id,
  '\"  THEN cantStk END) AS ','Suc',
   scs.id
  )ORDER BY scs.id) AS query FROM sucursales scs ORDER BY id";
  //echo $sqlQuery;
                          $resquery = mysqli_query($link, $sqlQuery) or die($error = 'Problemas al listar los Sucursales, notifica a tu Administrador' . mysqli_error($link));
                          $Query = mysqli_fetch_array($resquery);
                          $sqlDetallado = 'SELECT ' . $Query['query'] . ' FROM (SELECT SUM(stk.cantActual)  AS cantStk, stk.idSucursal FROM stocks stk
                          INNER JOIN productos pdc ON stk.idProducto = pdc.id 
                          INNER JOIN sucursales scs ON stk.idSucursal = scs.id 
                          WHERE stk.idProducto=' . $ident . '
                          GROUP BY stk.idSucursal ORDER BY scs.id) agrupSTK';
                                          //  echo $sqlDetallado.'</br></br>';
$resdet = mysqli_query($link, $sqlDetallado) or die($error = 'Problemas al listar los Sucursales2, notifica a tu Administrador' . mysqli_error($link));
$estTabla.="</thead><tbody>";

$resArray = mysqli_fetch_array($resdet);

 for ($i = 0; $i <= (count($resArray) / 2) - 1; $i++) {

if ($resArray[$i] != '') {
  if($resArray[$i]==0){
    $estTabla.= '<td id="detallado-' . $i . '" class="text-danger">0.0</td>';
  

  }else{
    $estTabla.= '<td id="detallado-' . $i . '" class="text-success"><b>' . $resArray[$i] . '</b></td>';
    $totalSuc+=$resArray[$i];
  }
  } else {
  $estTabla.= '<td id="detallado-' . $i . '" class="text-danger">0.0</td>';
  }
 }

 $estTabla.= '<td id="detallado-total" class="">'.$totalSuc.'</td>';

 $estTabla.="</tbody></table>";
 echo $estTabla;
  ?>
