<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');


$idVenta = (!empty($_POST['idVenta'])) ? $_POST['idVenta'] : 0 ;
$motivo = (isset($_POST['desc']) && $_POST['desc'] != '') ? $_POST['desc'] : '' ;
$userReg = $_SESSION['LZFident'];
$idSucursal = $_SESSION['LZFidSuc'];
/*
echo '<br>------------------------------------------------------------------------<br>';
echo '<br>$_POST:<br>';
print_r($_POST);
echo '<br>';
echo '<br>$idVenta: '.$idVenta;
echo '<br>';
echo '<br>------------------------------------------------------------------------<br>';
#exit(0);
# */
#echo '<br>------------ se consulta la venta para verificar que no esté cancelada, facturada, en corte o con boletas ------------<br>';
$sqlConVenta = "SELECT v.estatus, v.idCorte, c.estatus AS estatusCorte, COUNT(vf.id) AS facturada, COUNT(CASE pv.idFormaPago WHEN '1' THEN pv.id END) AS efectivo,
               COUNT(CASE pv.idFormaPago WHEN '2' THEN pv.id END) AS cheque, COUNT(CASE pv.idFormaPago WHEN '3' THEN pv.id END) AS transferencia,
               COUNT(CASE pv.idFormaPago WHEN ('4' OR '5') THEN pv.id END) AS tarjeta,COUNT(CASE pv.idFormaPago WHEN '6' THEN pv.id END) AS boletas,
               COUNT(CASE pv.idFormaPago WHEN '7' THEN pv.id END) AS credito, v.total
                FROM ventas v
                LEFT JOIN cortes c ON v.idCorte = c.id
    						LEFT JOIN vtasfact vf ON v.id = vf.idVenta
                LEFT JOIN pagosventas pv ON v.id = pv.idVenta
                WHERE v.id = '$idVenta'
    						GROUP BY v.id
    						LIMIT 1";
#echo '<br>$sqlConVenta: '.$sqlConVenta;
$resConVenta = mysqli_query($link,$sqlConVenta) or die(errorBD('Problemas al consultar la venta, notifica a tu Administrador.'));
$dt = mysqli_fetch_array($resConVenta);
$montoTotVenta = 0;
$montoTotVenta = $dt['total'];
#echo '<br>------------ se realizan las validaciones ------------<br>';
if ($dt['estatus'] != 2) {
#echo '<br> Línea 38, estatus venta cerrada';
  errorBD('Lo sentimos, no se realizó ninguna acción porque la venta ya fue cancelada.');
}
#echo '<br> Línea 41, si se encuentra en corte';
if ($dt['estatusCorte'] > 1) {
  errorBD('Lo sentimos, no se realizó ninguna acción porque la venta ya está en un corte cerrado.');
}
#echo '<br> Línea 45, si está facturada';
if ($dt['facturada'] > 0) {
  errorBD('Lo sentimos, no se realizó ninguna acción porque la venta ya fue facturada.');
}
#echo '<br>------------ se terminan las validaciones, hasta aquí todo bien ------------<br>';

#echo '<br>------------ Se procede a iniciar la transacción ------------<br>';

$sqlTransIn = "BEGIN";
#echo '<br>$sqlTransIn: '.$sqlTransIn;
$resTrasnIn = mysqli_query($link,$sqlTransIn) or die('Problemas al iniciar la transacción, notifica a tu Administrador.');
$precio = 0;

#echo '<br>------------ Se consulta sí hay crédito para cancelarlo ------------<br>';
$sqlCred = "SELECT COUNT(id) AS cant FROM creditos WHERE idVenta = '$idVenta'";
#echo '<br>$sqlCred: '.$sqlCred;
$resCred = mysqli_query($link,$sqlCred) or die(cancelaTrans($link,'Problemas al consultar los créditos, notifica a tu Administrador.'));
$sqC = mysqli_fetch_array($resCred);

