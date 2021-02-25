<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$idVnt = (!empty($_POST['idVenta'])) ? $_POST['idVenta'] : 0 ;
$ine = (!empty($_POST['credencialIne'])) ? $_POST['credencialIne'] : 0 ;
$idVentaBol = (!empty($_POST['idVentaBoleta'])) ? $_POST['idVentaBoleta'] : 0 ;
$idBanco = (isset($_POST['claveBanco']) && $_POST['claveBanco'] != '') ? $_POST['claveBanco'] : 0 ;
$formaPago = (!empty($_POST['formaPago']) && $_POST['formaPago'] > 0) ? $_POST['formaPago'] : 0 ;
$tipoVenta = (isset($_POST['tipoVenta']) && $_POST['tipoVenta'] > 0) ? $_POST['tipoVenta'] : 1 ;
$mpio = (!empty($_POST['municipio'])) ? $_POST['municipio'] : 0 ;
$cultivo = (!empty($_POST['cultivo'])) ? $_POST['cultivo'] : 0 ;
$superficie = (!empty($_POST['superficie'])) ? $_POST['superficie'] : 0 ;
$productor = (isset($_POST['productor']) && $_POST['productor'] != '') ? trim($_POST['productor']) : '' ;
$entrega = (isset($_POST['entregaBoleta']) && $_POST['entregaBoleta'] != '') ? trim($_POST['entregaBoleta']) : '' ;

$pago = (!empty($_POST['pago'])) ? str_replace("," , "" , $_POST['pago']) : 0 ;
$pagoCredito = (!empty($_POST['pagoCredito'])) ? str_replace("," , "" , $_POST['pagoCredito']) : 0 ;
$pagoCheque = (!empty($_POST['pagoCheque'])) ? str_replace("," , "" , $_POST['pagoCheque']) : 0 ;
$pagoTransfer = (!empty($_POST['pagoTransfer'])) ? str_replace("," , "" , $_POST['pagoTransfer']) : 0 ;
$pagoTarjeta = (!empty($_POST['pagoTarjeta'])) ? str_replace("," , "" , $_POST['pagoTarjeta']) : 0 ;
$pagoBol = (!empty($_POST['pagoBol'])) ? str_replace("," , "" , $_POST['pagoBol']) : 0 ;

$folioCheq = (isset($_POST['folioCheq']) && $_POST['folioCheq'] != '') ? $_POST['folioCheq'] : '' ;
$folioTransferencia = (isset($_POST['folioTransferencia']) && $_POST['folioTransferencia'] != '') ? $_POST['folioTransferencia'] : '' ;
$folioTarjeta = (isset($_POST['noTarjeta']) && $_POST['noTarjeta'] != '') ? $_POST['noTarjeta'] : '' ;
$folioBol = (isset($_POST['folioBoleta']) && $_POST['folioBoleta'] != '') ? $_POST['folioBoleta'] : '' ;

$claveCanera = (isset($_POST['claveCanera']) && $_POST['claveCanera'] != '') ? $_POST['claveCanera'] : '' ;
$ejido = (isset($_POST['ejido']) && $_POST['ejido'] != '') ? $_POST['ejido'] : '' ;
$tipoBol = (isset($_POST['tipoBol']) && $_POST['tipoBol'] != '') ? $_POST['tipoBol'] : '' ;
$userReg = $_SESSION['LZFident'];
$idSucursal = $_SESSION['LZFidSuc'];
$monto = 0;
$folio = '';
$idVenta = 0;

