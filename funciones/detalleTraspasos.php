<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');
$sucursal=$_SESSION['LZFidSuc'];
$userReg=$_SESSION['LZFident'];
$idTraspasos = (isset($_GET['idTraspasos']) and $_GET['idTraspasos'] != '') ? $_GET['idTraspasos'] : '';
$cant = (isset($_GET['cant']) and $_GET['cant'] != '') ? $_GET['cant'] : '';
$producto = (isset($_GET['producto']) and $_GET['producto'] != '') ? $_GET['producto'] : '';
//echo $idTraspasos."--->".$cant."-->".$producto;
//Detallado del Producto y funcion para seleccion de Stock Disponible
$sql="SELECT pro.id, pro.descripcion, stk.cantActual FROM productos pro INNER JOIN stocks stk ON stk.idProducto=pro.id AND stk.idSucursal='$sucursal' WHERE pro.id='$producto' OR codBarra='$producto'";
//echo $sql;
$res=mysqli_query($link,$sql) or die (errorBD('Problemas al consultar el Producto, notifica a tu Administrador'));
$var=mysqli_fetch_array($res);
$idProducto= $var['id'];
$cantStock=(isset($var['cantActual']) and $var['cantActual'] != '') ? $var['cantActual'] : 0; 
$nameProd=$var['descripcion'];
//echo "producto-->".$producto;
//Chequeo si el producto ya existe en ese Traspaso
$sql="SELECT cantEnvio FROM dettraspasos WHERE idProducto='$idProducto' and idTraspaso='$idTraspasos'";
$res=mysqli_query($link,$sql) or die (errorBD('Problemas al consultar el Producto, notifica a tu Administrador'));
$cantrow=mysqli_num_rows($res);
if($cantrow>=1){
	$var=mysqli_fetch_array($res);
  $cantidad= $var['cantEnvio']+1;
 //  echo "cantidad-->".$cantidad;
  if($cantStock<$cantidad){
    errorBD('Sólo tienes <b>'.$cantStock.'</b> no se puede solventar el traspaso');
    }
	$sql="UPDATE dettraspasos SET cantEnvio='$cantidad' WHERE idProducto='$producto' and idTraspaso='$idTraspasos'";
	mysqli_query($link,$sql) or die (errorBD('Problemas al actualizar el Traspaso, notifica a tu Administrador'));
  }
  




else{
  //echo "cantStock-->".$cantStock.'<br>';
  //echo "cantidad-->".$cant.'<br>';

  if($cantStock<1){
    errorBD('Sólo tienes <b>'.$cantStock.'</b> no se puede solventar el traspaso');
  }
    $sql="INSERT INTO dettraspasos(idTraspaso,idProducto,cantEnvio) VALUES('$idTraspasos','$producto',1)";
    
    mysqli_query($link,$sql) or die (errorBD('Problemas al guardar productos en detalle de Traspasos, notifica a tu Administrador<br>'));
  echo mysqli_error($link);
}

$_SESSION['LZmsjSuccessTraspasos'] = 'El Producto <b>'.$nameProd.'</b> se a seleccionado.';

header('location: ../Administrador/traspasos.php');

function errorCarga($error){
    $_SESSION['LZmsjInfoTraspasos'] = $error;
  //echo 'cayo: '.$error;
   header('location: ../Administrador/traspasos.php');
  exit(0);
  }
  function errorBD($msj)
  {
  //echo '<br>** Se dispara Error: '.$msj.' **<br>';
    $_SESSION['LZmsjInfoTraspasos'] = $msj;
  header('location: ../Administrador/traspasos.php');
    exit(0);
  }
?>
