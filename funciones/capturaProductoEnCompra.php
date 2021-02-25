<?php
session_start();
define('INCLUDE_CHECK',1);
require_once('../include/connect.php');

$idCompra = (!empty($_POST['idCompra'])) ? $_POST['idCompra'] : 0 ;
$sel = (!empty($_POST['selProducto'])) ? $_POST['selProducto'] : 1 ;
$idProd = (!empty($_POST['producto1'])) ? $_POST['producto1'] : 0 ;
$nomProd = (isset($_POST['producto2']) && $_POST['producto2'] != '') ? trim($_POST['producto2']) : '' ;
$unidades = (isset($_POST['unidades']) && $_POST['unidades'] != '') ? trim($_POST['unidades']) : 'Pieza' ;
$cantidad = (!empty($_POST['cantidad'])) ? $_POST['cantidad'] : 0 ;
$costo = (isset($_POST['costo']) && $_POST['costo'] != '') ? str_replace(',','',$_POST['costo']) : '' ;

$idUser = $_SESSION['LZFident'];
$idSuc = $_SESSION['LZFidSuc'];

/*
echo '<br> ####################################################################################################### <br>';
echo '<br$_POST: ';
print_r($_POST);
echo '<br>$sel: '.$sel;
echo '<br>$idProd: '.$idProd;
echo '<br>$prod2: '.$nomProd;
echo '<br>$unidades: '.$unidades;
echo '<br>$cantidad: '.$cantidad;
echo '<br>$costo: '.$costo;
echo '<br>$idCompra: '.$idCompra;
echo '<br> ####################################################################################################### <br>';
exit(0);
#*/
$cont = 0;
$msn = '';
if ($sel == 1 && $idProd == 0) {
  $msn = ' seleccionar un producto,';
  $cont++;
}
if ($sel == 2 && $nomProd == '') {
  $msn = ' ingresar el nombre del producto,';
  $cont++;
}
if ($unidades == '') {
  $msn = ' ingresar las unidades,';
  $cont++;
}
if ($cantidad == 0) {
  $msn = ' ingresar una cantidad,';
  $cont++;
}
if ($costo == '') {
  $msn = ' ingresar el costo unitario';
  $cont++;
}
$msn = trim($msn,',');

if ($cont > 0) {
  errorBD('Faltan datos, debes realizar lo siguiente: '.$msn);
}

$sqlCon = "SELECT * FROM compras WHERE id = $idCompra";
$resCon = mysqli_query($link,$sqlCon) or die(errorBD('Problemas al consultar los productos en compras, notifica a tu Administrador.'));
$c = mysqli_fetch_array($resCon);

if ($c['estatus'] > 1) {
  errorBD('No se realizó ningún cambio a la compra porque ya está cerrada o fue cancelada.');
}

$sqlCon = "SELECT * FROM detcompras dc WHERE idCompra = '$idCompra'  AND IF($idProd > 0, idProducto = '$idProd', nombreProducto = '$nomProd')";
$resCon = mysqli_query($link,$sqlCon) or die(errorBD('Problemas al consultar los productos en compras, notifica a tu Administrador.'));
$cnt = mysqli_num_rows($resCon);
$cn = mysqli_fetch_array($resCon);

if ($cnt > 0) {
  errorBD('Ya existe un producto ingresado con ese nombre en esta compra, elimine el anterior y vuelva a colocar la cantidad completa.');
}

if ($sel == 1) {
  $sqlIn = "INSERT INTO detcompras(idProducto,cantidad,idCompra,tipoUnidad,costoUnitario) VALUES('$idProd','$cantidad','$idCompra','$unidades','$costo')";
} else {
  $sqlIn = "INSERT INTO detcompras(nombreProducto,cantidad,idCompra,tipoUnidad,costoUnitario) VALUES('$nomProd','$cantidad','$idCompra','$unidades','$costo')";
}

$resIn = mysqli_query($link,$sqlIn) or die('Problemas al agregar el producto, notifica a tu Administrador.');


$_SESSION['LZFmsjSuccessCorporativoCompras'] = 'Producto cargado correctamente';
header('location: ../Corporativo/compras.php');
exit(0);

function errorBD($error){
  echo '<br>$error: '.$error;
  $_SESSION['LZFmsjCorporativoCompras'] = $error;
  header('location: ../Corporativo/compras.php');
  exit(0);
}
 ?>