########################################################################
####################### Comienza uso de $debug #########################
########################################################################
$debug = $ejecutar = 0;
########################################################################
####################### Finaliza uso de $debug #########################
########################################################################
#se obtiene la venta
$idVenta = ($idVenta < 1 && $idVnt > 0) ? $idVnt : 0 ;
$idVenta = ($idVenta < 1 && $idVentaBol > 0) ? $idVentaBol : $idVenta ;
#se verifica que la venta sea mayor o igual a 1
if ($idVenta < 1) {
  errorBD('Debes escoger un producto para crear la venta antes de agregar una boleta.',$tipoVenta);
}
#se verifica que la forma de pago sea mayor o igual a 1
if ($formaPago < 0) {
  errorBD('Debes escoger un método de pago para la venta, inténtalo nuevamente, si persiste notifica a tu Administrador.',$tipoVenta);
}
#se obtiene el valor de monto
$monto = ($monto == 0 && $pago > 0) ? $pago : 0 ;
$monto = ($monto == 0 && $pagoCredito > 0) ? $pagoCredito : $monto ;
$monto = ($monto == 0 && $pagoCheque > 0) ? $pagoCheque : $monto ;
$monto = ($monto == 0 && $pagoTransfer > 0) ? $pagoTransfer : $monto ;
$monto = ($monto == 0 && $pagoTarjeta > 0) ? $pagoTarjeta : $monto ;
$monto = ($monto == 0 && $pagoBol > 0) ? $pagoBol : $monto ;

#se obtiene el folio
$folio = ($folio == '' && $folioCheq != '') ? $folioCheq : '' ;
$folio = ($folio == '' && $folioTransferencia != '') ? $folioTransferencia : $folio ;
$folio = ($folio == '' && $folioTarjeta != '') ? $folioTarjeta : $folio ;
$folio = ($folio == '' && $folioBol != '') ? $folioBol : $folio ;

if ($debug == 1) {
echo '<br>########################################################################<br>';
echo '<br>$_POST:<br>';
print_r($_POST);
echo '<br>';
echo '<br>$idVenta: '.$idVenta;
echo '<br>$formaPago: '.$formaPago;
echo '<br>$folio: '.$folio;
echo '<br>$idBanco: '.$idBanco;
echo '<br>$mpio: '.$mpio;
echo '<br>$cultivo: '.$cultivo;
echo '<br>$ine: '.$ine;
echo '<br>$superficie: '.$superficie;
echo '<br>$productor: '.$productor;
echo '<br>$entrega: '.$entrega;
echo '<br>$monto: '.$monto;

echo '<br>$claveCanera: '.$claveCanera;
echo '<br>$ejido: '.$ejido;
echo '<br>$tipoBol: '.$tipoBol;

echo '<br>$userReg: '.$userReg;
echo '<br>$idSucursal: '.$idSucursal;
echo '<br>########################################################################<br>';
#exit(0);
}

#echo '<br> Línea 45';
$sqlVenta = "SELECT * FROM ventas WHERE id = '$idVenta'";
if ($debug == 1) {
  echo '<br>########################################################################<br>';
  echo '<br>$sqlVenta: '.$sqlVenta;
  echo '<br>########################################################################<br>';
}
$resVenta = mysqli_query($link,$sqlVenta) or die(errorBD('Problemas al consultar las boletas, notifica a tu Administrador.',$tipoVenta));
$est = mysqli_fetch_array($resVenta);

$idCliente = $est['idCliente'];

if ($debug == 1) {
  echo '<br>Se obtiene el estatus y el cliente';
  echo '<br>$idCliente: '.$idCliente;
  echo '<br>Estatus: '.$est['estatus'];
}

if ($est['estatus'] > 1) {
#  echo '<br> Línea 51';
  errorBD('La venta ya está cerrada y no se puede realizar cambios, prueba con otra venta.',$tipoVenta);
}

