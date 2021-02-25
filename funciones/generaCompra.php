<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');
$id = (isset($_POST['ident']) AND $_POST['ident'] != '') ? $_POST['ident'] : '' ;
$nota= (isset($_POST['nota']) AND $_POST['nota'] != '') ? $_POST['nota'] : '' ;
$total=(isset($_POST['total']) AND $_POST['total'] != '') ? $_POST['total'] : '' ;
$subtotal=(isset($_POST['subtotal']) AND $_POST['subtotal'] != '') ? $_POST['subtotal'] : '' ;
$iva=(isset($_POST['iva']) AND $_POST['iva'] != '') ? $_POST['iva'] : '' ;

$sucursal=$_SESSION['LZFidSuc'];
$userReg=$_SESSION['LZFident'];

if($id=='' ||  $total=="" || $subtotal=="" || $iva==""){
	errorCarga("Problemas en el ingreso de la compra. Inténtalo de Nuevo.");

	}


$sql="SELECT * FROM compras WHERE id='$id' AND estatus=1 ORDER BY id";
$res=mysqli_query($link,$sql) or die(errorBD("Problemas al consultar registro de la compra, notifica a tu Administrador."));
$cant=mysqli_num_rows($res);

if($cant==0){
	errorCarga("Compra efectuada o no registrada con éxito, notifica a tu Administrador.");

	//header('Location:../reimprimeTickets.php?idVenta='.$id.'&tipoTicket=lanzaCompra');
}


$sql="SELECT det.id AS detcompras, det.cantidad AS cantidad, com.idSucursal, det.idProducto, prod.descripcion AS producto
FROM compras com
INNER JOIN detcompras det ON com.id=det.idCompra
INNER JOIN productos prod ON det.idProducto=prod.id
WHERE com.id='$id' ORDER BY com.id";
$res=mysqli_query($link,$sql) or die(errorBD("Problemas al consultar detalles de la compra. Inténtalo de Nuevo."));
#echo $sql.'<br>';
$cant=mysqli_num_rows($res);
if($cant==0){
	errorCarga("Compra registrada sin productos registrados. Inténtalo de Nuevo.");
}


$sql="UPDATE compras SET total='$total', fechaCompra=NOW(), idUserReg='$userReg', estatus=2,nota='$nota',subtotal='$subtotal', iva='$iva' WHERE id='$id'";
mysqli_query($link,$sql) or die ("Problemas al finalizar tu compra, notifica a tu Administrador.");
//echo $sql;
$_SESSION['LZmsjSuccessCompra'] = 'La compra ha finalizado con éxito.';
//header('location: ../Administrador/compras.php');
header('location:../Administrador/reimprimeTickets.php?idVenta='.$id.'&tipoTicket=lanzaCompra');
function errorCarga($error){
    $_SESSION['LZmsjInfoCompra'] = $error;
 // echo 'cayo: '.$error;
  header('location: ../Administrador/compras.php');
  exit(0);
  }
  function errorBD($msj)
  {
    //echo '<br>** Se dispara Error: '.$msj.' **<br>';
    $_SESSION['LZmsjInfoCompra'] = $msj;
    header('location: ../Administrador/compras.php');
    exit(0);
  }

?>
