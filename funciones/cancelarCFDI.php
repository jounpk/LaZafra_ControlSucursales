<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');
require_once('CFDIS/CFDI_FACTURACION_FACTCOM.PHP');
$uidFact = (isset($_POST['uid'])) ? $_POST['uid'] : '' ;
$monto = (isset($_POST['monto'])) ? $_POST['monto'] : '' ;
$fechaReg = (isset($_POST['fechaReg'])) ? $_POST['fechaReg'] : '' ;
if ($uidFact == '' OR $monto=='' Or $fechaReg=='') {
    errorBD('No se reconoció la venta, actualiza e intenta otra vez, si persiste notifica a tu Administrador');
  }


//----------------------------------Filtros para la cancelacion de CFDI-----------------------
/*
Errores:
1: Correcto
2: Falla de Filtro
3: Falla de Base de Datos
*/
$sql="SELECT count(dtcomp.id) AS cantcomplementos FROM detcomplementos dtcomp
INNER JOIN facturas fact ON fact.uuid=dtcomp.uuidFact AND fact.uid='$uidFact'";
$respXcomp =  mysqli_query($link, $sql) or die(errorBD('Problemas al consultar Datos de Complementos.' . mysqli_error($link)));
$arrayXComp= mysqli_fetch_array($respXcomp);
$cant_complementos=$arrayXComp["cantcomplementos"];
$date_now = strtotime(date('d-m-Y H:i:00'));
$fechaCalculo=strtotime("+3 day", strtotime($fechaReg));
if($monto>=5000){
  echo '2|El monto excede $5000.00, consulta al receptor de la factura para su cancelación';

} else if($fechaCalculo<$date_now){
  echo '2|La fecha de Emisión excede a 3 días, consulta al receptor de la factura para su cancelación';
}else if($cant_complementos>=1){
  echo '2|Existe Complementos de Pago registrado, consulta al receptor de la factura para su cancelación';
}

else{
  $uids_Ventas=array($uidFact);
  $respuesta=cancelarFactura($uids_Ventas, $link, '0');
  
  switch ($respuesta["estatus"]){
    case '1':
        echo '1|'.$respuesta["mensaje"];
        $_SESSION['LZFmsjSuccessFactSuc']="Cancelación Correcta";
      break;
    case '0':
      echo '0|'.$respuesta["mensaje"]."|".json_decode($respuesta["idsErroneos"])[0];
      break;
    case '3':
        echo '0|'.$respuesta["mensaje"]."|".json_decode($respuesta["idsErroneos"])[0];
      break;  
  
  }
  
}





function errorBD($error){
    echo '0|'.$error;
  }
?>