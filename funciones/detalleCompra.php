<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');
$sucursal=$_SESSION['LZFidSuc'];
$userReg=$_SESSION['LZFident'];
if(isset($_GET['idCompra'])){$idCompra=$_GET['idCompra'];}else{$idCompra='0';}
if(isset($_GET['idProveedor'])){$idProveedor=$_GET['idProveedor'];}else{$idProveedor='0';}
if(isset($_GET['producto'])){$producto=$_GET['producto'];}else{$producto='0';}
if(isset($_GET['cant'])){$cantP=$_GET['cant'];}else{$cantP='0';}

//Detallado del Producto y funcion para seleccion de Precio
$sql="SELECT id, descripcion, costo, precioBase1, precioBase2, precioBase3, cantUdsP2, cantUdsP3 FROM productos WHERE id='$producto' or codBarra='$producto'";
$res=mysqli_query($link,$sql) or die (errorBD('Problemas al consultar el Producto, notifica a tu Administrador'));
$var=mysqli_fetch_array($res);
$idProducto= $var['id'];
$costo= $var['costo'];
$nameProd=$var['descripcion'];

//Chequeo si el producto ya existe en esa Compra
$sql="SELECT cantidad FROM detcompras WHERE idProducto='$idProducto' and idCompra='$idCompra'";
$res=mysqli_query($link,$sql);
$cant=mysqli_num_rows($res);

if($cant>=1){
	$var=mysqli_fetch_array($res);
	$cantidad= $var['cantidad']+$cantP;

	$sql="UPDATE detcompras SET cantidad='$cantidad', costoUnitario='$costo' WHERE idProducto='$idProducto' and idCompra='$idCompra'";
	mysqli_query($link,$sql) or die (errorBD('Problemas al actualizar la Compra, notifica a tu Administrador'));
	}
else{
    $sql="INSERT INTO detcompras(idCompra,idProducto,cantidad,costoUnitario) VALUES('$idCompra','$idProducto',1,'$costo')";
    
    mysqli_query($link,$sql) or die (errorBD('Problemas al guardar productos en detalle de Compra, notifica a tu Administrador<br>'.mysqli_error($link)));
    echo mysqli_error($link);
}

$_SESSION['LZmsjSuccessCompra'] = 'El Producto <b>'.$nameProd.'</b> se a seleccionado.';

header('location: ../Administrador/compras.php');

function errorCarga($error){
    $_SESSION['LZmsjInfoCompra'] = $error;
  //echo 'cayo: '.$error;
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
