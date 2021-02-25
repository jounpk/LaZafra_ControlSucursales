<?php
session_start();
define('INCLUDE_CHECK', 1);
require_once('../include/connect.php');

$tipoVenta = (!empty($_POST['tipoVenta'])) ? $_POST['tipoVenta'] : 0;
$idVenta = (isset($_POST['idVenta']) && $_POST['idVenta'] > 0) ? $_POST['idVenta'] : "";
$precioVenta = (!empty($_POST['precioVenta'])) ? $_POST['precioVenta'] : 0;
$montoPagado = (!empty($_POST['montoPagado'])) ? $_POST['montoPagado'] : 0;
$comision = (!empty($_POST['comision'])) ? $_POST['comision'] : 0;
$montoSubtotal = (!empty($_POST['montoSubtotal'])) ? $_POST['montoSubtotal'] : 0;
$montoComision = (!empty($_POST['montoComision'])) ? $_POST['montoComision'] : 0;
$cambio = (!empty($_POST['cambio'])) ? $_POST['cambio'] : 0;
$fechaAct = date('Y/m/d H:i:s');
$idSucursal = $_SESSION['LZFidSuc'];
$debug = 0;
if ($debug != 1) {
  error_reporting(0);
} else {
  error_reporting(1);
  echo 'Contenido de POST:</br>';
  print_r($_POST);
  echo '</br></br>';
}
$sinProcedimiento = 0;

if ($idVenta == '') {
  errorBD('No se reconoció la venta, vuelve a intentarlo, si persiste notifica a tu Administrador.', 0);
}
if ($comision > 0) {
  $incremento = ($comision + 100) / 100;
} else {
  $incremento = 0;
}

if ($precioVenta > $montoPagado) {
  errorBD('El pago debe ser mayor o igual al precio de la venta, si esto es así, notifica a tu Administrador.', 0);
}
if ($debug == 1) {
  echo '<br>########################################################################<br>';
  echo '<br>$_POST:<br>';
  print_r($_POST);
  echo '<br>';
  echo '<br>$idVenta: ' . $idVenta;
  echo '<br>$tipoVenta: ' . $tipoVenta;
  echo '<br>$precioVenta: ' . $precioVenta;
  echo '<br>$comision: ' . $comision;
  echo '<br>$incremento: ' . $incremento;
  echo '<br>$montoSubtotal: ' . $montoSubtotal;
  echo '<br>$montoComision: ' . $montoComision;
  echo '<br>$cambio: ' . $cambio;
  echo '<br>';
  echo '<br>########################################################################<br>';
}
/**************************** estatus de la venta por cerrar ***************************************/

$sql = "SELECT vta.estatus, vta.idCliente FROM ventas vta WHERE vta.id='$idVenta'";
//----------------devBug------------------------------
if ($debug == 1) {
  $resultXquery = mysqli_query($link, $sql) or die(errorBD('Problemas al Verificar el Estatus de  Ventas, notifica a tu Administrador', 0));
  $canInsert = mysqli_affected_rows($link);
  echo '<br>SQL: ' . $sql . '<br>';
  echo '<br>Cant de Registros Cargados: ' . $canInsert;
} else {
  $resultXquery = mysqli_query($link, $sql) or die(errorBD('Problemas al Verificar el Estatus de  Ventas, notifica a tu Administrador', 0));
  $canInsert = mysqli_affected_rows($link);
} //-------------Finaliza devBug------------------------------
$dat = mysqli_fetch_array($resultXquery);
$estatus = $dat['estatus'];
$idCliente = $dat['idCliente'];
if ($estatus != '1') {
  errorBD('Verifica el Estatus de la Venta', 0);
}