#echo '<br>------------ Se cancela el crédito y la venta ------------<br>';
  if ($sqC['cant'] > 0) {
    $sqlCancel = "UPDATE ventas SET estatus = '3' WHERE id = '$idVenta'";
#echo '<br>$sqlCancel: '.$sqlCancel;
    $resCancel = mysqli_query($link,$sqlCancel) or die(cancelaTrans($link,'Porblemas al actualizar la venta, notifica a tu Administrador.'));

    $sqlCanCred = "UPDATE creditos SET estatus = '3' WHERE idVenta = '$idVenta'";
#echo '<br>$sqlCanCred: '.$sqlCanCred;
    $resCanCed = mysqli_query($link,$sqlCanCred) or die(cancelaTrans($link,'Problemas al consultar los créditos, notifica a tu Administrador.'));
  } else {
    $sqlCancel = "UPDATE ventas SET estatus = '3' WHERE id = '$idVenta'";
#echo '<br>$sqlCancel: '.$sqlCancel;
    $resCancel = mysqli_query($link,$sqlCancel) or die(cancelaTrans($link, 'Problemas al consultar la venta, notifica a tu Administrador (2).'));
  }
  $montoDevolucion = $cantDevolucion = 0;
#echo '<br>------------ Se procede a consultar sí hay devoluciones de la venta  ------------<br>';
  $sqlConDev = "SELECT COUNT(d.id) AS cant, IF(SUM(d.cantidad * dv.precioVenta) > 0,SUM(d.cantidad * dv.precioVenta),0) AS montoDevolver
                FROM devoluciones d INNER JOIN detventas dv ON d.idDetVenta = dv.id WHERE d.idVenta = '$idVenta'";
#echo '<br>$sqlConDev: '.$sqlConDev;
  $resConDev = mysqli_query($link,$sqlConDev) or die(cancelaTrans($link,'Problemas al consultar las devoluciones en la venta, notifica a tu Administrador.'));
  $dev = mysqli_fetch_array($resConDev);
  $cantDevolucion = $dev['cant'];
  $montoDevolucion = $dev['montoDevolver'];

  ####################################################################
  ############## Comienzo de la devolución de los lotes ##############
  ####################################################################

  #echo '<br>------------ Se devuelven los lotes  ------------<br>';

   $sqlLotes = "UPDATE detventas dv
                INNER JOIN detvtalotes dl ON dv.id = dl.idDetVenta
                INNER JOIN lotestocks lt ON dl.idLote = lt.id
                SET lt.cant = ( lt.cant + IF(dv.cantCancel > 0, (dv.cantidad - dv.cantCancel) , dv.cantidad ) )
                WHERE dv.idVenta = '$idVenta'";
  #echo '<br>$sqlLotes: '.$sqlLotes;
    $resLotes = mysqli_query($link,$sqlLotes) or die(cancelaTrans($link,'Problemas al actualizar los lotes, notifica a tu Administrador.'));

# '------------ No se agrega en detdevlotes porque se cancela la venta y se devuelve todo de esa venta  ------------';
    ####################################################################
    ############## fin de la devolución de los lotes ##############
    ####################################################################
#echo '<br>------------ se obtiene si hay devoluciones anteriores y el monto a lo que suman para poder restarlo en la devolución, se actualiza también la cantidad final en detventas y devoluciones  ------------<br>';
#echo '<br>$cantDevolucion: '.$cantDevolucion;
#echo '<br>$montoDevolucion: '.$montoDevolucion;

  $sqlUpStk = "UPDATE stocks s INNER JOIN detventas dv ON s.idProducto = dv.idProducto LEFT JOIN devoluciones d ON dv.id = d.idDetVenta SET
          s.cantFinal = (s.cantActual + IF( d.cantidad > 0,(dv.cantidad - d.cantidad),dv.cantidad)), s.cantActual = (s.cantActual + IF( d.cantidad > 0,(dv.cantidad - d.cantidad),dv.cantidad)),
          dv.cantFinalCancel = (s.cantActual + IF( d.cantidad > 0,(dv.cantidad - d.cantidad),dv.cantidad)) , d.cantFinal = (s.cantActual + IF( d.cantidad > 0,(dv.cantidad - d.cantidad),dv.cantidad)),
          dv.cantCancel = (IF( d.cantidad > 0,(dv.cantidad - d.cantidad),dv.cantidad)), dv.fechaRegCancel = NOW()
          WHERE s.idSucursal = '$idSucursal' AND dv.idVenta = '$idVenta'";
