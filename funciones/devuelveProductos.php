<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');


$idVenta = (!empty($_POST['idVenta'])) ? $_POST['idVenta'] : 0 ;
$desc = (isset($_POST['desc']) && $_POST['desc'] != '') ? $_POST['desc'] : '' ;
$varCantidades = (isset($_POST['varCantidades']) && $_POST['varCantidades'] != '') ? $_POST['varCantidades'] : '' ;
$varIdDetVenta = (isset($_POST['varIdDetVenta']) && $_POST['varIdDetVenta'] != '') ? $_POST['varIdDetVenta'] : '' ;
$varIdLotes = (isset($_POST['varIdLotes']) && $_POST['varIdLotes'] != '') ? $_POST['varIdLotes'] : '' ;


$userReg = $_SESSION['LZFident'];
$idSucursal = $_SESSION['LZFidSuc'];

$cProd = explode(',',$varCantidades);
$idLotes = explode(',',$varIdLotes);
$ids = explode(',',$varIdDetVenta);
$cantP = count($ids);

/*
echo '<br>------------############------------############------------############<br>';
echo '<br>$_POST:<br>';
print_r($_POST);
echo '<br>';
echo '<br>$idVenta: '.$idVenta;
echo '<br>$desc: '.$desc;
echo '<br>$varCantidades: '.$varCantidades;
echo '<br>$varIdDetVenta: '.$varIdDetVenta;
echo '<br>$cantP: '.$cantP;

echo '<br>$cProd:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
echo var_dump($cProd);
echo '<br>$idLotes: ';
echo var_dump($idLotes);
echo '<br>$ids: ';
echo var_dump($ids);
echo '<br>$cantP: ';
echo var_dump($cantP);
echo '<br>';
echo '<br>------------############------------############------------############<br>';
#exit(0);
# */
#echo '<br>------------ se consulta la venta para verificar que no esté cancelada, facturada, en corte o con boletas ------------<br>';
$sqlConVenta = "SELECT v.estatus, v.idCorte,c.estatus AS estatusCorte, COUNT(vf.id) AS facturada,
							 COUNT(CASE pv.idFormaPago WHEN '1' THEN pv.id END) AS efectivo,
               COUNT(CASE pv.idFormaPago WHEN '2' THEN pv.id END) AS cheque,
							 COUNT(CASE pv.idFormaPago WHEN '3' THEN pv.id END) AS transferencia,
               COUNT(CASE pv.idFormaPago WHEN '4' THEN pv.id END) AS tarjeta,
							 COUNT(CASE pv.idFormaPago WHEN '5' THEN pv.id END) AS tarjeta2,
							 COUNT(CASE pv.idFormaPago WHEN '6' THEN pv.id END) AS boletas,
               COUNT(CASE pv.idFormaPago WHEN '7' THEN pv.id END) AS credito, v.total
                FROM ventas v
								LEFT JOIN cortes c ON v.idCorte = c.id
    						LEFT JOIN vtasfact vf ON v.id = vf.idVenta
                LEFT JOIN pagosventas pv ON v.id = pv.idVenta
                WHERE v.id = '$idVenta'
    						GROUP BY v.id
    						LIMIT 1";
