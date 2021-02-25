<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$idSucursal = $_SESSION['LZFidSuc'];
$idUser = $_SESSION['LZFident'];
$idLote = (isset($_POST['lote']) && $_POST['lote'] != '') ? $_POST['lote'] : 0 ;
$cantLote = (isset($_POST['cantLote']) && $_POST['cantLote'] != '') ? $_POST['cantLote'] : 0 ;
$cantResta = (isset($_POST['cantResta']) && $_POST['cantResta'] != '') ? $_POST['cantResta'] : 0 ;
$idCompra = (isset($_POST['idCompra']) && $_POST['idCompra'] != '') ? $_POST['idCompra'] : 0 ;
$idRecepcion = (isset($_POST['idRecepcion']) && $_POST['idRecepcion'] != '') ? $_POST['idRecepcion'] : 0 ;
$idDetCompra = (isset($_POST['idDetCompra']) && $_POST['idDetCompra'] != '') ? $_POST['idDetCompra'] : 0 ;
$idProducto = (isset($_POST['idProducto']) && $_POST['idProducto'] != '') ? $_POST['idProducto'] : 0 ;
$vista = (isset($_POST['vista']) && $_POST['vista'] != '') ? $_POST['vista'] : 1 ;

/*
echo '<br> ##################################################### <br>';
echo '<br>$_POST:<br>';
print_r($_POST);
echo '<br>$idLote:'.var_dump($idLote);
echo '<br>$cantLote:'.var_dump($cantLote);
echo '<br>$idCompra:'.$idCompra;
echo '<br>$idRecepcion:'.$idRecepcion;
echo '<br>$idDetCompra:'.$idDetCompra;
echo '<br>$idProducto:'.$idProducto;
echo '<br>$idSucursal:'.$idSucursal;
echo '<br>$idUser:'.$idUser;
echo '<br> ##################################################### <br>';
#exit(0);
#*/
$cantTotal = 0;
for ($i=0; $i < sizeof($idLote) ; $i++) {
  if ($cantLote[$i] > 0) {
    $cantTotal += $cantLote[$i];
  }
}

if ($cantTotal > $cantResta) {
  errorBD2('No se permite agregar más producto que el solicitado en la compra, verifica el detalle de la compra ('.$cantTotal.' > '.$cantResta.').');
}

$sqlInit = "START TRANSACTION";
#echo '<br>$sqlInit:'.$sqlInit;
$resInit = mysqli_query($link,$sqlInit) or die(errorBD('Problemas al iniciar, notifica a tu Administrador.',$link));

if ($idRecepcion < 1) {
  $sqlIn2 = "INSERT INTO recepciones(idCompra,idUserReg,fechaReg,idSucursal,estatus) VALUES('$idCompra','$idUser',NOW(),'$idSucursal','1')";
  #echo '<br>$sqlIn2:'.$sqlIn2;
  $resIn2 = mysqli_query($link,$sqlIn2) or die(errorBD('Problemas al agregar la recepción, notifica a tu Administrador.',$link));
  $idRecepcion = mysqli_insert_id($link);
}
$cont = 0;
$lot = '';
for ($i=0; $i < sizeof($idLote) ; $i++) {
  if ($cantLote[$i] > 0) {
    if ($idLote[$i] > 0) {
      #echo '<br>Condición con idLote mayor a cero';
      $sqlIn = "INSERT INTO detrecepciones(idRecepcion,idProducto,cantidad,idLote,caducidad,idDetCompra,estatus) SELECT '$idRecepcion','$idProducto','$cantLote[$i]','$idLote[$i]',l.caducidad,'$idDetCompra','1'
      FROM lotestocks l WHERE l.id = $idLote[$i]";
    } else {
      #echo '<br>Condición con idLote igual a cero';
      $sqlIn = "INSERT INTO detrecepciones(idRecepcion,idProducto,cantidad,idLote,caducidad,idDetCompra,estatus) VALUES('$idRecepcion','$idProducto','$cantLote[$i]','$idLote[$i]','0','$idDetCompra','1')";
      }
      #echo '<br>$sqlIn:'.$sqlIn;
      $resIn = mysqli_query($link,$sqlIn) or die(errorBD('Problemas al registrar el producto, notifica a tu Administrador.',$link));
  } else {
    $cont++;
    $lot .= $idLote[$i].', ';
  }
}

$sqlInit2 = "COMMIT";
#echo '<br>$sqlInit2:'.$sqlInit2;
$resInit2 = mysqli_query($link,$sqlInit2) or die(errorBD('Problemas al Cargar los productos, notifica a tu Administrador.',$link));

if ($cont > 0) {
  $lot = trim(", ",$lot);
  errorBD2('No se cargaron los siguientes lotes porque la cantidad es menor a 1: '.$lot);
}

if ($vista == 1) {
$_SESSION['LZFmsjSuccessRecibeProductoBodega'] = 'Se han capturado los productos correctamente.';
header('location: ../Bodega/recibeProductos.php');
} else {
  $_SESSION['LZFmsjSuccessRecibeProductoAdmin'] = 'Se han capturado los productos correctamente.';
  header('location: ../Administrador/adminRecibeProductos.php');
}
exit(0);

function errorBD($error,$link){
  $sqlInit3 = "ROLLBACK";
  #echo '<br>$error:'.$error;
  #echo '<br>$sqlInit3:'.$sqlInit3;
  $resInit3 = mysqli_query($link,$sqlInit3) or die('Problemas al cancelar, notifica a tu Administrador.');
  if ($GLOBALS['vista'] == 1) {
   $_SESSION['LZFmsjRecibeProductoBodega'] = $error;
   header('location: ../Bodega/recibeProductos.php');
   exit(0);
 } else {
   $_SESSION['LZFmsjRecibeProductoAdmin'] = $error;
   header('location: ../Administrador/adminRecibeProductos.php');
   exit(0);
 }
}

function errorBD2($error){
  #echo '<br>$error:'.$error;
  if ($GLOBALS['vista'] == 1) {
   $_SESSION['LZFmsjRecibeProductoBodega'] = $error;
   header('location: ../Bodega/recibeProductos.php');
   exit(0);
 } else {
   $_SESSION['LZFmsjRecibeProductoAdmin'] = $error;
   header('location: ../Administrador/adminRecibeProductos.php');
   exit(0);
 }
}

#*/
 ?>
