<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');
require_once('../assets/scripts/cadenas.php');
require_once('../assets/scripts/Thumb.php');

$caducidad = (isset($_POST['caducidad']) AND $_POST['caducidad'] != '') ? $_POST['caducidad'] : '' ;
$idRecepcion = (isset($_POST['idRecepcion']) AND $_POST['idRecepcion'] != '') ? $_POST['idRecepcion'] : 0 ;
$idProd = (isset($_POST['idProd']) AND $_POST['idProd'] != '') ? $_POST['idProd'] : 0 ;
$idCompra = (isset($_POST['idCompra']) AND $_POST['idCompra'] != '') ? $_POST['idCompra'] : 0 ;
$idDetCompra = (isset($_POST['idDetCompra']) AND $_POST['idDetCompra'] != '') ? $_POST['idDetCompra'] : 0 ;
$cantResta = (isset($_POST['cantResta']) && $_POST['cantResta'] != '') ? $_POST['cantResta'] : 0 ;
$cantidad = (isset($_POST['cantidad']) AND $_POST['cantidad'] != '') ? $_POST['cantidad'] : 0 ;
$idSucursal = $_SESSION['LZFidSuc'];
$idUser = $_SESSION['LZFident'];


if ($cantResta < $cantidad) {
  errorBD2('No se permite agregar más producto que el solicitado en la compra, verifica el detalle de la compra.');
}

if ($cantidad < 1) {
 errorBD2('No se recibió una cantidad o la cantidad es menos a 0, debes ingresar la cantidad, inténtalo de nuevo');
}

if ($idProd < 1) {
  errorBD2('No se reconoció el producto o el producto que deseas agregar el lote no se encuentra registrado en el Stock, debes tener el producto registrado en el stock, verifica tu stock.');
}

#tipo va a ser si tiene o no caducidad
$anioFecha = date_format(date_create($caducidad), 'Y');
$mesFecha = date_format(date_create($caducidad), 'm');
$diaFecha = date_format(date_create($caducidad), 'd');
if ($caducidad != '') {
  $nombre = 'lts_'.$idProd.'_'.$anioFecha.$mesFecha.$diaFecha;
  $tipo = 1;
} else {
  $nombre = 'lts_'.$idProd.'_gral';
  $tipo = 2;
}

$sqlInit = "START TRANSACTION";
$resInit = mysqli_query($link,$sqlInit) or die(errorBD('Problemas al iniciar, notifica a tu Administrador.'));

$sqlCon = "SELECT * FROM stocks WHERE idProducto = '$idProd' AND idSucursal = '$idSucursal'";
$resCon = mysqli_query($link,$sqlCon) or die(errorBD('Problemas al obtener el stock, notifica a tu Administrador.'));
$rs = mysqli_fetch_array($resCon);
$idStock = $rs['id'];

if ($idRecepcion < 1) {
  $sqlIn2 = "INSERT INTO recepciones(idCompra,idUserReg,fechaReg,idSucursal,estatus) VALUES('$idCompra','$idUser',NOW(),'$idSucursal','1')";
  $resIn2 = mysqli_query($link,$sqlIn2) or die(errorBD('Problemas al agregar la recepción, notifica a tu Administrador.'));
  $idRecepcion = mysqli_insert_id($link);
}

if ($tipo == 1) {
$sql = "INSERT INTO lotestocks(idSucursal,idProducto,idStock,cant,lote,caducidad,estatus) VALUES('$idSucursal','$idProd','$idStock','0','$nombre','$caducidad','1')";
$res = mysqli_query($link,$sql) or die(errorBD('Problemas al registrar el lote, notifica a tu Administrador.'));
$idLote = mysqli_insert_id($link);
} else {
    # se consulta la existencia del lote
  $cant = 0;
  $sql = "SELECT * FROM lotestocks WHERE lote = '$nombre' AND idSucursal = '$idSucursal' AND idProducto = '$idProd' LIMIT 1";
  $res = mysqli_query($link,$sql) or die(errorBD('Problemas al registrar el lote, notifica a tu Administrador.'));
  $cant = mysqli_num_rows($res);

  if ($cant > 0) {
    # si existe el lote, toma el id
    $lt = mysqli_fetch_array($res);
    $idLote = $lt['id'];
  } else {
    #si no existe lo crea
    $sql = "INSERT INTO lotestocks(idSucursal,idProducto,idStock,cant,lote,caducidad,estatus) VALUES('$idSucursal','$idProd','$idStock','0','$nombre','$caducidad','1')";
    $res = mysqli_query($link,$sql) or die(errorBD('Problemas al registrar el lote, notifica a tu Administrador.'));
    $idLote = mysqli_insert_id($link);
  }
}

$sqlIn = "INSERT INTO detrecepciones(idRecepcion,idProducto,cantidad,idLote,caducidad,idDetCompra,estatus) VALUES('$idRecepcion','$idProd','$cantidad','$idLote','$caducidad','$idDetCompra','1')";
$resIn = mysqli_query($link,$sqlIn) or die(errorBD('Problemas al registrar el producto, notifica a tu Administrador.'));

$sqlInit2 = "COMMIT";
$resInit2 = mysqli_query($link,$sqlInit2) or die(errorBD('Problemas al iniciar, notifica a tu Administrador.'));

echo '1|El lote se ha creado correctamente.|'.$idRecepcion.'|'.$sqlIn;
exit(0);

function errorBD($error){
  $sqlInit3 = "ROLLBACK";
  $resInit3 = mysqli_query($link,$sqlInit3) or die(errorBD('Problemas al iniciar, notifica a tu Administrador.'));
 echo '0|'.$error.'|0';
 exit(0);
}

function errorBD2($error){
 echo '0|'.$error;
 exit(0);
}
 ?>
