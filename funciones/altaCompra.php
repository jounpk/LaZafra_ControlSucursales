<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$idProveedor = (isset($_GET['ident']) AND $_GET['ident'] != '') ? $_GET['ident'] : '' ;
//if(isset($_GET['ident'])){$idProveedor=$_GET['ident'];}else{$idProveedor='';}
$sucursal=$_SESSION['LZFidSuc'];
$userReg=$_SESSION['LZFident'];


if ($idProveedor==""){
	//echo "Compra Null";
errorCarga("Selecciona un proveedor para continuar. Inténtalo de Nuevo.");
    }
    
$sqlCon="SELECT prov.nombre FROM proveedores prov WHERE id=".$idProveedor;
$resCon = mysqli_query($link,$sqlCon) or die(errorBD('Problemas al consultar los Proveedores, notifica a tu Administrador'));

$cant = mysqli_num_rows($resCon);

if ($cant < 0) {
    errorCarga('No se encuentra el Proveedor seleccionado, notifica a tu Administrador');
  }
$Prov=mysqli_fetch_array($resCon);
$nameProv=$Prov['nombre'];

$sql="INSERT INTO compras(fechaReg,idUserReg,idSucursal,estatus,idProveedor, fechaCompra) VALUES(NOW(),'$userReg','$sucursal',1,'$idProveedor', NOW())";
mysqli_query($link,$sql) or die (errorCarga('Error al guardar el Producto en la Compra.'));

//echo 'Ok';
$_SESSION['LZmsjSuccessCompra'] = 'El Proveedor <b>'.$nameProv.'</b> se a seleccionado.';

header('location: ../Administrador/compras.php');

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