$resConVenta = mysqli_query($link,$sqlConVenta) or die(errorBD('Problemas al consultar la venta, notifica a tu Administrador.'));
$dt = mysqli_fetch_array($resConVenta);
#echo '<br>------------ se realizan las validaciones ------------<br>';
if ($dt['estatus'] != 2) {
#		echo '<br> Línea 44';
  errorBD('Lo sentimos, no se realizó ninguna acción porque la venta ya fue cancelada.');
}
#echo '<br>Pasa estatus cerrado';
#		#echo '<br> Línea 47';
if ($dt['estatusCorte'] > 1) {
  errorBD('Lo sentimos, no se realizó ninguna acción porque la venta ya está en un corte cerrado.');
}
#echo '<br>Pasa estatus corte';
#echo '<br> Línea 51';
if ($dt['facturada'] > 0) {
  errorBD('Lo sentimos, no se realizó ninguna acción porque la venta ya fue facturada.');
}
#echo '<br>Pasa facturada';
#echo '<br> Línea 55';
if ($dt['boletas'] > 0) {
  errorBD('Lo sentimos, no se realizó ninguna acción porque la venta contiene pago con boletas, solo se permite devolver ventas con pago en efectivo y crédito.');
}
#echo '<br>Pasa boletas';
if ($dt['transferencia'] > 0) {
  errorBD('Lo sentimos, no se realizó ninguna acción porque la venta contiene pago con transferencia, solo se permite devolver ventas con pago en efectivo y crédito.');
}
#echo '<br>Pasa transferencia';
if ($dt['tarjeta'] > 0) {
  errorBD('Lo sentimos, no se realizó ninguna acción porque la venta contiene pago con tarjeta de débito, solo se permite devolver ventas con pago en efectivo y crédito.');
}
#echo '<br>Pasa tarjeta Débito';
if ($dt['tarjeta2'] > 0) {
  errorBD('Lo sentimos, no se realizó ninguna acción porque la venta contiene pago con tarjeta de crédito, solo se permite devolver ventas con pago en efectivo y crédito.');
}
#echo '<br>Pasa tarjeta Crédito';
if ($dt['cheque'] > 0) {
  errorBD('Lo sentimos, no se realizó ninguna acción porque la venta contiene pago con cheque, solo se permite devolver ventas con pago en efectivo y crédito.');
}
#echo '<br>Pasa Cheque';
#echo '<br> Línea 59';
#echo '<br>------------ Si pasa por el filtro se procede a realizar una transacción ------------<br>';