#echo '<br>$sqlUpStk: '.$sqlUpStk;
  $res = mysqli_query($link,$sqlUpStk) or die(cancelaTrans($link,'Problemas al actualizar el stock, notifica a tu Administrador.'));



#echo '<br>------------ Se actualiza el estatus de la venta a cancelada ------------<br>';

$sqlUpVenta = "UPDATE ventas SET estatus = '3' WHERE id = '$idVenta'";
#echo '<br>$sqlUpVenta: '.$sqlUpVenta;
$resUpVenta = mysqli_query($link,$sqlUpVenta) or die(cancelaTrans($link,'Problemas al actualizar la venta, notifica a tu Administrador (3).'));

$sqlUpCancel = "INSERT INTO cancelaciones(idSucursal,idVenta,monto,idUsuario,fecha,regCorte,motivo) VALUES('$idSucursal', '$idVenta', '$montoTotVenta', '$userReg', NOW(), '0','$motivo')";
#echo '<br><br>$sqlUpCancel: '.$sqlUpCancel;
$resUpVenta = mysqli_query($link,$sqlUpCancel) or die(cancelaTrans($link,'Problemas al agregar la cancelación, notifica a tu Administrador.'));

#echo '<br>------------ si hay créditos asociados a la venta se proceden a cancelarse ------------<br>';

  if ($sqC['cant'] > 0) {
    $sqlUpCred = "UPDATE creditos SET estatus = '3' WHERE idVenta = '$idVenta'";
#echo '<br>$sqlUpCred: '.$sqlUpCred;
    $resUpCred = mysqli_query($link,$sqlUpCred) or die(cancelaTrans($link,'Problemas al actualizar los créditos, notifica a tu Administrador (2).'));
  }
#echo '<br>------------ se muestra la devolución de dinero ------------<br>';
  $montoDevolver = $montoTotVenta - $montoDevolucion;
#echo '<br>$montoDevolver: '.$montoTotVenta.' - '.$montoDevolucion;
$_SESSION['cambioDevoluciones'] = $montoDevolver.'|'.$idVenta;

#echo '<br>------------ Se cierra la transacción ------------<br>';
$sqlTransIn = "COMMIT";
#echo '<br>$sqlTransIn: '.$sqlTransIn;
$resTrasnIn = mysqli_query($link,$sqlTransIn) or die('Problemas al iniciar la transacción, notifica a tu Administrador.');
#echo '<br> Línea 152';
$_SESSION['LZFmsjSuccessDevoluciones'] = 'Venta devuelta correctamente.';
header('location: ../devoluciones.php');
exit(0);

function cancelaTrans($link,$error){
#echo '<br> Entró en función de cancelaTrans()';
  $sqlBack = "ROLLBACK";
  $resBack = mysqli_query($link,$sqlBack) or die('No se pudo cancelar la venta. notifica a tu Administrador.');
#echo '<br>$sqlBack: '.$sqlBack;
  unset($_SESSION['cambioDevoluciones']);
  $_SESSION['LZFmsjDevoluciones'] = $error;
  header('location: ../devoluciones.php');
  exit(0);
}

function errorBD($error){
#echo '<br> Entró en función de error';
    unset($_SESSION['cambioDevoluciones']);
    $_SESSION['LZFmsjDevoluciones'] = $error;
    header('location: ../devoluciones.php');
    exit(0);
}
 ?>