if ($idCliente > 0 && ($pagoCredito > 0 || $formaPago == '99')) {
  $conCliente = "SELECT IF(SUM(cr.montoDeudor) > 0,SUM(cr.montoDeudor),0) AS totalDeuda,
              IF(cl.limiteCredito>0,cl.limiteCredito,0) AS limiteCredito,cl.credito
              FROM clientes cl
              LEFT JOIN creditos cr ON cl.id = cr.idCliente
              WHERE cr.estatus = 1 AND cl.id = '$idCliente'";

      if ($debug == 1) {
          echo '<br><br>$conCliente: '.$conCliente;
      }

  $resCliente = mysqli_query($link,$conCliente) or die(errorBD('Problemas al consultar el crédito del cliente, notifica a tu Administrador.',$tipoVenta));
  $datosCliente = mysqli_fetch_array($resCliente);
  $resta = 0;
  $totalDeuda = $datosCliente['totalDeuda'];
  $limiteDeCredito = $datosCliente['limiteCredito'];
  $tieneCreditoPermitido = $datosCliente['credito'];

  if ($tieneCreditoPermitido != 1) {
    errorBD('El cliente no tiene permitido tener crédito en la actualidad, verifica con las oficinas.',$tipoVenta);
  } else {

    $resta = $limiteDeCredito - $totalDeuda;

    if ($debug == 1) {
      echo '<br>############## Se verifica los montos del crédito del cliente ##########################';
      echo '<br>$limiteDeCredito: '.$limiteDeCredito;
      echo '<br>$totalDeuda: '.$totalDeuda;
      echo '<br>$resta: '.$resta;
      echo '<br>$Compra: '.$pagoCredito;
    }

    if ($resta < $pagoCredito) {
      errorBD('No se puede hacer el pago porque su deuda más el monto de ésta venta sobrepasa su crédito, sólo se le permite crédito por: $'.number_format($resta,2,'.',','),$tipoVenta);
    }
  }
}
	/**************************** validar la cantidad en el stock ***************************************/










 /******************************************************************************************************/


  $sqlIn = "INSERT INTO pagosventas(idVenta,idFormaPago,monto,montoPagado,folio,idBanco,superficie,idCultivo,idMunicipio,productor,clienteEntrega,fechaReg,idUserReg,credencialIne,ejido,claveCanera,tipoBoleta)
                          VALUES('$idVenta','$formaPago','$monto','$monto','$folio','$idBanco','$superficie','$cultivo','$mpio','$productor','$entrega',NOW(),'$userReg','$ine','$ejido','$claveCanera','$tipoBol')";
  if ($debug == 1) {
    echo '<br>########################################################################<br>';
    echo '<br>$sqlIn: '.$sqlIn;
    echo '<br>########################################################################<br>';
  }
  if ($ejecutar == 0) {
    $resIn = mysqli_query($link,$sqlIn) or die(errorBD('Problemas al capturar la forma de pago, notifica a tu Administrador.',$tipoVenta));
  }
#echo '<br>$sql: '.$sqlIn;

#  echo '<br> Línea 60';
if ($tipoVenta == 1) {
  if ($debug == 1) {
    echo '<br>########################################################################<br>';
    echo '<br>Todo ok, redirige a venta.php';
    echo '<br>########################################################################<br>';
  } else {
    $_SESSION['LZFmsjSuccessInicioVenta'] = 'El Pago se ha agregado correctamente';
    header('location: ../venta.php');
    exit(0);
  }
} else {
    if ($debug == 1) {
      echo '<br>########################################################################<br>';
      echo '<br>Todo ok, redirige a ventaEspecial.php';
      echo '<br>########################################################################<br>';
    } else {
      $_SESSION['LZFmsjSuccessInicioVentaEspecial'] = 'El Pago se ha agregado correctamente';
      header('location: ../ventaEspecial.php');
      exit(0);
    }
}

function errorBD($error,$tipoVenta){
#  		echo '<br> Línea 74';
#/*
  if ($tipoVenta == 1) {
    if ($GLOBALS['debug'] == 1) {
      echo '<br>########################################################################<br>';
      echo '<br>########### Entra a error ###########<br>';
      echo '<br>errorBD<br>'.$error.'<br>Redirige a venta.php';
      echo '<br>########################################################################<br>';
    } else {
  		$_SESSION['LZFmsjInicioVenta'] = $error;
  		header('location: ../venta.php');
  		exit(0);
    }
	} else {
    if ($GLOBALS['debug'] == 1) {
      echo '<br>########################################################################<br>';
      echo '<br>########### Entra a error ###########<br>';
      echo '<br>errorBD<br>'.$error.'<br>Redirige a ventaEspecial.php';
      echo '<br>########################################################################<br>';
    } else {
  		$_SESSION['LZFmsjInicioVentaEspecial'] = $error;
  		header('location: ../ventaEspecial.php');
  		exit(0);
    }
	}
#  */
  exit(0);
}
 ?>