#echo '<br>------------ Inicio de la Transacción ------------<br>';
$sqlTransIn = "BEGIN";
$resTrasnIn = mysqli_query($link,$sqlTransIn) or die('Problemas al iniciar la transacción, notifica a tu Administrador.');
$precio = $cantDevuelto = $cantDetVenta = $cantFinalStock = $cantTotDev = $cantFinalDetVta = 0;
#echo '<br>$sqlTransIn: '.$sqlTransIn;
for ($i=0; $i < $cantP; $i++) {
  if ($cProd[$i] >= 1) {
#echo '<br>------------ se consulta si hay un registro de ese producto en devoluciones ------------<br>';
    $sqlConsulta = "SELECT COUNT(id) AS cantidad,IF(SUM(cantidad) > 0,SUM(cantidad),0) AS cantDev FROM devoluciones WHERE idDetVenta = '$ids[$i]'";
    $resConsulta = mysqli_query($link,$sqlConsulta) or die(cancelaTrans($link,'Problemas al consultar las devoluciones, notifica a tu Administrador.'));
    $d = mysqli_fetch_array($resConsulta);
    $cantDevuelto = $d['cantDev'];
    $cantADevolver = $cProd[$i] + $cantDevuelto;
#echo '<br>$sqlConsulta: '.$sqlConsulta;
#echo '<br>Se obtiene la cantidad devuelta de ese producto: '.$cantDevuelto;
#echo '<br>------------ se toma la cantidad en $$ para después descontarlo de créditos ------------<br>';
    $sqlPrecio = "SELECT (precioVenta * $cProd[$i]) AS Precio, cantidad,cantFinal FROM detventas WHERE id = '$ids[$i]'";
    $resPrecio = mysqli_query($link,$sqlPrecio) or die(cancelaTrans($link,'Problemas al consultar el detalle de la venta, notifica a tu Administrador.'));
    $sp = mysqli_fetch_array($resPrecio);
    $cantDetVenta = $sp['cantidad'];
    $cantFinalDetVta = $sp['cantFinal'];
#echo '<br>$sqlPrecio: '.$sqlPrecio;
#echo '<br>Se evalua si la cantidad de venta es igual o mayor al total devuelto (devuelto + devolver): '.$cantADevolver.' <= '.$cantDetVenta;
    if ($cantADevolver <= $cantDetVenta) {
      #echo '<br>Sí pasó para devolver.';
      $precio += $sp['Precio'];
#echo '<br>------------ Se actualiza el stock ------------<br>';
      $sqlStock = "UPDATE stocks s INNER JOIN detventas dv ON s.idProducto = dv.idProducto
                    SET s.cantFinal = (s.cantActual + $cProd[$i]), s.cantActual = (s.cantActual + $cProd[$i])
                    WHERE s.idSucursal = '$idSucursal' AND dv.id = '$ids[$i]'";
      $resStock = mysqli_query($link,$sqlStock) or die(cancelaTrans($link,'Problemas al actualizar el stock, notifica a tu Administrador.'));
#echo '<br>$sqlStock: '.$sqlStock;
      $sqlConStock = "SELECT s.cantActual FROM stocks s INNER JOIN detventas dv ON s.idProducto = dv.idProducto WHERE s.idSucursal = '$idSucursal' AND dv.id = '$ids[$i]'";
      $resConStock = mysqli_query($link,$sqlConStock) or die(cancelaTrans($link,'Problemas al consultar el stock,, notifica a tu Administrador.'));
      $stk = mysqli_fetch_array($resConStock);
      $cantFinalStock = $stk['cantActual'];
			#echo '<br>$sqlConStock: '.$sqlConStock;
      #/*
#echo '<br>------------ se actuacliza el detallado de ventas ------------<br>';
      $sqlDetVta = "UPDATE detventas SET cantFinalCancel = $cantFinalStock, cantCancel = '$cantADevolver', fechaRegCancel = NOW() WHERE id = '$ids[$i]'";
      $resDetVta = mysqli_query($link,$sqlDetVta) or die(cancelaTrans($link,'Problemas al actualizar el detalle de la venta, notifica a tu Administrador.'));
	#echo '<br>$sqlDetVta: '.$sqlDetVta;
#/*
#echo '<br>------------ se consultan los lotes a los que ha descontado ------------<br>';
			$sqlConLote = "SELECT lt.estatus, lt.id FROM detvtalotes dl INNER JOIN lotestocks lt ON dl.idLote = lt.id WHERE dl.idDetVenta = '$ids[$i]'";
			#echo '<br>$sqlConLote: '.$sqlConLote;
			$resConLote = mysqli_query($link,$sqlConLote) or die(cancelaTrans($link,'Problemas al consultar los lotes, notifica a tu Administrador.'));
			$lt = mysqli_fetch_array($resConLote);
			$idLote = $lt['id'];
			$sqlUpLote = "UPDATE lotestocks SET cant = (cant + $cProd[$i]), estatus = 1 WHERE id = '$idLote'";
			#echo '<br>$sqlUpLote: '.$sqlUpLote;
			$resUpLote = mysqli_query($link,$sqlUpLote) or die(cancelaTrans($link,'Problemas al actualizar los lotes, notifica a tu Administrador.'));
			#*/
      $cantTotDev = $cantFinalDetVta +  $cProd[$i];
      if ($d['cantidad'] == 0) {
#echo '<br>------------ si no hay se ingresa uno nuevo ------------<br>';
        $sqlInsert = "INSERT INTO devoluciones(idSucursal,idVenta,idDetVenta,cantidad,motivo,idUserReg,fechaReg,regCorte,cantFinal)
                      VALUES('$idSucursal','$idVenta','$ids[$i]',$cProd[$i],'$desc','$userReg',NOW(),0,$cantFinalStock)";
        $resInsert = mysqli_query($link,$sqlInsert) or die(cancelaTrans($link,'Problemas al agregar la devolución,notifica a tu Administrador.'));
				$newIdDevolucion = mysqli_insert_id($link);
				#$newIdDevolucion = 1;
#echo '<br>$sqlInsert: '.$sqlInsert;
				$sqlInDevLote = "INSERT INTO detdevlotes(idDevolucion,idLote,cantidad) VALUES('$newIdDevolucion','$idLotes[$i]',$cProd[$i])";
				#echo '<br>$sqlInDevLote: '.$sqlInDevLote;
				$resInDevLote = mysqli_query($link,$sqlInDevLote) or die(cancelaTrans($link,'Problemas al insertar en devLotes, notifica a tu Administrador.'));

      } else {
#echo '<br>------------ sí hay, se actualiza el registro ------------<br>';
        $sqlUpdate = "UPDATE devoluciones SET cantFinal = $cantFinalStock, cantidad = (cantidad + $cProd[$i]), fechaReg = NOW(), idUserReg = '$userReg', motivo = '$desc' WHERE idDetVenta = '$ids[$i]' ";
        $resUpdate = mysqli_query($link,$sqlUpdate) or die(cancelaTrans($link, 'Problemas al actualizar las devoluciones, notifica a tu Administrador.'));
				$sqlConDev = "SELECT id FROM devoluciones WHERE idDetVenta = '$ids[$i]' LIMIT 1";
				$resConDev = mysqli_query($link,$sqlConDev) or die(cancelaTrans($link, 'Problemas al consultar la devolución, notifica a tu Administrador.'));
				$dev = mysqli_fetch_array($resConDev);
				$newIdDevolucion = $dev['id'];
				#$newIdDevolucion = 1;
#echo '<br>$sqlUpdate: '.$sqlUpdate;
#echo '<br>$sqlConDev: '.$sqlConDev;

				$sqlInDevLote = "INSERT INTO detdevlotes(idDevolucion,idLote,cantidad) VALUES('$newIdDevolucion','$idLotes[$i]',$cProd[$i])";
				#echo '<br>$sqlInDevLote: '.$sqlInDevLote;
				$resInDevLote = mysqli_query($link,$sqlInDevLote) or die(cancelaTrans($link,'Problemas al insertar en devLotes, notifica a tu Administrador.'));

      }
    }
  }
}