/******************************************************************************************************/
/**************************** Actualizar Datos de las Ventas  ***************************************/
mysqli_autocommit($link, FALSE);
mysqli_begin_transaction($link);
$sql = "UPDATE ventas vtas
INNER JOIN detventas dtvta ON vtas.id=dtvta.idVenta
INNER JOIN (SELECT dtvta.id, SUM(dtvta.precioVenta*(1+'$comision')) AS ComisionPrecio, '$montoSubtotal' AS subTotal, '$montoComision' AS comision  FROM ventas vta
INNER JOIN detventas dtvta ON vta.id=dtvta.idVenta
WHERE dtvta.idVenta='$idVenta'
GROUP BY dtvta.idProducto) datos ON dtvta.id = datos.id
SET vtas.subTotal=datos.subTotal, vtas.comision=datos.comision, dtvta.comisionPrecio=datos.ComisionPrecio, vtas.estatus='2'";
//----------------devBug------------------------------
if ($debug == 1) {
  $resultXquery = mysqli_query($link, $sql) or die(errorBD('Problemas al Actualizar Datos de  Ventas, notifica a tu Administrador', 1));
  $canInsert = mysqli_affected_rows($link);
  echo '<br>SQL: ' . $sql . '<br>';
  echo '<br>Cant de Registros Cargados: ' . $canInsert;
} else {
  $resultXquery = mysqli_query($link, $sql) or die(errorBD('Problemas al Actualizar Datos de  Ventas, notifica a tu Administrador', 1));
  $canInsert = mysqli_affected_rows($link);
} //-------------Finaliza devBug------------------------------

/******************************************************************************************************/
/**************************** Abrir Credito con cliente  ***************************************/
$sql = "INSERT INTO creditos (idVenta, idCliente, montoDeudor, totalDeuda, estatus, idCorte, montoTotalVenta)
SELECT '$idVenta' AS idVenta,vta.idCliente,monto AS montoDeudor,monto AS totalDeuda,'1' AS estatus,'0' AS corte, '$montoSubtotal' AS montoTotalVenta  FROM ventas vta
INNER JOIN  pagosventas pvta ON vta.id= pvta.idVenta
INNER JOIN sat_formapago fp ON pvta.idFormaPago=fp.id
WHERE pvta.idFormaPago='7' AND vta.id='$idVenta'";
//----------------devBug------------------------------
if ($debug == 1) {
  $resultXquery = mysqli_query($link, $sql) or die(errorBD('Problemas al Guardar los Creditos, notifica a tu Administrador', 1));
  $canInsert = mysqli_affected_rows($link);
  echo '<br>SQL: ' . $sql . '<br>';
  echo '<br>Cant de Registros Cargados: ' . $canInsert;
} else {
  $resultXquery = mysqli_query($link, $sql) or die(errorBD('Problemas al Guardar los Creditos, notifica a tu Administrador', 1));
  $canInsert = mysqli_affected_rows($link);
} //-------------Finaliza devBug------------------------------


/******************************************************************************************************/
/**************************** Cambio de Efectivo  ***************************************/



/******************************************************************************************************/


$sqlCon = "SELECT p.descripcion, dv.cantidad AS 'cantVenta', SUM(l.cant) AS 'cantLote'
          FROM detventas dv
          INNER JOIN productos p ON dv.idProducto = p.id
          LEFT JOIN  lotestocks l ON p.id = l.idProducto
          WHERE dv.idVenta = '$idVenta' AND l.idSucursal = '$idSucursal' AND l.estatus = '1'
          GROUP BY p.id";
if ($debug == 1) {
  echo '<br>$sqlCon: ' . $sqlCon;
}
$resCon = mysqli_query($link, $sqlCon) or die(errorBD('Problemas al conseguir los productos con lotes menores a su existencia en Stock, notifica a tu Administrador.', $tipoVenta));

