<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');
$sucursal=$_SESSION['LZFidSuc'];
$userReg=$_SESSION['LZFident'];
$idAjuste = (isset($_GET['idAjuste']) and $_GET['idAjuste'] != '') ? $_GET['idAjuste'] : '';
$producto = (isset($_GET['producto']) and $_GET['producto'] != '') ? $_GET['producto'] : '';
$movimiento = (isset($_GET['movimiento']) and $_GET['movimiento'] != '') ? $_GET['movimiento'] : '';

if($idAjuste==0){
 
  $sql="INSERT INTO ajustes(fechaReg, idUserReg, idSucursal, estatus) VALUES(NOW(),'$userReg','$sucursal',1)";
  mysqli_query($link,$sql) or die (errorCarga('Error al guardar el Ajuste.'));
  $idAjuste=mysqli_insert_id($link);
}
//echo $idAjuste."--->".$cant."-->".$producto;
//Detallado del Producto y funcion para seleccion de Stock Disponible
$sql="SELECT pro.id, pro.descripcion, stk.cantActual FROM productos pro INNER JOIN stocks stk ON stk.idProducto=pro.id AND stk.idSucursal='$sucursal' WHERE pro.id='$producto' OR codBarra='$producto'";
//echo $sql;
$res=mysqli_query($link,$sql) or die (errorBD('Problemas al consultar el Ajuste, notifica a tu Administrador'));
$var=mysqli_fetch_array($res);
$idProducto= $var['id'];
$cantStock=(isset($var['cantActual']) and $var['cantActual'] != '') ? $var['cantActual'] : 0; 
$nameProd=$var['descripcion'];
//echo "producto-->".$producto;
//Chequeo si el producto ya existe en ese Traspaso
$sql="SELECT id, cantidad FROM detajustes WHERE idProducto='$producto' and idAjuste='$idAjuste'";
//echo $sql;
$res=mysqli_query($link,$sql) or die (errorBD('Problemas al consultar el Producto, notifica a tu Administrador'));
$cantrow=mysqli_num_rows($res);
if($cantrow>=1){

    errorBD('Producto  <b>'.$nameProd.'</b> ya agregado.');
  
  }
  




else{
     $sql="INSERT INTO detajustes(idAjuste,idProducto,cantidad, tipo) VALUES('$idAjuste','$producto',1,'$movimiento')";
    mysqli_query($link,$sql) or die (errorBD('Problemas al guardar productos en detalle de Ajustes, notifica a tu Administrador<br>'));
   // echo mysqli_error($link);
}

$_SESSION['LZmsjSuccessAjuste'] = 'El Producto <b>'.$nameProd.'</b> se a seleccionado.';

header('location: ../Administrador/entradasYsalidas.php');

function errorCarga($error){
    $_SESSION['LZmsjInfoAjuste'] = $error;
  //echo 'cayo: '.$error;
  header('location: ../Administrador/entradasYsalidas.php');
  exit(0);
  }
  function errorBD($msj)
  {
  echo '<br>** Se dispara Error: '.$msj.' **<br>';
    $_SESSION['LZmsjInfoAjuste'] = $msj;
   header('location: ../Administrador/entradasYsalidas.php');
    exit(0);
  }
?>