#echo '<br>------------ se consulta si las cantidades son iguales ------------<br>';
$sqlCantProd = "SELECT SUM(dv.cantidad) AS cDetVenta, IF(SUM(d.cantidad) > 0,SUM(d.cantidad),0) AS cDev
                FROM detventas dv
                LEFT JOIN devoluciones d ON dv.id = d.idDetVenta
                WHERE dv.idVenta = '$idVenta'";
$resCantProd = mysqli_query($link,$sqlCantProd) or die(cancelaTrans($link,'Problemas al consultar si los productos a devolver son iguales a los vendidos, notifica a tu Administrador.'));
$rCantPd = mysqli_fetch_array($resCantProd);
#echo '<br>$sqlCantProd: '.$sqlCantProd;
if ($rCantPd['cDetVenta'] == $rCantPd['cDev']) {
#echo '<br>------------ si lo cumple se cancela la venta y los creditos si es que hay ------------<br>';
  $sqlCancel = "UPDATE ventas SET estatus = '3' WHERE id = '$idVenta'";
  $resCancel = mysqli_query($link,$sqlCancel) or die(cancelaTrans($link,'Problemas al actualizar la venta, notifica a tu Administrador.'));
#echo '<br>$sqlCancel: '.$sqlCancel;
  $sqlCred = "SELECT COUNT(id) AS cant FROM creditos WHERE idVenta = '$idVenta' AND estatus = '1'";
  $resCred = mysqli_query($link,$sqlCred) or die(cancelaTrans($link,'Problemas al consultar existencia de crédito en la venta, notifica a tu Administrador.'));
  $sqC = mysqli_fetch_array($resCred);
#echo '<br>$sqlCred: '.$sqlCred;

  if ($sqC['cant'] > 0) {
  $sqlCanCred = "UPDATE creditos SET estatus = '3' WHERE idVenta = '$idVenta'";
  $resCanCed = mysqli_query($link,$sqlCanCred) or die(cancelaTrans($link,'Problemas al actualizar los créditos, notifica a tu Administrador.'));
#echo '<br>$sqlCanCred: '.$sqlCanCred;
  }

	$sqlCred = "SELECT COUNT(id) AS cant, montoDeudor, totalDeuda FROM creditos WHERE idVenta = '$idVenta'";
	$resCred = mysqli_query($link,$sqlCred) or die(cancelaTrans($link,'Problemas al consultar si hay un crédito a la venta, notifica a tu Administrador.'));
#echo '<br>$sqlCred: '.$sqlCred;
	$sqC = mysqli_fetch_array($resCred);
			$monto1 = $monto2 = $cambio = 0;
	if ($sqC['cant'] > 0) {
		if ($sqC['montoDeudor'] > 0 && $sqC['montoDeudor'] > $precio) {
			$monto1 = $sqC['montoDeudor'] - $precio ;
			$cambio = 0;
			$_SESSION['cambioDevoluciones'] = $cambio.'|'.$idVenta;

	#echo '<br>$cambio: '.$cambio;
			$sqlMontoDevuelto = "INSERT INTO montosdevueltos(idDevolucion, idFormaPago, monto) VALUES('$newIdDevolucion','7','$precio')";
			$resMontoDevolver = mysqli_query($link,$sqlMontoDevuelto) or die(cancelaTrans($link,'Problemas al ingresar el monto devuelto(1), notifica a tu Administrador.'));
#echo '<br>$sqlCanCred: '.$sqlCanCred;
		} elseif ($sqC['montoDeudor'] > 0 && $sqC['montoDeudor'] < $precio) {
			$cambio = $precio - $sqC['montoDeudor'] ;
			$monto1 = 0 ;
			$monto2 = $sqC['montoDeudor'] ;
			$_SESSION['cambioDevoluciones'] = $cambio.'|'.$idVenta;

			#echo '<br>$cambio: '.$cambio;
			$sqlMontoDevuelto = "INSERT INTO montosdevueltos(idDevolucion, idFormaPago, monto) VALUES('$newIdDevolucion','7','$monto2'),('$newIdDevolucion','1','$cambio')";
			$resMontoDevolver = mysqli_query($link,$sqlMontoDevuelto) or die(cancelaTrans($link,'Problemas al ingresar el monto devuelto(2), notifica a tu Administrador.'));
#echo '<br>$sqlCanCred: '.$sqlCanCred;
		} else {
		}


	} else {
		$_SESSION['cambioDevoluciones'] = $precio.'|'.$idVenta;

		$sqlMontoDevuelto = "INSERT INTO montosdevueltos(idDevolucion, idFormaPago, monto) VALUES($newIdDevolucion,1,$precio)";
		$resMontoDevolver = mysqli_query($link,$sqlMontoDevuelto) or die(cancelaTrans($link,'Problemas al ingresar el monto devuelto(3), notifica a tu Administrador.'));
#echo '<br>$sqlCanCred: '.$sqlCanCred;
	}

} else {
#echo '<br>------------ si no lo cumple, se verifica si la venta tiene crédito ------------<br>';
  $sqlCred = "SELECT COUNT(id) AS cant, montoDeudor, totalDeuda FROM creditos WHERE idVenta = '$idVenta' AND estatus = '1'";
  $resCred = mysqli_query($link,$sqlCred) or die(cancelaTrans($link,'Problemas al consultar si hay un crédito a la venta, notifica a tu Administrador.'));
#echo '<br>$sqlCred: '.$sqlCred;
  $sqC = mysqli_fetch_array($resCred);
      $monto1 = $monto2 = $cambio = 0;
  if ($sqC['cant'] > 0) {
    if ($sqC['montoDeudor'] > 0 && $sqC['montoDeudor'] > $precio) {
      $monto1 = $sqC['montoDeudor'] - $precio ;
      $cambio = 0;
      $_SESSION['cambioDevoluciones'] = $cambio.'|'.$idVenta;

			#echo '<br>$cambio: '.$cambio;
      $sqlCanCred = "UPDATE creditos SET montoDeudor = '$monto1' WHERE idVenta = '$idVenta'";
      $resCanCed = mysqli_query($link,$sqlCanCred) or die(cancelaTrans($link,'Problemas al actualizar los créditos, notifica a tu Administrador.(2)'));

			$sqlMontoDevuelto = "INSERT INTO montosdevueltos(idDevolucion, idFormaPago, monto) VALUES('$newIdDevolucion','7','$precio')";
			$resMontoDevolver = mysqli_query($link,$sqlMontoDevuelto) or die(cancelaTrans($link,'Problemas al ingresar el monto devuelto(4), notifica a tu Administrador.'));
#echo '<br>$sqlCanCred: '.$sqlCanCred;
#echo '<br>$sqlMontoDevuelto: '.$sqlMontoDevuelto;
    } elseif ($sqC['montoDeudor'] > 0 && $sqC['montoDeudor'] < $precio) {
      $cambio = $precio - $sqC['montoDeudor'] ;
      $monto1 = 0 ;
			$monto2 = $sqC['montoDeudor'] ;
      $_SESSION['cambioDevoluciones'] = $cambio.'|'.$idVenta;

			#echo '<br>$cambio: '.$cambio;
      $sqlCanCred = "UPDATE creditos SET montoDeudor = '$monto1', estatus = '3' WHERE idVenta = '$idVenta'";
      $resCanCed = mysqli_query($link,$sqlCanCred) or die(cancelaTrans($link,'Problemas al actualizar los créditos, notifica a tu Administrador.(3)'));
			$sqlMontoDevuelto = "INSERT INTO montosdevueltos(idDevolucion, idFormaPago, monto) VALUES('$newIdDevolucion','7','$monto2'),('$newIdDevolucion','1','$cambio')";
			$resMontoDevolver = mysqli_query($link,$sqlMontoDevuelto) or die(cancelaTrans($link,'Problemas al ingresar el monto devuelto(5), notifica a tu Administrador.'));
#echo '<br>$sqlCanCred: '.$sqlCanCred;
#echo '<br>$sqlMontoDevuelto: '.$sqlMontoDevuelto;
    } else {
    }


  } else {
		$_SESSION['cambioDevoluciones'] = $precio.'|'.$idVenta;

		$sqlMontoDevuelto = "INSERT INTO montosdevueltos(idDevolucion, idFormaPago, monto) VALUES('$newIdDevolucion','1','$precio')";
		$resMontoDevolver = mysqli_query($link,$sqlMontoDevuelto) or die(cancelaTrans($link,'Problemas al ingresar el monto devuelto(6), notifica a tu Administrador.'));
	#echo '<br>$sqlMontoDevuelto: '.$sqlMontoDevuelto;
  }
}