if ($cambio > 0) {
  $sel = "SELECT * FROM pagosventas WHERE idVenta = '$idVenta' AND idFormaPago = '1' ORDER BY id DESC LIMIT 1";
  if ($debug == 1) {
    echo '<br>$sel: ' . $sel;
  }
  $resSel = mysqli_query($link, $sel) or die(errorBD('Problemas al consultar los pagos en efectivo, notifica a tu Administrador.', $tipoVenta));
  $cant = mysqli_num_rows($resSel);
  if ($cant > 0) {
    $fp = mysqli_fetch_array($resSel);
    $idFpago = $fp['id'];
    $mTotal = 0;
    $fMonto = $fp['monto'];
    $mTotal = $fMonto - $cambio;
    $sqlUp = "UPDATE pagosventas SET monto = '$mTotal' WHERE id = '$idFpago'";
    if ($debug == 1) {
      echo '<br>$sqlUp: ' . $sqlUp;
    }
    $resUP = mysqli_query($link, $sqlUp) or die(errorBD('Problemas al restar el cambio al pago en efectivo, notifica a tu Administrador.', $tipoVenta));
  }
}

$sql = "CALL SP_cierraVenta('$idVenta','$precioVenta','$comision','$incremento','$fechaAct','$montoSubtotal','$montoComision')";
if ($debug == 1) {
  echo '<br>$sql: ' . $sql;
  if ($sinProcedimiento == 1) {
    exit(0);
  }
}
$res = mysqli_query($link, $sql) or die(errorBD(mysqli_error($link), $tipoVenta));
$var = mysqli_fetch_array($res);
$mensaje = $var['mensaje'];
$estatus = $var['estatus'];


if ($estatus == 0) {

  errorBD($mensaje, $tipoVenta);
}

if ($estatus == 2) {

  $productos = '';

  while ($rs = mysqli_fetch_array($resCon)) {
    if ($rs['cantVenta'] > $rs['cantLote']) {
      $productos .= $rs['descripcion'] . ', ';
    }
  }
  $productos = trim($productos, ", ");
  errorBD($mensaje . ', verifica los lotes de los siguientes productos: ' . $productos, $tipoVenta);
}

#  echo '<br> Línea 26';
#/*
if ($debug == 1) {
  echo '<br>########################################################################<br>';
  echo '<br> Envía a ticket:<br>';
  echo '<br>location.href="../imprimeTicketVenta.php?idVenta=' . $idVenta . '&tipo=' . $tipoVenta;
  echo '<br> Envía a ticket:<br>';
  echo '<br>########################################################################<br>';
  exit(0);
} else {


  echo '<script>
        location.href="../imprimeTicketVenta.php?idVenta=' . $idVenta . '&tipo=' . $tipoVenta . '";
      </script> ';
  echo '<br>Se abrió?';
  exit(0);
}

if ($tipoVenta == 1) {
  if ($debug == 1) {
    echo '<br>########################################################################<br>';
    echo '<br> Envía a Venta:<br>';
    echo '<br>location: ../venta.php';
    echo '<br> $mensaje: ' . $mensaje . '<br>';
    echo '<br>########################################################################<br>';
    exit(0);
  } else {
    $_SESSION['LZFmsjSuccessInicioVenta'] = $mensaje;
    header('location: ../venta.php');
    exit(0);
  }
} else {
  if ($debug == 1) {
    echo '<br>########################################################################<br>';
    echo '<br> Envía a Venta Especial:<br>';
    echo '<br>location: ../ventaEspecial.php';
    echo '<br> $mensaje: ' . $mensaje . '<br>';
    echo '<br>########################################################################<br>';
    exit(0);
  } else {
    $_SESSION['LZFmsjSuccessInicioVentaEspecial'] = $mensaje;
    header('location: ../ventaEspecial.php');
    exit(0);
  }
}
#*/


function errorBD($error, $NecesitaRollBack)
{
  $link = $GLOBALS["link"];
  if ($NecesitaRollBack == '1') {
    mysqli_rollback($link);
  }
  if ($GLOBALS['debug'] == 1) {
    echo '<br><br>Det Error: ' . $error;
    echo '<br><br>Error Report: ' . mysqli_error($link);
  } else {
    echo '0|' . $error;
  }
  exit(0);
}