#echo '<br>------------ FIN de la Transacción ------------<br>';
$sqlTransIn = "COMMIT";
$resTrasnIn = mysqli_query($link,$sqlTransIn) or die('Problemas al cerrar la transacción, notifica a tu Administrador.');
#echo '<br>$sqlTransIn: '.$sqlTransIn;
#echo '<br> Línea 152';

$_SESSION['LZFmsjSuccessDevoluciones'] = 'Venta devuelta correctamente.';
header('location: ../devoluciones.php');
exit(0);

#/*

  function cancelaTrans($link,$error){
#echo '<br> Entró en función de cancelaTrans(), $error: '.$error;
    $sqlBack = "ROLLBACK";
    $resBack = mysqli_query($link,$sqlBack) or die('No se pudo realizar cancelar los movimientos. notifica a tu Administrador.');
#echo '<br>$sqlBack: '.$sqlBack;
		unset($_SESSION['cambioDevoluciones']);
    $_SESSION['LZFmsjDevoluciones'] = $error;
    header('location: ../devoluciones.php');
    exit(0);
  }

function errorBD($error){
#echo '<br> Entró en función de error:<br>'.$error;
		unset($_SESSION['cambioDevoluciones']);
		$_SESSION['LZFmsjDevoluciones'] = $error;
    header('location: ../devoluciones.php');
    exit(0);
}
#*/
 ?>
